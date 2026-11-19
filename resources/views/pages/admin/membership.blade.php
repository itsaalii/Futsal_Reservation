@extends('layouts.admin')

@section('title', 'Kelola Data Membership | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Kelola Data Membership',
    'active' => 'membership',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-ink">Pengajuan Membership & Validasi Member</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Setujui atau tolak pengajuan membership pelanggan dan periksa bukti pembayaran transfer.
          </p>
        </div>
      </div>

      @if(session('success'))
        <div class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
          <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">Nama Pelanggan</th>
              <th class="py-3 px-4">Tipe Paket</th>
              <th class="py-3 px-4">Bukti Pembayaran</th>
              <th class="py-3 px-4">Masa Berlaku</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-5 sm:px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($memberships as $m)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6">
                  <div class="font-bold text-ink">{{ $m->nama }}</div>
                  <div class="text-xs text-slate-400 mt-0.5">{{ $m->no_hp }}</div>
                </td>
                <td class="py-3.5 px-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                    {{ $m->membership_type }}
                  </span>
                </td>
                <td class="py-3.5 px-4">
                  @if($m->membership_proof === 'cash')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      Cash Kasir
                    </span>
                  @elseif($m->membership_proof)
                    <button type="button" 
                            onclick="viewMembershipProof('{{ asset('storage/' . $m->membership_proof) }}', '{{ $m->nama }}', 'Paket: {{ $m->membership_type }} | Update: {{ \Carbon\Carbon::parse($m->updated_at)->translatedFormat('d M Y H:i') }}')" 
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200/80 px-2.5 py-1 rounded-lg transition shadow-2xs">
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                      <span>Cek Bukti</span>
                    </button>
                  @else
                    <span class="text-slate-400 text-xs">-</span>
                  @endif
                </td>
                <td class="py-3.5 px-4 text-slate-600 text-xs">
                  {{ $m->membership_expires_at ? \Carbon\Carbon::parse($m->membership_expires_at)->translatedFormat('d M Y') : '-' }}
                </td>
                <td class="py-3.5 px-4">
                  @if($m->membership_status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                      Menunggu
                    </span>
                  @elseif($m->membership_status === 'active')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      Aktif
                    </span>
                  @elseif($m->membership_status === 'rejected')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                      Ditolak
                    </span>
                  @endif
                </td>
                <td class="py-3.5 px-5 sm:px-6 text-right">
                  @if($m->membership_status === 'pending')
                    <div class="inline-flex items-center gap-1.5 justify-end">
                      <form action="{{ route('admin.membership.terima', $m->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-2xs">
                          Setujui
                        </button>
                      </form>
                      <form action="{{ route('admin.membership.tolak', $m->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition" onclick="return confirm('Tolak permohonan membership ini?')">
                          Tolak
                        </button>
                      </form>
                    </div>
                  @else
                    <span class="text-slate-400 text-xs">-</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada pengajuan membership saat ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($memberships->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-100">
          {{ $memberships->links() }}
        </div>
      @endif
    </section>
  </main>

  <!-- Modern Modal Bukti Membership -->
  <div id="modalBuktiMembership" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-5 sm:p-6 shadow-2xl relative max-h-[90vh] flex flex-col">
      <div class="flex items-start justify-between pb-4 border-b border-slate-100 gap-3">
        <div>
          <h3 id="membershipModalTitle" class="font-bold text-base sm:text-lg text-ink">Bukti Pembayaran Membership</h3>
          <p id="membershipModalSubtitle" class="text-xs text-slate-500 mt-0.5">Detail bukti pembayaran pendaftaran membership</p>
        </div>
        <button type="button" onclick="closeModal('modalBuktiMembership')" class="p-1 rounded-lg text-slate-400 hover:text-ink hover:bg-slate-100 transition shrink-0">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="py-3 flex-1 overflow-y-auto text-center flex items-center justify-center min-h-[320px] bg-slate-50/70 rounded-xl my-3 border border-slate-100 p-2 relative">
        <!-- Spinner -->
        <div id="membershipLoadingSpinner" class="flex flex-col items-center justify-center gap-2 text-slate-400 py-8">
          <svg class="animate-spin w-8 h-8 text-teal-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
          </svg>
          <span class="text-xs font-medium">Memuat bukti membership...</span>
        </div>

        <img id="buktiMembershipImg" src="" alt="Bukti Membership" class="max-h-[65vh] w-auto max-w-full rounded-xl object-contain mx-auto shadow-sm hidden transition-all duration-200" />
        <iframe id="buktiMembershipFrame" class="w-full h-[65vh] rounded-xl border border-slate-200 hidden" title="Bukti PDF"></iframe>

        <div id="buktiMembershipFallback" class="text-sm text-slate-500 hidden p-6 text-center">
          <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <span class="block">Dokumen bukti tidak dapat dipratinjau langsung.</span>
          <a id="buktiMembershipLink" href="#" target="_blank" class="text-teal-600 underline font-semibold inline-block mt-2 hover:text-teal-700">
            Buka / Unduh Bukti di Tab Baru
          </a>
        </div>
      </div>

      <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
        <a id="membershipDownloadBtn" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200/80 transition shadow-2xs">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
          </svg>
          <span>Buka Ukuran Penuh</span>
        </a>
        <button type="button" onclick="closeModal('modalBuktiMembership')" class="px-5 py-2 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-800 transition">
          Tutup
        </button>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      function viewMembershipProof(fileUrl, title = '', subtitle = '') {
        const img = document.getElementById('buktiMembershipImg');
        const frame = document.getElementById('buktiMembershipFrame');
        const fallback = document.getElementById('buktiMembershipFallback');
        const link = document.getElementById('buktiMembershipLink');
        const spinner = document.getElementById('membershipLoadingSpinner');
        const downloadBtn = document.getElementById('membershipDownloadBtn');
        const titleEl = document.getElementById('membershipModalTitle');
        const subtitleEl = document.getElementById('membershipModalSubtitle');

        if (titleEl) {
          titleEl.textContent = title ? 'Bukti Membership: ' + title : 'Bukti Pembayaran Membership';
        }
        if (subtitleEl) {
          subtitleEl.textContent = subtitle || 'Detail bukti pembayaran pendaftaran membership';
        }

        img.classList.add('hidden');
        frame.classList.add('hidden');
        fallback.classList.add('hidden');
        if (spinner) spinner.classList.remove('hidden');

        if (!fileUrl) {
          if (spinner) spinner.classList.add('hidden');
          fallback.classList.remove('hidden');
          link.setAttribute('href', '#');
          if (downloadBtn) downloadBtn.setAttribute('href', '#');
          openModal('modalBuktiMembership');
          return;
        }

        link.setAttribute('href', fileUrl);
        if (downloadBtn) downloadBtn.setAttribute('href', fileUrl);

        const isPdf = fileUrl.toLowerCase().split('?')[0].endsWith('.pdf');

        if (isPdf) {
          if (spinner) spinner.classList.add('hidden');
          frame.src = fileUrl;
          frame.classList.remove('hidden');
        } else {
          img.onload = () => {
            if (spinner) spinner.classList.add('hidden');
            img.classList.remove('hidden');
          };
          img.onerror = () => {
            if (spinner) spinner.classList.add('hidden');
            img.classList.add('hidden');
            fallback.classList.remove('hidden');
          };
          img.src = fileUrl;
          if (img.complete && img.naturalWidth > 0) {
            if (spinner) spinner.classList.add('hidden');
            img.classList.remove('hidden');
          }
        }

        openModal('modalBuktiMembership');
      }
    </script>
  @endpush
@endsection
