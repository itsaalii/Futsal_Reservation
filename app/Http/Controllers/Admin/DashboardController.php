<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Auto-batalkan reservasi 'menunggu' yang sudah melewati batas waktu 1 jam (belum upload bukti)
        $limitTime = Carbon::now()->subHour();
        Reservasi::where('status', 'menunggu')
            ->whereNull('bukti_pembayaran')
            ->where('created_at', '<', $limitTime)
            ->update([
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan otomatis oleh sistem (melebihi batas waktu upload bukti pembayaran 1 jam)',
            ]);

        // Auto-deactivate membership yang 3 bulan tidak booking
        User::where('membership_status', 'active')
            ->where('status_member', '1')
            ->whereNotNull('membership_last_booking_at')
            ->where('membership_last_booking_at', '<', Carbon::now()->subMonths(3))
            ->update([
                'membership_status' => 'expired',
                'status_member' => '0',
            ]);

        $totalPendapatanReservasi = Reservasi::whereIn('status', ['disetujui', 'selesai', 'dibayar'])
            ->whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year)
            ->sum('total_harga');

        // Hitung pendapatan membership bulan ini (Biaya Rp 150.000 per member)
        // Member baru bulan ini memiliki expires_at tepat di (Bulan Ini + 3 Bulan)
        $targetMonth = $today->copy()->addMonths(3)->month;
        $targetYear = $today->copy()->addMonths(3)->year;

        $totalMemberBaruBulanIni = User::where('membership_status', 'active')
            ->whereMonth('membership_expires_at', $targetMonth)
            ->whereYear('membership_expires_at', $targetYear)
            ->count();

        $pendapatanMembership = $totalMemberBaruBulanIni * 150000;
        $totalPendapatanBulanIni = $totalPendapatanReservasi + $pendapatanMembership;

        $totalReservasiHariIni = Reservasi::whereDate('tanggal', $today)->count();

        $menungguPembayaran = Reservasi::where('status', 'menunggu')->count();

        $totalMemberAktif = User::where('membership_status', 'active')
            ->where('status_member', 1)
            ->count();

        $recentActivities = Reservasi::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('pages.admin.dashboard', compact(
            'totalPendapatanBulanIni',
            'totalReservasiHariIni',
            'menungguPembayaran',
            'totalMemberAktif',
            'recentActivities'
        ));
    }
}
