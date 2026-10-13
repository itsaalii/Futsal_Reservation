<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    /**
     * Tampilkan daftar akun pegawai (role: admin)
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $lapanganFilter = $request->query('lapangan_id');

        $query = User::with('lapangan')->where('role', 'admin');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('rfid_uid', 'like', "%{$search}%");
            });
        }

        if ($lapanganFilter) {
            $query->where('id_lapangan', $lapanganFilter);
        }

        $pegawais = $query->orderBy('nama')->paginate(10)->withQueryString();
        $lapangans = Lapangan::all();

        // Statistik ringkas
        $totalPegawai = User::where('role', 'admin')->count();
        $pegawaiDenganRfid = User::where('role', 'admin')->whereNotNull('rfid_uid')->where('rfid_uid', '!=', '')->count();
        $totalLapangan = $lapangans->count();

        return view('pages.admin.pegawai.index', compact(
            'pegawais',
            'lapangans',
            'search',
            'lapanganFilter',
            'totalPegawai',
            'pegawaiDenganRfid',
            'totalLapangan'
        ));
    }

    /**
     * Tampilkan formulir tambah akun pegawai baru
     */
    public function create()
    {
        $lapangans = Lapangan::where('status', 'aktif')->get();

        return view('pages.admin.pegawai.create', compact('lapangans'));
    }

    /**
     * Simpan akun pegawai baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'id_lapangan' => ['nullable', 'exists:lapangans,id'],
            'rfid_uid' => ['nullable', 'string', 'max:50', 'unique:users,rfid_uid'],
            'gaji_pokok' => ['nullable', 'numeric', 'min:0'],
            'tunjangan' => ['nullable', 'numeric', 'min:0'],
            'tarif_per_shift' => ['nullable', 'numeric', 'min:0'],
        ], [
            'nama.required' => 'Nama lengkap pegawai wajib diisi.',
            'email.required' => 'Email login wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'no_hp.required' => 'Nomor HP pegawai wajib diisi.',
            'password.required' => 'Password akun wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'rfid_uid.unique' => 'UID RFID ini sudah digunakan oleh akun lain.',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'id_lapangan' => $validated['id_lapangan'] ?: null,
            'rfid_uid' => $validated['rfid_uid'] ?: null,
            'gaji_pokok' => $validated['gaji_pokok'] ?? 2500000,
            'tunjangan' => $validated['tunjangan'] ?? 300000,
            'tarif_per_shift' => $validated['tarif_per_shift'] ?? 50000,
            'status_member' => 0,
        ]);

        return redirect()->route('admin.pegawai')->with('success', 'Akun pegawai baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit akun pegawai
     */
    public function edit($id)
    {
        $pegawai = User::where('role', 'admin')->findOrFail($id);
        $lapangans = Lapangan::all();

        return view('pages.admin.pegawai.edit', compact('pegawai', 'lapangans'));
    }

    /**
     * Perbarui data akun pegawai
     */
    public function update(Request $request, $id)
    {
        $pegawai = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pegawai->id)],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
            'id_lapangan' => ['nullable', 'exists:lapangans,id'],
            'rfid_uid' => ['nullable', 'string', 'max:50', Rule::unique('users', 'rfid_uid')->ignore($pegawai->id)],
            'gaji_pokok' => ['nullable', 'numeric', 'min:0'],
            'tunjangan' => ['nullable', 'numeric', 'min:0'],
            'tarif_per_shift' => ['nullable', 'numeric', 'min:0'],
        ], [
            'nama.required' => 'Nama lengkap pegawai wajib diisi.',
            'email.required' => 'Email login wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar di akun lain.',
            'no_hp.required' => 'Nomor HP pegawai wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'rfid_uid.unique' => 'UID RFID ini sudah digunakan oleh akun lain.',
        ]);

        $updateData = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'id_lapangan' => $validated['id_lapangan'] ?: null,
            'rfid_uid' => $validated['rfid_uid'] ?: null,
            'gaji_pokok' => $validated['gaji_pokok'] ?? 2500000,
            'tunjangan' => $validated['tunjangan'] ?? 300000,
            'tarif_per_shift' => $validated['tarif_per_shift'] ?? 50000,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $pegawai->update($updateData);

        return redirect()->route('admin.pegawai')->with('success', "Data akun pegawai {$pegawai->nama} berhasil diperbarui.");
    }

    /**
     * Hapus akun pegawai
     */
    public function destroy($id)
    {
        $pegawai = User::where('role', 'admin')->findOrFail($id);
        $nama = $pegawai->nama;

        $pegawai->delete();

        return redirect()->route('admin.pegawai')->with('success', "Akun pegawai {$nama} berhasil dihapus.");
    }
}
