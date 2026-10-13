@extends('layouts.admin')

@section('title', 'Manajemen Shift Pegawai | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Manajemen Shift Pegawai & Lapangan',
    'active' => 'shift',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <!-- Success / Error Feedback -->
    @if(session('success'))
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    @endif
    @if(session('error'))
      <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    <!-- Modern Tab Navigation -->
    <div class="flex flex-wrap items-center gap-2">
      <a href="{{ route('admin.shift', ['tab' => 'harian', 'lapangan_id' => $selectedLapanganId, 'tanggal' => $selectedTanggal]) }}" 
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ request('tab') !== 'default' ? 'bg-teal-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-ink hover:bg-slate-50 border border-slate-200' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>Jadwal Harian & Penyesuaian Shift</span>
      </a>

      @if($user->isSuperAdmin())
        <a href="{{ route('admin.shift', ['tab' => 'default', 'lapangan_id' => $selectedLapanganId]) }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ request('tab') === 'default' ? 'bg-teal-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-ink hover:bg-slate-50 border border-slate-200' }}">
          <span>👑</span>
          <span>Default Shift Mingguan (Rotasi)</span>
        </a>
      @endif
    </div>

    <!-- TAB 1: JADWAL HARIAN & OVERRIDE -->
    @if(request('tab') !== 'default')
      <div class="space-y-6">
        <!-- Filter Card -->
        <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
          <form method="GET" action="{{ route('admin.shift') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <input type="hidden" name="tab" value="harian">

            <div class="sm:col-span-5">
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Lapangan</label>
              <select name="lapangan_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" onchange="this.form.submit()">
                @foreach($lapangans as $lap)
                  <option value="{{ $lap->id }}" {{ $selectedLapanganId == $lap->id ? 'selected' : '' }}>
                    {{ $lap->nama }} {{ $user->isAdmin() && $user->id_lapangan == $lap->id ? '(Lapangan Tugas Anda)' : '' }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="sm:col-span-4">
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Tanggal</label>
              <input type="date" name="tanggal" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" value="{{ $selectedTanggal }}" onchange="this.form.submit()">
            </div>

            <div class="sm:col-span-3">
              <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-900 hover:bg-slate-800 text-white transition">
                Terapkan Filter
              </button>
            </div>
          </form>

          <!-- Status Banner Lapangan -->
          <div class="mt-5 p-5 rounded-2xl border transition {{ $isBuka ? 'border-teal-200 bg-teal-50/50' : 'border-rose-200 bg-rose-50/50' }}">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <div class="flex items-center gap-2 mb-1.5">
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $isBuka ? 'bg-teal-600 text-white' : 'bg-rose-600 text-white' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isBuka ? 'bg-teal-200' : 'bg-rose-200' }}"></span>
                    {{ $isBuka ? 'STATUS: LAPANGAN BUKA' : 'STATUS: LAPANGAN TUTUP' }}
                  </span>
                  <span class="text-xs text-slate-500 font-medium">
                    Hari: <strong class="text-ink capitalize">{{ $hariIndonesia }}</strong> ({{ \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('d F Y') }})
                  </span>
                </div>
                <h3 class="text-lg font-extrabold text-ink">
                  {{ $lapangans->firstWhere('id', $selectedLapanganId)?->nama ?? 'Lapangan' }}
                </h3>
                @if(!$isBuka)
                  <p class="text-xs sm:text-sm text-rose-700 mt-1 flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Tidak ada pegawai yang ditugaskan pada tanggal ini. Pemesanan lapangan otomatis dinonaktifkan.</span>
                  </p>
                @else
                  <p class="text-xs sm:text-sm text-teal-800 mt-1">
                    Jadwal operasional aktif. Sumber: <strong>{{ ($jadwalHarianInfo['is_override'] ?? false) ? 'Penyesuaian Khusus Tanggal (Override)' : 'Default Shift Mingguan' }}</strong>.
                  </p>
                @endif
              </div>

              <button type="button" onclick="openModal('modalOverrideShift')" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shrink-0 self-start sm:self-auto shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Ubah / Tambah Shift Tanggal Ini</span>
              </button>
            </div>
          </div>
        </section>

        <!-- Tabel Petugas Hari Ini -->
        <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
          <div class="pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-ink">Daftar Pegawai Bertugas Pada Tanggal Ini</h3>
            <p class="text-xs text-slate-500 mt-0.5">Petugas yang bertanggung jawab menjaga operasional lapangan.</p>
          </div>

          <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
              <thead>
                <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="py-3 px-5 sm:px-6">Shift</th>
                  <th class="py-3 px-4">Jam Kerja</th>
                  <th class="py-3 px-4">Nama Pegawai</th>
                  <th class="py-3 px-4">RFID UID</th>
                  <th class="py-3 px-4">Status</th>
                  <th class="py-3 px-4">Keterangan</th>
                  <th class="py-3 px-5 sm:px-6 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @php
                  $petugasList = $jadwalHarianInfo['semua_jadwal'] ?? collect();
                @endphp
                @forelse($petugasList as $item)
                  @php
                    $shiftName = $item->shift;
                    $jamRange = match($shiftName) {
                      'pagi - siang' => '07:00 - 15:00',
                      'siang - malam' => '15:00 - 23:00',
                      'malam - pagi' => '23:00 - 07:00',
                      default => 'Fleksibel',
                    };
                    $isOverrideRecord = isset($item->tanggal);
                  @endphp
                  <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-3.5 px-5 sm:px-6">
                      <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200 capitalize">
                        {{ $item->shift }}
                      </span>
                    </td>
                    <td class="py-3.5 px-4 font-mono text-xs text-slate-700">{{ $jamRange }}</td>
                    <td class="py-3.5 px-4 font-semibold text-ink">
                      <div>{{ $item->user->nama ?? '-' }}</div>
                      <div class="text-xs text-slate-400 font-normal">{{ $item->user->email ?? '' }}</div>
                    </td>
                    <td class="py-3.5 px-4">
                      <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded text-slate-700">
                        {{ $item->user->rfid_uid ?? '-' }}
                      </span>
                    </td>
                    <td class="py-3.5 px-4">
                      @if(($item->status ?? 'aktif') === 'aktif')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          Aktif Bertugas
                        </span>
                      @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 capitalize">
                          {{ $item->status }}
                        </span>
                      @endif
                    </td>
                    <td class="py-3.5 px-4 text-xs text-slate-500">
                      {{ $item->catatan ?: ($isOverrideRecord ? 'Override Spesifik' : 'Sesuai Rotasi Default') }}
                    </td>
                    <td class="py-3.5 px-5 sm:px-6 text-right">
                      @if($isOverrideRecord)
                        <form action="{{ route('admin.shift.override.delete', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Kembalikan shift pegawai ini ke default shift?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition">
                            Reset ke Default
                          </button>
                        </form>
                      @else
                        <span class="text-xs px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500">Default</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="py-8 text-center text-rose-600 text-sm">
                      Tidak ada petugas yang dijadwalkan pada hari ini (Lapangan Tutup).
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>
      </div>
    @endif

    <!-- TAB 2: DEFAULT SHIFT MINGGUAN (ROTASI - KHUSUS SUPERADMIN) -->
    @if($user->isSuperAdmin() && request('tab') === 'default')
      <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
          <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
              <span>👑</span>
              <span>Konfigurasi Superadmin</span>
            </div>
            <h2 class="text-base sm:text-lg font-bold text-ink">Rotasi Shift Default Mingguan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
              Sistem default shift otomatis berjalan setiap minggu (Senin s/d Minggu). Hari tanpa shift staf akan otomatis ditutup.
            </p>
          </div>
          <button type="button" onclick="openModal('modalDefaultShift')" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shrink-0 self-start sm:self-auto shadow-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Default Shift</span>
          </button>
        </div>

        <!-- Filter Lapangan Tab Default -->
        <form method="GET" action="{{ route('admin.shift') }}" class="my-5 max-w-sm">
          <input type="hidden" name="tab" value="default">
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Lapangan Yang Dikonfigurasi:</label>
          <select name="lapangan_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" onchange="this.form.submit()">
            @foreach($lapangans as $lap)
              <option value="{{ $lap->id }}" {{ $selectedLapanganId == $lap->id ? 'selected' : '' }}>
                {{ $lap->nama }}
              </option>
            @endforeach
          </select>
        </form>

        <!-- 7-Day Matrix Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $hari)
            @php
              $shiftsHariIni = $defaultShiftsGrouped[$hari] ?? collect();
            @endphp
            <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50/50 flex flex-col">
              <div class="bg-ink text-white px-4 py-2.5 flex items-center justify-between">
                <span class="font-bold text-sm capitalize">{{ $hari }}</span>
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $shiftsHariIni->isEmpty() ? 'bg-rose-500/80 text-white' : 'bg-teal-500 text-white' }}">
                  {{ $shiftsHariIni->isEmpty() ? 'Tutup (0 Staf)' : $shiftsHariIni->count() . ' Shift Aktif' }}
                </span>
              </div>
              <div class="p-3.5 flex-1 space-y-2">
                @forelse($shiftsHariIni as $ds)
                  <div class="p-2.5 bg-white rounded-xl border border-slate-200/80 flex items-center justify-between shadow-2xs">
                    <div>
                      <div class="font-semibold text-xs text-ink">{{ $ds->user->nama ?? 'Pegawai' }}</div>
                      <span class="inline-block text-[10px] uppercase font-bold text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded mt-0.5">
                        {{ $ds->shift }}
                      </span>
                    </div>
                    <form action="{{ route('admin.shift.default.delete', $ds->id) }}" method="POST" onsubmit="return confirm('Hapus shift default ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="w-6 h-6 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition" title="Hapus">
                        ×
                      </button>
                    </form>
                  </div>
                @empty
                  <div class="py-6 text-center text-xs text-slate-400 italic">
                    Tidak ada pegawai ditugaskan (Lapangan Tutup).
                  </div>
                @endforelse
              </div>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  </main>

  <!-- MODAL: UBAH SHIFT HARIAN (OVERRIDE) -->
  <div id="modalOverrideShift" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div>
          <h3 class="font-bold text-lg text-ink">Ubah / Tambah Shift Harian</h3>
          <p class="text-xs text-slate-500 mt-0.5">Menimpa (override) default rotasi pada tanggal terpilih.</p>
        </div>
        <button type="button" onclick="closeModal('modalOverrideShift')" class="p-1 rounded-lg text-slate-400 hover:text-ink hover:bg-slate-100 transition">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <form action="{{ route('admin.shift.override.store') }}" method="POST" class="mt-4 space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Lapangan</label>
          <select name="id_lapangan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" required>
            @foreach($lapangans as $lap)
              <option value="{{ $lap->id }}" {{ $selectedLapanganId == $lap->id ? 'selected' : '' }}>
                {{ $lap->nama }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tanggal</label>
          <input type="date" name="tanggal" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" value="{{ $selectedTanggal }}" required>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Pegawai (Admin)</label>
          <select name="id_user" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" required>
            <option value="">-- Pilih Pegawai --</option>
            @foreach($pegawaiList as $pegawai)
              <option value="{{ $pegawai->id }}">{{ $pegawai->nama }} ({{ $pegawai->email }})</option>
            @endforeach
          </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Shift</label>
            <select name="shift" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" required>
              <option value="pagi - siang">Pagi - Siang (07:00-15:00)</option>
              <option value="siang - malam">Siang - Malam (15:00-23:00)</option>
              <option value="malam - pagi">Malam - Pagi (23:00-07:00)</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Status</label>
            <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" required>
              <option value="aktif">Aktif Bertugas</option>
              <option value="tukar">Tukar Shift</option>
              <option value="izin">Izin</option>
              <option value="libur">Libur</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Catatan Perubahan (Opsional)</label>
          <input type="text" name="catatan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" placeholder="Contoh: Menggantikan shift rekan yang izin">
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2.5">
          <button type="button" onclick="closeModal('modalOverrideShift')" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
            Batal
          </button>
          <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
            Simpan Shift Harian
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: TAMBAH DEFAULT SHIFT MINGGUAN (KHUSUS SUPERADMIN) -->
  @if($user->isSuperAdmin())
    <div id="modalDefaultShift" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
          <div>
            <h3 class="font-bold text-lg text-ink">Tambah Default Shift Mingguan</h3>
            <p class="text-xs text-slate-500 mt-0.5">Jadwal rotasi mingguan berulang secara otomatis.</p>
          </div>
          <button type="button" onclick="closeModal('modalDefaultShift')" class="p-1 rounded-lg text-slate-400 hover:text-ink hover:bg-slate-100 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form action="{{ route('admin.shift.default.store') }}" method="POST" class="mt-4 space-y-4">
          @csrf
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Lapangan</label>
            <select name="id_lapangan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" required>
              @foreach($lapangans as $lap)
                <option value="{{ $lap->id }}" {{ $selectedLapanganId == $lap->id ? 'selected' : '' }}>
                  {{ $lap->nama }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Hari</label>
            <select name="hari" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white capitalize" required>
              @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $h)
                <option value="{{ $h }}" class="capitalize">{{ $h }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pegawai</label>
            <select name="id_user" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" required>
              <option value="">-- Pilih Pegawai --</option>
              @foreach($pegawaiList as $pegawai)
                <option value="{{ $pegawai->id }}">{{ $pegawai->nama }} ({{ $pegawai->email }})</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Shift</label>
            <select name="shift" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white" required>
              <option value="pagi - siang">Pagi - Siang (07:00 - 15:00)</option>
              <option value="siang - malam">Siang - Malam (15:00 - 23:00)</option>
              <option value="malam - pagi">Malam - Pagi (23:00 - 07:00)</option>
            </select>
          </div>

          <div class="pt-4 border-t border-slate-100 flex justify-end gap-2.5">
            <button type="button" onclick="closeModal('modalDefaultShift')" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
              Batal
            </button>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
              Simpan Default Shift
            </button>
          </div>
        </form>
      </div>
    </div>
  @endif
@endsection
