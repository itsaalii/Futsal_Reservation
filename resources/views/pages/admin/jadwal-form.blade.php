@extends('layouts.admin')

@section('title', 'Form Jadwal Lapangan | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Form Jadwal Lapangan',
    'active' => 'jadwal',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="max-w-2xl mx-auto space-y-5">
      <a href="{{ url('/admin/jadwal') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-ink transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke List Jadwal</span>
      </a>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div class="pb-5 border-b border-slate-100 mb-6">
          <h2 class="text-lg font-bold text-ink">Input Jadwal Lapangan</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tentukan slot waktu dan ketersediaan lapangan.</p>
        </div>

        <form id="jadwalForm" class="space-y-4" method="POST" action="{{ route('buat-jadwal') }}">
          @csrf
          <input type="hidden" id="jadwalId" />

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="jadwalTanggal">Tanggal</label>
            <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" type="date" id="jadwalTanggal" required name="tanggal"/>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="jadwalJamMulai">Jam Mulai</label>
              <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" type="time" id="jadwalJamMulai" required name="jam_mulai"/>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="jadwalJamSelesai">Jam Selesai</label>
              <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" type="time" id="jadwalJamSelesai" required name="jam_selesai" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="jadwalLapangan">Nama Lapangan</label>
              <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" type="text" id="jadwalLapangan" placeholder="Lapangan A" required name="nama_lapangan" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="jadwalHarga">Harga / Jam</label>
              <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" type="number" id="jadwalHarga" placeholder="200000" required name="harga_per_jam"/>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="jadwalStatus">Status</label>
              <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="jadwalStatus" name="status">
                <option value="Tersedia">Tersedia</option>
                <option value="Penuh">Penuh</option>
                <option value="Perawatan">Perawatan</option>
              </select>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ url('/admin/jadwal') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
              Batal
            </a>
            <button class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm" type="submit">
              Simpan Jadwal
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
@endsection
