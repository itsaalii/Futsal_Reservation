<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DefaultShift;
use App\Models\JadwalShift;
use App\Models\Lapangan;
use App\Models\User;
use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil lapangan yang tersedia
        if ($user->isSuperAdmin()) {
            $lapangans = Lapangan::all();
            $selectedLapanganId = $request->query('lapangan_id', $lapangans->first()?->id);
        } else {
            // Admin hanya lapangan yang ditugaskan
            $lapangans = Lapangan::where('id', $user->id_lapangan)->get();
            $selectedLapanganId = $user->id_lapangan ?? $lapangans->first()?->id;
        }

        $selectedTanggal = $request->query('tanggal', now()->toDateString());
        $hariIndonesia = ShiftService::getHariIndonesia($selectedTanggal);

        // Ambil data jadwal harian untuk lapangan & tanggal yang dipilih
        $jadwalHarianInfo = null;
        $isBuka = false;
        if ($selectedLapanganId) {
            $jadwalHarianInfo = ShiftService::getPetugasLapangan((int)$selectedLapanganId, $selectedTanggal);
            $isBuka = ShiftService::isLapanganBuka((int)$selectedLapanganId, $selectedTanggal);
        }

        // Ambil default shifts untuk tab default (khusus superadmin)
        $defaultShiftsGrouped = [];
        if ($user->isSuperAdmin() && $selectedLapanganId) {
            $defaultShifts = DefaultShift::with(['user', 'lapangan'])
                ->where('id_lapangan', $selectedLapanganId)
                ->get()
                ->groupBy('hari');

            $hariOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
            foreach ($hariOrder as $h) {
                $defaultShiftsGrouped[$h] = $defaultShifts->get($h, collect());
            }
        }

        // Daftar pegawai (role admin)
        $pegawaiList = User::where('role', 'admin')->get();

        return view('pages.admin.shift', compact(
            'lapangans',
            'selectedLapanganId',
            'selectedTanggal',
            'hariIndonesia',
            'jadwalHarianInfo',
            'isBuka',
            'defaultShiftsGrouped',
            'pegawaiList',
            'user'
        ));
    }

    /**
     * Simpan / Perbarui Override Shift Harian (Bisa dilakukan Admin & Superadmin)
     */
    public function storeOverride(Request $request)
    {
        $validated = $request->validate([
            'id_lapangan' => 'required|exists:lapangans,id',
            'id_user' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'shift' => 'required|in:pagi - siang,siang - malam,malam - pagi',
            'status' => 'required|in:aktif,izin,libur,tukar',
            'catatan' => 'nullable|string|max:255',
        ]);

        // Cek otorisasi jika role admin biasa
        $user = Auth::user();
        if ($user->isAdmin() && $user->id_lapangan != $validated['id_lapangan']) {
            return redirect()->back()->with('error', 'Anda hanya berhak merubah shift untuk lapangan yang ditugaskan kepada Anda.');
        }

        JadwalShift::updateOrCreate(
            [
                'id_lapangan' => $validated['id_lapangan'],
                'id_user' => $validated['id_user'],
                'tanggal' => $validated['tanggal'],
            ],
            [
                'shift' => $validated['shift'],
                'status' => $validated['status'],
                'catatan' => $validated['catatan'],
            ]
        );

        return redirect()->route('admin.shift', [
            'lapangan_id' => $validated['id_lapangan'],
            'tanggal' => $validated['tanggal'],
        ])->with('success', 'Jadwal shift harian berhasil diperbarui.');
    }

    /**
     * Hapus Override (Kembali menggunakan default shift)
     */
    public function deleteOverride($id)
    {
        $jadwal = JadwalShift::findOrFail($id);
        $user = Auth::user();

        if ($user->isAdmin() && $user->id_lapangan != $jadwal->id_lapangan) {
            return redirect()->back()->with('error', 'Tidak memiliki hak akses.');
        }

        $lapanganId = $jadwal->id_lapangan;
        $tanggal = $jadwal->tanggal;
        $jadwal->delete();

        return redirect()->route('admin.shift', [
            'lapangan_id' => $lapanganId,
            'tanggal' => $tanggal,
        ])->with('success', 'Override shift dihapus, kembali ke default shift.');
    }

    /**
     * Simpan Default Shift Mingguan (Khusus Superadmin)
     */
    public function storeDefault(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Hanya Superadmin yang berhak mengatur default shift mingguan.');
        }

        $validated = $request->validate([
            'id_lapangan' => 'required|exists:lapangans,id',
            'id_user' => 'required|exists:users,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'shift' => 'required|in:pagi - siang,siang - malam,malam - pagi',
        ]);

        DefaultShift::updateOrCreate(
            [
                'id_lapangan' => $validated['id_lapangan'],
                'id_user' => $validated['id_user'],
                'hari' => $validated['hari'],
            ],
            [
                'shift' => $validated['shift'],
            ]
        );

        return redirect()->route('admin.shift', [
            'lapangan_id' => $validated['id_lapangan'],
            'tab' => 'default',
        ])->with('success', 'Default shift mingguan berhasil disimpan.');
    }

    /**
     * Hapus Default Shift Mingguan (Khusus Superadmin)
     */
    public function deleteDefault($id)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Hanya Superadmin yang berhak menghapus default shift mingguan.');
        }

        $defaultShift = DefaultShift::findOrFail($id);
        $lapanganId = $defaultShift->id_lapangan;
        $defaultShift->delete();

        return redirect()->route('admin.shift', [
            'lapangan_id' => $lapanganId,
            'tab' => 'default',
        ])->with('success', 'Default shift berhasil dihapus.');
    }
}
