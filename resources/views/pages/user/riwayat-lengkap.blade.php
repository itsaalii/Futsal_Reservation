@extends('layouts.app')

@section('title', 'Riwayat Reservasi Lapangan | Jaya Futsal')

@section('content')
  @include('partials.user.reservasi-navbar')

  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-ink tracking-tight">Riwayat Booking Lapangan</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftar seluruh pesanan dan status sesi pertandingan futsal Anda.</p>
      </div>
      <a href="{{ route('reservasi.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-teal-600 hover:bg-teal-700 text-white transition self-start sm:self-auto shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Buat Booking Baru</span>
      </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">No</th>
              <th class="py-3 px-4">Tanggal Main</th>
              <th class="py-3 px-4">Jam Sesi</th>
              <th class="py-3 px-4">Total Biaya</th>
              <th class="py-3 px-4">Metode Bayar</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-5 sm:px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($semuaRiwayat as $r)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-mono text-xs text-slate-400 font-medium">
                  {{ $loop->iteration }}
                </td>
                <td class="py-3.5 px-4 font-semibold text-ink">
                  {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}
                </td>
                <td class="py-3.5 px-4 font-mono text-xs text-slate-700">
                  {{ \Carbon\Carbon::parse($r->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($r->jam_selesai)->format('H:i') }}
                </td>
                <td class="py-3.5 px-4 font-bold text-teal-700">
                  Rp{{ number_format($r->total_harga, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-4 text-xs font-semibold text-slate-600">
                  {{ $r->metode_pembayaran ? strtoupper($r->metode_pembayaran) : '-' }}
                </td>
                <td class="py-3.5 px-4">
                  @if ($r->status === 'menunggu')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                      Menunggu Bayar
                    </span>
                  @elseif ($r->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                      Menunggu Konfirmasi
                    </span>
                  @elseif (in_array($r->status, ['disetujui', 'dibayar']))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      Disetujui
                    </span>
                  @elseif ($r->status === 'selesai')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                      Selesai
                    </span>
                  @elseif ($r->status === 'dibatalkan')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                      Dibatalkan
                    </span>
                  @endif
                </td>
                <td class="py-3.5 px-5 sm:px-6 text-right">
                  @if(in_array($r->status, ['disetujui', 'dibayar', 'selesai']))
                    <a href="{{ route('reservasi.cetak', $r->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                      </svg>
                      <span>Cetak Tiket</span>
                    </a>
                  @else
                    <span class="text-slate-400 text-xs">-</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-12 text-center text-slate-400">
                  Belum ada riwayat booking lapangan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

  @include('partials.user.footer')
@endsection