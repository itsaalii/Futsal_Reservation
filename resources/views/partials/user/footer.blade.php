<footer class="bg-white border-t border-slate-200/80 py-6 mt-12 text-xs text-slate-500">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
    <p class="mb-0">&copy; {{ date('Y') }} Jaya Futsal Arena. Hak Cipta Dilindungi.</p>
    <div class="flex items-center gap-4">
      <a href="{{ url('/absensi') }}" target="_blank" class="text-teal-700 hover:underline font-semibold">
        Kiosk Absensi RFID
      </a>
      <span class="text-slate-300">•</span>
      <a href="{{ url('/login') }}" class="text-slate-700 hover:text-ink font-medium">
        Login Petugas / Kasir
      </a>
    </div>
  </div>
</footer>
