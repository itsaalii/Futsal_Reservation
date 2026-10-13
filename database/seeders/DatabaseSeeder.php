<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\DefaultShift;
use App\Models\JadwalShift;
use App\Models\Lapangan;
use App\Models\Penggajian;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. DATA LAPANGAN
        $lapangan1 = Lapangan::updateOrCreate(
            ['nama' => 'Lapangan 1 (Sintetis Vinyl)'],
            [
                'tipe_lantai' => 'Rumput Sintetis',
                'deskripsi' => 'Lapangan futsal rumput sintetis standar nasional, empuk, dan nyaman untuk pertandingan santai maupun turnamen.',
                'tarif_siang' => 60000,
                'tarif_malam' => 70000,
                'status' => 'aktif',
            ]
        );

        $lapangan2 = Lapangan::updateOrCreate(
            ['nama' => 'Lapangan 2 (Interlock Pro)'],
            [
                'tipe_lantai' => 'Vinyl Interlock',
                'deskripsi' => 'Lantai interlock pro fleksibel dengan grip tinggi, cocok untuk permainan tempo cepat dan teknik tinggi.',
                'tarif_siang' => 65000,
                'tarif_malam' => 75000,
                'status' => 'aktif',
            ]
        );

        $lapangan3 = Lapangan::updateOrCreate(
            ['nama' => 'Lapangan 3 (Indoor Premium)'],
            [
                'tipe_lantai' => 'Rumput Sintetis Premium',
                'deskripsi' => 'Lapangan rumput sintetis premium dilengkapi tribun penonton mini dan pencahayaan LED standar kompetisi.',
                'tarif_siang' => 70000,
                'tarif_malam' => 85000,
                'status' => 'aktif',
            ]
        );

        // 2. DATA USER (Superadmin, Admin 1, Admin 2, Admin 3, Member, User Biasa)
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'nama' => 'Owner Jaya Futsal (Superadmin)',
                'no_hp' => '081111111111',
                'role' => 'superadmin',
                'rfid_uid' => 'RFID-OWNER',
                'gaji_pokok' => 10000000,
                'tunjangan' => 2000000,
                'tarif_per_shift' => 0,
                'password' => Hash::make('superadmin123'),
            ]
        );

        $admin1 = User::updateOrCreate(
            ['email' => 'admin1@gmail.com'],
            [
                'nama' => 'Bambang Pegawai (Lap 1)',
                'no_hp' => '081222222221',
                'role' => 'admin',
                'id_lapangan' => $lapangan1->id,
                'rfid_uid' => 'RFID-ADM-001',
                'gaji_pokok' => 2500000,
                'tunjangan' => 300000,
                'tarif_per_shift' => 50000,
                'password' => Hash::make('admin123'),
            ]
        );

        $admin2 = User::updateOrCreate(
            ['email' => 'admin2@gmail.com'],
            [
                'nama' => 'Siti Pegawai (Lap 2)',
                'no_hp' => '081222222222',
                'role' => 'admin',
                'id_lapangan' => $lapangan2->id,
                'rfid_uid' => 'RFID-ADM-002',
                'gaji_pokok' => 2500000,
                'tunjangan' => 300000,
                'tarif_per_shift' => 50000,
                'password' => Hash::make('admin123'),
            ]
        );

        $admin3 = User::updateOrCreate(
            ['email' => 'admin3@gmail.com'],
            [
                'nama' => 'Rian Pegawai (Lap 3)',
                'no_hp' => '081222222223',
                'role' => 'admin',
                'id_lapangan' => $lapangan3->id,
                'rfid_uid' => 'RFID-ADM-003',
                'gaji_pokok' => 2500000,
                'tunjangan' => 300000,
                'tarif_per_shift' => 50000,
                'password' => Hash::make('admin123'),
            ]
        );

        // Pertahankan akun admin lama jika ada yang menggunakannya
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Admin Jaya Futsal',
                'no_hp' => '081234567890',
                'role' => 'admin',
                'id_lapangan' => $lapangan1->id,
                'rfid_uid' => 'RFID-ADM-000',
                'gaji_pokok' => 2500000,
                'tunjangan' => 300000,
                'tarif_per_shift' => 50000,
                'password' => Hash::make('admin123'),
            ]
        );

        $userMember = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'nama' => 'Ahmad Rizky (Member)',
                'no_hp' => '085712345678',
                'role' => 'user',
                'status_member' => '1',
                'membership_type' => 'Member',
                'membership_status' => 'active',
                'membership_expires_at' => Carbon::now()->addMonths(3),
                'membership_free_hour_used' => false,
                'membership_last_booking_at' => now(),
                'password' => Hash::make('user123'),
            ]
        );

        $userBiasa = User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'nama' => 'Budi Santoso',
                'no_hp' => '085887654321',
                'role' => 'user',
                'status_member' => '0',
                'password' => Hash::make('budi123'),
            ]
        );

        // 3. ATUR DEFAULT SHIFT MINGGUAN (Senin s/d Minggu Bergantian)
        DefaultShift::query()->delete();

        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        $shiftTypes = ['pagi - siang', 'siang - malam', 'malam - pagi'];
        $pegawaiList = [$admin1, $admin2, $admin3];

        // Buat rotasi bergantian setiap harinya untuk Lapangan 1
        foreach ($hariList as $indexHari => $hari) {
            for ($s = 0; $s < 3; $s++) {
                $pegawaiIndex = ($indexHari + $s) % 3;
                DefaultShift::create([
                    'id_user' => $pegawaiList[$pegawaiIndex]->id,
                    'id_lapangan' => $lapangan1->id,
                    'hari' => $hari,
                    'shift' => $shiftTypes[$s],
                ]);
            }
        }

        // Lapangan 2: Pegawai admin2 & admin1 bertugas
        foreach ($hariList as $indexHari => $hari) {
            DefaultShift::create([
                'id_user' => ($indexHari % 2 == 0) ? $admin2->id : $admin1->id,
                'id_lapangan' => $lapangan2->id,
                'hari' => $hari,
                'shift' => 'pagi - siang',
            ]);
            DefaultShift::create([
                'id_user' => ($indexHari % 2 == 0) ? $admin3->id : $admin2->id,
                'id_lapangan' => $lapangan2->id,
                'hari' => $hari,
                'shift' => 'siang - malam',
            ]);
        }

        // Lapangan 3: Buka Senin - Sabtu, Minggu sengaja tidak ada pegawai (TUTUP pada hari Minggu)
        foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari) {
            DefaultShift::create([
                'id_user' => $admin3->id,
                'id_lapangan' => $lapangan3->id,
                'hari' => $hari,
                'shift' => 'pagi - siang',
            ]);
            DefaultShift::create([
                'id_user' => $admin1->id,
                'id_lapangan' => $lapangan3->id,
                'hari' => $hari,
                'shift' => 'siang - malam',
            ]);
        }

        // 4. JADWAL SHIFT OVERRIDE CONTOH
        JadwalShift::query()->delete();
        $today = Carbon::today()->toDateString();
        JadwalShift::create([
            'id_user' => $admin1->id,
            'id_lapangan' => $lapangan1->id,
            'tanggal' => $today,
            'shift' => 'pagi - siang',
            'status' => 'aktif',
            'catatan' => 'Shift pagi hari ini terjadwal sesuai operasional',
        ]);

        // 5. DATA ABSENSI CONTOH HARI INI
        Absensi::query()->delete();
        Absensi::create([
            'id_user' => $admin1->id,
            'id_lapangan' => $lapangan1->id,
            'tanggal' => $today,
            'jam_masuk' => '06:55:00',
            'rfid_uid' => $admin1->rfid_uid,
            'shift' => 'pagi - siang',
            'status' => 'hadir',
            'catatan' => 'Absen masuk tepat waktu',
        ]);

        Absensi::create([
            'id_user' => $admin2->id,
            'id_lapangan' => $lapangan2->id,
            'tanggal' => $today,
            'jam_masuk' => '07:10:00',
            'rfid_uid' => $admin2->rfid_uid,
            'shift' => 'pagi - siang',
            'status' => 'terlambat',
            'catatan' => 'Terlambat 10 menit karena hujan',
        ]);

        // 6. UPDATE RESERVASI LAMA KE LAPANGAN 1 JIKA ADA
        Reservasi::whereNull('id_lapangan')->update(['id_lapangan' => $lapangan1->id]);

        // 7. BUAT BEBERAPA CONTOH RESERVASI DI LAPANGAN 1 & LAPANGAN 2
        Reservasi::firstOrCreate(
            [
                'id_user' => $userMember->id,
                'id_lapangan' => $lapangan1->id,
                'tanggal' => $today,
                'jam_mulai' => '19:00:00',
            ],
            [
                'jam_selesai' => '21:00:00',
                'total_harga' => 119000,
                'discount_amount' => 21000,
                'status' => 'disetujui',
                'metode_pembayaran' => 'Transfer Bank',
                'catatan' => 'Sparring Komunitas Futsal',
            ]
        );

        Reservasi::firstOrCreate(
            [
                'id_user' => $userBiasa->id,
                'id_lapangan' => $lapangan2->id,
                'tanggal' => $today,
                'jam_mulai' => '16:00:00',
            ],
            [
                'jam_selesai' => '18:00:00',
                'total_harga' => 140000,
                'discount_amount' => 0,
                'status' => 'selesai',
                'metode_pembayaran' => 'Cash',
                'catatan' => 'Latihan Tim Kampus',
            ]
        );

        // 8. DATA REKAP PENGGAJIAN CONTOH
        Penggajian::query()->delete();
        $periodeBulanIni = Carbon::now()->format('Y-m');
        foreach ([$admin1, $admin2, $admin3] as $adm) {
            Penggajian::create([
                'id_user' => $adm->id,
                'periode' => $periodeBulanIni,
                'total_kehadiran' => 24,
                'gaji_pokok' => $adm->gaji_pokok,
                'tunjangan' => $adm->tunjangan + (24 * $adm->tarif_per_shift),
                'potongan' => 0,
                'total_gaji' => $adm->gaji_pokok + $adm->tunjangan + (24 * $adm->tarif_per_shift),
                'status' => 'pending',
                'catatan' => 'Estimasi rekap kehadiran bulan berjalan',
            ]);
        }
    }
}
