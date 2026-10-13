<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Lapangan;
use App\Models\User;
use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Halaman Kiosk Absensi RFID Publik (Tanpa Login)
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $recentAbsensi = Absensi::with(['user', 'lapangan'])
            ->whereDate('tanggal', $today)
            ->latest()
            ->take(8)
            ->get();

        $lapangans = Lapangan::all();

        return view('pages.absensi', compact('recentAbsensi', 'lapangans', 'today'));
    }

    /**
     * Endpoint API untuk memproses Scan RFID Card
     */
    public function scan(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required|string',
        ]);

        $rfidUid = trim($request->input('rfid_uid'));
        $today = Carbon::today()->toDateString();
        $nowTime = Carbon::now()->format('H:i:s');

        // Cari pegawai berdasarkan RFID UID
        $user = User::where('rfid_uid', $rfidUid)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu RFID (' . $rfidUid . ') tidak terdaftar dalam sistem.',
            ], 404);
        }

        // Cari informasi lapangan & shift pegawai hari ini
        $idLapangan = $user->id_lapangan;
        $namaShift = 'Shift Reguler';

        if ($idLapangan) {
            $shiftInfo = ShiftService::getPetugasLapangan($idLapangan, $today);
            $petugasRecord = $shiftInfo['petugas']->firstWhere('id_user', $user->id);
            if ($petugasRecord) {
                $namaShift = ucwords($petugasRecord->shift);
            }
        }

        // Cek riwayat absensi hari ini
        $absensi = Absensi::where('id_user', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            // TAP MASUK
            $absensiBaru = Absensi::create([
                'id_user' => $user->id,
                'id_lapangan' => $idLapangan,
                'tanggal' => $today,
                'jam_masuk' => $nowTime,
                'rfid_uid' => $rfidUid,
                'shift' => $namaShift,
                'status' => 'hadir',
                'catatan' => 'Absen Masuk via RFID',
            ]);

            return response()->json([
                'success' => true,
                'type' => 'masuk',
                'message' => 'Selamat bertugas, ' . $user->nama . '!',
                'data' => [
                    'nama' => $user->nama,
                    'role' => strtoupper($user->role),
                    'lapangan' => $user->lapangan ? $user->lapangan->nama : 'Semua Lapangan',
                    'shift' => $namaShift,
                    'jam' => Carbon::now()->format('H:i:s'),
                    'tanggal' => Carbon::now()->translatedFormat('l, d F Y'),
                    'status' => 'HADIR (MASUK)',
                ]
            ]);
        } elseif (empty($absensi->jam_keluar)) {
            // TAP PULANG
            $absensi->jam_keluar = $nowTime;
            $absensi->save();

            return response()->json([
                'success' => true,
                'type' => 'pulang',
                'message' => 'Terima kasih atas kerja kerasnya, ' . $user->nama . '!',
                'data' => [
                    'nama' => $user->nama,
                    'role' => strtoupper($user->role),
                    'lapangan' => $user->lapangan ? $user->lapangan->nama : 'Semua Lapangan',
                    'shift' => $absensi->shift ?? $namaShift,
                    'jam' => Carbon::now()->format('H:i:s'),
                    'tanggal' => Carbon::now()->translatedFormat('l, d F Y'),
                    'status' => 'SELESAI (PULANG)',
                ]
            ]);
        } else {
            // SUDAH MASUK & PULANG
            return response()->json([
                'success' => false,
                'type' => 'lengkap',
                'message' => $user->nama . ' sudah menyelesaikan absensi masuk dan pulang hari ini.',
                'data' => [
                    'nama' => $user->nama,
                    'jam_masuk' => substr($absensi->jam_masuk, 0, 5),
                    'jam_keluar' => substr($absensi->jam_keluar, 0, 5),
                ]
            ], 400);
        }
    }

    /**
     * Dapatkan daftar log absensi terbaru untuk live reload
     */
    public function getRecent()
    {
        $today = Carbon::today()->toDateString();
        $absensis = Absensi::with(['user', 'lapangan'])
            ->whereDate('tanggal', $today)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->user->nama ?? 'Pegawai',
                    'lapangan' => $item->lapangan->nama ?? 'Umum',
                    'jam_masuk' => substr($item->jam_masuk, 0, 5),
                    'jam_keluar' => $item->jam_keluar ? substr($item->jam_keluar, 0, 5) : '-',
                    'shift' => $item->shift ?? '-',
                    'status' => $item->status,
                ];
            });

        return response()->json($absensis);
    }
}
