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
      <div class="bg-teal-50 border border-teal-200/80 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-start sm:items-center gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h2 class="text-base font-bold text-teal-900">Selamat bertugas, {{ $user->nama }}!</h2>
            <p class="text-xs sm:text-sm text-teal-700 mt-0.5">
              Anda ditugaskan mengelola <span class="font-semibold underline">{{ $user->lapangan ? $user->lapangan->nama : 'Lapangan Umum' }}</span>. Pantau ketersediaan slot dan validasi pembayaran tepat waktu.
            </p>
          </div>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-600 text-white self-start sm:self-auto shrink-0 shadow-2xs">
          Pegawai Bertugas
        </span>
      </div>
    @elseif($user->isSuperAdmin())
      <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-start sm:items-center gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-ink text-white flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <div>
            <h2 class="text-base font-bold text-ink">Selamat datang, Owner!</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
              Anda memiliki kontrol penuh atas seluruh lapangan, shift rotasi staf, audit keuangan, dan rekapitulasi penggajian pegawai.
            </p>
          </div>
        </div>
        <div class="flex flex-wrap gap-2 self-start sm:self-auto">
          <a href="{{ route('admin.lapangan') }}" class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-800 transition">
            Kelola Lapangan
          </a>
          <a href="{{ route('admin.penggajian') }}" class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
            Rekap Penggajian
          </a>
        </div>
      </div>
    @endif

    <!-- Metric Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
      <!-- Card 1: Reservasi Hari Ini -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Reservasi Hari Ini</span>
          <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
          <span class="text-3xl font-extrabold text-ink tracking-tight">{{ $totalReservasiHariIni }}</span>
          <span class="text-xs font-medium text-slate-500">Sesi Pertandingan</span>
        </div>
        <p class="text-[11px] text-slate-400 mt-2">Jadwal aktif booking hari ini</p>
      </div>

      <!-- Card 2: Menunggu Pembayaran -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Menunggu Bayar</span>
          <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
          <span class="text-3xl font-extrabold text-amber-600 tracking-tight">{{ $menungguPembayaran }}</span>
          <span class="text-xs font-medium text-slate-500">Invoice</span>
        </div>
        <p class="text-[11px] text-slate-400 mt-2">Perlu validasi kasir atau transfer</p>
      </div>

      <!-- Card 3: Member Aktif -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Member Aktif</span>
          <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
          <span class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ $totalMemberAktif }}</span>
          <span class="text-xs font-medium text-slate-500">Orang</span>
        </div>
        <p class="text-[11px] text-slate-400 mt-2">Pelanggan membership terdaftar</p>
      </div>

      <!-- Card 4: Pendapatan Bulan Ini -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Omset Bulan Ini</span>
          <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-3">
          <span class="text-2xl font-extrabold text-teal-700 tracking-tight block">
            Rp{{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
          </span>
        </div>
        <p class="text-[11px] text-slate-400 mt-2 truncate">
          {{ $user->isSuperAdmin() ? 'Akumulasi seluruh lapangan & member' : 'Lapangan penugasan Anda' }}
        </p>
      </div>
    </div>

    <!-- Status Operasional Lapangan Hari Ini -->
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-base sm:text-lg font-bold text-ink">Status Operasional Lapangan Hari Ini</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">Real-time</span>
          </div>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Otomatis sinkron dengan penugasan shift pegawai. Jika tidak ada pegawai bertugas, pemesanan otomatis ditutup.
          </p>
        </div>
        <a href="{{ route('admin.shift') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 transition shrink-0 self-start sm:self-auto shadow-2xs">
          <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
          </svg>
          <span>Atur Shift Lapangan</span>
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">
        @forelse($lapanganStatuses as $st)
          <div class="rounded-2xl border p-4.5 transition {{ $st['is_buka'] ? 'border-teal-200 bg-teal-50/40' : 'border-rose-200 bg-rose-50/40' }}">
            <div class="flex items-center justify-between mb-3">
              <span class="font-bold text-ink text-sm sm:text-base">{{ $st['lapangan']->nama }}</span>
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $st['is_buka'] ? 'bg-teal-600 text-white' : 'bg-rose-600 text-white' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $st['is_buka'] ? 'bg-teal-200' : 'bg-rose-200' }}"></span>
                {{ $st['is_buka'] ? 'BUKA' : 'TUTUP' }}
              </span>
            </div>

            <div class="text-xs space-y-1.5">
              @if($st['is_buka'])
                <span class="font-semibold text-teal-800 block">Staf Bertugas Hari Ini:</span>
                <ul class="space-y-1 text-slate-600 pl-1">
                  @foreach($st['petugas'] as $ptg)
                    <li class="flex items-center gap-2">
                      <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                      <span><strong>{{ $ptg->user->nama ?? '-' }}</strong></span>
                      <span class="text-[11px] px-1.5 py-0.5 rounded bg-white text-teal-800 border border-teal-200 font-medium capitalize">{{ $ptg->shift }}</span>
                    </li>
                  @endforeach
                </ul>
              @else
                <div class="text-rose-700 bg-rose-100/60 p-2.5 rounded-xl border border-rose-200/60 flex items-start gap-2">
                  <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <span>Tidak ada staf bertugas hari ini. Pemesanan publik dinonaktifkan otomatis.</span>
                </div>
              @endif
            </div>
          </div>
        @empty
          <div class="col-span-full py-8 text-center text-slate-400">Belum ada data lapangan terdaftar.</div>
        @endforelse
      </div>
    </section>

    <!-- Aktivitas Reservasi Terbaru -->
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-base sm:text-lg font-bold text-ink">Aktivitas Reservasi Terbaru</h2>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium">5 Transaksi Terakhir</span>
          </div>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pantau status transaksi yang baru masuk ke sistem.</p>
        </div>
        <a href="{{ url('/admin/reservasi') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition self-start sm:self-auto shadow-sm">
          <span>Buka Kelola Reservasi</span>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </a>
      </div>

      <!-- Modern Table -->
      <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">Pemesan</th>
              <th class="py-3 px-4">Lapangan</th>
              <th class="py-3 px-4">Tanggal Main</th>
              <th class="py-3 px-4">Jam Sesi</th>
              <th class="py-3 px-4">Total Biaya</th>
              <th class="py-3 px-5 sm:px-6">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($recentActivities as $activity)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-semibold text-ink">
                  {{ $activity->user->nama ?? 'Pengguna' }}
                </td>
                <td class="py-3.5 px-4">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $activity->lapangan ? $activity->lapangan->nama : 'Lapangan Utama' }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ \Carbon\Carbon::parse($activity->tanggal)->translatedFormat('d M Y') }}
                </td>
                <td class="py-3.5 px-4 font-mono text-xs text-slate-700">
                  {{ substr($activity->jam_mulai, 0, 5) }} - {{ substr($activity->jam_selesai, 0, 5) }}
                </td>
                <td class="py-3.5 px-4 font-bold text-ink">
                  Rp{{ number_format($activity->total_harga, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-5 sm:px-6">
                  @if ($activity->status === 'menunggu')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                      Menunggu Pembayaran
                    </span>
                  @elseif ($activity->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                      Menunggu Konfirmasi
                    </span>
                  @elseif (in_array($activity->status, ['disetujui', 'dibayar']))
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      Disetujui
                    </span>
                  @elseif ($activity->status === 'selesai')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                      Selesai
                    </span>
                  @elseif ($activity->status === 'dibatalkan')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                      Dibatalkan
                    </span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                      {{ ucfirst($activity->status) }}
                    </span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada aktivitas reservasi terbaru.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
@endsection
