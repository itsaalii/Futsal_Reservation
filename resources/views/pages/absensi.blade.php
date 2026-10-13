<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Kiosk Absensi RFID | Jaya Futsal</title>

  <!-- Google Font: IBM Plex Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"IBM Plex Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
          },
          colors: {
            ink: '#18232F',
            paper: '#F2F5F7',
            teal: {
              50: '#E7F4F2',
              100: '#CBE6E2',
              200: '#97CFC7',
              300: '#5EEAD4',
              400: '#2DD4BF',
              500: '#14B8A6',
              600: '#0F766E',
              700: '#0B5F58',
              800: '#084843',
              900: '#05312E',
            }
          }
        }
      }
    }
  </script>

  <style>
    @keyframes pulseRing {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(15, 118, 110, 0.5); }
      70% { transform: scale(1.05); box-shadow: 0 0 0 24px rgba(15, 118, 110, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(15, 118, 110, 0); }
    }
    .pulse-animation {
      animation: pulseRing 2.2s infinite;
    }
    .scanner-active {
      border-color: #0F766E !important;
      box-shadow: 0 0 25px rgba(15, 118, 110, 0.25) !important;
    }
  </style>
</head>
<body class="bg-paper text-ink font-sans min-h-screen flex flex-col antialiased selection:bg-teal-100 selection:text-teal-900">

  <!-- Kiosk Top Navigation Bar -->
  <header class="bg-white border-b border-slate-200/80 px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 002.83 6.13" />
        </svg>
      </div>
      <div>
        <h1 class="text-base sm:text-lg font-bold text-ink leading-tight">JAYA FUTSAL RFID ATTENDANCE KIOSK</h1>
        <p class="text-xs text-slate-500">Terminal Scan Presensi Mandiri Pegawai (Tanpa Perlu Login)</p>
      </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-3">
      <a href="{{ route('home') }}" class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
        ← Ke Website
      </a>
      <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 transition shadow-2xs">
        Login Admin
      </a>
    </div>
  </header>

  <!-- Kiosk Body Content -->
  <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col justify-center">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
      <!-- Left Column: Scanner Target & Live Clock (7 cols) -->
      <div class="lg:col-span-6 flex flex-col">
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm flex-1 flex flex-col justify-between text-center">
          <div>
            <!-- Live Digital Clock -->
            <div id="liveClock" class="font-mono text-4xl sm:text-5xl font-black text-teal-700 tracking-tight">
              00:00:00
            </div>
            <div id="liveDate" class="text-xs sm:text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">
              Memuat Waktu...
            </div>
          </div>

          <!-- RFID Scanner Target Area -->
          <div id="scannerArea" class="my-6 py-8 px-4 rounded-3xl border-2 border-dashed border-teal-300 bg-teal-50/40 hover:bg-teal-50/70 transition-all cursor-pointer flex flex-col items-center justify-center relative overflow-hidden group">
            <div class="w-24 h-24 rounded-full bg-teal-600/15 text-teal-700 flex items-center justify-center mb-4 pulse-animation">
              <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>

            <h2 class="text-xl sm:text-2xl font-black text-ink tracking-tight mb-1">TEMPELKAN KARTU RFID</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-sm mb-4">
              Dekatkan kartu RFID pegawai ke sensor USB reader.<br>Sistem otomatis mencatat Jam Masuk / Jam Pulang.
            </p>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span>Scanner Siap & Aktif</span>
            </span>

            <!-- Hidden input that holds focus for physical RFID reader -->
            <input type="text" id="rfidInput" class="absolute opacity-0 pointer-events-none" autofocus autocomplete="off">
          </div>

          <!-- Dummy Quick-Test Simulation Buttons -->
          <div class="pt-4 border-t border-slate-100 text-left">
            <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5">
              <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
              </svg>
              <span>Uji Coba Cepat (Simulasi Klik Kartu Dummy):</span>
            </div>
            <div class="flex flex-wrap gap-2">
              <button type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition" onclick="simulateScan('RFID-ADM-001')">
                👤 Bambang (Lap 1)
              </button>
              <button type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition" onclick="simulateScan('RFID-ADM-002')">
                👤 Siti (Lap 2)
              </button>
              <button type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition" onclick="simulateScan('RFID-ADM-003')">
                👤 Rian (Lap 3)
              </button>
              <button type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition" onclick="simulateScan('RFID-OWNER')">
                👑 Superadmin
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Live Feed Kehadiran Hari Ini (6 cols) -->
      <div class="lg:col-span-6 flex flex-col">
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm flex-1 flex flex-col justify-between overflow-hidden">
          <div>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
              <div>
                <h2 class="text-base sm:text-lg font-bold text-ink">Log Kehadiran Hari Ini</h2>
                <p class="text-xs text-slate-500">Riwayat scan presensi real-time terkini</p>
              </div>
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-600 animate-ping"></span>
                <span>Live Feed</span>
              </span>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                  <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <th class="py-2.5 px-2">Pegawai</th>
                    <th class="py-2.5 px-2">Lapangan</th>
                    <th class="py-2.5 px-2">Shift</th>
                    <th class="py-2.5 px-2">Masuk</th>
                    <th class="py-2.5 px-2">Pulang</th>
                    <th class="py-2.5 px-2 text-right">Status</th>
                  </tr>
                </thead>
                <tbody id="recentAttendanceBody" class="divide-y divide-slate-100">
                  @forelse($recentAbsensi as $absen)
                    <tr class="hover:bg-slate-50/60 transition">
                      <td class="py-3 px-2 font-bold text-ink">
                        {{ $absen->user->nama ?? 'Pegawai' }}
                      </td>
                      <td class="py-3 px-2 text-slate-500">
                        {{ $absen->lapangan->nama ?? 'Umum' }}
                      </td>
                      <td class="py-3 px-2">
                        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                          {{ $absen->shift ?: 'Reguler' }}
                        </span>
                      </td>
                      <td class="py-3 px-2 font-mono font-bold text-emerald-700">
                        {{ substr($absen->jam_masuk, 0, 5) }}
                      </td>
                      <td class="py-3 px-2 font-mono font-semibold text-slate-600">
                        {{ $absen->jam_keluar ? substr($absen->jam_keluar, 0, 5) : '-' }}
                      </td>
                      <td class="py-3 px-2 text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $absen->jam_keluar ? 'bg-slate-100 text-slate-600' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                          {{ $absen->jam_keluar ? 'Selesai' : 'Bertugas' }}
                        </span>
                      </td>
                    </tr>
                  @empty
                    <tr id="emptyRow">
                      <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                        Belum ada aktivitas scan RFID hari ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Bottom Notice -->
          <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-500 flex items-center gap-2.5">
            <svg class="w-4 h-4 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Reader scanner USB membaca kode kartu otomatis saat didekatkan. Klik layar sekali jika pembaca belum fokus.</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- MODAL FEEDBACK HASIL SCAN (TAILWIND) -->
  <div id="scanResultModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl relative text-center">
      <div id="modalIconContainer" class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-3"></div>
      <h3 class="text-xl font-black text-ink mb-1" id="modalTitle">Scan Berhasil</h3>
      <p class="text-xs text-slate-500 mb-4" id="modalSubtitle"></p>

      <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 text-xs text-left space-y-2 mb-5">
        <div class="flex justify-between">
          <span class="text-slate-400 font-medium">Nama Pegawai:</span>
          <strong class="text-ink" id="modalNama">-</strong>
        </div>
        <div class="flex justify-between">
          <span class="text-slate-400 font-medium">Lapangan Tugas:</span>
          <span class="text-slate-700" id="modalLapangan">-</span>
        </div>
        <div class="flex justify-between">
          <span class="text-slate-400 font-medium">Shift:</span>
          <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-teal-50 text-teal-800" id="modalShift">-</span>
        </div>
        <div class="flex justify-between">
          <span class="text-slate-400 font-medium">Waktu Scan:</span>
          <strong class="text-teal-700 font-mono text-xs" id="modalWaktu">-</strong>
        </div>
      </div>

      <button type="button" onclick="closeKioskModal()" class="w-full py-2.5 rounded-xl text-sm font-semibold bg-ink hover:bg-slate-800 text-white transition">
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
        iconContainer.className = `w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-3 ${isMasuk ? 'bg-teal-50 text-teal-700' : 'bg-emerald-50 text-emerald-700'}`;
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
        modalTitle.className = isMasuk ? 'text-xl font-black text-teal-800 mb-1' : 'text-xl font-black text-emerald-800 mb-1';
        modalSubtitle.textContent = body.message;

        modalNama.textContent = body.data.nama;
        modalLapangan.textContent = body.data.lapangan;
        modalShift.textContent = body.data.shift;
        modalWaktu.textContent = `${body.data.jam} WIB`;
      } else {
        iconContainer.className = 'w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-3 bg-rose-50 text-rose-600';
        iconContainer.innerHTML = `
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        `;
        modalTitle.textContent = 'Gagal Melakukan Absensi';
        modalTitle.className = 'text-xl font-black text-rose-700 mb-1';
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
            tbody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-slate-400 text-xs">Belum ada aktivitas scan RFID hari ini.</td></tr>';
            return;
          }
          data.forEach(item => {
            const row = `
              <tr class="hover:bg-slate-50/60 transition">
                <td class="py-3 px-2 font-bold text-ink">${item.nama}</td>
                <td class="py-3 px-2 text-slate-500">${item.lapangan}</td>
                <td class="py-3 px-2">
                  <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                    ${item.shift}
                  </span>
                </td>
                <td class="py-3 px-2 font-mono font-bold text-emerald-700">${item.jam_masuk}</td>
                <td class="py-3 px-2 font-mono font-semibold text-slate-600">${item.jam_keluar}</td>
                <td class="py-3 px-2 text-right">
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold ${item.jam_keluar !== '-' ? 'bg-slate-100 text-slate-600' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'}">
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
