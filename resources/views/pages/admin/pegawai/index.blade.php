@extends('layouts.admin')

@section('title', 'Kelola Akun Pegawai | Superadmin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Kelola Akun Pegawai',
    'active' => 'pegawai',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <!-- Stat Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="glass-card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-[#2F6BFF] border border-blue-500/20 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
        <div>
          <div class="text-xs font-bold text-[#5B6478] uppercase tracking-wider">Total Pegawai</div>
          <div class="text-2xl font-extrabold text-[#12162B]">{{ $totalPegawai }} <span class="text-xs font-semibold text-[#5B6478]">Orang</span></div>
        </div>
      </div>

      <div class="glass-card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-[#22B14C] border border-emerald-500/20 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </div>
        <div>
          <div class="text-xs font-bold text-[#5B6478] uppercase tracking-wider">Kartu RFID Terdaftar</div>
          <div class="text-2xl font-extrabold text-[#12162B]">{{ $pegawaiDenganRfid }} <span class="text-xs font-semibold text-[#5B6478]">Akun</span></div>
        </div>
      </div>

      <div class="glass-card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-[#7B5CE6] border border-purple-500/20 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
        </div>
        <div>
          <div class="text-xs font-bold text-[#5B6478] uppercase tracking-wider">Total Lapangan</div>
          <div class="text-2xl font-extrabold text-[#12162B]">{{ $totalLapangan }} <span class="text-xs font-semibold text-[#5B6478]">Arena</span></div>
        </div>
      </div>
    </div>

    <!-- Main Table Card -->
    <section class="glass-card p-5 sm:p-7">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/60">
        <div>
          <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-primary-100 text-[#2F6BFF] border border-primary-200 mb-2">
            <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Hak Akses Superadmin</span>
          </div>
          <h2 class="text-lg sm:text-xl font-extrabold text-[#12162B]">Daftar Akun Pegawai (Admin Lapangan)</h2>
          <p class="text-xs sm:text-sm text-[#5B6478] mt-0.5">
            Kelola data staf yang bertugas melayani reservasi offline, jadwal shift, dan absensi RFID.
          </p>
        </div>

        <a href="{{ route('admin.pegawai.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs sm:text-sm font-extrabold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white shadow-md shadow-blue-500/25 transition transform hover:-translate-y-0.5 shrink-0">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
          </svg>
          <span>Tambah Akun Pegawai Baru</span>
        </a>
      </div>

      <!-- Feedback Alerts -->
      @if(session('success'))
        <div class="mt-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
          <svg class="w-5 h-5 text-[#22B14C] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if(session('error'))
        <div class="mt-4 bg-rose-500/10 border border-rose-500/30 text-rose-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
          <svg class="w-5 h-5 text-[#EF4F5F] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      <!-- Search & Filters -->
      <form action="{{ route('admin.pegawai') }}" method="GET" class="mt-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#8A93A6]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pegawai, email, no HP, atau UID RFID..." class="w-full bg-white/70 backdrop-blur-md rounded-xl pl-9 pr-4 py-2.5 text-xs sm:text-sm text-[#12162B] placeholder-[#8A93A6] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
        </div>

        <select name="lapangan_id" onchange="this.form.submit()" class="bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition">
          <option value="">Semua Lapangan</option>
          @foreach($lapangans as $lap)
            <option value="{{ $lap->id }}" {{ $lapanganFilter == $lap->id ? 'selected' : '' }}>
              {{ $lap->nama }}
            </option>
          @endforeach
        </select>

        @if($search || $lapanganFilter)
          <a href="{{ route('admin.pegawai') }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-white/60 hover:bg-white text-[#5B6478] border border-white/80 flex items-center justify-center transition">
            Reset
          </a>
        @endif
      </form>

      <!-- Table Content -->
      <div class="mt-5 overflow-x-auto rounded-2xl border border-white/70 bg-white/40 backdrop-blur-md">
        <table class="w-full text-left text-xs sm:text-sm text-[#12162B]">
          <thead>
            <tr class="border-b border-white/70 bg-white/60 text-[11px] font-extrabold uppercase tracking-wider text-[#5B6478]">
              <th class="py-3.5 px-4">Pegawai</th>
              <th class="py-3.5 px-4">Kontak & Email</th>
              <th class="py-3.5 px-4">Penugasan Lapangan</th>
              <th class="py-3.5 px-4">Kartu RFID</th>
              <th class="py-3.5 px-4">Standar Penggajian</th>
              <th class="py-3.5 px-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/60">
            @forelse($pegawais as $pegawai)
              <tr class="hover:bg-white/60 transition">
                <td class="py-4 px-4 font-bold">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#2F6BFF] to-[#3A4BD1] text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
                      {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                    </div>
                    <div>
                      <div class="font-extrabold text-[#12162B] text-sm">{{ $pegawai->nama }}</div>
                      <div class="text-[11px] font-medium text-[#2F6BFF]">Admin Operasional</div>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-4">
                  <div class="font-medium text-[#12162B]">{{ $pegawai->email }}</div>
                  <div class="text-xs text-[#5B6478] mt-0.5">{{ $pegawai->no_hp }}</div>
                </td>
                <td class="py-4 px-4">
                  @if($pegawai->lapangan)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-[#2F6BFF] border border-blue-500/20">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#2F6BFF]"></span>
                      {{ $pegawai->lapangan->nama }}
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-[#F5A524] border border-amber-500/20">
                      Belum Ditugaskan
                    </span>
                  @endif
                </td>
                <td class="py-4 px-4">
                  @if($pegawai->rfid_uid)
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-900/5 text-[#1E2A78] border border-slate-300/40">
                      <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3" />
                      </svg>
                      <span>{{ $pegawai->rfid_uid }}</span>
                    </div>
                  @else
                    <span class="text-xs text-[#8A93A6] italic">Belum ada RFID</span>
                  @endif
                </td>
                <td class="py-4 px-4">
                  <div class="text-xs space-y-0.5">
                    <div><span class="text-[#5B6478]">Gaji Pokok:</span> <strong class="text-[#12162B]">Rp{{ number_format($pegawai->gaji_pokok ?: 2500000, 0, ',', '.') }}</strong></div>
                    <div><span class="text-[#5B6478]">Tunjangan:</span> <strong class="text-[#12162B]">Rp{{ number_format($pegawai->tunjangan ?: 300000, 0, ',', '.') }}</strong></div>
                    <div><span class="text-[#5B6478]">Shift:</span> <strong class="text-[#2F6BFF]">Rp{{ number_format($pegawai->tarif_per_shift ?: 50000, 0, ',', '.') }}</strong></div>
                  </div>
                </td>
                <td class="py-4 px-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="p-2 rounded-xl text-[#2F6BFF] hover:bg-blue-500/10 transition" title="Edit Akun Pegawai">
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a>

                    <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pegawai {{ $pegawai->nama }}?');" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="p-2 rounded-xl text-[#EF4F5F] hover:bg-rose-500/10 transition" title="Hapus Akun Pegawai">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-10 text-center text-[#5B6478]">
                  <div class="flex flex-col items-center justify-center gap-2">
                    <svg class="w-10 h-10 text-[#8A93A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="font-semibold text-sm">Belum ada akun pegawai yang terdaftar.</p>
                    <a href="{{ route('admin.pegawai.create') }}" class="text-xs text-[#2F6BFF] hover:underline font-bold mt-1">
                      + Tambah Akun Pegawai Sekarang
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination Links -->
      <div class="mt-5">
        {{ $pegawais->links() }}
      </div>
    </section>
  </main>
@endsection
