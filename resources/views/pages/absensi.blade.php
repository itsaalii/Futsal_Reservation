<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Kiosk Absensi RFID | Jaya Futsal</title>

  <!-- Google Font: Plus Jakarta Sans & IBM Plex Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', '"IBM Plex Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
          },
          colors: {
            primary: {
              DEFAULT: '#2F6BFF',
              dark: '#3A4BD1',
              50: '#EEF4FF',
              100: '#DDE9FF',
              200: '#C0D5FF',
              300: '#94BAFF',
              400: '#6196FF',
              500: '#2F6BFF',
              600: '#2557DE',
              700: '#3A4BD1',
              800: '#2935A1',
              900: '#1E2A78',
            },
            teal: {
              50: '#EEF4FF',
              100: '#DDE9FF',
              200: '#C0D5FF',
              300: '#94BAFF',
              400: '#6196FF',
              500: '#2F6BFF',
              600: '#2F6BFF',
              700: '#3A4BD1',
              800: '#2935A1',
              900: '#1E2A78',
            },
            secondary: '#7B5CE6',
            accent: '#4FA8F0',
            brand: {
              page: '#DCE4EF',
              sidebar: '#C9D6F2',
              card: 'rgba(255, 255, 255, 0.55)',
              border: 'rgba(255, 255, 255, 0.7)',
              heading: '#12162B',
              name: '#1E2A78',
              muted: '#5B6478',
              disabled: '#8A93A6',
              success: '#22B14C',
              warning: '#F5A524',
              danger: '#EF4F5F',
              info: '#3B82F6',
            },
            ink: '#12162B',
            paper: '#DCE4EF',
          }
        }
      }
    }
  </script>

  <style>
    @keyframes pulseRing {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(47, 107, 255, 0.5); }
      70% { transform: scale(1.05); box-shadow: 0 0 0 24px rgba(47, 107, 255, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(47, 107, 255, 0); }
    }
    .pulse-animation {
      animation: pulseRing 2.2s infinite;
    }
    .scanner-active {
      border-color: #2F6BFF !important;
      box-shadow: 0 0 25px rgba(47, 107, 255, 0.3) !important;
    }

    body {
      background-color: #DCE4EF;
      background-image: 
        radial-gradient(circle at 12% 15%, rgba(79, 168, 240, 0.22) 0%, transparent 45%),
        radial-gradient(circle at 88% 80%, rgba(123, 92, 230, 0.18) 0%, transparent 50%),
        radial-gradient(circle at 50% 50%, rgba(47, 107, 255, 0.08) 0%, transparent 60%);
      background-attachment: fixed;
    }

    .kiosk-glass {
      background: rgba(255, 255, 255, 0.65);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid rgba(255, 255, 255, 0.8);
      box-shadow: 0 15px 35px -5px rgba(30, 42, 120, 0.06), 0 5px 15px rgba(0, 0, 0, 0.02);
    }
  </style>
