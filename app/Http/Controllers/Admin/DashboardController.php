<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Models\User;
use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
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

        $resQuery = Reservasi::query();
        if ($user->isAdmin()) {
            $resQuery->where('id_lapangan', $user->id_lapangan);
        }

        $totalPendapatanReservasi = (clone $resQuery)
            ->whereIn('status', ['disetujui', 'selesai', 'dibayar'])
            ->whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year)
            ->sum('total_harga');

        $pendapatanMembership = 0;
        if ($user->isSuperAdmin()) {
            $targetMonth = $today->copy()->addMonths(3)->month;
            $targetYear = $today->copy()->addMonths(3)->year;

            $totalMemberBaruBulanIni = User::where('membership_status', 'active')
                ->whereMonth('membership_expires_at', $targetMonth)
                ->whereYear('membership_expires_at', $targetYear)
                ->count();

            $pendapatanMembership = $totalMemberBaruBulanIni * 150000;
        }

        $totalPendapatanBulanIni = $totalPendapatanReservasi + $pendapatanMembership;

        $totalReservasiHariIni = (clone $resQuery)->whereDate('tanggal', $today)->count();

        $menungguPembayaran = (clone $resQuery)->where('status', 'menunggu')->count();

        $totalMemberAktif = User::where('membership_status', 'active')
            ->where('status_member', 1)
            ->count();

        $recentActivities = (clone $resQuery)->with(['user', 'lapangan'])
            ->latest()
            ->take(5)
            ->get();

        // Status Lapangan hari ini (Buka / Tutup & shift)
        $lapanganStatuses = [];
        $lapangans = Lapangan::all();
        foreach ($lapangans as $lap) {
            $petugasInfo = ShiftService::getPetugasLapangan($lap->id, $today->toDateString());
            $isBuka = ShiftService::isLapanganBuka($lap->id, $today->toDateString());
            $lapanganStatuses[] = [
                'lapangan' => $lap,
                'is_buka' => $isBuka,
                'petugas' => $petugasInfo['petugas'],
            ];
        }

        return view('pages.admin.dashboard', compact(
            'totalPendapatanBulanIni',
            'totalReservasiHariIni',
            'menungguPembayaran',
            'totalMemberAktif',
            'recentActivities',
            'lapanganStatuses',
            'user'
        ));
    }
}
