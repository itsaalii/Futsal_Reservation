@extends('layouts.admin')

@section('title', 'Form Pelanggan | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Form Pelanggan',
    'active' => 'pelanggan',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="max-w-2xl mx-auto space-y-5">
      <a href="{{ route('admin.pelanggan') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-ink transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Daftar Pelanggan</span>
      </a>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div class="pb-5 border-b border-slate-100 mb-6">
          <h2 class="text-lg font-bold text-ink">{{ ($isEdit ?? false) ? 'Edit Data Pelanggan' : 'Tambah Pelanggan Baru' }}</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola identitas akun dan status membership pelanggan.</p>
        </div>

        @if($errors->any())
          <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-sm">
            <ul class="list-disc pl-4 space-y-1">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ ($isEdit ?? false) ? route('admin.pelanggan.update', $pelanggan->id) : route('admin.pelanggan.store') }}" class="space-y-4">
          @csrf
          @if($isEdit ?? false)
            @method('PUT')
          @endif

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="pelangganNama">Nama Pelanggan <span class="text-rose-500">*</span></label>
              <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="pelangganNama" name="nama" type="text" value="{{ old('nama', $pelanggan->nama ?? '') }}" required />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="pelangganTelepon">Nomor Telepon / WA <span class="text-rose-500">*</span></label>
              <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="pelangganTelepon" name="no_hp" type="text" value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}" required />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="pelangganEmail">Alamat Email <span class="text-rose-500">*</span></label>
            <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="pelangganEmail" name="email" type="email" value="{{ old('email', $pelanggan->email ?? '') }}" required />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="pelangganStatusMember">Status Membership</label>
            <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="pelangganStatusMember" name="status_member">
              <option value="1" {{ old('status_member', $pelanggan->status_member ?? 0) == 1 ? 'selected' : '' }}>Member Aktif</option>
              <option value="0" {{ old('status_member', $pelanggan->status_member ?? 0) == 0 ? 'selected' : '' }}>Non Member</option>
            </select>
          </div>

          <!-- Dynamic Membership Box -->
          <div id="membershipFields" class="hidden">
            <div class="bg-teal-50/60 rounded-xl border border-teal-200/80 p-4 space-y-3">
              <div class="flex items-center gap-2 text-xs font-bold text-teal-800 uppercase tracking-wider">
                <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span>Detail Paket Membership</span>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1" for="membershipType">Tipe Paket</label>
                  <select class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm bg-white" id="membershipType" name="membership_type">
                    <option value="Basic" {{ old('membership_type', $pelanggan->membership_type ?? '') === 'Basic' ? 'selected' : '' }}>Basic (1 Bulan)</option>
                    <option value="Pro Team" {{ old('membership_type', $pelanggan->membership_type ?? '') === 'Pro Team' ? 'selected' : '' }}>Pro Team (2 Bulan)</option>
                    <option value="Elite League" {{ old('membership_type', $pelanggan->membership_type ?? '') === 'Elite League' ? 'selected' : '' }}>Elite League (6 Bulan)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-600 mb-1">Metode Pembayaran</label>
                  <input class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-100 text-sm text-slate-500" type="text" value="Cash Kasir (Diterima Langsung)" disabled />
                </div>
              </div>
              @if(($isEdit ?? false) && ($pelanggan->membership_expires_at ?? null))
                <p class="text-xs text-teal-800">
                  Membership aktif sampai: <strong>{{ \Carbon\Carbon::parse($pelanggan->membership_expires_at)->translatedFormat('d F Y') }}</strong>
                </p>
              @endif
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="pelangganPassword">Kata Sandi (Password)</label>
            <div class="relative">
              <input class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="pelangganPassword" name="password" type="password" {{ ($isEdit ?? false) ? '' : 'required' }} />
              <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-ink transition" onclick="togglePasswordVisibility()">
                <svg id="eyeIcon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">{{ ($isEdit ?? false) ? 'Kosongkan jika tidak ingin merubah password.' : 'Minimal 6 karakter.' }}</p>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.pelanggan') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
              Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm">
              Simpan Data Pelanggan
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  @push('scripts')
    <script>
      function togglePasswordVisibility() {
        const input = document.getElementById('pelangganPassword');
        input.type = input.type === 'password' ? 'text' : 'password';
      }

      document.addEventListener('DOMContentLoaded', () => {
        const statusSelect = document.getElementById('pelangganStatusMember');
        const membershipFields = document.getElementById('membershipFields');

        function toggleMembershipFields() {
          if (statusSelect.value === '1') {
            membershipFields.classList.remove('hidden');
          } else {
            membershipFields.classList.add('hidden');
          }
        }

        statusSelect.addEventListener('change', toggleMembershipFields);
        toggleMembershipFields();
      });
    </script>
  @endpush
@endsection
