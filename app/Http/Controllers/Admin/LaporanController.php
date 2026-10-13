<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $paidStatuses = ['disetujui', 'selesai', 'dibayar'];

        // Ambil semua filter dari request
        $bulanFilter = $request->input('bulan');
        $statusFilter = $request->input('status');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');

        // Filter lapangan berdasarkan peran
        $lapangans = collect();
        $assignedLapangan = null;
        $lapanganFilter = null;

        if ($user->isSuperAdmin()) {
            $lapangans = Lapangan::all();
            $lapanganFilter = $request->input('lapangan_id');
        } else {
            // Admin (pegawai) HANYA BISA melihat lapangan yang ditugaskan kepadanya
            $lapanganFilter = $user->id_lapangan;
            $assignedLapangan = $user->lapangan;
        }

        $filterDate = null;
        if (!empty($bulanFilter)) {
            try {
                $filterDate = Carbon::createFromFormat('Y-m', $bulanFilter);
            } catch (\Exception $ex) {
                $filterDate = null;
            }
        }

        // Base query builder untuk semua data yang relevan (lunas + batal)
        $baseQuery = Reservasi::with(['user', 'lapangan'])
            ->whereIn('status', array_merge($paidStatuses, ['dibatalkan']));

        // Terapkan filter lapangan
        if (!empty($lapanganFilter)) {
            $baseQuery->where('id_lapangan', $lapanganFilter);
        }

        // Terapkan filter bulan
        if ($filterDate) {
            $baseQuery->whereYear('tanggal', $filterDate->year)
                ->whereMonth('tanggal', $filterDate->month);
        }

        // Terapkan filter rentang tanggal
        if (!empty($tanggalDari)) {
            $baseQuery->where('tanggal', '>=', $tanggalDari);
        }
        if (!empty($tanggalSampai)) {
            $baseQuery->where('tanggal', '<=', $tanggalSampai);
        }

        // Terapkan filter status
        if (!empty($statusFilter)) {
            if ($statusFilter === 'dibatalkan') {
                $baseQuery->where('status', 'dibatalkan');
            } elseif ($statusFilter === 'selesai') {
                $baseQuery->where('status', 'selesai');
            } elseif ($statusFilter === 'disetujui') {
                $baseQuery->whereIn('status', ['disetujui', 'dibayar']);
            }
        }

        // Statistik card — ikut filter
        $totalPendapatan = (clone $baseQuery)->whereIn('status', $paidStatuses)->sum('total_harga');
        $totalTransaksi = (clone $baseQuery)->whereIn('status', $paidStatuses)->count();
        $totalBatal = (clone $baseQuery)->where('status', 'dibatalkan')->count();
        $rataRata = $totalTransaksi > 0 ? (int) round($totalPendapatan / $totalTransaksi) : 0;

        // Tabel Laporan Penyewaan (paginated)
        $laporanSewa = (clone $baseQuery)
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->appends($request->query());

        // Tabel Laporan Keuangan Bulanan
        $laporanBulanan = (clone $baseQuery)->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m');
            })
            ->map(function ($items) use ($paidStatuses) {
                $label = Carbon::parse($items->first()->tanggal)->translatedFormat('F Y');
                $transaksiLunas = $items->whereIn('status', $paidStatuses)->count();
                $reservasiBatal = $items->where('status', 'dibatalkan')->count();
                $pendapatan = $items->whereIn('status', $paidStatuses)->sum('total_harga');

                return [
                    'label' => $label,
                    'transaksi_lunas' => $transaksiLunas,
                    'reservasi_batal' => $reservasiBatal,
                    'pendapatan' => $pendapatan,
                ];
            })
            ->sortKeysDesc()
            ->values();

        // Available months untuk filter dropdown
        $monthQuery = Reservasi::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->whereNotNull('tanggal');
        if (!empty($lapanganFilter)) {
            $monthQuery->where('id_lapangan', $lapanganFilter);
        }
        $availableMonths = $monthQuery->distinct()->orderByDesc('bulan')->pluck('bulan');

        // Label filter aktif
        $filterLabels = [];
        if ($user->isSuperAdmin() && !empty($lapanganFilter)) {
            $currentLap = Lapangan::find($lapanganFilter);
            if ($currentLap) {
                $filterLabels[] = 'Lapangan: ' . $currentLap->nama;
            }
        } elseif ($user->isAdmin() && $assignedLapangan) {
            $filterLabels[] = 'Lapangan Tugas: ' . $assignedLapangan->nama;
        }

        if ($filterDate) {
            $filterLabels[] = $filterDate->translatedFormat('F Y');
        }
        if (!empty($statusFilter)) {
            $statusNames = [
                'disetujui' => 'Disetujui',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
            ];
            $filterLabels[] = 'Status: ' . ($statusNames[$statusFilter] ?? ucfirst($statusFilter));
        }
        if (!empty($tanggalDari)) {
            $filterLabels[] = 'Dari: ' . Carbon::parse($tanggalDari)->format('d M Y');
        }
        if (!empty($tanggalSampai)) {
            $filterLabels[] = 'Sampai: ' . Carbon::parse($tanggalSampai)->format('d M Y');
        }
        $filterLabel = !empty($filterLabels) ? implode(' | ', $filterLabels) : 'Semua Data';

        return view('pages.admin.laporan', compact(
            'totalPendapatan',
            'totalTransaksi',
            'totalBatal',
            'rataRata',
            'laporanSewa',
            'laporanBulanan',
            'availableMonths',
            'bulanFilter',
            'statusFilter',
            'tanggalDari',
            'tanggalSampai',
            'lapanganFilter',
            'lapangans',
            'assignedLapangan',
            'user',
            'filterLabel'
        ));
    }

    /**
     * Export laporan ke CSV (bisa dibuka di Excel)
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $paidStatuses = ['disetujui', 'selesai', 'dibayar'];

        $bulanFilter = $request->input('bulan');
        $statusFilter = $request->input('status');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');

        if ($user->isSuperAdmin()) {
            $lapanganFilter = $request->input('lapangan_id');
        } else {
            $lapanganFilter = $user->id_lapangan;
        }

        $filterDate = null;
        if (!empty($bulanFilter)) {
            try {
                $filterDate = Carbon::createFromFormat('Y-m', $bulanFilter);
            } catch (\Exception $ex) {
                $filterDate = null;
            }
        }

        $query = Reservasi::with(['user', 'lapangan'])
            ->whereIn('status', array_merge($paidStatuses, ['dibatalkan']));

        if (!empty($lapanganFilter)) {
            $query->where('id_lapangan', $lapanganFilter);
        }

        if ($filterDate) {
            $query->whereYear('tanggal', $filterDate->year)
                ->whereMonth('tanggal', $filterDate->month);
        }
        if (!empty($tanggalDari)) {
            $query->where('tanggal', '>=', $tanggalDari);
        }
        if (!empty($tanggalSampai)) {
            $query->where('tanggal', '<=', $tanggalSampai);
        }
        if (!empty($statusFilter)) {
            if ($statusFilter === 'dibatalkan') {
                $query->where('status', 'dibatalkan');
            } elseif ($statusFilter === 'selesai') {
                $query->where('status', 'selesai');
            } elseif ($statusFilter === 'disetujui') {
                $query->whereIn('status', ['disetujui', 'dibayar']);
            }
        }

        $data = $query->orderByDesc('tanggal')->get();

        $filename = 'laporan_reservasi_' . now()->format('Y-m-d_His') . '.csv';

        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($handle, [
                'No Transaksi',
                'Tanggal',
                'Nama Pelanggan',
                'Lapangan',
                'Jam Mulai',
                'Jam Selesai',
                'Total Harga',
                'Status',
            ], ';');

            // Data rows
            foreach ($data as $row) {
                $statusLabel = match ($row->status) {
                    'disetujui', 'dibayar' => 'Disetujui',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                    default => ucfirst($row->status),
                };

                fputcsv($handle, [
                    '#RSV-' . str_pad($row->id, 4, '0', STR_PAD_LEFT),
                    Carbon::parse($row->tanggal)->format('d/m/Y'),
                    $row->user->nama ?? '-',
                    $row->lapangan->nama ?? 'Lapangan Utama',
                    Carbon::parse($row->jam_mulai)->format('H:i'),
                    Carbon::parse($row->jam_selesai)->format('H:i'),
                    $row->total_harga,
                    $statusLabel,
                ], ';');
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
