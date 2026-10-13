@extends('layouts.app')

@section('title', 'Login Akun | Jaya Futsal')

@section('content')
  <div class="min-h-screen bg-paper flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Subtle Background Ornaments -->
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary-200/35 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-secondary/15 blur-3xl pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
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
          <h2 class="text-2xl font-black text-[#12162B] tracking-tight">Selamat Datang Kembali</h2>
          <p class="text-xs sm:text-sm text-[#5B6478] mt-1">
            Masuk ke akun Anda untuk memesan lapangan atau mengelola jadwal.
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

        <form class="space-y-4" method="POST" action="{{ route('proses-login') }}">
          @csrf

          <div>
            <label class="block text-xs font-bold text-[#5B6478] uppercase tracking-wider mb-1.5" for="loginEmail">
              Alamat Email <span class="text-[#EF4F5F]">*</span>
            </label>
            <input class="w-full px-4 py-3 rounded-xl border border-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:border-[#2F6BFF] bg-white/70" 
                   id="loginEmail" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" autofocus />
          </div>

          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-xs font-bold text-[#5B6478] uppercase tracking-wider" for="loginPassword">
                Kata Sandi <span class="text-[#EF4F5F]">*</span>
              </label>
            </div>
            <div class="relative">
              <input class="w-full pl-4 pr-11 py-3 rounded-xl border border-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:border-[#2F6BFF] bg-white/70" 
                     id="loginPassword" type="password" name="password" required placeholder="Masukkan kata sandi" />
              <button type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#8A93A6] hover:text-[#12162B] transition" onclick="togglePasswordVisibility()">
                <svg id="eyeIcon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
          </div>

          <div class="pt-2">
            <button class="w-full py-3.5 px-4 rounded-xl text-sm font-bold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition shadow-md shadow-blue-500/25" type="submit">
              Masuk ke Akun
            </button>
          </div>
        </form>

        <div class="mt-6 pt-5 border-t border-white/60 text-center text-xs text-[#5B6478]">
          Belum memiliki akun?
          <a href="{{ url('/register') }}" class="font-bold text-[#2F6BFF] hover:underline ml-1">
            Daftar Akun Baru
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

  @push('scripts')
    <script>
      function togglePasswordVisibility() {
        const input = document.getElementById('loginPassword');
        input.type = input.type === 'password' ? 'text' : 'password';
      }
    </script>
  @endpush
@endsection
