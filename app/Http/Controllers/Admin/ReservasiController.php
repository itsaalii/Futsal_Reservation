<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        // 1. Auto-batalkan reservasi 'menunggu' yang sudah melewati batas waktu 1 jam (belum upload bukti)
        $limitTime = Carbon::now()->subHour();
        Reservasi::where('status', 'menunggu')
            ->whereNull('bukti_pembayaran')
            ->where('created_at', '<', $limitTime)
            ->update([
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan otomatis oleh sistem (melebihi batas waktu upload bukti pembayaran 1 jam)',
            ]);

        // 2. Auto-selesaikan reservasi yang waktu mainnya sudah lewat
        // (tanggal + jam_selesai < sekarang) dan statusnya masih disetujui/dibayar
        $now = now();
        Reservasi::whereIn('status', ['disetujui', 'dibayar'])
            ->where(function ($query) use ($now) {
                $query->where('tanggal', '<', $now->toDateString())
                    ->orWhere(function ($q) use ($now) {
                        $q->where('tanggal', '=', $now->toDateString())
                          ->where('jam_selesai', '<=', $now->format('H:i:s'));
                    });
            })
            ->update(['status' => 'selesai']);

        // Query terpisah untuk tabel Validasi (status aktif)
        $validasi = Reservasi::with('user')
            ->whereIn('status', ['menunggu', 'pending', 'disetujui', 'dibayar'])
            ->latest()
            ->paginate(10, ['*'], 'validasi_page');

        // Query terpisah untuk tabel Riwayat (status selesai/batal)
        $riwayat = Reservasi::with('user')
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->latest()
            ->paginate(10, ['*'], 'riwayat_page');

        // Hitung status untuk dashboard / card indikator
        $countMenungguBayar = Reservasi::where('status', 'pending')->count();
        $countMenungguPembayaran = Reservasi::where('status', 'menunggu')->count();
        $countDisetujui = Reservasi::whereIn('status', ['disetujui', 'dibayar'])->count();
        $countSelesai = Reservasi::where('status', 'selesai')->count();

        return view('pages.admin.reservasi', compact(
            'validasi',
            'riwayat',
            'countMenungguBayar',
            'countMenungguPembayaran',
            'countDisetujui',
            'countSelesai'
        ));
    }

    public function terimaPembayaran(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        // Hanya bisa menerima pembayaran dari status 'pending' (sudah upload bukti)
        if ($reservasi->status !== 'pending') {
            return redirect()->back()->with('error', 'Reservasi ini tidak dalam status menunggu konfirmasi pembayaran.');
        }

        $reservasi->status = 'disetujui';
        $reservasi->save();

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function tolakPembayaran(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        // Hanya bisa menolak dari status 'pending' atau 'menunggu'
        if (!in_array($reservasi->status, ['pending', 'menunggu'])) {
            return redirect()->back()->with('error', 'Reservasi ini tidak dapat ditolak dari status saat ini.');
        }

        $reservasi->status = 'dibatalkan';
        $reservasi->catatan = $reservasi->catatan
            ? $reservasi->catatan . ' | Ditolak oleh admin.'
            : 'Ditolak oleh admin.';
        $reservasi->save();

        return redirect()->back()->with('success', 'Pembayaran ditolak / reservasi dibatalkan.');
    }

    public function selesaikanReservasi(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        // Hanya bisa menyelesaikan dari status 'disetujui' atau 'dibayar'
        if (!in_array($reservasi->status, ['disetujui', 'dibayar'])) {
            return redirect()->back()->with('error', 'Hanya reservasi yang sudah disetujui yang dapat diselesaikan.');
        }

        $reservasi->status = 'selesai';
        $reservasi->save();

        return redirect()->back()->with('success', 'Reservasi diselesaikan.');
    }

    public function create()
    {
        $pelanggan = \App\Models\User::where('role', 'user')->get();
        return view('pages.admin.reservasi-manual', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_mulai' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$|^24:00(:00)?$/'],
            'jam_selesai' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$|^24:00(:00)?$/', 'after:jam_mulai'],
            'catatan' => 'nullable|string|max:500',
        ]);

        $jamMulai = Carbon::parse($validatedData['jam_mulai']);
        $jamSelesai = Carbon::parse($validatedData['jam_selesai']);

        // CEK BENTROK JADWAL
        $bentrok = Reservasi::where('tanggal', $validatedData['tanggal'])
            ->whereNotIn('status', ['dibatalkan']) // Abaikan yang dibatalkan
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->where(function ($q) use ($jamMulai, $jamSelesai) {
                    $q->whereTime('jam_mulai', '<=', $jamMulai->format('H:i:s'))
                        ->whereTime('jam_selesai', '>', $jamMulai->format('H:i:s'));
                })
                    ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                        $q->whereTime('jam_mulai', '<', $jamSelesai->format('H:i:s'))
                            ->whereTime('jam_selesai', '>=', $jamSelesai->format('H:i:s'));
                    })
                    ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                        $q->whereTime('jam_mulai', '>=', $jamMulai->format('H:i:s'))
                            ->whereTime('jam_mulai', '<', $jamSelesai->format('H:i:s'));
                    });
            })->exists();

        if ($bentrok) {
            return redirect()->back()->with('error', 'Maaf, lapangan sudah dibooking pada jam tersebut.');
        }

        $jamMulaiInt = (int) $jamMulai->format('H');
        $jamSelesaiInt = (int) $jamSelesai->format('H');

        $totalHarga = 0;
        for ($jam = $jamMulaiInt; $jam < $jamSelesaiInt; $jam++) {
            $totalHarga += ($jam < 17) ? 60000 : 70000;
        }

        // Cek diskon membership
        $user = \App\Models\User::find($validatedData['id_user']);
        $freeHourApplied = false;
        $discountAmount = 0;

        if ($user && $user->membership_status === 'active' && $user->status_member == '1') {
            if ($user->membership_expires_at && Carbon::parse($user->membership_expires_at)->isFuture()) {
                if (!$user->membership_free_hour_used) {
                    $jamTerMahal = 0;
                    for ($jam = $jamMulaiInt; $jam < $jamSelesaiInt; $jam++) {
                        $hargaJam = ($jam < 17) ? 60000 : 70000;
                        if ($hargaJam > $jamTerMahal) $jamTerMahal = $hargaJam;
                    }
                    $totalHarga -= $jamTerMahal;
                    if ($totalHarga < 0) $totalHarga = 0;
                    $freeHourApplied = true;
                }
                if ($totalHarga > 0) {
                    $discountAmount = round($totalHarga * 0.15);
                    $totalHarga -= $discountAmount;
                }
            } else {
                $user->membership_status = 'expired';
                $user->status_member = '0';
                $user->save();
            }
        }

        $reservasi = Reservasi::create([
            'id_user' => $validatedData['id_user'],
            'tanggal' => $validatedData['tanggal'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'total_harga' => $totalHarga,
            'discount_amount' => $discountAmount,
            'status' => 'disetujui',
            'metode_pembayaran' => 'Cash',
            'catatan' => $validatedData['catatan'] ? $validatedData['catatan'] . ' (Manual via Admin)' : 'Manual via Admin',
        ]);

        if ($user && $user->membership_status === 'active' && $user->status_member == '1') {
            $user->membership_last_booking_at = now();
            if ($freeHourApplied) {
                $user->membership_free_hour_used = true;
            }
            $user->save();
        }

        return redirect()->route('admin.reservasi')->with('success', 'Reservasi manual berhasil dibuat dan disetujui.');
    }
}
