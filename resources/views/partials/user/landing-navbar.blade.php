<nav class="sticky top-4 z-40 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-2xl px-4 sm:px-6 py-3 shadow-sm flex items-center justify-between gap-4">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group shrink-0">
      <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm group-hover:bg-teal-700 transition">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div>
      <div>
        <span class="font-black text-base sm:text-lg text-ink tracking-tight leading-none block">Jaya Futsal</span>
        <span class="text-[10px] font-bold text-teal-600 uppercase tracking-widest block">Arena Booking</span>
      </div>
    </a>

    <!-- Center Nav Links (Desktop) -->
    <div class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
      <a href="#fitur" class="hover:text-teal-700 transition">Fasilitas</a>
      <a href="#harga" class="hover:text-teal-700 transition">Jadwal & Harga</a>
      <a href="#membership" class="hover:text-teal-700 transition">Membership</a>
      <a href="{{ url('/absensi') }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200 hover:bg-teal-100 transition">
        <span>📟</span>
        <span>Kiosk RFID</span>
      </a>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
      @auth
        <a href="{{ Auth::user()->isAdmin() || Auth::user()->isSuperAdmin() ? url('/admin/dashboard') : url('/reservasi') }}" 
           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-800 transition">
          Dashboard
        </a>
      @else
        <a href="{{ url('/login') }}" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-ink hover:bg-slate-50 transition">
          Login
        </a>
      @endauth

      <a href="{{ url('/reservasi') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
        Reservasi Lapangan
      </a>
    </div>
  </div>
</nav>
