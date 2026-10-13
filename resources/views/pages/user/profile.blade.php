@extends('layouts.app')

@section('title', 'Profil Member & Keanggotaan | Jaya Futsal')

@section('content')
  @include('partials.user.reservasi-navbar')

  <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-6">
    <!-- Back to Booking -->
    <a href="{{ route('reservasi.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#5B6478] hover:text-[#12162B] transition">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
      </svg>
      <span>Kembali ke Dashboard Reservasi</span>
    </a>

    <!-- Profile Header Card -->
    <div class="bg-white/65 backdrop-blur-2xl rounded-3xl border border-white/80 p-6 sm:p-8 shadow-[0_10px_30px_-5px_rgba(30,42,120,0.06)]">
      <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
        <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-[#2F6BFF] to-[#7B5CE6] text-white font-black text-2xl flex items-center justify-center shadow-md shadow-blue-500/25 shrink-0">
          {{ strtoupper(substr($user->nama ?? 'U', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-1">
            <h1 class="text-2xl font-black text-[#12162B]">{{ $user->nama }}</h1>
            @if($user->status_member && $user->membership_status === 'active')
              <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-primary-100 text-[#2F6BFF] border border-primary-200">
                Member VIP
              </span>
            @endif
          </div>
          <p class="text-xs sm:text-sm text-[#5B6478] font-mono">
            {{ $user->email }} • {{ $user->no_hp ?: 'No HP belum terdaftar' }}
          </p>
        </div>
      </div>
    </div>

    <!-- Membership Status Section -->
    <div class="bg-white/65 backdrop-blur-2xl rounded-3xl border border-white/80 p-6 sm:p-8 shadow-[0_10px_30px_-5px_rgba(30,42,120,0.06)]">
      <div class="pb-4 border-b border-white/60 mb-6 flex items-center justify-between">
        <div>
          <h2 class="text-lg font-bold text-[#12162B]">Status Keanggotaan (Membership)</h2>
          <p class="text-xs text-[#5B6478] mt-0.5">Pantau masa aktif paket dan voucher keuntungan Anda.</p>
        </div>
        <span class="text-xs px-3 py-1 rounded-full bg-white/70 text-[#2F6BFF] font-bold border border-white/80">Keanggotaan</span>
      </div>

      @if(empty($user->membership_type) || $user->membership_status === 'rejected')
        <div class="rounded-2xl border border-white/70 bg-white/50 p-5 text-[#5B6478] space-y-2">
          <h3 class="font-bold text-sm text-[#12162B]">Belum Terdaftar Sebagai Member</h3>
          <p class="text-xs text-[#5B6478]">
            Daftarkan tim Anda sekarang untuk menikmati diskon tetap 15% setiap bermain dan voucher free 1 jam main pertama.
          </p>
          @if($user->membership_status === 'rejected')
            <p class="text-xs text-[#EF4F5F] font-semibold pt-2 border-t border-white/60">
              Catatan: Pengajuan membership sebelumnya ditolak atau bukti pembayaran belum valid.
            </p>
          @endif
          <div class="pt-2">
            <a href="{{ url('/reservasi?show_membership=1') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition shadow-sm shadow-blue-500/25">
              Daftar Paket Member
            </a>
          </div>
        </div>
      @elseif($user->membership_status === 'pending')
        <div class="rounded-2xl border border-amber-300/40 bg-amber-500/10 p-5 text-amber-900 space-y-2">
          <div class="flex items-center gap-2 font-bold text-sm text-[#F5A524]">
            <svg class="w-4 h-4 text-[#F5A524]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Menunggu Konfirmasi Pembayaran</span>
          </div>
          <p class="text-xs text-amber-800">
            Pengajuan paket <strong>{{ $user->membership_type }}</strong> Anda sedang diverifikasi oleh admin. Harap tunggu sesaat.
          </p>
        </div>
      @elseif($user->membership_status === 'active')
        @php
          $expires = \Carbon\Carbon::parse($user->membership_expires_at);
          $sisaHari = (int) now()->diffInDays($expires, false);
        @endphp
        @if($sisaHari < 0)
          <div class="rounded-2xl border border-rose-300/40 bg-rose-500/10 p-5 text-[#EF4F5F] space-y-2">
            <h3 class="font-bold text-sm text-[#EF4F5F]">Membership Kedaluwarsa!</h3>
            <p class="text-xs text-rose-700">
              Masa aktif membership Anda telah berakhir sejak {{ $expires->translatedFormat('d F Y') }}.
            </p>
            <div class="pt-2">
              <a href="{{ url('/reservasi?show_membership=1') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition">
                Perpanjang Membership
              </a>
            </div>
          </div>
        @else
          <div class="rounded-2xl border border-primary-200/80 bg-primary-50/70 p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-primary-200/60">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#2F6BFF]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <span class="font-bold text-base text-[#1E2A78]">Member Aktif: {{ $user->membership_type ?: 'Member Jaya Futsal' }}</span>
              </div>
              <span class="text-xs font-bold text-[#2F6BFF] bg-white/80 px-3 py-1 rounded-full border border-primary-200 shadow-2xs">
                Sisa {{ $sisaHari }} hari lagi
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
              <div class="bg-white/80 backdrop-blur-md p-4 rounded-2xl border border-white/90 shadow-2xs">
                <span class="text-[#5B6478] block mb-1">Voucher Free 1 Jam Main:</span>
                @if(!$user->membership_free_hour_used)
                  <span class="font-bold text-[#22B14C] inline-flex items-center gap-1 text-sm">
                    <span>Tersedia</span>
                    <span class="text-[11px] text-[#8A93A6] font-normal">(Otomatis aktif saat booking)</span>
                  </span>
                @else
                  <span class="font-bold text-[#8A93A6] text-sm">Sudah Digunakan</span>
                @endif
              </div>

              <div class="bg-white/80 backdrop-blur-md p-4 rounded-2xl border border-white/90 shadow-2xs">
                <span class="text-[#5B6478] block mb-1">Diskon 15% Semua Lapangan:</span>
                <span class="font-bold text-[#22B14C] text-sm">Aktif Sepanjang Periode</span>
              </div>
            </div>

            <p class="text-xs text-[#1E2A78]">
              Berlaku hingga: <strong>{{ $expires->translatedFormat('d F Y') }}</strong>
            </p>
          </div>
        @endif
      @endif
    </div>
  </main>

  @include('partials.user.footer')
@endsection