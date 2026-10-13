@php
  $active = $active ?? '';
  $title = $title ?? 'Panel Admin';
  $userAuth = Auth::user();
@endphp

<header class="glass-header sticky top-0 z-30">
  <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4">
    <!-- Left: Hamburger Toggle & User Greeting matching desain.jpeg -->
    <div class="flex items-center gap-3.5 min-w-0">
      <!-- Toggle Sidebar Button -->
      <button id="toggleSidebarBtn" type="button" 
              class="p-2 rounded-xl text-[#5B6478] hover:text-[#12162B] hover:bg-white/60 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/20 transition"
              title="Sembunyikan / Tampilkan Sidebar">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- User Avatar & Welcome Header (desain.jpeg style) -->
      <div class="flex items-center gap-3 min-w-0">
        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#2F6BFF] to-[#7B5CE6] text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
          {{ strtoupper(substr($userAuth->nama ?? 'A', 0, 1)) }}
        </div>
        <div class="min-w-0">
          <div class="text-[11px] font-medium text-[#5B6478] leading-none mb-0.5">Welcome,</div>
          <h1 class="text-sm sm:text-base font-bold text-[#1E2A78] truncate leading-tight">
            {{ $userAuth->nama ?? 'Administrator' }}
          </h1>
        </div>
      </div>
    </div>

    <!-- Center: Modern Search Pill (matching desain.jpeg) -->
    <div class="hidden lg:flex items-center flex-1 max-w-xs mx-4">
      <div class="relative w-full">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#8A93A6]">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="text" 
               placeholder="Search..." 
               class="w-full bg-white/60 backdrop-blur-md rounded-full pl-9 pr-4 py-1.5 text-xs text-[#12162B] placeholder-[#8A93A6] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
      </div>
    </div>

    <!-- Right: Quick Badges & Actions -->
    <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
      @if ($userAuth && $userAuth->isSuperAdmin())
        <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/70 text-[#2F6BFF] border border-white/80 shadow-2xs">
          <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          <span>Superadmin</span>
        </div>
      @elseif ($userAuth && $userAuth->isAdmin())
        <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/70 text-slate-700 border border-white/80 shadow-2xs">
          <span class="w-2 h-2 rounded-full bg-[#22B14C]"></span>
          <span>{{ $userAuth->lapangan ? $userAuth->lapangan->nama : 'Pegawai' }}</span>
        </div>
      @endif

      <!-- Kiosk RFID Link -->
      <a href="{{ route('absensi.index') }}" target="_blank" 
         class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-white/70 hover:bg-white text-[#2F6BFF] border border-white/80 transition shadow-2xs">
        <svg class="w-4 h-4 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 002.83 6.13" />
        </svg>
        <span class="hidden md:inline">Kiosk RFID</span>
      </a>

      <!-- Website User Link -->
      <a href="{{ url('/') }}" target="_blank" 
         class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium bg-white/60 hover:bg-white text-[#5B6478] hover:text-[#12162B] border border-white/80 transition shadow-2xs">
        <svg class="w-4 h-4 text-[#5B6478]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
        </svg>
        <span>Lihat Web</span>
      </a>

      <!-- Logout Action -->
      <a href="{{ route('logout') }}" 
         onclick="event.preventDefault(); document.getElementById('admin-header-logout-form').submit();"
         class="p-2 sm:px-3 sm:py-1.5 rounded-full text-xs sm:text-sm font-semibold text-[#EF4F5F] hover:bg-rose-500/10 border border-transparent transition"
         title="Keluar dari sistem">
        <span class="hidden sm:inline">Logout</span>
        <svg class="w-4 h-4 sm:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
      </a>
      <form id="admin-header-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
      </form>
    </div>
  </div>
</header>
