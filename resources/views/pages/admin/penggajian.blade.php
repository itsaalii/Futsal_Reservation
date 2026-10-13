@extends('layouts.admin')

@section('title', 'Rekapitulasi Penggajian Pegawai | Superadmin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Rekapitulasi Penggajian Pegawai',
    'active' => 'penggajian',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
        <div>
          <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
            <span>👑</span>
            <span>Hak Akses Superadmin</span>
          </div>
          <h2 class="text-base sm:text-lg font-bold text-ink">Payroll & Rekapitulasi Gaji Pegawai</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Dihitung otomatis berdasarkan rekam absensi RFID, shift terjadwal, gaji pokok, serta insentif kehadiran pegawai.
          </p>
        </div>
        <div class="flex flex-wrap gap-2.5 self-start sm:self-auto">
          <form action="{{ route('admin.penggajian.generate') }}" method="POST">
            @csrf
            <input type="hidden" name="periode" value="{{ $periode }}">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              <span>Hitung Ulang Kehadiran & Gaji</span>
            </button>
          </form>
          <a href="{{ route('admin.penggajian.export', ['periode' => $periode]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 transition shadow-2xs">
            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Export CSV</span>
          </a>
        </div>
      </div>

      @if(session('success'))
        <div class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
          <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <!-- Filter Periode Bulan -->
      <form method="GET" action="{{ route('admin.penggajian') }}" class="my-5 flex flex-wrap items-end gap-3 max-w-md">
        <div class="flex-1 min-w-[200px]">
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Periode Penggajian:</label>
          <input type="month" name="periode" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" value="{{ $periode }}" onchange="this.form.submit()">
        </div>
        <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold bg-slate-900 hover:bg-slate-800 text-white transition">
          Terapkan
        </button>
      </form>

      <!-- KPI Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-slate-50/80 rounded-2xl border border-slate-200/80 p-4.5">
          <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Anggaran Gaji</span>
          <div class="mt-2 text-2xl font-extrabold text-ink">
            Rp {{ number_format($totalPengeluaranGaji, 0, ',', '.') }}
          </div>
          <p class="text-[11px] text-slate-400 mt-1">Periode {{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y') }}</p>
        </div>

        <div class="bg-slate-50/80 rounded-2xl border border-slate-200/80 p-4.5">
          <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Jumlah Pegawai</span>
          <div class="mt-2 text-2xl font-extrabold text-ink">
            {{ $totalPegawaiTergaji }} Pegawai
          </div>
          <p class="text-[11px] text-slate-400 mt-1">Staff operasional aktif</p>
        </div>

        <div class="bg-emerald-50/60 rounded-2xl border border-emerald-200/80 p-4.5">
          <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Sudah Dibayarkan</span>
          <div class="mt-2 text-2xl font-extrabold text-emerald-700">
            Rp {{ number_format($totalSudahDibayar, 0, ',', '.') }}
          </div>
          <p class="text-[11px] text-emerald-600 mt-1">Status Lunas</p>
        </div>

        <div class="bg-amber-50/60 rounded-2xl border border-amber-200/80 p-4.5">
          <span class="text-xs font-bold text-amber-800 uppercase tracking-wider block">Menunggu Bayar</span>
          <div class="mt-2 text-2xl font-extrabold text-amber-700">
            Rp {{ number_format($totalMenungguBayar, 0, ',', '.') }}
          </div>
          <p class="text-[11px] text-amber-600 mt-1">Status Pending</p>
        </div>
      </div>

      <!-- Tabel Penggajian -->
      <div class="-mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">Pegawai</th>
              <th class="py-3 px-4">Lapangan Tugas</th>
              <th class="py-3 px-4">Kehadiran (Shift)</th>
              <th class="py-3 px-4">Gaji Pokok</th>
              <th class="py-3 px-4">Tunjangan</th>
              <th class="py-3 px-4">Total Gaji</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-5 sm:px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($penggajians as $gaji)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6">
                  <div class="font-bold text-ink">{{ $gaji->user->nama ?? 'Pegawai' }}</div>
                  <div class="text-xs text-slate-400 font-mono mt-0.5">RFID: {{ $gaji->user->rfid_uid ?? '-' }}</div>
                </td>
                <td class="py-3.5 px-4">
                  <span class="px-2.5 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                    {{ $gaji->user->lapangan ? $gaji->user->lapangan->nama : 'Umum / Rotasi' }}
                  </span>
                </td>
                <td class="py-3.5 px-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                    {{ $gaji->total_kehadiran }} Shift Hadir
                  </span>
                </td>
                <td class="py-3.5 px-4 text-slate-600">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                <td class="py-3.5 px-4 text-slate-600">Rp {{ number_format($gaji->tunjangan, 0, ',', '.') }}</td>
                <td class="py-3.5 px-4 font-bold text-teal-700 text-base">
                  Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-4">
                  @if($gaji->status === 'dibayar')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      LUNAS
                    </span>
                    <div class="text-[10px] text-slate-400 mt-0.5">
                      {{ $gaji->tanggal_bayar ? \Carbon\Carbon::parse($gaji->tanggal_bayar)->format('d/m/Y') : '' }}
                    </div>
                  @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                      PENDING
                    </span>
                  @endif
                </td>
                <td class="py-3.5 px-5 sm:px-6 text-right">
                  <div class="inline-flex items-center gap-1.5 justify-end">
                    <button type="button" onclick="openModal('modalSlip{{ $gaji->id }}')" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                      Slip Gaji
                    </button>

                    @if($gaji->status !== 'dibayar')
                      <form action="{{ route('admin.penggajian.bayar', $gaji->id) }}" method="POST" onsubmit="return confirm('Tandai gaji untuk {{ $gaji->user->nama }} sudah dibayar?');">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-2xs">
                          Bayar
                        </button>
                      </form>
                    @endif
                  </div>

                  <!-- Modal Slip Gaji -->
                  <div id="modalSlip{{ $gaji->id }}" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden text-left">
                    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
                      <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                          <h3 class="font-bold text-lg text-ink">Slip Gaji Pegawai</h3>
                          <p class="text-xs text-slate-400">Jaya Futsal - Rekapitulasi Payroll</p>
                        </div>
                        <button type="button" onclick="closeModal('modalSlip{{ $gaji->id }}')" class="p-1 rounded-lg text-slate-400 hover:text-ink hover:bg-slate-100 transition">
                          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>

                      <div class="mt-4 space-y-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-1.5 text-xs">
                          <div class="flex justify-between"><span class="text-slate-500">Nama Pegawai:</span><strong class="text-ink">{{ $gaji->user->nama }}</strong></div>
                          <div class="flex justify-between"><span class="text-slate-500">Jabatan:</span><span class="text-slate-700">Pegawai ({{ $gaji->user->lapangan ? $gaji->user->lapangan->nama : 'Umum' }})</span></div>
                          <div class="flex justify-between"><span class="text-slate-500">Periode:</span><span class="text-slate-700">{{ \Carbon\Carbon::createFromFormat('Y-m', $gaji->periode)->translatedFormat('F Y') }}</span></div>
                          <div class="flex justify-between"><span class="text-slate-500">Total Kehadiran:</span><span class="font-bold text-teal-700">{{ $gaji->total_kehadiran }} Shift</span></div>
                        </div>

                        <div class="space-y-2 text-xs">
                          <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rincian Komponen</div>
                          <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Gaji Pokok</span>
                            <span class="font-semibold text-ink">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</span>
                          </div>
                          <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Tunjangan Kehadiran</span>
                            <span class="font-semibold text-ink">Rp {{ number_format($gaji->tunjangan, 0, ',', '.') }}</span>
                          </div>
                          @if($gaji->potongan > 0)
                            <div class="flex justify-between py-1 border-b border-slate-100 text-rose-600">
                              <span>Potongan</span>
                              <span class="font-semibold">- Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</span>
                            </div>
                          @endif
                          <div class="flex justify-between pt-2 text-sm font-bold text-teal-800">
                            <span>Total Diterima</span>
                            <span>Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}</span>
                          </div>
                        </div>

                        <div class="text-xs text-slate-500 bg-slate-50 p-2.5 rounded-lg italic">
                          Catatan: {{ $gaji->catatan ?: 'Kehadiran terverifikasi RFID.' }}
                        </div>
                      </div>

                      <div class="pt-4 mt-4 border-t border-slate-100 flex justify-end gap-2.5">
                        <button type="button" onclick="closeModal('modalSlip{{ $gaji->id }}')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                          Tutup
                        </button>
                        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-ink hover:bg-slate-800 text-white transition">
                          Cetak Slip
                        </button>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="py-8 text-center text-slate-400">
                  Belum ada data rekapitulasi gaji untuk periode ini. Silakan klik tombol <strong>"Hitung Ulang Kehadiran & Gaji"</strong> di atas.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
@endsection
