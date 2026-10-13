<?php

namespace App\Services;

use App\Models\DefaultShift;
use App\Models\JadwalShift;
use Carbon\Carbon;

class ShiftService
{
    /**
     * Dapatkan nama hari dalam bahasa Indonesia lowercase
     */
    public static function getHariIndonesia(string $dateString): string
    {
        $dayOfWeek = Carbon::parse($dateString)->dayOfWeekIso; // 1 = Senin, 7 = Minggu
        $days = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];
        return $days[$dayOfWeek] ?? 'senin';
    }

    /**
     * Dapatkan daftar pegawai yang bertugas di lapangan tertentu pada tanggal tertentu
     */
    public static function getPetugasLapangan(int $idLapangan, string $tanggal)
    {
        // 1. Cek apakah ada override jadwal spesifik di tanggal tersebut
        $overrides = JadwalShift::with(['user', 'lapangan'])
            ->where('id_lapangan', $idLapangan)
            ->whereDate('tanggal', $tanggal)
            ->get();

        if ($overrides->isNotEmpty()) {
            return [
                'is_override' => true,
                'petugas' => $overrides->where('status', 'aktif')->values(),
                'semua_jadwal' => $overrides,
            ];
        }

        // 2. Jika tidak ada override, ambil default shift mingguan
        $hari = self::getHariIndonesia($tanggal);
        $defaultShifts = DefaultShift::with(['user', 'lapangan'])
            ->where('id_lapangan', $idLapangan)
            ->where('hari', $hari)
            ->get();

        return [
            'is_override' => false,
            'petugas' => $defaultShifts,
            'semua_jadwal' => $defaultShifts,
        ];
    }

    /**
     * Cek apakah lapangan buka pada tanggal tertentu (ada pegawai bertugas)
     */
    public static function isLapanganBuka(int $idLapangan, string $tanggal): bool
    {
        $data = self::getPetugasLapangan($idLapangan, $tanggal);
        return $data['petugas']->isNotEmpty();
    }
}
