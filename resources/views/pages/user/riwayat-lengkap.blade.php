@extends('layouts.app')

@section('title', 'Riwayat Reservasi Lapangan | Jaya Futsal')

@section('content')
  @include('partials.user.reservasi-navbar')

  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-[#12162B] tracking-tight">Riwayat Booking Lapangan</h1>
        <p class="text-xs sm:text-sm text-[#5B6478] mt-0.5">Daftar seluruh pesanan dan status sesi pertandingan futsal Anda.</p>
      </div>
      <a href="{{ route('reservasi.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition self-start sm:self-auto shadow-sm shadow-blue-500/25">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Buat Booking Baru</span>
      </a>
    </div>

    <!-- Table Glass Card -->
    <div class="bg-white/65 backdrop-blur-2xl rounded-3xl border border-white/80 shadow-[0_10px_30px_-5px_rgba(30,42,120,0.06)] overflow-hidden">
      <div class="p-5 sm:p-6 pb-4 border-b border-white/60 flex items-center justify-between">
        <div>
          <h2 class="text-base font-bold text-[#12162B]">Daftar Transaksi Booking</h2>
          <p class="text-xs text-[#5B6478] mt-0.5">Seluruh rekapan pemesanan lapangan futsal Anda</p>
        </div>
        <span class="text-xs px-3 py-1 rounded-full bg-white/70 text-[#2F6BFF] font-bold border border-white/80">{{ count($semuaRiwayat) }} Reservasi</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-white/60 bg-white/40 text-[#5B6478] font-bold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">No</th>
              <th class="py-3 px-4">Tanggal Main</th>
              <th class="py-3 px-4">Jam Sesi</th>
              <th class="py-3 px-4">Total Biaya</th>
              <th class="py-3 px-4">Metode Bayar</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-5 sm:px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/50">
            @forelse($semuaRiwayat as $r)
              <tr class="hover:bg-white/40 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-mono text-xs text-[#8A93A6] font-medium">
                  {{ $loop->iteration }}
                </td>
                <td class="py-3.5 px-4 font-bold text-[#12162B]">
                  {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}
                </td>
                <td class="py-3.5 px-4 font-mono text-xs text-[#5B6478]">
                  {{ \Carbon\Carbon::parse($r->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($r->jam_selesai)->format('H:i') }}
                </td>
                <td class="py-3.5 px-4 font-extrabold text-[#2F6BFF]">
                  Rp{{ number_format($r->total_harga, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-4 text-xs font-bold text-[#1E2A78]">
                  {{ $r->metode_pembayaran ? strtoupper($r->metode_pembayaran) : '-' }}
                </td>
                <td class="py-3.5 px-4">
                  @if ($r->status === 'menunggu')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-[#F5A524] border border-amber-300/40">
                      Menunggu Bayar
                    </span>
                  @elseif ($r->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500/10 text-[#3B82F6] border border-blue-300/40">
                      Konfirmasi
                    </span>
                  @elseif (in_array($r->status, ['disetujui', 'dibayar']))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-[#22B14C] border border-emerald-300/40">
                      Disetujui
                    </span>
                  @elseif ($r->status === 'selesai')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-100 text-[#2F6BFF] border border-primary-200">
                      Selesai
                    </span>
                  @elseif ($r->status === 'dibatalkan')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/10 text-[#EF4F5F] border border-rose-300/40">
                      Dibatalkan
                    </span>
                  @endif
                </td>
                <td class="py-3.5 px-5 sm:px-6 text-right">
                  @if(in_array($r->status, ['disetujui', 'dibayar', 'selesai']))
                    <a href="{{ route('reservasi.cetak', $r->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/80 hover:bg-white text-[#12162B] border border-white/90 transition shadow-2xs">
                      <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                      </svg>
                      <span>Cetak Tiket</span>
                    </a>
                  @else
                    <span class="text-[#8A93A6] text-xs">-</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-12 text-center text-[#8A93A6]">
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