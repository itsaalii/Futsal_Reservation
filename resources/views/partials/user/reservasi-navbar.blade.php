<nav class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-2xs">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group shrink-0">
      <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm group-hover:bg-teal-700 transition">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div>
      <div>
        <span class="font-black text-base sm:text-lg text-ink tracking-tight leading-none block">Jaya Futsal</span>
        <span class="text-[10px] font-bold text-teal-600 uppercase tracking-widest block">Dashboard User</span>
      </div>
    </a>

    <!-- Center Links -->
    <div class="hidden sm:flex items-center gap-6 text-sm font-medium text-slate-600">
      <a href="{{ route('reservasi.index') }}#step-1" class="hover:text-teal-700 transition {{ request()->is('reservasi') ? 'text-teal-700 font-semibold' : '' }}">
        Reservasi
      </a>
      <a href="{{ route('reservasi.riwayat-lengkap') }}" class="hover:text-teal-700 transition {{ request()->is('riwayat-lengkap*') ? 'text-teal-700 font-semibold' : '' }}">
        Riwayat Booking
      </a>
      <a href="{{ route('profile') }}" class="hover:text-teal-700 transition {{ request()->is('profile*') ? 'text-teal-700 font-semibold' : '' }}">
        Membership & Profil
      </a>
    </div>

    <!-- Right User Status & Logout -->
    <div class="flex items-center gap-3 shrink-0">
      <a href="{{ route('profile') }}" class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-50 transition" title="Lihat Profil">
        <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 font-bold text-xs flex items-center justify-center border border-teal-200">
          {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
        </div>
        <span class="hidden md:inline font-semibold text-xs sm:text-sm text-ink max-w-[120px] truncate">
          {{ Auth::user()->nama ?? 'Profil Saya' }}
        </span>
      </a>

      <button type="button" 
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="px-3 py-1.5 rounded-xl text-xs sm:text-sm font-medium text-rose-600 hover:bg-rose-50 transition">
        Logout
      </button>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
      </form>
    </div>
  </div>
</nav>