</head>
<body class="text-ink font-sans min-h-screen flex flex-col antialiased selection:bg-primary-100 selection:text-primary-900">

  <!-- Kiosk Top Navigation Bar -->
  <header class="bg-white/60 backdrop-blur-xl border-b border-white/80 px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#2F6BFF] to-[#7B5CE6] text-white flex items-center justify-center shadow-md shadow-blue-500/20">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 002.83 6.13" />
        </svg>
      </div>
      <div>
        <h1 class="text-base sm:text-lg font-extrabold text-[#12162B] leading-tight">JAYA FUTSAL RFID ATTENDANCE KIOSK</h1>
        <p class="text-xs text-[#5B6478]">Terminal Scan Presensi Mandiri Pegawai (Tanpa Perlu Login)</p>
      </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-3">
      <a href="{{ route('home') }}" class="px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold text-[#5B6478] hover:text-[#12162B] bg-white/70 hover:bg-white border border-white/80 transition shadow-2xs">
        ← Ke Website
      </a>
      <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold text-white bg-[#2F6BFF] hover:bg-[#3A4BD1] transition shadow-sm shadow-blue-500/25">
        Login Admin
      </a>
    </div>
  </header>

  <!-- Kiosk Body Content -->
  <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col justify-center">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
      <!-- Left Column: Scanner Target & Live Clock (6 cols) -->
      <div class="lg:col-span-6 flex flex-col">
        <div class="kiosk-glass rounded-3xl p-6 sm:p-8 flex-1 flex flex-col justify-between text-center">
          <div>
            <!-- Live Digital Clock -->
            <div id="liveClock" class="font-mono text-4xl sm:text-5xl font-black text-[#2F6BFF] tracking-tight">
              00:00:00
            </div>
            <div id="liveDate" class="text-xs sm:text-sm font-bold text-[#5B6478] uppercase tracking-widest mt-1">
              Memuat Waktu...
            </div>
          </div>

          <!-- RFID Scanner Target Area -->
          <div id="scannerArea" class="my-6 py-8 px-4 rounded-3xl border-2 border-dashed border-[#2F6BFF]/40 bg-primary-50/50 hover:bg-primary-50/80 transition-all cursor-pointer flex flex-col items-center justify-center relative overflow-hidden group">
            <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-[#2F6BFF]/20 to-[#7B5CE6]/20 text-[#2F6BFF] flex items-center justify-center mb-4 pulse-animation">
              <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>

            <h2 class="text-xl sm:text-2xl font-extrabold text-[#12162B] tracking-tight mb-1">TEMPELKAN KARTU RFID</h2>
            <p class="text-xs sm:text-sm text-[#5B6478] max-w-sm mb-4">
              Dekatkan kartu RFID pegawai ke sensor USB reader.<br>Sistem otomatis mencatat Jam Masuk / Jam Pulang.
            </p>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-[#22B14C] border border-emerald-300/40 shadow-2xs">
              <span class="w-2 h-2 rounded-full bg-[#22B14C] animate-pulse"></span>
              <span>Scanner Siap & Aktif</span>
            </span>

            <!-- Hidden input that holds focus for physical RFID reader -->
            <input type="text" id="rfidInput" class="absolute opacity-0 pointer-events-none" autofocus autocomplete="off">
          </div>

          <!-- Dummy Quick-Test Simulation Buttons -->
          <div class="pt-4 border-t border-white/60 text-left">
            <div class="flex items-center gap-1.5 text-xs font-bold text-[#5B6478] uppercase tracking-wider mb-2.5">
              <svg class="w-3.5 h-3.5 text-[#F5A524]" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
              </svg>
              <span>Uji Coba Cepat (Simulasi Klik Kartu Dummy):</span>
            </div>
            <div class="flex flex-wrap gap-2">
              <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/70 hover:bg-white text-[#12162B] border border-white/80 transition shadow-2xs" onclick="simulateScan('RFID-ADM-001')">
                <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Bambang (Lap 1)</span>
              </button>
              <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/70 hover:bg-white text-[#12162B] border border-white/80 transition shadow-2xs" onclick="simulateScan('RFID-ADM-002')">
                <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Siti (Lap 2)</span>
              </button>
              <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/70 hover:bg-white text-[#12162B] border border-white/80 transition shadow-2xs" onclick="simulateScan('RFID-ADM-003')">
                <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Rian (Lap 3)</span>
              </button>
              <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-primary-100 hover:bg-primary-200 text-[#2F6BFF] border border-primary-200 transition shadow-2xs" onclick="simulateScan('RFID-OWNER')">
                <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Superadmin</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Live Feed Kehadiran Hari Ini (6 cols) -->
      <div class="lg:col-span-6 flex flex-col">
        <div class="kiosk-glass rounded-3xl p-6 sm:p-8 flex-1 flex flex-col justify-between overflow-hidden">
          <div>
            <div class="flex items-center justify-between pb-4 border-b border-white/60 mb-4">
              <div>
                <h2 class="text-base sm:text-lg font-bold text-[#12162B]">Log Kehadiran Hari Ini</h2>
                <p class="text-xs text-[#5B6478]">Riwayat scan presensi real-time terkini</p>
              </div>
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/70 text-[#2F6BFF] border border-white/80">
                <span class="w-1.5 h-1.5 rounded-full bg-[#2F6BFF] animate-ping"></span>
                <span>Live Feed</span>
              </span>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                  <tr class="border-b border-white/60 text-[#5B6478] font-bold uppercase tracking-wider text-[11px]">
                    <th class="py-2.5 px-2">Pegawai</th>
                    <th class="py-2.5 px-2">Lapangan</th>
                    <th class="py-2.5 px-2">Shift</th>
                    <th class="py-2.5 px-2">Masuk</th>
                    <th class="py-2.5 px-2">Pulang</th>
                    <th class="py-2.5 px-2 text-right">Status</th>
                  </tr>
                </thead>
                <tbody id="recentAttendanceBody" class="divide-y divide-white/50">
                  @forelse($recentAbsensi as $absen)
                    <tr class="hover:bg-white/40 transition">
                      <td class="py-3 px-2 font-bold text-[#12162B]">
                        {{ $absen->user->nama ?? 'Pegawai' }}
                      </td>
                      <td class="py-3 px-2 text-[#5B6478]">
                        {{ $absen->lapangan->nama ?? 'Umum' }}
                      </td>
                      <td class="py-3 px-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/70 text-[#2F6BFF] border border-white/80">
                          {{ $absen->shift ?: 'Reguler' }}
                        </span>
                      </td>
                      <td class="py-3 px-2 font-mono font-bold text-[#22B14C]">
                        {{ substr($absen->jam_masuk, 0, 5) }}
                      </td>
                      <td class="py-3 px-2 font-mono font-semibold text-[#5B6478]">
                        {{ $absen->jam_keluar ? substr($absen->jam_keluar, 0, 5) : '-' }}
                      </td>
                      <td class="py-3 px-2 text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $absen->jam_keluar ? 'bg-white/60 text-[#5B6478]' : 'bg-emerald-500/10 text-[#22B14C] border border-emerald-300/40' }}">
                          {{ $absen->jam_keluar ? 'Selesai' : 'Bertugas' }}
                        </span>
                      </td>
                    </tr>
                  @empty
                    <tr id="emptyRow">
                      <td colspan="6" class="py-8 text-center text-[#8A93A6] text-xs">
                        Belum ada aktivitas scan RFID hari ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Bottom Notice -->
          <div class="mt-4 p-3.5 rounded-2xl bg-white/50 border border-white/70 text-xs text-[#5B6478] flex items-center gap-2.5">
            <svg class="w-4 h-4 text-[#2F6BFF] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Reader scanner USB membaca kode kartu otomatis saat didekatkan. Klik layar sekali jika pembaca belum fokus.</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- MODAL FEEDBACK HASIL SCAN (TAILWIND) -->
  <div id="scanResultModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="kiosk-glass rounded-3xl max-w-sm w-full p-6 shadow-2xl relative text-center">
      <div id="modalIconContainer" class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-3"></div>
      <h3 class="text-xl font-extrabold text-[#12162B] mb-1" id="modalTitle">Scan Berhasil</h3>
      <p class="text-xs text-[#5B6478] mb-4" id="modalSubtitle"></p>

      <div class="bg-white/60 rounded-2xl p-4 border border-white/80 text-xs text-left space-y-2 mb-5">
        <div class="flex justify-between">
          <span class="text-[#5B6478] font-medium">Nama Pegawai:</span>
          <strong class="text-[#12162B]" id="modalNama">-</strong>
        </div>
        <div class="flex justify-between">
          <span class="text-[#5B6478] font-medium">Lapangan Tugas:</span>
          <span class="text-[#1E2A78] font-semibold" id="modalLapangan">-</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#5B6478] font-medium">Shift:</span>
          <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary-100 text-[#2F6BFF]" id="modalShift">-</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#5B6478] font-medium">Waktu Scan:</span>
          <strong class="text-[#2F6BFF] font-mono text-xs" id="modalWaktu">-</strong>
        </div>
      </div>

      <button type="button" onclick="closeKioskModal()" class="w-full py-2.5 rounded-xl text-sm font-semibold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition shadow-sm shadow-blue-500/25">
        Lanjut
      </button>
    </div>
  </div>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const rfidInput = document.getElementById('rfidInput');
    const scannerArea = document.getElementById('scannerArea');
    const modal = document.getElementById('scanResultModal');
    let modalTimer = null;

    // Live Digital Clock
    function updateClock() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      document.getElementById('liveClock').textContent = `${hours}:${minutes}:${seconds}`;

      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('liveDate').textContent = now.toLocaleDateString('id-ID', options);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Auto-focus keep-alive for physical RFID USB Reader
    function ensureFocus() {
      if (document.activeElement !== rfidInput) {
        rfidInput.focus();
      }
    }
    document.addEventListener('click', ensureFocus);
    setInterval(ensureFocus, 1000);
    ensureFocus();

    // Listen for RFID input (Readers send code + Enter key)
    rfidInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const code = this.value.trim();
        this.value = '';
        if (code) {
          processScan(code);
        }
      }
    });

    function simulateScan(uid) {
      processScan(uid);
    }

    // Process scan via API
    function processScan(rfidUid) {
      scannerArea.classList.add('scanner-active');

      fetch("{{ route('absensi.scan') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ rfid_uid: rfidUid })
      })
      .then(response => response.json().then(data => ({ status: response.status, body: data })))
      .then(({ status, body }) => {
        scannerArea.classList.remove('scanner-active');

        if (status === 200 && body.success) {
          showModal(true, body);
          refreshRecent();
        } else {
          showModal(false, body);
        }
      })
      .catch(err => {
        scannerArea.classList.remove('scanner-active');
        alert('Terjadi kesalahan jaringan saat memproses scan.');
      });
    }

    function showModal(isSuccess, body) {
      const iconContainer = document.getElementById('modalIconContainer');
      const modalTitle = document.getElementById('modalTitle');
      const modalSubtitle = document.getElementById('modalSubtitle');
      const modalNama = document.getElementById('modalNama');
      const modalLapangan = document.getElementById('modalLapangan');
      const modalShift = document.getElementById('modalShift');
      const modalWaktu = document.getElementById('modalWaktu');

      if (isSuccess) {
        const isMasuk = body.type === 'masuk';
        iconContainer.className = `w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-3 ${isMasuk ? 'bg-primary-50 text-[#2F6BFF]' : 'bg-emerald-500/10 text-[#22B14C]'}`;
        iconContainer.innerHTML = isMasuk ? `
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
        ` : `
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        `;
        modalTitle.textContent = isMasuk ? 'Absen Masuk Berhasil!' : 'Absen Pulang Berhasil!';
        modalTitle.className = isMasuk ? 'text-xl font-extrabold text-[#1E2A78] mb-1' : 'text-xl font-extrabold text-[#22B14C] mb-1';
        modalSubtitle.textContent = body.message;

        modalNama.textContent = body.data.nama;
        modalLapangan.textContent = body.data.lapangan;
        modalShift.textContent = body.data.shift;
        modalWaktu.textContent = `${body.data.jam} WIB`;
      } else {
        iconContainer.className = 'w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-3 bg-rose-50 text-[#EF4F5F]';
        iconContainer.innerHTML = `
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        `;
        modalTitle.textContent = 'Gagal Melakukan Absensi';
        modalTitle.className = 'text-xl font-extrabold text-[#EF4F5F] mb-1';
        modalSubtitle.textContent = body.message;

        modalNama.textContent = body.data?.nama || 'Tidak Terdaftar';
        modalLapangan.textContent = '-';
        modalShift.textContent = '-';
        modalWaktu.textContent = '-';
      }

      modal.classList.remove('hidden');

      if (modalTimer) clearTimeout(modalTimer);
      modalTimer = setTimeout(() => {
        closeKioskModal();
      }, 4000);
    }

    function closeKioskModal() {
      modal.classList.add('hidden');
      ensureFocus();
    }

    // Refresh live feed
    function refreshRecent() {
      fetch("{{ route('absensi.live') }}")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById('recentAttendanceBody');
          tbody.innerHTML = '';
          if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-[#8A93A6] text-xs">Belum ada aktivitas scan RFID hari ini.</td></tr>';
            return;
          }
          data.forEach(item => {
            const row = `
              <tr class="hover:bg-white/40 transition">
                <td class="py-3 px-2 font-bold text-[#12162B]">${item.nama}</td>
                <td class="py-3 px-2 text-[#5B6478]">${item.lapangan}</td>
                <td class="py-3 px-2">
                  <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/70 text-[#2F6BFF] border border-white/80">
                    ${item.shift}
                  </span>
                </td>
                <td class="py-3 px-2 font-mono font-bold text-[#22B14C]">${item.jam_masuk}</td>
                <td class="py-3 px-2 font-mono font-semibold text-[#5B6478]">${item.jam_keluar}</td>
                <td class="py-3 px-2 text-right">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold ${item.jam_keluar !== '-' ? 'bg-white/60 text-[#5B6478]' : 'bg-emerald-500/10 text-[#22B14C] border border-emerald-300/40'}">
                    ${item.jam_keluar !== '-' ? 'Selesai' : 'Bertugas'}
                  </span>
                </td>
              </tr>
            `;
            tbody.innerHTML += row;
          });
        });
    }

    // Auto-poll live feed every 10 seconds
    setInterval(refreshRecent, 10000);
  </script>
</body>
</html>
