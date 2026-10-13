<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel lapangans
        Schema::create('lapangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Contoh: "Lapangan 1 - Rumput Sintetis"
            $table->string('tipe_lantai')->default('Rumput Sintetis'); // Rumput Sintetis, Vinyl, Matras
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('tarif_siang')->default(60000);
            $table->unsignedBigInteger('tarif_malam')->default(70000);
            $table->string('status')->default('aktif'); // aktif, maintenance, nonaktif
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        // 2. Modifikasi tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('id_lapangan')->nullable()->after('role')->constrained('lapangans')->nullOnDelete();
            $table->string('rfid_uid')->nullable()->unique()->after('id_lapangan');
            $table->unsignedBigInteger('gaji_pokok')->default(0)->after('rfid_uid');
            $table->unsignedBigInteger('tunjangan')->default(0)->after('gaji_pokok');
            $table->unsignedBigInteger('tarif_per_shift')->default(50000)->after('tunjangan');
        });

        // 3. Modifikasi tabel reservasis untuk menambahkan id_lapangan
        Schema::table('reservasis', function (Blueprint $table) {
            $table->foreignId('id_lapangan')->nullable()->after('id_user')->constrained('lapangans')->nullOnDelete();
        });

        // 4. Buat tabel default_shifts (Shift mingguan default per lapangan & pegawai)
        Schema::create('default_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_lapangan')->constrained('lapangans')->cascadeOnDelete();
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->enum('shift', ['pagi - siang', 'siang - malam', 'malam - pagi']);
            $table->timestamps();
        });

        // 5. Buat tabel jadwal_shifts (Shift spesifik per tanggal / override harian)
        Schema::create('jadwal_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_lapangan')->constrained('lapangans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('shift', ['pagi - siang', 'siang - malam', 'malam - pagi']);
            $table->string('status')->default('aktif'); // aktif, izin, libur, tukar
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 6. Buat tabel absensis (Scan RFID Kiosk)
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_lapangan')->nullable()->constrained('lapangans')->nullOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk');
            $table->time('jam_keluar')->nullable();
            $table->string('rfid_uid');
            $table->string('shift')->nullable();
            $table->string('status')->default('hadir'); // hadir, terlambat, pulang_cepat
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 7. Buat tabel penggajians (Rekapitulasi Gaji Pegawai)
        Schema::create('penggajians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('periode', 7); // Contoh: "2026-10"
            $table->unsignedInteger('total_kehadiran')->default(0);
            $table->unsignedBigInteger('gaji_pokok')->default(0);
            $table->unsignedBigInteger('tunjangan')->default(0);
            $table->unsignedBigInteger('potongan')->default(0);
            $table->unsignedBigInteger('total_gaji')->default(0);
            $table->string('status')->default('pending'); // pending, dibayar
            $table->date('tanggal_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajians');
        Schema::dropIfExists('absensis');
        Schema::dropIfExists('jadwal_shifts');
        Schema::dropIfExists('default_shifts');

        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropForeign(['id_lapangan']);
            $table->dropColumn('id_lapangan');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_lapangan']);
            $table->dropColumn([
                'id_lapangan',
                'rfid_uid',
                'gaji_pokok',
                'tunjangan',
                'tarif_per_shift'
            ]);
        });

        Schema::dropIfExists('lapangans');
    }
};
