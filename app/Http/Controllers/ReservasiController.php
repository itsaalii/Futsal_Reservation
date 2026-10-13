<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    public function tampilRiwayatBooking()
    {
        $unpaidReservasi = Reservasi::with('lapangan')
            ->where('id_user', Auth::id())
            ->where('status', 'menunggu')
            ->whereNull('bukti_pembayaran')
            ->first();

        // Cek apakah reservasi yang belum dibayar sudah melewati batas waktu 1 jam
        $expiredMessage = null;
        if ($unpaidReservasi && Carbon::parse($unpaidReservasi->created_at)->addHour()->isPast()) {
            $unpaidReservasi->update([
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan otomatis: batas waktu upload bukti pembayaran (1 jam) telah habis.',
            ]);
            $expiredMessage = 'Reservasi sebelumnya telah dibatalkan otomatis karena batas waktu pembayaran (1 jam) telah habis. Silakan buat reservasi baru.';
            $unpaidReservasi = null;
        }

        $riwayat = Reservasi::with('lapangan')
            ->where('id_user', Auth::id())
            ->latest()
            ->take(3)
            ->get();

        $lapangans = Lapangan::where('status', 'aktif')->get();
        $lastReservasiId = session('last_reservasi_id');

        return view('pages.user.reservasi', compact('riwayat', 'lastReservasiId', 'unpaidReservasi', 'expiredMessage', 'lapangans'));
    }

    public function riwayatLengkap()
    {
        $semuaRiwayat = Reservasi::with('lapangan')
            ->where('id_user', Auth::id())
            ->latest()
            ->get();

        return view('pages.user.riwayat-lengkap', compact('semuaRiwayat'));
    }

    public function buatPesanan(Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Anda harus login terlebih dahulu untuk membuat pesanan.'], 401);
            }
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu untuk membuat pesanan.');
        }

        // Auto-cancel expired reservations sebelum cek tanggungan
        $limitTime = Carbon::now()->subHour();
        Reservasi::where('id_user', Auth::id())
            ->where('status', 'menunggu')
            ->whereNull('bukti_pembayaran')
            ->where('created_at', '<', $limitTime)
            ->update([
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan otomatis: batas waktu upload bukti pembayaran (1 jam) telah habis.',
            ]);

        // Cek apakah user memiliki tanggungan pembayaran
        $unpaidCount = Reservasi::where('id_user', Auth::id())
            ->where('status', 'menunggu')
            ->whereNull('bukti_pembayaran')
            ->count();

        if ($unpaidCount > 0) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Selesaikan pembayaran di jadwal sebelumnya terlebih dahulu.'], 400);
            }
            return redirect()->back()->with('error', 'Selesaikan pembayaran di jadwal sebelumnya terlebih dahulu.');
        }

        $validatedData = $request->validate([
            'id_lapangan' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$|^24:00(:00)?$/'],
            'jam_selesai' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$|^24:00(:00)?$/', 'after:jam_mulai'],
            'catatan' => 'nullable|string|max:500',
        ]);

        $lapangan = Lapangan::findOrFail($validatedData['id_lapangan']);

        // CEK APAKAH LAPANGAN BUKA (ADA PEGAWAI BERTUGAS) PADA TANGGAL INI
        $isBuka = ShiftService::isLapanganBuka($lapangan->id, $validatedData['tanggal']);
        if (!$isBuka) {
            $msg = 'Maaf, ' . $lapangan->nama . ' TUTUP pada tanggal ini karena tidak ada pegawai yang bertugas.';
            if ($request->wantsJson()) {
                return response()->json(['message' => $msg], 400);
            }
            return redirect()->back()->with('error', $msg);
        }

        $jamMulai = Carbon::parse($validatedData['jam_mulai']);
        $jamSelesai = Carbon::parse($validatedData['jam_selesai']);

        // CEK BENTROK JADWAL DI LAPANGAN YANG DIPILIH
        $bentrok = Reservasi::where('id_lapangan', $lapangan->id)
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
            $msg = 'Maaf, ' . $lapangan->nama . ' sudah dibooking pada jam tersebut.';
            if ($request->wantsJson()) {
                return response()->json(['message' => $msg], 400);
            }
            return redirect()->back()->with('error', $msg);
        }

        // === HARGA BERDASARKAN TARIF LAPANGAN ===
        $jamMulaiInt = (int) $jamMulai->format('H');
        $jamSelesaiInt = (int) $jamSelesai->format('H');
        $tarifSiang = $lapangan->tarif_siang ?: 60000;
        $tarifMalam = $lapangan->tarif_malam ?: 70000;

        $totalHarga = 0;
        for ($jam = $jamMulaiInt; $jam < $jamSelesaiInt; $jam++) {
            $totalHarga += ($jam < 17) ? $tarifSiang : $tarifMalam;
        }

        // === CEK MEMBERSHIP: FREE 1 JAM + DISKON 15% ===
        $user = Auth::user();
        $freeHourApplied = false;
        $discountAmount = 0;

        if ($user && $user->membership_status === 'active' && $user->status_member == '1') {
            if ($user->membership_expires_at && Carbon::parse($user->membership_expires_at)->isFuture()) {
                if (!$user->membership_free_hour_used) {
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
                $user->membership_status = 'expired';
                $user->status_member = '0';
                $user->save();
            }
        }

        $reservasi = Reservasi::create([
            'id_user' => Auth::id(),
            'id_lapangan' => $lapangan->id,
            'tanggal' => $validatedData['tanggal'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'total_harga' => $totalHarga,
            'discount_amount' => $discountAmount,
            'status' => 'menunggu',
            'catatan' => $validatedData['catatan'],
        ]);

        if ($user && $user->membership_status === 'active' && $user->status_member == '1') {
            $user->membership_last_booking_at = now();
            if ($freeHourApplied) {
                $user->membership_free_hour_used = true;
            }
            $user->save();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.',
                'reservasi' => $reservasi
            ], 200);
        }

        return redirect()
            ->route('reservasi.index')
            ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.')
            ->with('last_reservasi_id', $reservasi->id);
    }

    public function prosesPembayaran(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        if ($reservasi->id_user !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($reservasi->status !== 'menunggu') {
            return response()->json(['message' => 'Reservasi ini sudah tidak dalam status menunggu pembayaran.'], 400);
        }

        if (Carbon::parse($reservasi->created_at)->addHour()->isPast()) {
            $reservasi->update([
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan otomatis: batas waktu upload bukti pembayaran (1 jam) telah habis.',
            ]);
            return response()->json(['message' => 'Maaf, batas waktu pembayaran sudah habis. Reservasi dibatalkan otomatis.'], 400);
        }

        $validatedData = $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $reservasi->metode_pembayaran = $validatedData['metode_pembayaran'];
        $reservasi->bukti_pembayaran = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        $reservasi->status = 'pending';
        $reservasi->save();

        return response()->json([
            'message' => 'Pembayaran berhasil, Silahkan tunggu konfirmasi dari admin',
            'reservasi' => $reservasi
        ], 200);
    }

    public function batalkanPesanan(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        if ($reservasi->id_user !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!in_array($reservasi->status, ['menunggu', 'dibatalkan'])) {
            return response()->json(['message' => 'Reservasi ini tidak dapat dibatalkan dari status saat ini.'], 400);
        }

        $reservasi->status = 'dibatalkan';
        $reservasi->save();

        return response()->json([
            'message' => 'Pesanan berhasil dibatalkan',
            'reservasi' => $reservasi
        ], 200);
    }

    public function cetakTiket($id)
    {
        $reservasi = Reservasi::with(['user', 'lapangan'])->where('id', $id)
            ->where('id_user', Auth::id())
            ->whereIn('status', ['disetujui', 'dibayar', 'selesai'])
            ->firstOrFail();

        return view('pages.user.cetak-tiket', compact('reservasi'));
    }

    public function cekJadwalTersedia(Request $request)
    {
        $tanggal = $request->query('tanggal', now()->format('Y-m-d'));
        $idLapangan = $request->query('id_lapangan');

        if (!$idLapangan) {
            $defaultLapangan = Lapangan::where('status', 'aktif')->first();
            $idLapangan = $defaultLapangan ? $defaultLapangan->id : null;
        }

        if (!$idLapangan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data lapangan belum tersedia.'
            ], 404);
        }

        $lapangan = Lapangan::findOrFail($idLapangan);

        // Auto-batalkan reservasi 'menunggu' yang sudah expired (>1 jam)
        $limitTime = Carbon::now()->subHour();
        Reservasi::where('status', 'menunggu')
            ->whereNull('bukti_pembayaran')
            ->where('created_at', '<', $limitTime)
            ->update([
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan otomatis oleh sistem (melebihi batas waktu upload bukti pembayaran 1 jam)',
            ]);

        // CEK APAKAH LAPANGAN BUKA PADA TANGGAL TERSEBUT
        $isBuka = ShiftService::isLapanganBuka($lapangan->id, $tanggal);
        if (!$isBuka) {
            return response()->json([
                'status' => 'tutup',
                'message' => $lapangan->nama . ' TUTUP pada hari/tanggal ini karena tidak ada pegawai bertugas.',
                'lapangan' => $lapangan,
                'data' => []
            ]);
        }

        // Ambil data booking lapangan yang dipilih pada tanggal tersebut
        $bookingHariIni = Reservasi::where('id_lapangan', $lapangan->id)
            ->where('tanggal', $tanggal)
            ->whereNotIn('status', ['dibatalkan'])
            ->get();

        $jamOperasional = [];
        $start = 7;
        $end = 22;

        $today = now()->format('Y-m-d');
        $currentHour = (int) now()->format('H');

        $tarifSiang = $lapangan->tarif_siang ?: 60000;
        $tarifMalam = $lapangan->tarif_malam ?: 70000;

        for ($i = $start; $i <= $end; $i++) {
            $jamFormat = sprintf('%02d:00', $i);
            $status = 'Tersedia';
            $keterangan = '';
            $harga = ($i < 17) ? $tarifSiang : $tarifMalam;

            if ($tanggal === $today && $i <= $currentHour) {
                $status = 'Waktu Berlalu';
                $keterangan = 'Jam ini sudah terlewat';
            } else {
                foreach ($bookingHariIni as $booking) {
                    $jamMulaiBooking = (int) substr($booking->jam_mulai, 0, 2);
                    $jamSelesaiBooking = (int) substr($booking->jam_selesai, 0, 2);

                    if ($i >= $jamMulaiBooking && $i < $jamSelesaiBooking) {
                        $status = 'Sudah Dibooking';
                        $keterangan = "Sudah ada yang booking dari jam " . substr($booking->jam_mulai, 0, 5) . " sampai " . substr($booking->jam_selesai, 0, 5);
                    }
                }
            }

            $jamOperasional[] = [
                'jam' => $jamFormat,
                'status' => $status,
                'keterangan' => $keterangan,
                'harga' => $harga
            ];
        }

        return response()->json([
            'status' => 'success',
            'lapangan' => $lapangan,
            'data' => $jamOperasional
        ]);
    }
}
