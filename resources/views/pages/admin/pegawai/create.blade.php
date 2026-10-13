@extends('layouts.admin')

@section('title', 'Tambah Akun Pegawai | Superadmin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Tambah Akun Pegawai Baru',
    'active' => 'pegawai',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="max-w-3xl mx-auto space-y-5">
      <a href="{{ route('admin.pegawai') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-[#5B6478] hover:text-[#12162B] transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Daftar Pegawai</span>
      </a>

      <div class="glass-card p-6 sm:p-8">
        <div class="pb-5 border-b border-white/60 mb-6">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-primary-100 text-[#2F6BFF] border border-primary-200 mb-2">
            <svg class="w-3.5 h-3.5 text-[#2F6BFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Superadmin Feature</span>
          </div>
          <h2 class="text-lg sm:text-xl font-extrabold text-[#12162B]">Form Tambah Akun Pegawai (Admin)</h2>
          <p class="text-xs sm:text-sm text-[#5B6478] mt-0.5">
            Daftarkan akun staf baru untuk mengoperasikan kasir, melayani booking offline (walk-in), dan absensi RFID.
          </p>
        </div>

        @if ($errors->any())
          <div class="mb-6 bg-rose-500/10 border border-rose-500/30 text-rose-800 rounded-2xl p-4 text-xs sm:text-sm">
            <div class="font-bold mb-1">Perhatikan kesalahan berikut:</div>
            <ul class="list-disc pl-4 space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('admin.pegawai.store') }}" method="POST" class="space-y-6">
          @csrf

          <!-- Bagian 1: Data Identitas & Kredensial -->
          <div>
            <h3 class="text-xs font-extrabold text-[#2F6BFF] uppercase tracking-wider mb-3">1. Informasi Akun & Kredensial</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  Nama Lengkap Pegawai <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Budi Santoso" class="w-full bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
              </div>

              <div>
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  Email Login <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="pegawai@jayafutsal.com" class="w-full bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
              </div>

              <div>
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  Nomor HP / WhatsApp <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="081234567890" class="w-full bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
              </div>

              <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  Password Akun <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
                <p class="text-[11px] text-[#5B6478] mt-1">Digunakan untuk login ke panel admin pegawai.</p>
              </div>
            </div>
          </div>

          <!-- Bagian 2: Penugasan Lapangan & Kartu RFID -->
          <div class="pt-4 border-t border-white/60">
            <h3 class="text-xs font-extrabold text-[#2F6BFF] uppercase tracking-wider mb-3">2. Penugasan Lapangan & Absensi RFID</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  Penugasan Lapangan Futsal
                </label>
                <select name="id_lapangan" class="w-full bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition">
                  <option value="">-- Pilih Lapangan (Opsional) --</option>
                  @foreach($lapangans as $lap)
                    <option value="{{ $lap->id }}" {{ old('id_lapangan') == $lap->id ? 'selected' : '' }}>
                      {{ $lap->nama }} ({{ $lap->tipe_lantai }})
                    </option>
                  @endforeach
                </select>
                <p class="text-[11px] text-[#5B6478] mt-1">Pegawai hanya dapat mengelola reservasi offline pada lapangan tugasnya.</p>
              </div>

              <div>
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  UID Kartu RFID
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#8A93A6]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 002.83 6.13" />
                    </svg>
                  </div>
                  <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}" placeholder="Contoh: A1B2C3D4" class="w-full bg-white/70 backdrop-blur-md rounded-xl pl-9 pr-4 py-2.5 text-xs sm:text-sm font-mono text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
                </div>
                <p class="text-[11px] text-[#5B6478] mt-1">Nomor UID unik kartu RFID untuk tap di Kiosk Absensi.</p>
              </div>
            </div>
          </div>

          <!-- Bagian 3: Konfigurasi Gaji Pegawai -->
          <div class="pt-4 border-t border-white/60">
            <h3 class="text-xs font-extrabold text-[#2F6BFF] uppercase tracking-wider mb-3">3. Konfigurasi Standar Penggajian</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  Gaji Pokok (Rp)
                </label>
                <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', 2500000) }}" min="0" step="10000" class="w-full bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
              </div>

              <div>
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  Tunjangan Tetap (Rp)
                </label>
                <input type="number" name="tunjangan" value="{{ old('tunjangan', 300000) }}" min="0" step="10000" class="w-full bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
              </div>

              <div>
                <label class="block text-xs font-bold text-[#12162B] mb-1.5">
                  Tarif per Shift (Rp)
                </label>
                <input type="number" name="tarif_per_shift" value="{{ old('tarif_per_shift', 50000) }}" min="0" step="5000" class="w-full bg-white/70 backdrop-blur-md rounded-xl px-4 py-2.5 text-xs sm:text-sm text-[#12162B] border border-white/80 focus:outline-none focus:ring-2 focus:ring-[#2F6BFF]/30 focus:bg-white transition" />
              </div>
            </div>
            <p class="text-[11px] text-[#5B6478] mt-1.5">Nilai ini menjadi dasar kalkulasi rekapitulasi penggajian bulanan otomatis.</p>
          </div>

          <!-- Buttons -->
          <div class="pt-5 border-t border-white/60 flex items-center justify-end gap-3">
            <a href="{{ route('admin.pegawai') }}" class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold bg-white/70 hover:bg-white text-[#5B6478] border border-white/80 transition">
              Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-full text-xs sm:text-sm font-extrabold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white shadow-md shadow-blue-500/25 transition transform hover:-translate-y-0.5">
              Simpan Akun Pegawai
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
@endsection
