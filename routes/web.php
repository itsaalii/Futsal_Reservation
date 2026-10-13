<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PenggajianController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ReservasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::view('/', 'pages.user.index')->name('home');

// KIOSK ABSENSI RFID (Halaman tersendiri tanpa perlu login)
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::post('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
Route::get('/absensi/live', [AbsensiController::class, 'getRecent'])->name('absensi.live');

// Public API untuk kalender
Route::get('/api/jadwal-kalender', function (Request $request) {
    $lapanganId = $request->query('id_lapangan');
    $query = \App\Models\Reservasi::with(['user', 'lapangan'])->whereNotIn('status', ['dibatalkan']);
    
    if ($lapanganId) {
        $query->where('id_lapangan', $lapanganId);
    }

    $reservasis = $query->get();

    $events = $reservasis->map(function ($reservasi) {
        $lapanganName = $reservasi->lapangan ? '[' . $reservasi->lapangan->nama . '] ' : '';
        $title = $lapanganName . ($reservasi->catatan ?: 'Dibooking');

        $color = '#3f8f5f'; // grass dark
        if ($reservasi->status == 'menunggu' || $reservasi->status == 'pending') {
            $color = '#f97316'; // accent orange
        }

        return [
            'title' => $title,
            'start' => $reservasi->tanggal . 'T' . $reservasi->jam_mulai,
            'end' => $reservasi->tanggal . 'T' . $reservasi->jam_selesai,
            'color' => $color,
        ];
    });

    return response()->json($events);
});

// Guest Routes
Route::view('/login', 'pages.user.login')->name('login');
Route::view('/register', 'pages.user.register')->name('register');
Route::post('/proses-register', [AuthController::class, 'prosesRegister'])->name('proses-register');
Route::post('/proses-login', [AuthController::class, 'prosesLogin'])->name('proses-login');

// Auth Routes (Pengguna yang login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- GROUP RESERVASI USER ---
    Route::prefix('reservasi')->name('reservasi.')->controller(ReservasiController::class)->group(function () {
        Route::get('/', 'tampilRiwayatBooking')->name('index');
        Route::get('/riwayat', 'riwayatLengkap')->name('riwayat-lengkap');
        Route::get('/cek-jadwal', 'cekJadwalTersedia')->name('cek-jadwal');
        Route::post('/', 'buatPesanan')->name('buat');
        Route::post('/{reservasi}/bayar', 'prosesPembayaran')->name('bayar');
        Route::post('/{reservasi}/batal', 'batalkanPesanan')->name('batal');
        Route::get('/{reservasi}/cetak', 'cetakTiket')->name('cetak');
    });

    // --- GROUP MEMBERSHIP & PROFILE ---
    Route::post('/membership/register', [MembershipController::class, 'register'])->name('membership.register');
    Route::get('/profile', [MembershipController::class, 'profile'])->name('profile');

    // --- GROUP ADMIN & SUPERADMIN ---
    Route::prefix('admin')->middleware('is_admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Reservasi
        Route::controller(AdminReservasiController::class)->group(function () {
            Route::get('/reservasi', 'index')->name('admin.reservasi');
            Route::get('/reservasi/create', 'create')->name('admin.reservasi.create');
            Route::post('/reservasi/store', 'store')->name('admin.reservasi.store');
            Route::post('/reservasi/{id}/terima', 'terimaPembayaran')->name('admin.reservasi.terima');
            Route::post('/reservasi/{id}/tolak', 'tolakPembayaran')->name('admin.reservasi.tolak');
            Route::post('/reservasi/{id}/selesai', 'selesaikanReservasi')->name('admin.reservasi.selesai');
        });

        // Laporan (Admin melihat lapangan sendiri, Superadmin melihat semua/filter)
        Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
        Route::get('/laporan/export', [LaporanController::class, 'exportExcel'])->name('admin.laporan.export');

        // Kelola Shift (Admin & Superadmin bisa merubah override harian)
        Route::controller(ShiftController::class)->group(function () {
            Route::get('/shift', 'index')->name('admin.shift');
            Route::post('/shift/override', 'storeOverride')->name('admin.shift.override.store');
            Route::delete('/shift/override/{id}', 'deleteOverride')->name('admin.shift.override.delete');
        });

        // Membership
        Route::controller(AdminMembershipController::class)->group(function () {
            Route::get('/membership', 'index')->name('admin.membership');
            Route::post('/membership/{id}/terima', 'terima')->name('admin.membership.terima');
            Route::post('/membership/{id}/tolak', 'tolak')->name('admin.membership.tolak');
        });
        Route::view('/membership/form', 'pages.admin.membership-form')->name('admin.membership.form');

        // Pelanggan
        Route::controller(PelangganController::class)->group(function () {
            Route::get('/pelanggan', 'index')->name('admin.pelanggan');
            Route::get('/pelanggan/form', 'create')->name('admin.pelanggan.form');
            Route::post('/pelanggan', 'store')->name('admin.pelanggan.store');
            Route::get('/pelanggan/{id}/edit', 'edit')->name('admin.pelanggan.edit');
            Route::put('/pelanggan/{id}', 'update')->name('admin.pelanggan.update');
            Route::delete('/pelanggan/{id}', 'destroy')->name('admin.pelanggan.destroy');
        });

        // --- KHUSUS SUPERADMIN (PEMILIK LAPANGAN) ---
        Route::middleware('is_superadmin')->group(function () {
            // CRUD Lapangan
            Route::controller(LapanganController::class)->group(function () {
                Route::get('/lapangan', 'index')->name('admin.lapangan');
                Route::get('/lapangan/create', 'create')->name('admin.lapangan.create');
                Route::post('/lapangan', 'store')->name('admin.lapangan.store');
                Route::get('/lapangan/{id}/edit', 'edit')->name('admin.lapangan.edit');
                Route::put('/lapangan/{id}', 'update')->name('admin.lapangan.update');
                Route::delete('/lapangan/{id}', 'destroy')->name('admin.lapangan.destroy');
            });

            // Default Shift Mingguan
            Route::post('/shift/default', [ShiftController::class, 'storeDefault'])->name('admin.shift.default.store');
            Route::delete('/shift/default/{id}', [ShiftController::class, 'deleteDefault'])->name('admin.shift.default.delete');

            // Rekapitulasi Penggajian
            Route::controller(PenggajianController::class)->group(function () {
                Route::get('/penggajian', 'index')->name('admin.penggajian');
                Route::post('/penggajian/generate', 'generate')->name('admin.penggajian.generate');
                Route::post('/penggajian/{id}/bayar', 'bayar')->name('admin.penggajian.bayar');
                Route::get('/penggajian/export', 'export')->name('admin.penggajian.export');
            });
        });
    });
});
