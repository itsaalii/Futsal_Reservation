<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Penggajian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PenggajianController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->query('periode', now()->format('Y-m'));

        // Ambil data penggajian pada periode terpilih
        $penggajians = Penggajian::with('user.lapangan')
            ->where('periode', $periode)
            ->get();

        // Jika belum ada data untuk periode ini, sediakan opsi kalkulasi otomatis
        $totalPengeluaranGaji = $penggajians->sum('total_gaji');
        $totalPegawaiTergaji = $penggajians->count();
        $totalSudahDibayar = $penggajians->where('status', 'dibayar')->sum('total_gaji');
        $totalMenungguBayar = $penggajians->where('status', 'pending')->sum('total_gaji');

        // Available periods
        $availablePeriodes = Penggajian::distinct()->orderByDesc('periode')->pluck('periode');
        if ($availablePeriodes->doesntContain($periode)) {
            $availablePeriodes->prepend($periode);
        }

        return view('pages.admin.penggajian', compact(
            'penggajians',
            'periode',
            'totalPengeluaranGaji',
            'totalPegawaiTergaji',
            'totalSudahDibayar',
            'totalMenungguBayar',
            'availablePeriodes'
        ));
    }

    /**
     * Hitung Ulang & Sinkronisasi Rekapitulasi Gaji dari Data Absensi & Shift
     */
    public function generate(Request $request)
    {
        $periode = $request->input('periode', now()->format('Y-m'));
        $startOfMonth = Carbon::createFromFormat('Y-m', $periode)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::createFromFormat('Y-m', $periode)->endOfMonth()->toDateString();

        // Ambil semua pegawai dengan role 'admin'
        $pegawaiList = User::where('role', 'admin')->get();

        foreach ($pegawaiList as $pegawai) {
            // Hitung jumlah kehadiran pegawai di bulan tersebut
            $kehadiran = Absensi::where('id_user', $pegawai->id)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->count();

            $gajiPokok = $pegawai->gaji_pokok ?: 2500000;
            $tunjanganTetap = $pegawai->tunjangan ?: 300000;
            $tarifShift = $pegawai->tarif_per_shift ?: 50000;

            $tunjanganKehadiran = $kehadiran * $tarifShift;
            $totalTunjangan = $tunjanganTetap + $tunjanganKehadiran;
            $potongan = 0;
            $totalGaji = $gajiPokok + $totalTunjangan - $potongan;

            $penggajian = Penggajian::firstOrNew([
                'id_user' => $pegawai->id,
                'periode' => $periode,
            ]);

            // Hanya update jika belum berstatus dibayar (agar histori bayar tidak tertimpa)
            if ($penggajian->status !== 'dibayar') {
                $penggajian->total_kehadiran = $kehadiran;
                $penggajian->gaji_pokok = $gajiPokok;
                $penggajian->tunjangan = $totalTunjangan;
                $penggajian->potongan = $potongan;
                $penggajian->total_gaji = $totalGaji;
                $penggajian->status = 'pending';
                $penggajian->catatan = 'Dihitung otomatis: ' . $kehadiran . ' shift hadir x Rp ' . number_format($tarifShift, 0, ',', '.') . ' + tunjangan tetap.';
                $penggajian->save();
            }
        }

        return redirect()->route('admin.penggajian', ['periode' => $periode])
            ->with('success', 'Rekapitulasi penggajian untuk periode ' . Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y') . ' berhasil diperbarui.');
    }

    /**
     * Tandai Penggajian sebagai Dibayar (Lunas)
     */
    public function bayar(Request $request, $id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->status = 'dibayar';
        $penggajian->tanggal_bayar = now()->toDateString();
        $penggajian->save();

        return redirect()->back()->with('success', 'Gaji untuk ' . $penggajian->user->nama . ' berhasil ditandai LUNAS.');
    }

    /**
     * Export Rekapitulasi Gaji ke CSV/Excel
     */
    public function export(Request $request)
    {
        $periode = $request->input('periode', now()->format('Y-m'));
        $data = Penggajian::with('user.lapangan')
            ->where('periode', $periode)
            ->get();

        $filename = 'rekap_gaji_' . $periode . '_' . now()->format('His') . '.csv';

        $response = new StreamedResponse(function () use ($data, $periode) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'ID Pegawai',
                'Nama Pegawai',
                'Lapangan Penugasan',
                'Periode',
                'Total Kehadiran',
                'Gaji Pokok',
                'Tunjangan (Kehadiran & Tetap)',
                'Potongan',
                'Total Gaji Bersih',
                'Status Pembayaran',
                'Tanggal Bayar',
            ], ';');

            foreach ($data as $item) {
                fputcsv($handle, [
                    '#PEG-' . $item->user->id,
                    $item->user->nama ?? '-',
                    $item->user->lapangan->nama ?? 'Semua Lapangan',
                    $item->periode,
                    $item->total_kehadiran . ' Shift',
                    $item->gaji_pokok,
                    $item->tunjangan,
                    $item->potongan,
                    $item->total_gaji,
                    strtoupper($item->status),
                    $item->tanggal_bayar ? Carbon::parse($item->tanggal_bayar)->format('d/m/Y') : '-',
                ], ';');
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
