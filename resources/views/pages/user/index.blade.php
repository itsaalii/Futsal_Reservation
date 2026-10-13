@extends('layouts.app')

@section('title', 'Jaya Futsal | Sistem Reservasi Lapangan')

@section('content')
  @include('partials.user.landing-navbar')

  <!-- Hero Section -->
  <header class="py-12 sm:py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
        <!-- Hero Text (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200 shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-teal-600 animate-pulse"></span>
            <span>SISTEM RESERVASI ONLINE 24/7</span>
          </div>

          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-ink tracking-tight leading-none">
            Main Futsal Jadi Lebih Praktis di <span class="text-teal-700">Jaya Futsal</span>
          </h1>

          <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl">
            Cek slot ketersediaan lapangan real-time, pesan dalam hitungan menit, konfirmasi pembayaran otomatis, dan nikmati diskon khusus member.
          </p>

          <!-- Action Buttons -->
          <div class="flex flex-wrap items-center gap-3 pt-2">
            <a href="{{ url('/reservasi') }}" class="px-6 py-3.5 rounded-2xl text-sm sm:text-base font-bold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm hover:shadow flex items-center gap-2">
              <span>Mulai Booking Lapangan</span>
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
            <a href="#harga" class="px-6 py-3.5 rounded-2xl text-sm sm:text-base font-semibold bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 transition shadow-2xs">
              Lihat Tarif Sewa
            </a>
          </div>

          <!-- Key Metrics -->
          <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-200/80 max-w-lg">
            <div>
              <div class="text-2xl sm:text-3xl font-black text-ink">3 Lap</div>
              <div class="text-xs text-slate-500 mt-0.5">Vinyl & Sintetis</div>
            </div>
            <div>
              <div class="text-2xl sm:text-3xl font-black text-teal-700">Real-time</div>
              <div class="text-xs text-slate-500 mt-0.5">Sinkron Shift Staff</div>
            </div>
            <div>
              <div class="text-2xl sm:text-3xl font-black text-ink">15% Off</div>
              <div class="text-xs text-slate-500 mt-0.5">Untuk Member</div>
            </div>
          </div>
        </div>

        <!-- Hero Card Feature (5 cols) -->
        <div class="lg:col-span-5">
          <div class="bg-white rounded-3xl border border-slate-200/90 p-7 sm:p-8 shadow-md space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
              <h2 class="text-lg font-bold text-ink">Keunggulan Jaya Futsal</h2>
              <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200">Arena Standar</span>
            </div>

            <div class="space-y-4">
              <div class="flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-sm font-bold text-ink">Ketersediaan Jam Terjamin</h3>
                  <p class="text-xs text-slate-500 mt-0.5">Sistem otomatis menutup pemesanan jika lapangan tidak memiliki petugas yang berjaga.</p>
                </div>
              </div>

              <div class="flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-sm font-bold text-ink">Pembayaran Cepat QRIS & Cash</h3>
                  <p class="text-xs text-slate-500 mt-0.5">Bisa bayar langsung via transfer scan QRIS atau bayar tunai di meja kasir lapangan.</p>
                </div>
              </div>

              <div class="flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-sm font-bold text-ink">Kiosk Presensi RFID Mandiri</h3>
                  <p class="text-xs text-slate-500 mt-0.5">Petugas lapangan mencatat presensi mandiri dengan kartu RFID cepat tanpa antri.</p>
                </div>
              </div>
            </div>

            <a href="{{ url('/reservasi') }}" class="w-full py-3 rounded-xl text-xs sm:text-sm font-bold bg-ink hover:bg-slate-800 text-white transition flex items-center justify-center gap-2">
              <span>Buka Form Booking Sekarang</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="space-y-16 sm:space-y-24">
    <!-- Section Fitur & Fasilitas -->
    <section id="fitur" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="text-xs font-bold text-teal-600 uppercase tracking-widest">Fasilitas Lengkap</span>
        <h2 class="text-2xl sm:text-3xl font-black text-ink tracking-tight mt-1">Standar Lapangan Terbaik</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Didesain khusus untuk kenyamanan bermain, latihan tim, dan turnamen futsal.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          <h3 class="text-base font-bold text-ink mb-1.5">Rumput Sintetis & Vinyl Interlock</h3>
          <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Pilihan lantai rumput sintetis lembut dan vinyl interlock standar kompetisi, anti-slip dan aman untuk lutut serta engkel.
          </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
          </div>
          <h3 class="text-base font-bold text-ink mb-1.5">Lampu LED Sorot Terang</h3>
          <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Pencahayaan stadion optimal untuk pertandingan malam hari tanpa silau dan tanpa blind spot.
          </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <h3 class="text-base font-bold text-ink mb-1.5">Ruang Ganti & Toilet Bersih</h3>
          <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Dilengkapi kamar mandi shower air bersih, mushola yang tenang, dan loker penitipan barang yang terjaga.
          </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-base font-bold text-ink mb-1.5">Booking Mandiri 24 Jam</h3>
          <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Tidak perlu menunggu konfirmasi manual via chat lambat. Pilih slot kosong dan dapatkan tiket booking langsung.
          </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h3 class="text-base font-bold text-ink mb-1.5">Metode Bayar Fleksibel</h3>
          <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Dukung pembayaran QRIS instan dari semua bank / e-wallet, atau selesaikan pembayaran cash langsung di kasir.
          </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h3 class="text-base font-bold text-ink mb-1.5">Program Diskon Membership</h3>
          <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Daftar paket member untuk menikmati potongan harga 15% setiap bermain plus voucher gratis 1 jam pertama.
          </p>
        </div>
      </div>
    </section>

    <!-- Section Kalender Jadwal Peminjaman -->
    <section id="jadwal-kalender" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 mb-6">
          <div>
            <span class="text-xs font-bold text-teal-600 uppercase tracking-widest">Kalender Jadwal</span>
            <h2 class="text-xl sm:text-2xl font-black text-ink tracking-tight mt-1">Cek Slot Jadwal Pertandingan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pantau sesi yang sudah terisi dan slot yang masih tersedia secara langsung.</p>
          </div>
          <a href="{{ url('/reservasi') }}" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-teal-600 hover:bg-teal-700 text-white transition self-start sm:self-auto shadow-sm">
            Pilih Jadwal & Booking
          </a>
        </div>
        <div id="calendar" class="text-sm"></div>
      </div>
    </section>

    <!-- Section Tarif Harga -->
    <section id="harga" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 mb-6">
          <div>
            <span class="text-xs font-bold text-teal-600 uppercase tracking-widest">Tarif Sewa</span>
            <h2 class="text-xl sm:text-2xl font-black text-ink tracking-tight mt-1">Daftar Harga Sewa Lapangan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tarif terjangkau dengan fasilitas kualitas premium.</p>
          </div>
          <a href="{{ url('/reservasi') }}" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-ink hover:bg-slate-800 text-white transition self-start sm:self-auto">
            Booking Sesi
          </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-slate-50/80 rounded-2xl border border-slate-200/80 p-6 flex items-center justify-between">
            <div>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Sesi Siang</span>
              <h3 class="text-lg font-bold text-ink mt-1">Pagi s/d Sore Hari</h3>
              <p class="text-xs text-slate-500 mt-0.5">07:00 - 17:00 WIB</p>
            </div>
            <div class="text-right">
              <span class="text-2xl sm:text-3xl font-black text-ink">Rp60.000</span>
              <span class="text-xs text-slate-500 block">/ jam</span>
            </div>
          </div>

          <div class="bg-teal-50/60 rounded-2xl border border-teal-200/80 p-6 flex items-center justify-between">
            <div>
              <span class="text-xs font-bold text-teal-700 uppercase tracking-wider block">Sesi Prime Time</span>
              <h3 class="text-lg font-bold text-teal-900 mt-1">Malam Hari (Lampu Menyala)</h3>
              <p class="text-xs text-teal-700 mt-0.5">17:00 - 23:00 WIB</p>
            </div>
            <div class="text-right">
              <span class="text-2xl sm:text-3xl font-black text-teal-700">Rp70.000</span>
              <span class="text-xs text-teal-600 block">/ jam</span>
            </div>
          </div>
        </div>

        <div class="mt-6 p-4 rounded-2xl bg-teal-50/80 border border-teal-200/80 text-xs sm:text-sm text-teal-900 flex items-start gap-3">
          <svg class="w-5 h-5 text-teal-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <strong>Keuntungan Member:</strong> Member aktif mendapatkan <strong>FREE 1 jam bermain</strong> (voucher sekali pakai) + <strong>Diskon 15%</strong> untuk setiap reservasi selama masa aktif keanggotaan.
          </div>
        </div>
      </div>
    </section>

    <!-- Section Membership -->
    <section id="membership" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-12 shadow-sm">
        <div class="lg:col-span-7 space-y-4">
          <span class="text-xs font-bold text-teal-600 uppercase tracking-widest">Keanggotaan Eksklusif</span>
          <h2 class="text-2xl sm:text-3xl font-black text-ink tracking-tight">Gabung Sebagai Member Jaya Futsal</h2>
          <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
            Daftar sebagai member resmi Jaya Futsal untuk mendapatkan diskon tetap 15% setiap booking, jaminan ketersediaan prime time, serta voucher gratis 1 jam bermain pertama kali.
          </p>
          <div class="pt-2">
            <a href="{{ url('/reservasi?show_membership=1') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-bold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
              <span>Daftar Membership Sekarang</span>
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
          </div>
        </div>

        <div class="lg:col-span-5">
          <div class="bg-paper rounded-2xl border border-slate-200 p-6 sm:p-8 space-y-4">
            <div class="flex justify-between items-baseline">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Paket All-Access</span>
              <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200">Masa Aktif 3 Bulan</span>
            </div>
            <div class="text-3xl sm:text-4xl font-black text-ink">
              Rp150.000 <span class="text-xs font-normal text-slate-500">/ 3 bulan</span>
            </div>
            <ul class="space-y-2 text-xs sm:text-sm text-slate-600">
              <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                <span>Free 1 Jam Bermain (Voucher sekali pakai)</span>
              </li>
              <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                <span><strong>Diskon 15%</strong> untuk seluruh sesi booking</span>
              </li>
              <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                <span>Prioritas konfirmasi & pemilihan slot</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </main>

  @include('partials.user.footer')
@endsection

@push('styles')
<style>
  .fc .fc-button-primary {
    background-color: #0F766E !important;
    border-color: #0F766E !important;
    border-radius: 0.75rem !important;
    font-size: 0.8125rem !important;
    font-weight: 600 !important;
  }
  .fc .fc-button-primary:hover {
    background-color: #0B5F58 !important;
    border-color: #0B5F58 !important;
  }
  .fc-theme-standard th {
    background-color: #F8FAFC !important;
    border-color: #E2E8F0 !important;
  }
  .fc-theme-standard td {
    border-color: #F1F5F9 !important;
  }
  .fc-event {
    border-radius: 6px !important;
    padding: 2px 4px !important;
  }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/id.global.min.js'></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'id',
      initialView: 'timeGridWeek',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      slotMinTime: '07:00:00',
      slotMaxTime: '24:00:00',
      allDaySlot: false,
      events: '/api/jadwal-kalender',
      height: 'auto',
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: false,
        hour12: false
      },
      slotLabelFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
      }
    });
    calendar.render();
  });
</script>
@endpush
