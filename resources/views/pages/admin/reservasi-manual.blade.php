@extends('layouts.admin')

@section('title', 'Tambah Reservasi Manual | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Tambah Reservasi Manual',
    'active' => 'reservasi',
  ])

  <main class="py-4 py-lg-5">
    <div class="container" style="max-width: 800px;">
        <a href="{{ route('admin.reservasi') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="panel-box shadow-sm border p-4 rounded-3 bg-white">
            <h3 class="h5 mb-4 border-bottom pb-2">Form Reservasi Manual</h3>

            <form action="{{ route('admin.reservasi.store') }}" method="POST" id="formReservasiManual">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold" for="id_user">Pilih Pelanggan / Pemesan</label>
                    <select class="form-select" id="id_user" name="id_user" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggan as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->no_hp ?? '-' }})</option>
                        @endforeach
                    </select>
                    <small class="text-secondary">Pilih akun pengguna yang akan dihubungkan dengan reservasi ini.</small>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="tanggalMain">Tanggal Main</label>
                        <input class="form-control" id="tanggalMain" name="tanggal" type="date" required min="{{ date('Y-m-d') }}" />
                        <div class="invalid-feedback d-none" id="tanggalError"></div>
                        <div class="valid-feedback d-none" id="tanggalSuccess">Jadwal tersedia! Silakan pilih jam mulai.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="jamMain">Jam Mulai</label>
                        <select class="form-select" id="jamMain" name="jam_mulai" required disabled>
                            <option value="">Pilih tanggal terlebih dahulu...</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="jamSelesaiMain">Jam Selesai</label>
                    <select class="form-select" id="jamSelesaiMain" name="jam_selesai" required disabled>
                        <option value="">Pilih jam mulai terlebih dahulu...</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="catatan">Catatan Tambahan</label>
                    <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Contoh: DP 50%, atau langsung cash di tempat"></textarea>
                </div>

                <div class="alert alert-info py-2 small mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Catatan Sistem:</strong> Reservasi yang dibuat dari form ini akan otomatis berstatus <strong>Disetujui</strong> dengan metode pembayaran <strong>Cash</strong>.
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <i class="bi bi-check-circle me-1"></i> Simpan Reservasi
                    </button>
                </div>
            </form>
        </div>
    </div>
  </main>

  @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  @endpush

  @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#id_user').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Pilih Pelanggan --",
                width: '100%'
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const tanggalMainInput = document.getElementById('tanggalMain');
            const jamSelesaiSelect = document.getElementById('jamSelesaiMain');
            const jamMainSelect = document.getElementById('jamMain');
            const errMessage = document.getElementById('tanggalError');
            const successMessage = document.getElementById('tanggalSuccess');

            async function handleTanggalChange() {
                const tanggalPilih = tanggalMainInput?.value;

                jamMainSelect.innerHTML = '<option value="">Memuat...</option>';
                jamMainSelect.disabled = true;
                jamSelesaiSelect.innerHTML = '<option value="">Pilih jam mulai terlebih dahulu...</option>';
                jamSelesaiSelect.disabled = true;
                errMessage.classList.add('d-none');
                successMessage.classList.add('d-none');

                if (!tanggalPilih) return;

                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const pickedDate = new Date(tanggalPilih);

                if (pickedDate < today) {
                    errMessage.textContent = 'Jangan pilih hari yang sudah berlalu.';
                    errMessage.classList.remove('d-none');
                    errMessage.classList.add('d-block');
                    jamMainSelect.innerHTML = '<option value="">Tanggal sudah berlalu</option>';
                    return;
                }

                errMessage.textContent = 'Maaf, jadwal di tanggal ini tidak tersedia / penuh.';

                try {
                    const response = await fetch(`/reservasi/cek-jadwal?tanggal=${tanggalPilih}`);
                    const result = await response.json();
                    
                    const jadwalOperasional = result.data || [];

                    jamMainSelect.innerHTML = '';
                    
                    if(jadwalOperasional.length > 0) {
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
                            successMessage.classList.remove('d-none');
                            successMessage.classList.add('d-block');
                            jamMainSelect.disabled = false;
                            
                            for (let i = 0; i < jamMainSelect.options.length; i++) {
                                if (!jamMainSelect.options[i].disabled) {
                                    jamMainSelect.selectedIndex = i;
                                    break;
                                }
                            }
                            
                            updateJamSelesai();
                        } else {
                            errMessage.classList.remove('d-none');
                            errMessage.classList.add('d-block');
                            jamMainSelect.innerHTML = '<option value="">Semua jadwal penuh</option>';
                        }
                    } else {
                        errMessage.classList.remove('d-none');
                        errMessage.classList.add('d-block');
                        jamMainSelect.innerHTML = '<option value="">Tidak ada jadwal / penuh</option>';
                    }
                } catch(err) {
                    console.error(err);
                    jamMainSelect.innerHTML = '<option value="">Gagal memuat jadwal</option>';
                }
            }

            function updateJamSelesai() {
                const jamMainVal = jamMainSelect?.value;
                
                jamSelesaiSelect.innerHTML = '';
                jamSelesaiSelect.disabled = true;
                
                if (!jamMainVal) return;
                
                const jadwalOperasional = JSON.parse(jamMainSelect.dataset.operasional || '[]');
                const startIndex = jadwalOperasional.findIndex(j => j.jam === jamMainVal);
                if (startIndex === -1) return;
                
                jamSelesaiSelect.disabled = false;
                
                let maxDurasi = 5; 
                let curDurasi = 1;
                
                for (let i = startIndex + 1; i <= jadwalOperasional.length; i++) {
                    if (curDurasi > maxDurasi) break;
                    
                    let jamSelesaiStr = '';
                    if (i < jadwalOperasional.length) {
                        jamSelesaiStr = jadwalOperasional[i].jam;
                        
                        const opt = document.createElement('option');
                        opt.value = jamSelesaiStr;
                        opt.textContent = `${jamSelesaiStr} (${curDurasi} Jam)`;
                        jamSelesaiSelect.appendChild(opt);
                        
                        if (jadwalOperasional[i].status !== 'Tersedia') break;
                    } else {
                        let lastJam = parseInt(jadwalOperasional[i-1].jam.split(':')[0]);
                        jamSelesaiStr = (lastJam + 1).toString().padStart(2, '0') + ':00';
                        
                        const opt = document.createElement('option');
                        opt.value = jamSelesaiStr;
                        opt.textContent = `${jamSelesaiStr} (${curDurasi} Jam)`;
                        jamSelesaiSelect.appendChild(opt);
                    }
                    
                    curDurasi++;
                }
            }

            tanggalMainInput?.addEventListener('change', handleTanggalChange);
            jamMainSelect?.addEventListener('change', updateJamSelesai);
            
            if (tanggalMainInput?.value) {
                handleTanggalChange();
            }

            document.getElementById('formReservasiManual')?.addEventListener('submit', function() {
                const btn = document.getElementById('btnSubmit');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';
            });
        });
    </script>
  @endpush
@endsection
