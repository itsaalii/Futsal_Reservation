<header class="sticky top-0 z-40 pt-4 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto bg-white/70 backdrop-blur-xl border border-white/80 rounded-full px-5 sm:px-7 py-3 shadow-sm flex items-center justify-between">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group shrink-0">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#2F6BFF] to-[#7B5CE6] text-white flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div>
      <div>
        <span class="font-extrabold text-base sm:text-lg text-[#12162B] tracking-tight leading-none block">Jaya Futsal</span>
        <span class="text-[10px] font-bold text-[#2F6BFF] uppercase tracking-widest block">Arena Booking</span>
      </div>
    </a>

    <!-- Desktop Navigation Links -->
    <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-[#5B6478]">
      <a href="#fitur" class="hover:text-[#2F6BFF] transition">Fasilitas</a>
      <a href="#jadwal-kalender" class="hover:text-[#2F6BFF] transition">Jadwal Kalender</a>
      <a href="#harga" class="hover:text-[#2F6BFF] transition">Harga Sewa</a>
      <a href="#membership" class="hover:text-[#2F6BFF] transition">Membership</a>
      <a href="{{ route('absensi.index') }}" target="_blank" class="hover:text-[#2F6BFF] transition flex items-center gap-1">
        <span>Kiosk RFID</span>
        <svg class="w-3.5 h-3.5 text-[#8A93A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
        </svg>
      </a>
    </nav>

    <!-- Desktop Actions -->
    <div class="hidden md:flex items-center gap-3 shrink-0">
      @auth
        <a href="{{ Auth::user()->isAdmin() || Auth::user()->isSuperAdmin() ? url('/admin/dashboard') : url('/reservasi') }}" 
           class="px-4 py-2 rounded-full text-xs font-bold bg-white/80 hover:bg-white text-[#12162B] border border-white/90 shadow-2xs transition">
          Dashboard
        </a>
      @else
        <a href="{{ url('/login') }}" class="px-4 py-2 rounded-full text-xs font-bold text-[#5B6478] hover:text-[#12162B] transition">
          Login
        </a>
        <a href="{{ url('/register') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-white/80 hover:bg-white text-[#12162B] border border-white/90 shadow-2xs transition">
          Register
        </a>
      @endauth
      <a href="{{ url('/reservasi') }}" class="px-5 py-2.5 rounded-full text-xs font-extrabold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition shadow-md shadow-blue-500/25">
        Reservasi Sekarang
      </a>
    </div>

    <!-- Mobile Menu Button -->
    <button id="mobileNavToggle" type="button" class="md:hidden p-2 rounded-xl text-[#5B6478] hover:text-[#12162B] hover:bg-white/60 transition" aria-label="Menu">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- Mobile Dropdown Menu -->
  <div id="mobileNavMenu" class="hidden md:hidden max-w-7xl mx-auto mt-2 bg-white/90 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-lg space-y-3">
    <div class="flex flex-col space-y-2 text-sm font-semibold text-[#5B6478]">
      <a href="#fitur" class="py-2 px-3 rounded-xl hover:bg-white/60 hover:text-[#2F6BFF] transition">Fasilitas</a>
      <a href="#jadwal-kalender" class="py-2 px-3 rounded-xl hover:bg-white/60 hover:text-[#2F6BFF] transition">Jadwal Kalender</a>
      <a href="#harga" class="py-2 px-3 rounded-xl hover:bg-white/60 hover:text-[#2F6BFF] transition">Harga Sewa</a>
      <a href="#membership" class="py-2 px-3 rounded-xl hover:bg-white/60 hover:text-[#2F6BFF] transition">Membership</a>
      <a href="{{ route('absensi.index') }}" target="_blank" class="py-2 px-3 rounded-xl hover:bg-white/60 hover:text-[#2F6BFF] transition">Kiosk RFID</a>
    </div>
    <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
      @auth
        <a href="{{ Auth::user()->isAdmin() || Auth::user()->isSuperAdmin() ? url('/admin/dashboard') : url('/reservasi') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-bold bg-white text-[#12162B] border border-slate-200 shadow-2xs">
          Dashboard
        </a>
      @else
        <div class="grid grid-cols-2 gap-2">
          <a href="{{ url('/login') }}" class="text-center py-2.5 rounded-xl text-xs font-bold text-[#5B6478] bg-slate-100">
            Login
          </a>
          <a href="{{ url('/register') }}" class="text-center py-2.5 rounded-xl text-xs font-bold bg-white text-[#12162B] border border-slate-200">
            Register
          </a>
        </div>
      @endauth
      <a href="{{ url('/reservasi') }}" class="w-full text-center py-3 rounded-xl text-xs font-extrabold bg-[#2F6BFF] text-white shadow-md shadow-blue-500/25">
        Reservasi Sekarang
      </a>
    </div>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('mobileNavToggle');
    const menu = document.getElementById('mobileNavMenu');
    if (toggle && menu) {
      toggle.addEventListener('click', () => {
        menu.classList.toggle('hidden');
      });
      // Close when clicking nav link
      menu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => menu.classList.add('hidden'));
      });
    }
  });
</script>
