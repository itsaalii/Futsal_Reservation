@extends('layouts.admin')

@section('title', 'Form Membership | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Form Membership',
    'active' => 'membership',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="max-w-2xl mx-auto space-y-5">
      <a href="{{ url('/admin/membership') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-ink transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke List Membership</span>
      </a>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div class="pb-5 border-b border-slate-100 mb-6">
          <h2 class="text-lg font-bold text-ink">Input Data Membership</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola paket langganan dan status keanggotaan.</p>
        </div>

        <form id="membershipFormAdmin" class="space-y-4">
          <input type="hidden" id="membershipId" />

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="membershipNamaTim">Nama Tim / PIC</label>
              <input class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="membershipNamaTim" type="text" required />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="membershipPaket">Paket</label>
              <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="membershipPaket">
                <option value="Basic">Basic (1 Bulan)</option>
                <option value="Pro Team">Pro Team (2 Bulan)</option>
                <option value="Elite League">Elite League (6 Bulan)</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="membershipMasaAktif">Masa Aktif</label>
              <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="membershipMasaAktif">
                <option>1 Bulan</option>
                <option>3 Bulan</option>
                <option>6 Bulan</option>
                <option>12 Bulan</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5" for="membershipStatus">Status</label>
              <select class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 bg-white" id="membershipStatus">
                <option>Aktif</option>
                <option>Menunggu Aktivasi</option>
                <option>Kadaluarsa</option>
              </select>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ url('/admin/membership') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
              Batal
            </a>
            <button class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-sm" type="submit">
              Simpan Membership
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
@endsection
