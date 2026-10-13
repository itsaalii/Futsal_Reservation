@extends('layouts.admin')

@section('title', ($isEdit ? 'Edit Lapangan' : 'Tambah Lapangan') . ' | Superadmin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => $isEdit ? 'Edit Data Lapangan' : 'Tambah Lapangan Baru',
    'active' => 'lapangan',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="max-w-2xl mx-auto space-y-5">
      <a href="{{ route('admin.lapangan') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-ink transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Master Lapangan</span>
      </a>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div class="pb-5 border-b border-slate-100 mb-6">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
            <span>👑</span>
            <span>Hak Akses Superadmin</span>
          </div>
          <h2 class="text-lg font-bold text-ink">{{ $isEdit ? 'Perbarui Lapangan #' . $lapangan->id : 'Form Lapangan Baru' }}</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Isi formulir lengkap untuk konfigurasi lapangan futsal.</p>
        </div>

        @if ($errors->any())
          <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-sm">
            <ul class="list-disc pl-4 space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ $isEdit ? route('admin.lapangan.update', $lapangan->id) : route('admin.lapangan.store') }}" method="POST" class="space-y-4">
          @csrf
          @if ($isEdit)
            @method('PUT')
          @endif

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
              Nama Lapangan <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="nama" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" placeholder="Contoh: Lapangan 1 (Sintetis Vinyl)" value="{{ old('nama', $lapangan->nama ?? '') }}" required>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                Tipe Lantai / Rumput <span class="text-rose-500">*</span>
              </label>
              <input type="text" name="tipe_lantai" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" placeholder="Contoh: Rumput Sintetis / Vinyl Interlock" value="{{ old('tipe_lantai', $lapangan->tipe_lantai ?? 'Rumput Sintetis') }}" required>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                Status Operasional <span class="text-rose-500">*</span>
              </label>
              <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" required>
                <option value="aktif" {{ old('status', $lapangan->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif (Siap Booking)</option>
                <option value="maintenance" {{ old('status', $lapangan->status ?? '') === 'maintenance' ? 'selected' : '' }}>Maintenance (Perawatan)</option>
                <option value="nonaktif" {{ old('status', $lapangan->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                Tarif Siang (07:00 - 16:59) / Jam <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-slate-400 font-semibold">Rp</span>
                <input type="number" name="tarif_siang" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" placeholder="60000" value="{{ old('tarif_siang', $lapangan->tarif_siang ?? 60000) }}" required min="0" step="5000">
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                Tarif Malam (17:00 - 22:59) / Jam <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-slate-400 font-semibold">Rp</span>
                <input type="number" name="tarif_malam" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" placeholder="70000" value="{{ old('tarif_malam', $lapangan->tarif_malam ?? 70000) }}" required min="0" step="5000">
              </div>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Deskripsi / Fasilitas Lapangan</label>
            <textarea name="deskripsi" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" placeholder="Tuliskan spesifikasi lapangan, ukuran, kelengkapan gawang, lampu, dsb.">{{ old('deskripsi', $lapangan->deskripsi ?? '') }}</textarea>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.lapangan') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
              Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
              {{ $isEdit ? 'Simpan Perubahan' : 'Buat Lapangan Baru' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
@endsection
