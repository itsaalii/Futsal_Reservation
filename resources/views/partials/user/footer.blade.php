<footer class="bg-white/60 backdrop-blur-xl border-t border-white/80 py-8 mt-16 text-xs text-[#5B6478]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
    <p class="mb-0 font-medium">&copy; {{ date('Y') }} Jaya Futsal Arena. Hak Cipta Dilindungi.</p>
    <div class="flex items-center gap-4">
      <a href="{{ url('/absensi') }}" target="_blank" class="text-[#2F6BFF] hover:underline font-bold">
        Kiosk Absensi RFID
      </a>
      <span class="text-slate-300">•</span>
      <a href="{{ url('/login') }}" class="text-[#5B6478] hover:text-[#12162B] font-semibold transition">
        Login Petugas / Kasir
      </a>
    </div>
  </div>
</footer>
