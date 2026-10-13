@php
  $userAuth = Auth::user();
  $currentRoute = Route::currentRouteName();
  $uri = request()->path();
@endphp

<!-- Mobile Backdrop Overlay -->
<div id="sidebarBackdrop" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm hidden md:hidden transition-opacity"></div>

<!-- Sidebar Container (Glassmorphic matching desain.jpeg) -->
<aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-64 glass-sidebar flex flex-col transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0 md:static shrink-0 shadow-lg md:shadow-none">
  <!-- Brand & Logo -->
  <div class="h-16 px-5 border-b border-white/60 flex items-center justify-between">
    <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2.5 group">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#2F6BFF] to-[#7B5CE6] text-white flex items-center justify-center shadow-md shadow-blue-500/25 group-hover:scale-105 transition-transform">
        <!-- Modern Stadium / Court Icon -->
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div>
      <div>
        <span class="font-extrabold text-lg text-[#12162B] tracking-tight block leading-tight">Jaya Futsal</span>
        <span class="text-[10px] font-bold text-[#5B6478] block tracking-wider uppercase">Management Hub</span>
      </div>
    </a>

    <!-- Close button for mobile -->
    <button id="closeSidebarBtn" class="md:hidden p-1.5 rounded-lg text-[#5B6478] hover:text-[#12162B] hover:bg-white/50 transition" aria-label="Tutup Menu">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- User Role Profile Pill -->
  <div class="p-3.5 border-b border-white/50">
    <div class="flex items-center gap-3 bg-white/50 backdrop-blur-md rounded-2xl p-2.5 border border-white/75 shadow-2xs">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#2F6BFF] to-[#3A4BD1] text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-xs">
        {{ strtoupper(substr($userAuth->nama ?? 'A', 0, 1)) }}
      </div>
      <div class="min-w-0 flex-1">
        <div class="font-bold text-sm text-[#1E2A78] truncate">{{ $userAuth->nama ?? 'Admin' }}</div>
        <div class="text-[11px] text-[#5B6478] truncate">
          @if ($userAuth && $userAuth->isSuperAdmin())
            <span class="inline-flex items-center text-[#2F6BFF] font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-[#2F6BFF] mr-1.5 animate-pulse"></span>
              Superadmin (Owner)
            </span>
          @elseif ($userAuth && $userAuth->isAdmin())
            <span class="inline-flex items-center text-slate-700 font-medium">
              <span class="w-1.5 h-1.5 rounded-full bg-[#22B14C] mr-1.5"></span>
              {{ $userAuth->lapangan ? $userAuth->lapangan->nama : 'Staf Pegawai' }}
            </span>
          @else
            <span class="text-[#5B6478]">Pengguna</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Navigation Menu Links -->
  <nav class="flex-1 overflow-y-auto p-3 space-y-5">
    <!-- Group: OPERASIONAL -->
    <div>
      <div class="px-3 mb-1.5 text-[11px] font-bold text-[#5B6478]/80 uppercase tracking-wider">Operasional</div>
      <div class="space-y-1">
        <!-- Dashboard -->
        <a href="{{ url('/admin/dashboard') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('admin/dashboard*') ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/dashboard*') ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span>Dashboard</span>
        </a>

        <!-- Kelola Reservasi -->
        @php
          $isKelolaReservasiActive = (request()->routeIs('admin.reservasi') || request()->is('admin/reservasi')) && !request()->routeIs('admin.reservasi.create') && !request()->is('admin/reservasi/create*');
          $isWalkInActive = request()->routeIs('admin.reservasi.create') || request()->is('admin/reservasi/create*');
        @endphp
        <a href="{{ url('/admin/reservasi') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ $isKelolaReservasiActive ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
          <svg class="w-5 h-5 {{ $isKelolaReservasiActive ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>Kelola Reservasi</span>
        </a>

        @if ($userAuth && !$userAuth->isSuperAdmin())
          <!-- Reservasi Manual (Kasir / Walk-in) - Khusus Admin Lapangan -->
          <a href="{{ route('admin.reservasi.create') }}" 
             class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ $isWalkInActive ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
            <svg class="w-5 h-5 {{ $isWalkInActive ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Reservasi Kasir (Walk-in)</span>
          </a>
        @endif

        <!-- Shift Pegawai -->
        <a href="{{ url('/admin/shift') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('admin/shift*') ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/shift*') ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Shift Pegawai</span>
        </a>
      </div>
    </div>

    <!-- Group: MANAJEMEN & KEUANGAN -->
    <div>
      <div class="px-3 mb-1.5 text-[11px] font-bold text-[#5B6478]/80 uppercase tracking-wider">Laporan & Pengguna</div>
      <div class="space-y-1">
        <!-- Laporan -->
        <a href="{{ url('/admin/laporan') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('admin/laporan*') ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/laporan*') ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span>Laporan & Keuangan</span>
        </a>

        @if ($userAuth && $userAuth->isSuperAdmin())
          <!-- Kelola Lapangan (Owner Only) -->
          <a href="{{ url('/admin/lapangan') }}" 
             class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('admin/lapangan*') ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
            <svg class="w-5 h-5 {{ request()->is('admin/lapangan*') ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <div class="flex items-center justify-between flex-1">
              <span>Kelola Lapangan</span>
              <span class="text-[10px] bg-primary-100 text-[#2F6BFF] px-2 py-0.5 rounded-full font-bold">Owner</span>
            </div>
          </a>

          <!-- Kelola Akun Pegawai (Owner Only) -->
          <a href="{{ route('admin.pegawai') }}" 
             class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('admin/pegawai*') ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
            <svg class="w-5 h-5 {{ request()->is('admin/pegawai*') ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <div class="flex items-center justify-between flex-1">
              <span>Kelola Pegawai</span>
              <span class="text-[10px] bg-primary-100 text-[#2F6BFF] px-2 py-0.5 rounded-full font-bold">Owner</span>
            </div>
          </a>

          <!-- Rekap Penggajian (Owner Only) -->
          <a href="{{ url('/admin/penggajian') }}" 
             class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('admin/penggajian*') ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
            <svg class="w-5 h-5 {{ request()->is('admin/penggajian*') ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <div class="flex items-center justify-between flex-1">
              <span>Rekap Penggajian</span>
              <span class="text-[10px] bg-primary-100 text-[#2F6BFF] px-2 py-0.5 rounded-full font-bold">Owner</span>
            </div>
          </a>
        @endif

        <!-- Data Membership -->
        <a href="{{ url('/admin/membership') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('admin/membership*') ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/membership*') ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
          </svg>
          <span>Data Membership</span>
        </a>

        <!-- Daftar Pelanggan -->
        <a href="{{ url('/admin/pelanggan') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('admin/pelanggan*') ? 'bg-white text-[#2F6BFF] shadow-[0_4px_12px_rgba(47,107,255,0.12)] border border-white/80' : 'text-[#5B6478] hover:text-[#12162B] hover:bg-white/45' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/pelanggan*') ? 'text-[#2F6BFF]' : 'text-[#5B6478]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span>Daftar Pelanggan</span>
        </a>
      </div>
    </div>

    <!-- Group: TERMINAL & AKSES -->
    <div>
      <div class="px-3 mb-1.5 text-[11px] font-bold text-[#5B6478]/80 uppercase tracking-wider">Akses Terkait</div>
      <div class="space-y-1">
        <!-- Kiosk RFID -->
        <a href="{{ route('absensi.index') }}" target="_blank" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#5B6478] hover:text-[#2F6BFF] hover:bg-white/45 transition group">
          <svg class="w-5 h-5 text-[#5B6478] group-hover:text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 002.83 6.13" />
          </svg>
          <div class="flex items-center justify-between flex-1">
            <span>Kiosk Absensi RFID</span>
            <svg class="w-3.5 h-3.5 text-[#8A93A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
          </div>
        </a>

        <!-- Website User -->
        <a href="{{ url('/') }}" target="_blank"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#5B6478] hover:text-[#2F6BFF] hover:bg-white/45 transition group">
          <svg class="w-5 h-5 text-[#5B6478] group-hover:text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
          </svg>
          <div class="flex items-center justify-between flex-1">
            <span>Website Pengunjung</span>
            <svg class="w-3.5 h-3.5 text-[#8A93A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
          </div>
        </a>
      </div>
    </div>
  </nav>

  <!-- Bottom: Logout Action -->
  <div class="p-3.5 border-t border-white/60">
    <a href="{{ route('logout') }}" 
       onclick="event.preventDefault(); document.getElementById('admin-sidebar-logout-form').submit();"
       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#EF4F5F] hover:bg-rose-500/10 transition w-full">
      <svg class="w-5 h-5 text-[#EF4F5F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
      </svg>
      <span>Keluar (Logout)</span>
    </a>
    <form id="admin-sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
      @csrf
    </form>
  </div>
</aside>
