@extends('layouts.admin')

@section('title', 'Laporan & Keuangan | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Laporan Penyewaan & Keuangan',
    'active' => 'laporan',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <!-- Role Access Banner -->
    @if($user->isAdmin())
      <div class="bg-teal-50 border border-teal-200/80 rounded-2xl p-4 flex items-center justify-between gap-4 text-sm text-teal-800">
        <div class="flex items-center gap-3">
          <div class="w-2.5 h-2.5 rounded-full bg-teal-600"></div>
          <span>Akses Pegawai Lapangan: Menampilkan laporan keuangan & penyewaan untuk <strong>{{ $assignedLapangan ? $assignedLapangan->nama : 'Lapangan Tugas' }}</strong>.</span>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-600 text-white shadow-2xs">Pegawai Bertugas</span>
      </div>
    @endif

    <!-- Metric Stat Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Pendapatan</span>
        <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-teal-700" id="lapTotalPendapatan">
          Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Berdasarkan filter aktif</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Transaksi Lunas</span>
        <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-emerald-600" id="lapTotalTransaksi">
          {{ $totalTransaksi ?? 0 }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Booking berhasil terbayar</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Dibatalkan</span>
        <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-rose-600" id="lapTotalBatal">
          {{ $totalBatal ?? 0 }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Pembatalan reservasi</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Rata-rata Transaksi</span>
        <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-ink" id="lapRataRata">
          Rp{{ number_format($rataRata ?? 0, 0, ',', '.') }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Nilai per sesi penyewaan</p>
      </div>
    </div>

    <!-- Filter Card -->
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-ink">Filter Laporan & Parameter Pencarian</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            {{ $user->isSuperAdmin() ? 'Filter data berdasarkan lapangan, bulan, status, dan rentang tanggal.' : 'Filter data pada lapangan tugas Anda.' }}
          </p>
        </div>
        <a href="{{ route('admin.laporan.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition self-start sm:self-auto shadow-sm">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <span>Export CSV (Excel)</span>
        </a>
      </div>

      <form class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3.5 items-end" method="GET" id="filterForm">
        @if($user->isSuperAdmin())
          <div class="lg:col-span-2">
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="filterLapangan">Filter Lapangan</label>
            <select class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" id="filterLapangan" name="lapangan_id" onchange="this.form.submit()">
              <option value="">Semua Lapangan</option>
              @foreach(($lapangans ?? []) as $lap)
                <option value="{{ $lap->id }}" {{ ($lapanganFilter ?? '') == $lap->id ? 'selected' : '' }}>
                  {{ $lap->nama }}
                </option>
              @endforeach
            </select>
          </div>
        @endif

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="filterBulan">Bulan</label>
          <select class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" id="filterBulan" name="bulan" onchange="this.form.submit()">
            <option value="">Semua Bulan</option>
            @foreach(($availableMonths ?? []) as $bulan)
              @php
                $label = \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y');
              @endphp
              <option value="{{ $bulan }}" {{ ($bulanFilter ?? '') === $bulan ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="filterStatus">Status</label>
          <select class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" id="filterStatus" name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="disetujui" {{ ($statusFilter ?? '') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="selesai" {{ ($statusFilter ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ ($statusFilter ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="filterDari">Tanggal Dari</label>
          <input class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" type="date" id="filterDari" name="tanggal_dari" value="{{ $tanggalDari ?? '' }}" onchange="this.form.submit()">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="filterSampai">Tanggal Sampai</label>
          <input class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" type="date" id="filterSampai" name="tanggal_sampai" value="{{ $tanggalSampai ?? '' }}" onchange="this.form.submit()">
        </div>

        <div class="sm:col-span-2 lg:col-span-1">
          <a href="{{ route('admin.laporan') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
            Reset
          </a>
        </div>
      </form>

      <!-- Active Filter Pill Badges -->
      <div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-slate-100 text-xs">
        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold">Parameter: {{ $filterLabel ?? 'Semua Data' }}</span>
        <span class="px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 font-semibold">Pendapatan: Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</span>
        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold">Transaksi: {{ $totalTransaksi ?? 0 }}</span>
        <span class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-800 font-semibold">Batal: {{ $totalBatal ?? 0 }}</span>
      </div>
    </section>

    <!-- Table 1: Laporan Penyewaan -->
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
      <div class="flex items-center justify-between gap-3 pb-5 border-b border-slate-100">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-ink">Rincian Laporan Penyewaan</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftar transaksi dan histori penyewaan lapangan futsal.</p>
        </div>
        <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">Data Operasional</span>
      </div>

      <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">No Transaksi</th>
              <th class="py-3 px-4">Tanggal Main</th>
              <th class="py-3 px-4">Pelanggan</th>
              <th class="py-3 px-4">Lapangan</th>
              <th class="py-3 px-4">Jam Sesi</th>
              <th class="py-3 px-4">Total Bayar</th>
              <th class="py-3 px-5 sm:px-6">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($laporanSewa ?? [] as $row)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-mono text-xs font-semibold text-slate-600">
                  #RSV-{{ str_pad($row->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}
                </td>
                <td class="py-3.5 px-4 font-semibold text-ink">
                  {{ $row->user->nama ?? '-' }}
                </td>
                <td class="py-3.5 px-4">
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                    {{ $row->lapangan->nama ?? 'Lapangan Utama' }}
                  </span>
                </td>
                <td class="py-3.5 px-4 font-mono text-xs text-slate-700">
                  {{ \Carbon\Carbon::parse($row->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($row->jam_selesai)->format('H:i') }}
                </td>
                <td class="py-3.5 px-4 font-bold text-ink">
                  Rp{{ number_format($row->total_harga, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-5 sm:px-6">
                  @if(in_array($row->status, ['disetujui', 'dibayar']))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      Disetujui
                    </span>
                  @elseif($row->status === 'selesai')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                      Selesai
                    </span>
                  @elseif($row->status === 'dibatalkan')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                      Dibatalkan
                    </span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                      {{ ucfirst($row->status) }}
                    </span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data penyewaan sesuai filter.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($laporanSewa->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-100">
          {{ $laporanSewa->links() }}
        </div>
      @endif
    </section>

    <!-- Table 2: Laporan Keuangan Bulanan -->
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
      <div class="flex items-center justify-between gap-3 pb-5 border-b border-slate-100">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-ink">Rekapitulasi Keuangan Bulanan</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ringkasan omset dan performa finansial per bulan.</p>
        </div>
        <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">Omset Bulanan</span>
      </div>

      <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">Bulan Periode</th>
              <th class="py-3 px-4">Transaksi Lunas</th>
              <th class="py-3 px-4">Reservasi Batal</th>
              <th class="py-3 px-5 sm:px-6 text-right">Total Pendapatan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($laporanBulanan ?? [] as $row)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-semibold text-ink">
                  {{ $row['label'] }}
                </td>
                <td class="py-3.5 px-4 text-emerald-700 font-semibold">
                  {{ $row['transaksi_lunas'] }} sesi
                </td>
                <td class="py-3.5 px-4 text-rose-600">
                  {{ $row['reservasi_batal'] }} sesi
                </td>
                <td class="py-3.5 px-5 sm:px-6 text-right font-extrabold text-teal-700">
                  Rp{{ number_format($row['pendapatan'], 0, ',', '.') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="py-8 text-center text-slate-400">Belum ada riwayat keuangan bulanan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
@endsection
