@php
  $userAuth = Auth::user();
  $currentRoute = Route::currentRouteName();
  $uri = request()->path();
@endphp

<!-- Mobile Backdrop Overlay -->
<div id="sidebarBackdrop" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden md:hidden transition-opacity"></div>

<!-- Sidebar Container -->
<aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0 md:static shrink-0 shadow-sm md:shadow-none">
  <!-- Brand & Logo -->
  <div class="h-16 px-5 border-b border-slate-200/80 flex items-center justify-between bg-white">
    <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2.5 group">
      <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm group-hover:bg-teal-700 transition">
        <!-- Court / Stadium Icon -->
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div>
      <div>
        <span class="font-bold text-lg text-ink tracking-tight block leading-tight">Jaya Futsal</span>
        <span class="text-[11px] font-medium text-slate-400 block tracking-wider uppercase">Management Hub</span>
      </div>
    </a>

    <!-- Close button for mobile -->
    <button id="closeSidebarBtn" class="md:hidden p-1.5 rounded-lg text-slate-500 hover:text-ink hover:bg-slate-100 transition" aria-label="Tutup Menu">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- User Role Profile Pill -->
  <div class="p-4 border-b border-slate-100 bg-slate-50/60">
    <div class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-teal-100 text-teal-800 font-bold text-sm flex items-center justify-center shrink-0 border border-teal-200">
        {{ strtoupper(substr($userAuth->nama ?? 'A', 0, 1)) }}
      </div>
      <div class="min-w-0 flex-1">
        <div class="font-semibold text-sm text-ink truncate">{{ $userAuth->nama ?? 'Admin' }}</div>
        <div class="text-xs text-slate-500 truncate">
          @if ($userAuth && $userAuth->isSuperAdmin())
            <span class="inline-flex items-center text-teal-700 font-medium">
              <span class="w-1.5 h-1.5 rounded-full bg-teal-600 mr-1.5"></span>
              Superadmin (Owner)
            </span>
          @elseif ($userAuth && $userAuth->isAdmin())
            <span class="inline-flex items-center text-slate-700 font-medium">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
              {{ $userAuth->lapangan ? $userAuth->lapangan->nama : 'Staf Pegawai' }}
            </span>
          @else
            <span class="text-slate-500">Pengguna</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Navigation Menu Links -->
  <nav class="flex-1 overflow-y-auto p-3 space-y-6">
    <!-- Group: OPERASIONAL -->
    <div>
      <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Operasional</div>
      <div class="space-y-1">
        <!-- Dashboard -->
        <a href="{{ url('/admin/dashboard') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('admin/dashboard*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/dashboard*') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span>Dashboard</span>
        </a>

        <!-- Kelola Reservasi -->
        <a href="{{ url('/admin/reservasi') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ (request()->is('admin/reservasi') || (request()->is('admin/reservasi*') && !request()->is('admin/reservasi/tambah*'))) ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
          <svg class="w-5 h-5 {{ (request()->is('admin/reservasi') || (request()->is('admin/reservasi*') && !request()->is('admin/reservasi/tambah*'))) ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>Kelola Reservasi</span>
        </a>

        <!-- Reservasi Manual (Kasir) -->
        <a href="{{ route('admin.reservasi.create') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('admin/reservasi/tambah*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/reservasi/tambah*') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Reservasi Kasir (Walk-in)</span>
        </a>

        <!-- Shift Pegawai -->
        <a href="{{ url('/admin/shift') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('admin/shift*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/shift*') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Shift Pegawai</span>
        </a>
      </div>
    </div>

    <!-- Group: MANAJEMEN & KEUANGAN -->
    <div>
      <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laporan & Pengguna</div>
      <div class="space-y-1">
        <!-- Laporan -->
        <a href="{{ url('/admin/laporan') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('admin/laporan*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/laporan*') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span>Laporan & Keuangan</span>
        </a>

        @if ($userAuth && $userAuth->isSuperAdmin())
          <!-- Kelola Lapangan (Owner Only) -->
          <a href="{{ url('/admin/lapangan') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('admin/lapangan*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
            <svg class="w-5 h-5 {{ request()->is('admin/lapangan*') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <div class="flex items-center justify-between flex-1">
              <span>Kelola Lapangan</span>
              <span class="text-[10px] bg-teal-100 text-teal-800 px-1.5 py-0.5 rounded font-bold">Owner</span>
            </div>
          </a>

          <!-- Rekap Penggajian (Owner Only) -->
          <a href="{{ url('/admin/penggajian') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('admin/penggajian*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
            <svg class="w-5 h-5 {{ request()->is('admin/penggajian*') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <div class="flex items-center justify-between flex-1">
              <span>Rekap Penggajian</span>
              <span class="text-[10px] bg-teal-100 text-teal-800 px-1.5 py-0.5 rounded font-bold">Owner</span>
            </div>
          </a>
        @endif

        <!-- Data Membership -->
        <a href="{{ url('/admin/membership') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('admin/membership*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/membership*') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
          </svg>
          <span>Data Membership</span>
        </a>

        <!-- Daftar Pelanggan -->
        <a href="{{ url('/admin/pelanggan') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('admin/pelanggan*') ? 'bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
          <svg class="w-5 h-5 {{ request()->is('admin/pelanggan*') ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span>Daftar Pelanggan</span>
        </a>
      </div>
    </div>

    <!-- Group: TERMINAL & AKSES -->
    <div>
      <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Akses Terkait</div>
      <div class="space-y-1">
        <!-- Kiosk RFID -->
        <a href="{{ route('absensi.index') }}" target="_blank" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:text-teal-700 hover:bg-teal-50/60 transition group">
          <svg class="w-5 h-5 text-slate-400 group-hover:text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 002.83 6.13" />
          </svg>
          <div class="flex items-center justify-between flex-1">
            <span>Kiosk Absensi RFID</span>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
          </div>
        </a>

        <!-- Website User -->
        <a href="{{ url('/') }}" target="_blank"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:text-teal-700 hover:bg-teal-50/60 transition group">
          <svg class="w-5 h-5 text-slate-400 group-hover:text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
          </svg>
          <div class="flex items-center justify-between flex-1">
            <span>Website Pengunjung</span>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
          </div>
        </a>
      </div>
    </div>
  </nav>

  <!-- Bottom: Logout Action -->
  <div class="p-4 border-t border-slate-200/80 bg-white">
    <a href="{{ route('logout') }}" 
       onclick="event.preventDefault(); document.getElementById('admin-sidebar-logout-form').submit();"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-rose-600 hover:bg-rose-50 transition w-full">
      <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
      </svg>
      <span>Keluar (Logout)</span>
    </a>
    <form id="admin-sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
      @csrf
    </form>
  </div>
</aside>
