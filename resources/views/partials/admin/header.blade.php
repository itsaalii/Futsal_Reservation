@php
  $active = $active ?? '';
  $title = $title ?? 'Panel Admin';
  $userAuth = Auth::user();
@endphp

<header class="bg-white border-b border-slate-200/80 sticky top-0 z-30">
  <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4">
    <!-- Left: Hamburger Toggle & Title -->
    <div class="flex items-center gap-3.5 min-w-0">
      <!-- Toggle Sidebar Button -->
      <button id="toggleSidebarBtn" type="button" 
              class="p-2 rounded-xl text-slate-600 hover:text-ink hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-600/20 transition"
              title="Sembunyikan / Tampilkan Sidebar">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <div class="min-w-0">
        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold text-teal-600 tracking-wider uppercase">Jaya Futsal</span>
          <span class="text-slate-300">/</span>
          <span class="text-xs text-slate-500 font-medium truncate">{{ $title }}</span>
        </div>
        <h1 class="text-lg sm:text-xl font-bold text-ink truncate leading-tight mt-0.5">{{ $title }}</h1>
      </div>
    </div>

    <!-- Right: Quick Badges & Actions -->
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
      @if ($userAuth && $userAuth->isSuperAdmin())
        <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/70">
          <span>👑</span>
          <span>Superadmin (Owner)</span>
        </div>
      @elseif ($userAuth && $userAuth->isAdmin())
        <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/70">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          <span>{{ $userAuth->lapangan ? $userAuth->lapangan->nama : 'Pegawai Umum' }}</span>
        </div>
      @endif

      <!-- Kiosk RFID Link -->
      <a href="{{ route('absensi.index') }}" target="_blank" 
         class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs sm:text-sm font-medium bg-teal-50 text-teal-700 hover:bg-teal-100 border border-teal-200/60 transition shadow-2xs">
        <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 002.83 6.13" />
        </svg>
        <span class="hidden md:inline">Kiosk RFID</span>
      </a>

      <!-- Website User Link -->
      <a href="{{ url('/') }}" target="_blank" 
         class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs sm:text-sm font-medium bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 transition shadow-2xs">
        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
        </svg>
        <span>Lihat Web</span>
      </a>

      <!-- Logout Action -->
      <a href="{{ route('logout') }}" 
         onclick="event.preventDefault(); document.getElementById('admin-header-logout-form').submit();"
         class="p-2 sm:px-3 sm:py-1.5 rounded-xl text-xs sm:text-sm font-medium text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition"
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
