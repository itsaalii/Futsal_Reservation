@extends('layouts.admin')

@section('title', 'Kelola Lapangan | Superadmin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Kelola Data Lapangan',
    'active' => 'lapangan',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <section class="glass-card p-5 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-white/60">
        <div>
          <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-primary-100 text-[#2F6BFF] border border-primary-200 mb-2">
            <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Hak Akses Superadmin</span>
          </div>
          <h2 class="text-base sm:text-lg font-bold text-[#12162B]">Daftar Lapangan Futsal</h2>
          <p class="text-xs sm:text-sm text-[#5B6478] mt-0.5">
            Kelola master data lapangan, tipe lantai, status ketersediaan, serta tarif sewa siang dan malam.
          </p>
        </div>
        <a href="{{ route('admin.lapangan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition self-start sm:self-auto shadow-sm shadow-blue-500/25">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Tambah Lapangan Baru</span>
        </a>
      </div>

      <!-- Feedback Alerts -->
      @if(session('success'))
        <div class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
          <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if(session('error'))
        <div class="mt-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
          <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      <!-- Court Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
        @forelse($lapangans as $item)
          <div class="bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-2xs hover:shadow-md transition flex flex-col p-5 sm:p-6">
            <div class="flex items-start justify-between gap-2 mb-3">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $item->status === 'aktif' ? 'bg-emerald-500/10 text-[#22B14C] border border-emerald-300/40' : ($item->status === 'maintenance' ? 'bg-amber-500/10 text-[#F5A524] border border-amber-300/40' : 'bg-white/60 text-[#5B6478]') }}">
                {{ $item->status }}
              </span>
              <span class="font-mono text-xs text-[#8A93A6]">#LAP-{{ str_pad($item->id, 2, '0', STR_PAD_LEFT) }}</span>
            </div>

            <h3 class="text-base font-bold text-[#12162B]">{{ $item->nama }}</h3>
            <div class="flex items-center gap-1.5 text-xs text-[#2F6BFF] font-semibold mt-1 mb-2">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
              <span>{{ $item->tipe_lantai }}</span>
            </div>

            <p class="text-xs text-[#5B6478] flex-1 mb-4 line-clamp-2">
              {{ $item->deskripsi ?: 'Tidak ada deskripsi spesifik.' }}
            </p>

            <!-- Pricing Box -->
            <div class="bg-white/50 rounded-xl p-3.5 sm:p-4 border border-white/70 space-y-2 text-xs mb-4">
              <div class="flex items-center justify-between">
                <span class="text-[#5B6478]">Tarif Siang (07-17):</span>
                <span class="font-bold text-[#12162B]">Rp {{ number_format($item->tarif_siang, 0, ',', '.') }}/jam</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[#5B6478]">Tarif Malam (17-23):</span>
                <span class="font-bold text-[#2F6BFF]">Rp {{ number_format($item->tarif_malam, 0, ',', '.') }}/jam</span>
              </div>
            </div>

            <!-- Card Bottom Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
              <div class="text-slate-500">
                <span><strong>{{ $item->reservasis_count ?? 0 }}</strong> booking</span> • 
                <span><strong>{{ $item->pegawai_count ?? 0 }}</strong> staf</span>
              </div>
              <div class="flex items-center gap-1.5">
                <a href="{{ route('admin.lapangan.edit', $item->id) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                  Edit
                </a>
                <form action="{{ route('admin.lapangan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition">
                    Hapus
                  </button>
                </form>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full py-12 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-300">
            <p class="text-sm text-slate-500 mb-3">Belum ada master data lapangan futsal.</p>
            <a href="{{ route('admin.lapangan.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
              Buat Lapangan Pertama
            </a>
          </div>
        @endforelse
      </div>
    </section>
  </main>
@endsection
