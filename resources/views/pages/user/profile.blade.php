@extends('layouts.app')

@section('title', 'Profil Member & Keanggotaan | Jaya Futsal')

@section('content')
  @include('partials.user.reservasi-navbar')

  <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-6">
    <!-- Back to Booking -->
    <a href="{{ route('reservasi.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-ink transition">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
      </svg>
      <span>Kembali ke Dashboard Reservasi</span>
    </a>

    <!-- Profile Header Card -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
      <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
        <div class="w-20 h-20 rounded-2xl bg-teal-600 text-white font-black text-2xl flex items-center justify-center shadow-md">
          {{ strtoupper(substr($user->nama ?? 'U', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-1">
            <h1 class="text-2xl font-black text-ink">{{ $user->nama }}</h1>
            @if($user->status_member && $user->membership_status === 'active')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200">
                Member VIP
              </span>
            @endif
          </div>
          <p class="text-xs sm:text-sm text-slate-500 font-mono">
            {{ $user->email }} • {{ $user->no_hp ?: 'No HP belum terdaftar' }}
          </p>
        </div>
      </div>
    </div>

    <!-- Membership Status Section -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
      <div class="pb-4 border-b border-slate-100 mb-6 flex items-center justify-between">
        <div>
          <h2 class="text-lg font-bold text-ink">Status Keanggotaan (Membership)</h2>
          <p class="text-xs text-slate-500 mt-0.5">Pantau masa aktif paket dan voucher keuntungan Anda.</p>
        </div>
        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-semibold">Keanggotaan</span>
      </div>

      @if(empty($user->membership_type) || $user->membership_status === 'rejected')
        <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 text-slate-700 space-y-2">
          <h3 class="font-bold text-sm text-ink">Belum Terdaftar Sebagai Member</h3>
          <p class="text-xs text-slate-500">
            Daftarkan tim Anda sekarang untuk menikmati diskon tetap 15% setiap bermain dan voucher free 1 jam main pertama.
          </p>
          @if($user->membership_status === 'rejected')
            <p class="text-xs text-rose-600 font-semibold pt-2 border-t border-slate-200">
              Catatan: Pengajuan membership sebelumnya ditolak atau bukti pembayaran belum valid.
            </p>
          @endif
          <div class="pt-2">
            <a href="{{ url('/reservasi?show_membership=1') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
              Daftar Paket Member
            </a>
          </div>
        </div>
      @elseif($user->membership_status === 'pending')
        <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-5 text-amber-900 space-y-2">
          <div class="flex items-center gap-2 font-bold text-sm text-amber-800">
            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Menunggu Konfirmasi Pembayaran</span>
          </div>
          <p class="text-xs text-amber-700">
            Pengajuan paket <strong>{{ $user->membership_type }}</strong> Anda sedang diverifikasi oleh admin. Harap tunggu sesaat.
          </p>
        </div>
      @elseif($user->membership_status === 'active')
        @php
          $expires = \Carbon\Carbon::parse($user->membership_expires_at);
          $sisaHari = (int) now()->diffInDays($expires, false);
        @endphp
        @if($sisaHari < 0)
          <div class="rounded-2xl border border-rose-200 bg-rose-50/60 p-5 text-rose-900 space-y-2">
            <h3 class="font-bold text-sm text-rose-800">Membership Kedaluwarsa!</h3>
            <p class="text-xs text-rose-700">
              Masa aktif membership Anda telah berakhir sejak {{ $expires->translatedFormat('d F Y') }}.
            </p>
            <div class="pt-2">
              <a href="{{ url('/reservasi?show_membership=1') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white transition">
                Perpanjang Membership
              </a>
            </div>
          </div>
        @else
          <div class="rounded-2xl border border-teal-200 bg-teal-50/60 p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-teal-200/60">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <span class="font-bold text-base text-teal-900">Member Aktif: {{ $user->membership_type ?: 'Member Jaya Futsal' }}</span>
              </div>
              <span class="text-xs font-bold text-teal-800 bg-teal-100/80 px-2.5 py-0.5 rounded-full">
                Sisa {{ $sisaHari }} hari lagi
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
              <div class="bg-white/80 p-3 rounded-xl border border-teal-200/60">
                <span class="text-slate-500 block mb-0.5">Voucher Free 1 Jam Main:</span>
                @if(!$user->membership_free_hour_used)
                  <span class="font-bold text-emerald-700 inline-flex items-center gap-1">
                    <span>✓ Tersedia</span>
                    <span class="text-[11px] text-slate-400 font-normal">(Otomatis aktif saat booking)</span>
                  </span>
                @else
                  <span class="font-bold text-slate-400">Sudah Digunakan</span>
                @endif
              </div>

              <div class="bg-white/80 p-3 rounded-xl border border-teal-200/60">
                <span class="text-slate-500 block mb-0.5">Diskon 15% Semua Lapangan:</span>
                <span class="font-bold text-emerald-700">✓ Aktif Sepanjang Periode</span>
              </div>
            </div>

            <p class="text-xs text-teal-800">
              Berlaku hingga: <strong>{{ $expires->translatedFormat('d F Y') }}</strong>
            </p>
          </div>
        @endif
      @endif
    </div>
  </main>

  @include('partials.user.footer')
@endsection