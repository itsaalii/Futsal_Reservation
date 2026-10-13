<nav class="bg-white/60 backdrop-blur-xl border-b border-white/80 sticky top-0 z-30 shadow-2xs">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group shrink-0">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#2F6BFF] to-[#7B5CE6] text-white flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div>
      <div>
        <span class="font-extrabold text-base sm:text-lg text-[#12162B] tracking-tight leading-none block">Jaya Futsal</span>
        <span class="text-[10px] font-bold text-[#2F6BFF] uppercase tracking-widest block">Dashboard User</span>
      </div>
    </a>

    <!-- Center Links -->
    <div class="hidden sm:flex items-center gap-6 text-sm font-semibold text-[#5B6478]">
      <a href="{{ route('reservasi.index') }}#step-1" class="hover:text-[#2F6BFF] transition {{ request()->is('reservasi') ? 'text-[#2F6BFF] font-bold' : '' }}">
        Reservasi
      </a>
      <a href="{{ route('reservasi.riwayat-lengkap') }}" class="hover:text-[#2F6BFF] transition {{ request()->is('riwayat-lengkap*') ? 'text-[#2F6BFF] font-bold' : '' }}">
        Riwayat Booking
      </a>
      <a href="{{ route('profile') }}" class="hover:text-[#2F6BFF] transition {{ request()->is('profile*') ? 'text-[#2F6BFF] font-bold' : '' }}">
        Membership & Profil
      </a>
    </div>

    <!-- Right User Status & Logout -->
    <div class="flex items-center gap-3 shrink-0">
      <a href="{{ route('profile') }}" class="flex items-center gap-2.5 p-1 rounded-full hover:bg-white/60 transition" title="Lihat Profil">
        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#2F6BFF] to-[#3A4BD1] text-white font-bold text-xs flex items-center justify-center shadow-xs">
          {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
        </div>
        <span class="hidden md:inline font-bold text-xs sm:text-sm text-[#1E2A78] max-w-[120px] truncate">
          {{ Auth::user()->nama ?? 'Profil Saya' }}
        </span>
      </a>

      <button type="button" 
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold text-[#EF4F5F] hover:bg-rose-500/10 transition">
        Logout
      </button>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
      </form>
    </div>
  </div>
</nav>
