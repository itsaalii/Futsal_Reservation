@extends('layouts.app')

@section('title', 'Jaya Futsal | Reservasi Lapangan')

@section('content')
  @include('partials.user.landing-navbar')

  <main>
    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-6 pb-14 sm:pt-10 sm:pb-20">
      <!-- Ambient background glows -->
      <div class="absolute -top-32 -left-32 w-80 h-80 bg-blue-400/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute top-1/2 -right-32 w-96 h-96 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
          <!-- Hero Left Column -->
          <div class="lg:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-[#2F6BFF] text-xs font-extrabold uppercase tracking-widest">
              <span class="w-2 h-2 rounded-full bg-[#2F6BFF] animate-pulse"></span>
              Sistem Reservasi Online
            </div>

            <div class="flex flex-wrap items-center gap-2">
              <span class="px-3.5 py-1.5 rounded-full text-xs font-bold text-[#1E2A78] bg-white/70 backdrop-blur-md border border-white/80 shadow-2xs">Pilih Jadwal</span>
              <svg class="w-3.5 h-3.5 text-[#8A93A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              <span class="px-3.5 py-1.5 rounded-full text-xs font-bold text-[#1E2A78] bg-white/70 backdrop-blur-md border border-white/80 shadow-2xs">Cek Slot</span>
              <svg class="w-3.5 h-3.5 text-[#8A93A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              <span class="px-3.5 py-1.5 rounded-full text-xs font-bold text-[#1E2A78] bg-white/70 backdrop-blur-md border border-white/80 shadow-2xs">Bayar</span>
              <svg class="w-3.5 h-3.5 text-[#8A93A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              <span class="px-3.5 py-1.5 rounded-full text-xs font-bold text-[#2F6BFF] bg-blue-50/80 backdrop-blur-md border border-blue-200/80 shadow-2xs">Main</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#12162B] tracking-tight leading-[1.12]">
              Main Futsal Jadi <span class="bg-gradient-to-r from-[#2F6BFF] to-[#7B5CE6] bg-clip-text text-transparent">Lebih Gampang</span> di Jaya Futsal
            </h1>

            <p class="text-base sm:text-lg text-[#5B6478] leading-relaxed max-w-2xl font-normal">
              Cek slot lapangan real-time, booking dalam hitungan menit, bayar langsung, dan pantau riwayat pertandinganmu dari satu dashboard.
            </p>

            <div class="flex flex-wrap items-center gap-4 pt-2">
              <a href="{{ url('/reservasi') }}" class="px-7 py-3.5 rounded-full text-sm font-extrabold text-white bg-gradient-to-r from-[#2F6BFF] to-[#3A4BD1] hover:from-[#2557DE] hover:to-[#2935A1] shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                <span>Mulai Reservasi</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </a>
              <a href="#harga" class="px-7 py-3.5 rounded-full text-sm font-bold text-[#12162B] bg-white/75 hover:bg-white backdrop-blur-md border border-white/90 shadow-sm transition transform hover:-translate-y-0.5">
                Lihat Harga
              </a>
            </div>

            <!-- Quick Stats Cards with generous inner padding -->
            <div class="grid grid-cols-3 gap-3 sm:gap-4 pt-6">
              <div class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-2xl p-4 sm:p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="text-2xl sm:text-3xl font-extrabold text-[#12162B] tracking-tight">1</div>
                <div class="text-xs font-semibold text-[#5B6478] mt-1">Lapangan Indoor</div>
              </div>
              <div class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-2xl p-4 sm:p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="text-2xl sm:text-3xl font-extrabold text-[#12162B] tracking-tight">24/7</div>
                <div class="text-xs font-semibold text-[#5B6478] mt-1">Dukungan Reservasi</div>
              </div>
              <div class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-2xl p-4 sm:p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="text-2xl sm:text-3xl font-extrabold text-[#12162B] tracking-tight">1.2k+</div>
                <div class="text-xs font-semibold text-[#5B6478] mt-1">Booking Sukses/Bulan</div>
              </div>
            </div>
          </div>

          <!-- Hero Right Column -->
          <div class="lg:col-span-5 space-y-6">
            <div class="bg-white/55 backdrop-blur-xl border border-white/80 rounded-3xl p-5 sm:p-7 shadow-xl space-y-5">
              <div class="rounded-2xl overflow-hidden shadow-inner border border-white/60 bg-emerald-700/10 p-2">
                <img class="w-full h-auto rounded-xl object-contain drop-shadow" src="{{ asset('assets/img/futsal-pitch.svg') }}" alt="Ilustrasi lapangan futsal" />
              </div>

              <div class="bg-white/80 backdrop-blur-md rounded-2xl p-5 sm:p-6 border border-white/80 shadow-xs space-y-3">
                <h2 class="text-base font-extrabold text-[#12162B] flex items-center gap-2">
                  <svg class="w-5 h-5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Kenapa Pilih Jaya Futsal?
                </h2>
                <ul class="space-y-3 text-xs sm:text-sm font-medium text-[#5B6478]">
                  <li class="flex items-start gap-2.5">
                    <div class="w-5 h-5 rounded-full bg-emerald-500/15 text-[#22B14C] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>Tersedia jadwal per jam yang selalu terupdate real-time</span>
                  </li>
                  <li class="flex items-start gap-2.5">
                    <div class="w-5 h-5 rounded-full bg-emerald-500/15 text-[#22B14C] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>Pembayaran fleksibel & praktis via QRIS otomatis</span>
                  </li>
                  <li class="flex items-start gap-2.5">
                    <div class="w-5 h-5 rounded-full bg-emerald-500/15 text-[#22B14C] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>Bisa batalkan reservasi sesuai kebijakan fleksibel</span>
                  </li>
                  <li class="flex items-start gap-2.5">
                    <div class="w-5 h-5 rounded-full bg-emerald-500/15 text-[#22B14C] flex items-center justify-center shrink-0 mt-0.5">
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>Program membership untuk diskon rutin & promo eksklusif</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Keunggulan / Fasilitas Section -->
    <section id="fitur" class="py-14 sm:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
          <p class="text-xs font-extrabold text-[#2F6BFF] uppercase tracking-widest mb-2">Keunggulan Kami</p>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-[#12162B] tracking-tight">Fasilitas & Pelayanan Terbaik</h2>
          <p class="text-sm sm:text-base text-[#5B6478] mt-3">Nikmati pengalaman bertanding futsal dengan standar arena profesional dan kenyamanan maksimal.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
          <!-- Card 1 -->
          <article class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-3xl p-7 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-[#2F6BFF] border border-blue-500/20 flex items-center justify-center mb-5 group-hover:bg-[#2F6BFF] group-hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#12162B] mb-2">Rumput Sintetis Premium</h3>
            <p class="text-sm text-[#5B6478] leading-relaxed">
              Lapangan kami menggunakan rumput sintetis standar internasional yang aman, tidak licin, dan nyaman digunakan untuk pertandingan intens.
            </p>
          </article>

          <!-- Card 2 -->
          <article class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-3xl p-7 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-[#2F6BFF] border border-blue-500/20 flex items-center justify-center mb-5 group-hover:bg-[#2F6BFF] group-hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#12162B] mb-2">Fasilitas Lengkap</h3>
            <p class="text-sm text-[#5B6478] leading-relaxed">
              Dilengkapi dengan ruang ganti pemain yang luas, toilet bersih, shower, mushola, serta area parkir yang aman untuk kendaraan Anda.
            </p>
          </article>

          <!-- Card 3 -->
          <article class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-3xl p-7 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-[#2F6BFF] border border-blue-500/20 flex items-center justify-center mb-5 group-hover:bg-[#2F6BFF] group-hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#12162B] mb-2">Kantin & Ruang Tunggu</h3>
            <p class="text-sm text-[#5B6478] leading-relaxed">
              Tersedia kantin yang menyajikan berbagai pilihan minuman segar dan makanan ringan, serta area tribun penonton yang nyaman.
            </p>
          </article>

          <!-- Card 4 -->
          <article class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-3xl p-7 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-[#2F6BFF] border border-blue-500/20 flex items-center justify-center mb-5 group-hover:bg-[#2F6BFF] group-hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#12162B] mb-2">Booking Online 24/7</h3>
            <p class="text-sm text-[#5B6478] leading-relaxed">
              Tidak perlu repot datang atau telepon. Cek jadwal kosong secara real-time dan booking lapangan langsung dari HP kamu kapan saja.
            </p>
          </article>

          <!-- Card 5 -->
          <article class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-3xl p-7 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-[#2F6BFF] border border-blue-500/20 flex items-center justify-center mb-5 group-hover:bg-[#2F6BFF] group-hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#12162B] mb-2">Pembayaran Fleksibel</h3>
            <p class="text-sm text-[#5B6478] leading-relaxed">
              Mendukung pembayaran instan dengan QRIS, verifikasi otomatis, serta bukti pembayaran digital langsung tersimpan di akun Anda.
            </p>
          </article>

          <!-- Card 6 -->
          <article class="bg-white/65 backdrop-blur-xl border border-white/80 rounded-3xl p-7 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col group">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-[#2F6BFF] border border-blue-500/20 flex items-center justify-center mb-5 group-hover:bg-[#2F6BFF] group-hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#12162B] mb-2">Program Membership</h3>
            <p class="text-sm text-[#5B6478] leading-relaxed">
              Dapatkan harga spesial, diskon rutin 15%, dan prioritas booking pada jam-jam prime time dengan mendaftar sebagai member Jaya Futsal.
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- Jadwal Kalender Section -->
    <section id="jadwal-kalender" class="py-14 sm:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
          <p class="text-xs font-extrabold text-[#2F6BFF] uppercase tracking-widest mb-2">Cek Ketersediaan</p>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-[#12162B] tracking-tight">Jadwal Peminjaman Lapangan</h2>
          <p class="text-sm sm:text-base text-[#5B6478] mt-3">Pantau slot yang sudah dipesan maupun yang masih tersedia sebelum menentukan jadwal bermain tim Anda.</p>
        </div>

        <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 sm:p-8 shadow-sm">
          <div id="calendar"></div>
        </div>
      </div>
    </section>

    <!-- Harga Sewa Lapangan Section -->
    <section id="harga" class="py-14 sm:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
          <div>
            <p class="text-xs font-extrabold text-[#2F6BFF] uppercase tracking-widest mb-2">Melihat Jadwal & Harga</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#12162B] tracking-tight">Harga Sewa Lapangan</h2>
          </div>
          <a href="{{ url('/reservasi') }}#step-1" class="px-6 py-3 rounded-full text-xs font-extrabold text-white bg-[#2F6BFF] hover:bg-[#3A4BD1] shadow-md shadow-blue-500/20 transition self-start sm:self-auto flex items-center gap-2">
            <span>Pilih Jadwal</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </a>
        </div>

        <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#12162B]">
              <thead>
                <tr class="border-b border-slate-200/80 text-xs font-bold uppercase tracking-wider text-[#5B6478]">
                  <th class="py-4 px-4 sm:px-6">Waktu</th>
                  <th class="py-4 px-4 sm:px-6">Jam Operasional</th>
                  <th class="py-4 px-4 sm:px-6">Harga Sewa per Jam</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-white/50 transition">
                  <td class="py-4 px-4 sm:px-6 font-bold flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span>Pagi / Siang</span>
                  </td>
                  <td class="py-4 px-4 sm:px-6 text-[#5B6478] font-medium">07:00 - 17:00</td>
                  <td class="py-4 px-4 sm:px-6 font-extrabold text-[#12162B]">
                    Rp60.000 <span class="text-xs font-normal text-[#5B6478]">/ jam</span>
                  </td>
                </tr>
                <tr class="hover:bg-white/50 transition">
                  <td class="py-4 px-4 sm:px-6 font-bold flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-[#2F6BFF] flex items-center justify-center shrink-0">
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </div>
                    <span>Malam</span>
                  </td>
                  <td class="py-4 px-4 sm:px-6 text-[#5B6478] font-medium">17:00 - 23:00</td>
                  <td class="py-4 px-4 sm:px-6 font-extrabold text-[#12162B]">
                    Rp70.000 <span class="text-xs font-normal text-[#5B6478]">/ jam</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="bg-blue-50/80 border border-blue-200/80 rounded-2xl p-4 sm:p-5 flex items-start sm:items-center gap-3 text-xs sm:text-sm text-[#1E2A78]">
            <div class="w-6 h-6 rounded-full bg-blue-500/15 text-[#2F6BFF] flex items-center justify-center shrink-0 mt-0.5 sm:mt-0">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <strong>Member Aktif</strong> mendapatkan <strong>FREE 1 jam bermain</strong> (voucher sekali pakai) + <strong>Diskon 15%</strong> setiap reservasi selama masa membership.
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Membership Section -->
    <section id="membership" class="py-14 sm:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
          <!-- Membership Info -->
          <div class="lg:col-span-7 space-y-4">
            <p class="text-xs font-extrabold text-[#2F6BFF] uppercase tracking-widest mb-1">Daftar Membership</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#12162B] tracking-tight">Member Jaya Futsal</h2>
            <p class="text-base text-[#5B6478] leading-relaxed">
              Daftar sebagai member Jaya Futsal dengan biaya Rp150.000 dan dapatkan keuntungan eksklusif
              berupa <strong>free 1 jam bermain</strong> (voucher sekali pakai) selama masa membership 3 bulan.
              Jika dalam 3 bulan tidak pernah melakukan booking, membership akan otomatis hangus.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4">
              <div class="bg-white/60 backdrop-blur-md border border-white/80 rounded-2xl p-4 shadow-2xs">
                <div class="font-bold text-sm text-[#12162B]">Hemat Biaya Bermain</div>
                <div class="text-xs text-[#5B6478] mt-1">Diskon 15% setiap reservasi tanpa batas pertandingan.</div>
              </div>
              <div class="bg-white/60 backdrop-blur-md border border-white/80 rounded-2xl p-4 shadow-2xs">
                <div class="font-bold text-sm text-[#12162B]">Prioritas Reservasi</div>
                <div class="text-xs text-[#5B6478] mt-1">Konfirmasi instan pada jam-jam sibuk prime time malam hari.</div>
              </div>
            </div>
          </div>

          <!-- Membership Card -->
          <div class="lg:col-span-5">
            <div class="bg-white/80 backdrop-blur-xl border-2 border-blue-500/30 rounded-3xl p-7 sm:p-9 shadow-xl relative overflow-hidden space-y-6">
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-xs font-extrabold text-[#2F6BFF] uppercase tracking-wider block">Paket Eksklusif</span>
                  <h3 class="text-xl font-extrabold text-[#12162B]">Member Jaya Futsal</h3>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-[#2F6BFF] flex items-center justify-center font-bold text-sm">
                  3 Bln
                </div>
              </div>

              <div class="pt-2">
                <div class="text-3xl sm:text-4xl font-extrabold text-[#12162B] tracking-tight">
                  Rp150.000 <span class="text-xs sm:text-sm font-semibold text-[#5B6478]">/ 3 bulan</span>
                </div>
                <p class="text-xs text-[#5B6478] mt-1">Biaya terjangkau untuk benefit rutin</p>
              </div>

              <ul class="space-y-3 text-xs sm:text-sm text-[#5B6478]">
                <li class="flex items-center gap-2.5">
                  <div class="w-5 h-5 rounded-full bg-emerald-500/15 text-[#22B14C] flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <span><strong>Free 1 jam bermain</strong> (voucher sekali pakai)</span>
                </li>
                <li class="flex items-center gap-2.5">
                  <div class="w-5 h-5 rounded-full bg-emerald-500/15 text-[#22B14C] flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <span><strong>Diskon 15%</strong> untuk setiap reservasi</span>
                </li>
                <li class="flex items-center gap-2.5">
                  <div class="w-5 h-5 rounded-full bg-emerald-500/15 text-[#22B14C] flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <span>Prioritas konfirmasi booking</span>
                </li>
                <li class="flex items-center gap-2.5">
                  <div class="w-5 h-5 rounded-full bg-emerald-500/15 text-[#22B14C] flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <span>Masa aktif 3 bulan kalender</span>
                </li>
              </ul>

              <a href="{{ url('/reservasi?show_membership=1') }}" class="w-full text-center py-3.5 px-6 rounded-full text-sm font-extrabold text-white bg-gradient-to-r from-[#2F6BFF] to-[#3A4BD1] hover:from-[#2557DE] hover:to-[#2935A1] shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5 block">
                Daftar Sekarang
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  @include('partials.user.footer')
@endsection

@push('styles')
<style>
  #calendar {
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .fc-event {
    border: none !important;
    border-radius: 8px;
    padding: 3px 6px;
    font-size: 0.82rem;
  }
  .fc-event-title {
    font-weight: 700;
  }
  .fc-timegrid-slot {
    height: 2.5em;
  }
  .fc .fc-button-primary {
    background: linear-gradient(135deg, var(--primary, #2F6BFF) 0%, var(--primary-dark, #3A4BD1) 100%) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    text-transform: capitalize;
    border-radius: 12px !important;
    font-weight: 600;
    padding: 0.5rem 1rem !important;
    box-shadow: 0 4px 12px rgba(47, 107, 255, 0.2) !important;
  }
  .fc .fc-button-primary:hover {
    background: var(--primary-dark, #3A4BD1) !important;
  }
  .fc .fc-button-primary:not(:disabled):active,
  .fc .fc-button-primary:not(:disabled).fc-button-active {
    background: var(--primary-dark, #3A4BD1) !important;
  }
  .fc .fc-button-primary:disabled {
    background: #B8D1FF !important;
    border-color: #B8D1FF !important;
  }
  .fc-theme-standard th {
    background-color: rgba(255, 255, 255, 0.7) !important;
    border-bottom-width: 2px !important;
  }
  .fc-col-header-cell-cushion {
    color: var(--text-heading, #12162B);
    text-decoration: none !important;
    font-weight: 800;
    padding: 10px 4px !important;
    display: block;
    font-size: 0.95rem;
  }
  .fc-col-header-cell-cushion:hover {
    color: var(--primary, #2F6BFF);
  }
  .fc .fc-col-header-cell.fc-day-today {
    background-color: rgba(47, 107, 255, 0.08) !important;
  }
  .fc-daygrid-day-number {
    color: var(--text-heading, #12162B);
    text-decoration: none !important;
    font-weight: 700;
  }
  .fc-daygrid-day-number:hover {
    color: var(--primary, #2F6BFF);
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
