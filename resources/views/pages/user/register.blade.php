@extends('layouts.app')

@section('title', 'Daftar Akun Baru | Jaya Futsal')

@section('content')
  <div class="min-h-screen bg-paper flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Subtle Background Ornaments -->
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary-200/35 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-secondary/15 blur-3xl pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-lg relative z-10">
      <!-- Brand Logo -->
      <a href="{{ url('/') }}" class="flex items-center justify-center gap-2.5 mb-6 group">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#2F6BFF] to-[#7B5CE6] text-white flex items-center justify-center shadow-md shadow-blue-500/25 group-hover:scale-105 transition-transform">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
        </div>
        <div class="text-left">
          <span class="text-2xl font-black text-ink tracking-tight block leading-none">Jaya Futsal</span>
          <span class="text-xs font-bold text-[#2F6BFF] uppercase tracking-widest">Arena Booking</span>
        </div>
      </a>

      <!-- Auth Glass Card -->
      <div class="bg-white/65 backdrop-blur-2xl rounded-3xl border border-white/80 p-8 sm:p-10 shadow-[0_20px_50px_rgba(30,42,120,0.08)]">
        <div class="mb-6">
          <h2 class="text-2xl font-black text-[#12162B] tracking-tight">Buat Akun Member</h2>
          <p class="text-xs sm:text-sm text-[#5B6478] mt-1">
            Daftar sekarang untuk booking lapangan online dan nikmati promo diskon reguler.
          </p>
        </div>

        @if ($errors->any())
          <div class="mb-5 bg-rose-500/10 border border-rose-300/40 text-[#EF4F5F] rounded-2xl p-4 text-xs">
            <ul class="list-disc pl-4 space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form class="space-y-4" method="POST" action="{{ route('proses-register') }}">
          @csrf

          <div>
            <label class="block text-xs font-bold text-[#5B6478] uppercase tracking-wider mb-1.5" for="nama">
              Nama Lengkap <span class="text-[#EF4F5F]">*</span>
            </label>
            <input class="w-full px-4 py-3 rounded-xl border border-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:border-[#2F6BFF] bg-white/70" 
                   id="nama" type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Budi Santoso" autofocus />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[#5B6478] uppercase tracking-wider mb-1.5" for="email">
                Alamat Email <span class="text-[#EF4F5F]">*</span>
              </label>
              <input class="w-full px-4 py-3 rounded-xl border border-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:border-[#2F6BFF] bg-white/70" 
                     id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="budi@email.com" />
            </div>

            <div>
              <label class="block text-xs font-bold text-[#5B6478] uppercase tracking-wider mb-1.5" for="no_hp">
                Nomor WhatsApp <span class="text-[#EF4F5F]">*</span>
              </label>
              <input class="w-full px-4 py-3 rounded-xl border border-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:border-[#2F6BFF] bg-white/70" 
                     id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="081234567890" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[#5B6478] uppercase tracking-wider mb-1.5" for="password">
                Kata Sandi <span class="text-[#EF4F5F]">*</span>
              </label>
              <input class="w-full px-4 py-3 rounded-xl border border-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:border-[#2F6BFF] bg-white/70" 
                     id="password" type="password" name="password" required placeholder="Minimal 6 karakter" />
            </div>

            <div>
              <label class="block text-xs font-bold text-[#5B6478] uppercase tracking-wider mb-1.5" for="password_confirmation">
                Konfirmasi Sandi <span class="text-[#EF4F5F]">*</span>
              </label>
              <input class="w-full px-4 py-3 rounded-xl border border-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:border-[#2F6BFF] bg-white/70" 
                     id="password_confirmation" type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" />
            </div>
          </div>

          <div class="pt-2">
            <button class="w-full py-3.5 px-4 rounded-xl text-sm font-bold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition shadow-md shadow-blue-500/25" type="submit">
              Daftar Sekarang
            </button>
          </div>
        </form>

        <div class="mt-6 pt-5 border-t border-white/60 text-center text-xs text-[#5B6478]">
          Sudah memiliki akun?
          <a href="{{ url('/login') }}" class="font-bold text-[#2F6BFF] hover:underline ml-1">
            Masuk di sini
          </a>
        </div>
      </div>

      <div class="text-center mt-6">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#5B6478] hover:text-[#12162B] transition">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Kembali ke Halaman Depan</span>
        </a>
      </div>
    </div>
  </div>
@endsection
