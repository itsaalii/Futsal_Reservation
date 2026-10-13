<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::withCount(['reservasis', 'pegawai'])->get();
        return view('pages.admin.lapangan', compact('lapangans'));
    }

    public function create()
    {
        return view('pages.admin.lapangan-form', [
            'lapangan' => null,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe_lantai' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tarif_siang' => 'required|numeric|min:0',
            'tarif_malam' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,maintenance,nonaktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('lapangan', 'public');
        }

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan')->with('success', 'Lapangan baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        return view('pages.admin.lapangan-form', [
            'lapangan' => $lapangan,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe_lantai' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tarif_siang' => 'required|numeric|min:0',
            'tarif_malam' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,maintenance,nonaktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('lapangan', 'public');
        }

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan')->with('success', 'Data lapangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        
        // Hapus lapangan
        $lapangan->delete();

        return redirect()->route('admin.lapangan')->with('success', 'Lapangan berhasil dihapus.');
    }
}
