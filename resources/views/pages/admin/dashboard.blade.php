@extends('layouts.admin')

@section('title', 'Admin Dashboard | Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Dashboard Overview',
    'active' => 'dashboard',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <!-- Welcome Role Banner -->
    @if($user->isAdmin())
      <div class="glass-card p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3.5">
          <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#2F6BFF] to-[#3A4BD1] text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h2 class="text-base font-bold text-[#1E2A78]">Selamat bertugas, {{ $user->nama }}!</h2>
            <p class="text-xs sm:text-sm text-[#5B6478] mt-0.5">
              Anda ditugaskan mengelola <span class="font-bold text-[#2F6BFF] underline">{{ $user->lapangan ? $user->lapangan->nama : 'Lapangan Umum' }}</span>. Pantau ketersediaan slot dan validasi pembayaran tepat waktu.
            </p>
          </div>
        </div>
        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#2F6BFF] text-white self-start sm:self-auto shrink-0 shadow-sm shadow-blue-500/25">
          Pegawai Bertugas
        </span>
      </div>
    @elseif($user->isSuperAdmin())
      <div class="glass-card p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3.5">
          <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#2F6BFF] to-[#7B5CE6] text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <div>
            <h2 class="text-base font-bold text-[#1E2A78]">Selamat datang, Owner!</h2>
            <p class="text-xs sm:text-sm text-[#5B6478] mt-0.5">
              Anda memiliki kontrol penuh atas seluruh lapangan, shift rotasi staf, audit keuangan, dan rekapitulasi penggajian pegawai.
            </p>
          </div>
        </div>
        <div class="flex flex-wrap gap-2.5 self-start sm:self-auto">
          <a href="{{ route('admin.lapangan') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-white/70 hover:bg-white text-[#12162B] border border-white/80 transition shadow-2xs">
            Kelola Lapangan
          </a>
          <a href="{{ route('admin.penggajian') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition shadow-sm shadow-blue-500/25">
            Rekap Penggajian
          </a>
        </div>
      </div>
    @endif

    <!-- Metric Stat Cards Grid (styled like desain.jpeg widgets) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
      <!-- Card 1: Reservasi Hari Ini -->
      <div class="glass-card p-5 sm:p-6 hover:translate-y-[-2px] transition-all">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[#5B6478] uppercase tracking-wider">Reservasi Hari Ini</span>
          <div class="w-8 h-8 rounded-lg bg-primary-100/70 text-[#2F6BFF] flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
        <div class="mt-3 flex items-center justify-between">
          <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-[#12162B] tracking-tight">{{ $totalReservasiHariIni }}</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/10 text-[#22B14C]">
              +8%
            </span>
          </div>
          <!-- Mini Sparkline visualization from desain.jpeg -->
          <div class="flex items-end gap-1 h-7">
            <div class="w-1.5 bg-[#2F6BFF]/30 rounded-t h-3"></div>
            <div class="w-1.5 bg-[#2F6BFF]/40 rounded-t h-4"></div>
            <div class="w-1.5 bg-[#2F6BFF]/60 rounded-t h-3"></div>
            <div class="w-1.5 bg-[#2F6BFF]/80 rounded-t h-6"></div>
            <div class="w-1.5 bg-[#2F6BFF] rounded-t h-7"></div>
          </div>
        </div>
        <p class="text-[11px] text-[#5B6478] mt-2">Jadwal aktif booking hari ini</p>
      </div>

      <!-- Card 2: Menunggu Pembayaran -->
      <div class="glass-card p-5 sm:p-6 hover:translate-y-[-2px] transition-all">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[#5B6478] uppercase tracking-wider">Menunggu Bayar</span>
          <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-[#F5A524] flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-3 flex items-center justify-between">
          <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-[#F5A524] tracking-tight">{{ $menungguPembayaran }}</span>
            <span class="text-xs font-semibold text-[#5B6478]">Invoice</span>
          </div>
          <!-- Donut Progress Accent matching desain.jpeg Task Progress -->
          <div class="relative w-7 h-7">
            <svg class="w-7 h-7 transform -rotate-90" viewBox="0 0 36 36">
              <path class="text-slate-200" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
              <path class="text-[#F5A524]" stroke-dasharray="60, 100" stroke-width="4" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
            </svg>
          </div>
        </div>
        <p class="text-[11px] text-[#5B6478] mt-2">Perlu validasi kasir atau transfer</p>
      </div>

      <!-- Card 3: Member Aktif -->
      <div class="glass-card p-5 sm:p-6 hover:translate-y-[-2px] transition-all">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[#5B6478] uppercase tracking-wider">Member Aktif</span>
          <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-[#22B14C] flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-3 flex items-center justify-between">
          <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-[#12162B] tracking-tight">{{ $totalMemberAktif }}</span>
            <span class="text-xs font-semibold text-[#5B6478]">Orang</span>
          </div>
          <!-- Progress Bar Indicator -->
          <div class="w-14 bg-slate-200/70 rounded-full h-2 overflow-hidden">
            <div class="bg-gradient-to-r from-[#2F6BFF] to-[#22B14C] h-full rounded-full" style="width: 84%"></div>
          </div>
        </div>
        <p class="text-[11px] text-[#5B6478] mt-2">Pelanggan membership terdaftar</p>
      </div>

      <!-- Card 4: Pendapatan Bulan Ini -->
      <div class="glass-card p-5 sm:p-6 hover:translate-y-[-2px] transition-all">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[#5B6478] uppercase tracking-wider">Omset Bulan Ini</span>
          <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-[#2F6BFF] flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-3">
          <span class="text-2xl font-extrabold text-[#1E2A78] tracking-tight block">
            Rp{{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
          </span>
        </div>
        <p class="text-[11px] text-[#5B6478] mt-2 truncate">
          {{ $user->isSuperAdmin() ? 'Akumulasi seluruh lapangan & member' : 'Lapangan penugasan Anda' }}
        </p>
      </div>
    </div>

    <!-- Status Operasional Lapangan Hari Ini -->
    <section class="glass-card p-5 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-white/60">
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-base sm:text-lg font-bold text-[#12162B]">Status Operasional Lapangan Hari Ini</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/70 text-[#2F6BFF] border border-white/80">Real-time</span>
          </div>
          <p class="text-xs sm:text-sm text-[#5B6478] mt-0.5">
            Otomatis sinkron dengan penugasan shift pegawai. Jika tidak ada pegawai bertugas, pemesanan otomatis ditutup.
          </p>
        </div>
        <a href="{{ route('admin.shift') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-white/70 hover:bg-white text-[#12162B] border border-white/80 transition shrink-0 self-start sm:self-auto shadow-2xs">
          <svg class="w-4 h-4 text-[#5B6478]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
          </svg>
          <span>Atur Shift Lapangan</span>
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">
        @forelse($lapanganStatuses as $st)
          <div class="rounded-2xl border p-5 sm:p-6 transition {{ $st['is_buka'] ? 'border-white/80 bg-white/60 backdrop-blur-md' : 'border-rose-200/80 bg-rose-50/40 backdrop-blur-md' }}">
            <div class="flex items-center justify-between mb-3">
              <span class="font-bold text-[#12162B] text-sm sm:text-base">{{ $st['lapangan']->nama }}</span>
              <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $st['is_buka'] ? 'bg-[#22B14C] text-white shadow-2xs' : 'bg-[#EF4F5F] text-white shadow-2xs' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                {{ $st['is_buka'] ? 'BUKA' : 'TUTUP' }}
              </span>
            </div>

            <div class="text-xs space-y-1.5">
              @if($st['is_buka'])
                <span class="font-semibold text-[#1E2A78] block">Staf Bertugas Hari Ini:</span>
                <ul class="space-y-1 text-[#5B6478] pl-1">
                  @foreach($st['petugas'] as $ptg)
                    <li class="flex items-center gap-2">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#2F6BFF]"></span>
                      <span><strong>{{ $ptg->user->nama ?? '-' }}</strong></span>
                      <span class="text-[11px] px-2 py-0.5 rounded-full bg-white text-[#2F6BFF] border border-white/80 font-bold capitalize">{{ $ptg->shift }}</span>
                    </li>
                  @endforeach
                </ul>
              @else
                <div class="text-[#EF4F5F] bg-rose-50/70 p-3.5 rounded-xl border border-rose-200/60 flex items-start gap-2">
                  <svg class="w-4 h-4 text-[#EF4F5F] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <span>Tidak ada staf bertugas hari ini. Pemesanan publik dinonaktifkan otomatis.</span>
                </div>
              @endif
            </div>
          </div>
        @empty
          <div class="col-span-full py-8 text-center text-[#8A93A6]">Belum ada data lapangan terdaftar.</div>
        @endforelse
      </div>
    </section>

    <!-- Aktivitas Reservasi Terbaru -->
    <section class="glass-card p-5 sm:p-6 overflow-hidden">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-white/60">
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-base sm:text-lg font-bold text-[#12162B]">Aktivitas Reservasi Terbaru</h2>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-white/70 text-[#5B6478] font-bold border border-white/80">5 Transaksi Terakhir</span>
          </div>
          <p class="text-xs sm:text-sm text-[#5B6478] mt-0.5">Pantau status transaksi yang baru masuk ke sistem.</p>
        </div>
        <a href="{{ url('/admin/reservasi') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition self-start sm:self-auto shadow-sm shadow-blue-500/25">
          <span>Buka Kelola Reservasi</span>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </a>
      </div>

      <!-- Modern Table with Frosted Styling -->
      <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-white/60 bg-white/40 text-[#5B6478] font-bold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">Pemesan</th>
              <th class="py-3 px-4">Lapangan</th>
              <th class="py-3 px-4">Tanggal Main</th>
              <th class="py-3 px-4">Jam Sesi</th>
              <th class="py-3 px-4">Total Biaya</th>
              <th class="py-3 px-5 sm:px-6">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/50">
            @forelse($recentActivities as $activity)
              <tr class="hover:bg-white/40 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-bold text-[#12162B]">
                  {{ $activity->user->nama ?? 'Pengguna' }}
                </td>
                <td class="py-3.5 px-4">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-white/70 text-[#1E2A78] border border-white/80">
                    {{ $activity->lapangan ? $activity->lapangan->nama : 'Lapangan Utama' }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-[#5B6478]">
                  {{ \Carbon\Carbon::parse($activity->tanggal)->translatedFormat('d M Y') }}
                </td>
                <td class="py-3.5 px-4 font-mono text-xs text-[#12162B]">
                  {{ substr($activity->jam_mulai, 0, 5) }} - {{ substr($activity->jam_selesai, 0, 5) }}
                </td>
                <td class="py-3.5 px-4 font-extrabold text-[#12162B]">
                  Rp{{ number_format($activity->total_harga, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-5 sm:px-6">
                  @if ($activity->status === 'menunggu')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-[#F5A524] border border-amber-300/40">
                      Menunggu Bayar
                    </span>
                  @elseif ($activity->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-[#3B82F6] border border-blue-300/40">
                      Konfirmasi
                    </span>
                  @elseif (in_array($activity->status, ['disetujui', 'dibayar']))
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-[#22B14C] border border-emerald-300/40">
                      Disetujui
                    </span>
                  @elseif ($activity->status === 'selesai')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-primary-100 text-[#2F6BFF] border border-primary-200">
                      Selesai
                    </span>
                  @elseif ($activity->status === 'dibatalkan')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-[#EF4F5F] border border-rose-300/40">
                      Dibatalkan
                    </span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-white/60 text-[#5B6478]">
                      {{ ucfirst($activity->status) }}
                    </span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-[#8A93A6]">Belum ada aktivitas reservasi terbaru.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
@endsection
