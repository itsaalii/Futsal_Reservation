<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

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

        // Filter lapangan berdasarkan peran
        $lapangans = collect();
        $assignedLapangan = null;
        $lapanganFilter = null;

        if ($user->isSuperAdmin()) {
            $lapangans = Lapangan::all();
            $lapanganFilter = $request->input('lapangan_id');
        } else {
            // Admin (pegawai) HANYA BISA melihat lapangan yang ditugaskan
            $lapanganFilter = $user->id_lapangan;
            $assignedLapangan = $user->lapangan;
        }

        // Query terpisah untuk tabel Validasi (status aktif)
        $validasiQuery = Reservasi::with(['user', 'lapangan'])
            ->whereIn('status', ['menunggu', 'pending', 'disetujui', 'dibayar']);

        // Query terpisah untuk tabel Riwayat (status selesai/batal)
        $riwayatQuery = Reservasi::with(['user', 'lapangan'])
            ->whereIn('status', ['selesai', 'dibatalkan']);

        // Filter per lapangan jika ada
        if (!empty($lapanganFilter)) {
            $validasiQuery->where('id_lapangan', $lapanganFilter);
            $riwayatQuery->where('id_lapangan', $lapanganFilter);
        }

        $validasi = $validasiQuery->latest()->paginate(10, ['*'], 'validasi_page')->appends($request->query());
        $riwayat = $riwayatQuery->latest()->paginate(10, ['*'], 'riwayat_page')->appends($request->query());

        // Hitung status untuk dashboard / card indikator
        $countQuery = Reservasi::query();
        if (!empty($lapanganFilter)) {
            $countQuery->where('id_lapangan', $lapanganFilter);
        }

        $countMenungguBayar = (clone $countQuery)->where('status', 'pending')->count();
        $countMenungguPembayaran = (clone $countQuery)->where('status', 'menunggu')->count();
        $countDisetujui = (clone $countQuery)->whereIn('status', ['disetujui', 'dibayar'])->count();
        $countSelesai = (clone $countQuery)->where('status', 'selesai')->count();

        return view('pages.admin.reservasi', compact(
            'validasi',
            'riwayat',
            'countMenungguBayar',
            'countMenungguPembayaran',
            'countDisetujui',
            'countSelesai',
            'lapangans',
            'assignedLapangan',
            'lapanganFilter',
            'user'
        ));
    }

    public function terimaPembayaran(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $user = Auth::user();

        // Admin hanya bisa mengelola lapangan tugasnya
        if ($user->isAdmin() && $reservasi->id_lapangan && $reservasi->id_lapangan != $user->id_lapangan) {
            return redirect()->back()->with('error', 'Anda tidak berwenang mengelola reservasi di luar lapangan tugas Anda.');
        }

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
        $user = Auth::user();

        if ($user->isAdmin() && $reservasi->id_lapangan && $reservasi->id_lapangan != $user->id_lapangan) {
            return redirect()->back()->with('error', 'Anda tidak berwenang mengelola reservasi di luar lapangan tugas Anda.');
        }

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
        $user = Auth::user();

        if ($user->isAdmin() && $reservasi->id_lapangan && $reservasi->id_lapangan != $user->id_lapangan) {
            return redirect()->back()->with('error', 'Anda tidak berwenang mengelola reservasi di luar lapangan tugas Anda.');
        }

        if (!in_array($reservasi->status, ['disetujui', 'dibayar'])) {
            return redirect()->back()->with('error', 'Hanya reservasi yang sudah disetujui yang dapat diselesaikan.');
        }

        $reservasi->status = 'selesai';
        $reservasi->save();

        return redirect()->back()->with('success', 'Reservasi diselesaikan.');
    }

    public function create()
    {
        $user = Auth::user();
        $pelanggan = User::where('role', 'user')->get();

        if ($user->isSuperAdmin()) {
            $lapangans = Lapangan::where('status', 'aktif')->get();
        } else {
            $lapangans = Lapangan::where('id', $user->id_lapangan)->where('status', 'aktif')->get();
        }

        return view('pages.admin.reservasi-manual', compact('pelanggan', 'lapangans', 'user'));
    }

    public function store(Request $request)
    {
        $userAuth = Auth::user();

        $rules = [
            'id_user' => 'required|exists:users,id',
            'id_lapangan' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$|^24:00(:00)?$/'],
            'jam_selesai' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$|^24:00(:00)?$/', 'after:jam_mulai'],
            'catatan' => 'nullable|string|max:500',
        ];

        $validatedData = $request->validate($rules);

        // Jika admin, pastikan lapangan sesuai penugasannya
        if ($userAuth->isAdmin() && $validatedData['id_lapangan'] != $userAuth->id_lapangan) {
            return redirect()->back()->with('error', 'Anda hanya dapat membuat booking di lapangan yang ditugaskan kepada Anda.');
        }

        $lapangan = Lapangan::findOrFail($validatedData['id_lapangan']);

        $jamMulai = Carbon::parse($validatedData['jam_mulai']);
        $jamSelesai = Carbon::parse($validatedData['jam_selesai']);

        // CEK BENTROK JADWAL DI LAPANGAN YANG SAMA
        $bentrok = Reservasi::where('id_lapangan', $validatedData['id_lapangan'])
            ->where('tanggal', $validatedData['tanggal'])
            ->whereNotIn('status', ['dibatalkan'])
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
            return redirect()->back()->with('error', 'Maaf, ' . $lapangan->nama . ' sudah dibooking pada jam tersebut.');
        }

        $jamMulaiInt = (int) $jamMulai->format('H');
        $jamSelesaiInt = (int) $jamSelesai->format('H');

        $tarifSiang = $lapangan->tarif_siang ?: 60000;
        $tarifMalam = $lapangan->tarif_malam ?: 70000;

        $totalHarga = 0;
        for ($jam = $jamMulaiInt; $jam < $jamSelesaiInt; $jam++) {
            $totalHarga += ($jam < 17) ? $tarifSiang : $tarifMalam;
        }

        // Cek diskon membership
        $userCustomer = User::find($validatedData['id_user']);
        $freeHourApplied = false;
        $discountAmount = 0;

        if ($userCustomer && $userCustomer->membership_status === 'active' && $userCustomer->status_member == '1') {
            if ($userCustomer->membership_expires_at && Carbon::parse($userCustomer->membership_expires_at)->isFuture()) {
                if (!$userCustomer->membership_free_hour_used) {
                    $jamTerMahal = 0;
                    for ($jam = $jamMulaiInt; $jam < $jamSelesaiInt; $jam++) {
                        $hargaJam = ($jam < 17) ? $tarifSiang : $tarifMalam;
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
                $userCustomer->membership_status = 'expired';
                $userCustomer->status_member = '0';
                $userCustomer->save();
            }
        }

        $reservasi = Reservasi::create([
            'id_user' => $validatedData['id_user'],
            'id_lapangan' => $validatedData['id_lapangan'],
            'tanggal' => $validatedData['tanggal'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'total_harga' => $totalHarga,
            'discount_amount' => $discountAmount,
            'status' => 'disetujui',
            'metode_pembayaran' => 'Cash',
            'catatan' => $validatedData['catatan'] ? $validatedData['catatan'] . ' (Manual via Kasir)' : 'Manual via Kasir',
        ]);

        if ($userCustomer && $userCustomer->membership_status === 'active' && $userCustomer->status_member == '1') {
            $userCustomer->membership_last_booking_at = now();
            if ($freeHourApplied) {
                $userCustomer->membership_free_hour_used = true;
            }
            $userCustomer->save();
        }

        return redirect()->route('admin.reservasi')->with('success', 'Reservasi manual berhasil dibuat dan disetujui untuk ' . $lapangan->nama . '.');
    }
}
