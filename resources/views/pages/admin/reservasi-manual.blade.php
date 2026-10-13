@extends('layouts.admin')

@section('title', 'Tambah Reservasi Kasir | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Reservasi Manual (Kasir)',
    'active' => 'reservasi',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="max-w-3xl mx-auto space-y-5">
      <!-- Back Button -->
      <a href="{{ route('admin.reservasi') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-ink transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Kelola Reservasi</span>
      </a>

      @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
          <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      <!-- Main Form Card -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div class="pb-5 border-b border-slate-100 mb-6">
          <h2 class="text-lg font-bold text-ink">Formulir Booking Kasir / Walk-in</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Reservasi yang dibuat langsung di kasir oleh petugas dengan metode pembayaran Cash (Tunai).
          </p>
        </div>

        <form action="{{ route('admin.reservasi.store') }}" method="POST" id="formReservasiManual" class="space-y-5">
          @csrf

          <!-- Pelanggan -->
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="id_user">
              Pilih Pelanggan / Pemesan <span class="text-rose-500">*</span>
            </label>
            <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="id_user" name="id_user" required>
              <option value="">-- Pilih Pelanggan --</option>
              @foreach($pelanggan as $p)
                <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->no_hp ?? '-' }})</option>
              @endforeach
            </select>
          </div>

          <!-- Lapangan -->
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="id_lapangan">
              Pilih Lapangan <span class="text-rose-500">*</span>
            </label>
            <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="id_lapangan" name="id_lapangan" required>
              @foreach($lapangans as $lap)
                <option value="{{ $lap->id }}" data-tarif-siang="{{ $lap->tarif_siang }}" data-tarif-malam="{{ $lap->tarif_malam }}">
                  {{ $lap->nama }} - Lantai {{ $lap->tipe_lantai }} (Siang: Rp{{ number_format($lap->tarif_siang, 0, ',', '.') }}, Malam: Rp{{ number_format($lap->tarif_malam, 0, ',', '.') }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Tanggal & Jam Mulai -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="tanggalMain">
                Tanggal Main <span class="text-rose-500">*</span>
              </label>
              <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="tanggalMain" name="tanggal" type="date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" />
              <div class="hidden text-xs text-rose-600 mt-1.5 font-medium" id="tanggalError"></div>
              <div class="hidden text-xs text-teal-700 mt-1.5 font-medium" id="tanggalSuccess">Jadwal tersedia! Silakan tentukan jam mulai.</div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="jamMain">
                Jam Mulai <span class="text-rose-500">*</span>
              </label>
              <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white disabled:bg-slate-100 disabled:text-slate-400" id="jamMain" name="jam_mulai" required disabled>
                <option value="">Pilih tanggal / lapangan terlebih dahulu...</option>
              </select>
            </div>
          </div>

          <!-- Jam Selesai -->
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="jamSelesaiMain">
              Jam Selesai (Durasi Main) <span class="text-rose-500">*</span>
            </label>
            <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white disabled:bg-slate-100 disabled:text-slate-400" id="jamSelesaiMain" name="jam_selesai" required disabled>
              <option value="">Pilih jam mulai terlebih dahulu...</option>
            </select>
          </div>

          <!-- Catatan -->
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="catatan">
              Catatan Tambahan
            </label>
            <textarea class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="catatan" name="catatan" rows="3" placeholder="Contoh: DP Rp 50.000 atau Pelunasan Cash di kasir"></textarea>
          </div>

          <!-- Notice Note -->
          <div class="bg-teal-50 border border-teal-200/80 rounded-xl p-4 text-xs text-teal-800 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-teal-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <strong>Catatan Kasir:</strong> Reservasi yang dibuat dari form ini akan otomatis berstatus <strong>Disetujui</strong> dengan metode pembayaran <strong>Cash</strong>.
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.reservasi') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
              Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm inline-flex items-center gap-2" id="btnSubmit">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span>Simpan Reservasi Manual</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
      .select2-container .select2-selection--single {
        height: 42px !important;
        border-radius: 0.75rem !important;
        border-color: #cbd5e1 !important;
        padding: 6px 12px !important;
      }
      .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
      }
    </style>
  @endpush

  @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#id_user').select2({
          placeholder: "-- Cari Pelanggan --",
          width: '100%'
        });
      });

      document.addEventListener("DOMContentLoaded", () => {
        const tanggalMainInput = document.getElementById('tanggalMain');
        const lapanganSelect = document.getElementById('id_lapangan');
        const jamSelesaiSelect = document.getElementById('jamSelesaiMain');
        const jamMainSelect = document.getElementById('jamMain');
        const errMessage = document.getElementById('tanggalError');
        const successMessage = document.getElementById('tanggalSuccess');

        async function handleScheduleCheck() {
          const tanggalPilih = tanggalMainInput?.value;
          const lapanganId = lapanganSelect?.value;

          jamMainSelect.innerHTML = '<option value="">Memuat...</option>';
          jamMainSelect.disabled = true;
          jamSelesaiSelect.innerHTML = '<option value="">Pilih jam mulai terlebih dahulu...</option>';
          jamSelesaiSelect.disabled = true;
          errMessage.classList.add('hidden');
          successMessage.classList.add('hidden');

          if (!tanggalPilih || !lapanganId) return;

          const today = new Date();
          today.setHours(0, 0, 0, 0);
          const pickedDate = new Date(tanggalPilih);

          if (pickedDate < today) {
            errMessage.textContent = 'Jangan pilih hari yang sudah berlalu.';
            errMessage.classList.remove('hidden');
            jamMainSelect.innerHTML = '<option value="">Tanggal sudah berlalu</option>';
            return;
          }

          try {
            const response = await fetch(`/reservasi/cek-jadwal?tanggal=${tanggalPilih}&id_lapangan=${lapanganId}`);
            const result = await response.json();
            
            if (result.status === 'tutup') {
              errMessage.textContent = result.message || 'Lapangan TUTUP pada tanggal ini (Tidak ada pegawai bertugas).';
              errMessage.classList.remove('hidden');
              jamMainSelect.innerHTML = '<option value="">Lapangan Tutup</option>';
              return;
            }

            const jadwalOperasional = result.data || [];
            jamMainSelect.innerHTML = '';
            
            if (jadwalOperasional.length > 0) {
              let adaTersedia = false;
              
              jadwalOperasional.forEach(j => {
                const opt = document.createElement('option');
                opt.value = j.jam; 
                const hargaLabel = j.harga ? ` - Rp${new Intl.NumberFormat('id-ID').format(j.harga)}` : '';

                if (j.status === 'Tersedia') {
                  opt.textContent = `${j.jam} (Tersedia${hargaLabel})`;
                  adaTersedia = true;
                } else {
                  opt.textContent = `${j.jam} - ${j.keterangan}`;
                  opt.disabled = true;
                }
                
                jamMainSelect.appendChild(opt);
              });
              
              jamMainSelect.dataset.operasional = JSON.stringify(jadwalOperasional);

              if (adaTersedia) {
                successMessage.classList.remove('hidden');
                jamMainSelect.disabled = false;
                
                for (let i = 0; i < jamMainSelect.options.length; i++) {
                  if (!jamMainSelect.options[i].disabled) {
                    jamMainSelect.selectedIndex = i;
                    break;
                  }
                }
                
                updateJamSelesai();
              } else {
                errMessage.textContent = 'Semua slot jadwal pada hari ini sudah terisi.';
                errMessage.classList.remove('hidden');
                jamMainSelect.innerHTML = '<option value="">Semua jadwal penuh</option>';
              }
            } else {
              errMessage.textContent = 'Jadwal tidak tersedia.';
              errMessage.classList.remove('hidden');
              jamMainSelect.innerHTML = '<option value="">Tidak ada jadwal</option>';
            }
          } catch (error) {
            errMessage.textContent = 'Gagal memuat jadwal operasional.';
            errMessage.classList.remove('hidden');
          }
        }

        function updateJamSelesai() {
          jamSelesaiSelect.innerHTML = '';
          const selectedJamMulai = jamMainSelect.value;
          if (!selectedJamMulai) {
            jamSelesaiSelect.disabled = true;
            jamSelesaiSelect.innerHTML = '<option value="">Pilih jam mulai terlebih dahulu...</option>';
            return;
          }

          const operasionalData = JSON.parse(jamMainSelect.dataset.operasional || '[]');
          const startHour = parseInt(selectedJamMulai.split(':')[0], 10);
          let canContinue = true;

          for (let i = 1; i <= 4; i++) {
            const currentHour = startHour + i;
            if (currentHour > 23) break;

            const checkHourStr = `${String(currentHour - 1).padStart(2, '0')}:00`;
            const slotData = operasionalData.find(j => j.jam === checkHourStr);
            if (i > 1 && slotData && slotData.status !== 'Tersedia') {
              canContinue = false;
            }
            if (!canContinue && i > 1) break;

            const optionVal = `${String(currentHour).padStart(2, '0')}:00`;
            const opt = document.createElement('option');
            opt.value = optionVal;
            opt.textContent = `${optionVal} (${i} Jam)`;
            jamSelesaiSelect.appendChild(opt);
          }

          jamSelesaiSelect.disabled = false;
        }

        tanggalMainInput?.addEventListener('change', handleScheduleCheck);
        lapanganSelect?.addEventListener('change', handleScheduleCheck);
        jamMainSelect?.addEventListener('change', updateJamSelesai);

        if (tanggalMainInput?.value) {
          handleScheduleCheck();
        }
      });
    </script>
  @endpush
@endsection
