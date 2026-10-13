@extends('layouts.admin')

@section('title', 'Kelola Reservasi | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Kelola Reservasi Lapangan',
    'active' => 'reservasi',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <!-- Stat Counters -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Menunggu Konfirmasi</span>
        <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-sky-600" id="rvMenungguBayar">
          {{ $countMenungguBayar ?? 0 }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Bukti transfer sudah diunggah</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Menunggu Bayar</span>
        <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-amber-600" id="rvMenungguPembayaran">
          {{ $countMenungguPembayaran ?? 0 }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Menunggu user melunasi tagihan</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Reservasi Disetujui</span>
        <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-emerald-600" id="rvDisetujui">
          {{ $countDisetujui ?? 0 }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Siap bertanding di lapangan</p>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Selesai Bertanding</span>
        <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-teal-700" id="rvSelesai">
          {{ $countSelesai ?? 0 }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Total sesi terselesaikan</p>
      </div>
    </div>

    <!-- Alert Notifications -->
    @if (session('success'))
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    @endif
    @if (session('error'))
      <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-center gap-3 text-sm">
        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    <!-- Role Filter & Context -->
    @if($user->isAdmin())
      <div class="bg-teal-50 border border-teal-200/80 rounded-2xl p-4 flex items-center justify-between gap-4 text-sm text-teal-800">
        <div class="flex items-center gap-3">
          <div class="w-2.5 h-2.5 rounded-full bg-teal-600"></div>
          <span>Akses Staf Lapangan: Menampilkan reservasi untuk <strong>{{ $assignedLapangan ? $assignedLapangan->nama : 'Lapangan Tugas' }}</strong>.</span>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-600 text-white shadow-2xs">Petugas Aktif</span>
      </div>
    @elseif($user->isSuperAdmin())
      <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.reservasi') }}" class="flex flex-wrap items-center gap-3">
          <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Filter Lapangan:</label>
          <select name="lapangan_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600/20 bg-white">
            <option value="">Semua Lapangan</option>
            @foreach(($lapangans ?? []) as $lap)
              <option value="{{ $lap->id }}" {{ ($lapanganFilter ?? '') == $lap->id ? 'selected' : '' }}>
                {{ $lap->nama }}
              </option>
            @endforeach
          </select>
          @if(!empty($lapanganFilter))
            <a href="{{ route('admin.reservasi') }}" class="px-3 py-1.5 rounded-xl text-xs font-medium text-slate-600 hover:text-ink hover:bg-slate-100 transition">
              Reset Filter
            </a>
          @endif
        </form>
      </div>
    @endif

    <!-- Section: Validasi Pembayaran & Kelola Sesi -->
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-base sm:text-lg font-bold text-ink">Validasi Pembayaran & Status Booking</h2>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-teal-50 text-teal-700 font-semibold border border-teal-200/60">Aktif</span>
          </div>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Konfirmasi pembayaran, mulai sesi, atau batalkan reservasi.</p>
        </div>
        @if(!$user->isSuperAdmin())
          <a href="{{ route('admin.reservasi.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-[#2F6BFF] hover:bg-[#3A4BD1] text-white transition self-start sm:self-auto shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Reservasi Kasir</span>
          </a>
        @endif
      </div>

      <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">No Transaksi</th>
              <th class="py-3 px-4">PIC / Tim</th>
              <th class="py-3 px-4">Tanggal & Lapangan</th>
              <th class="py-3 px-4">Jam Sesi</th>
              <th class="py-3 px-4">Total / Bukti</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-5 sm:px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($validasi as $r)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-mono text-xs font-bold text-teal-700">
                  #RSV-{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="py-3.5 px-4">
                  <div class="font-semibold text-ink">{{ $r->user->nama ?? 'Unknown' }}</div>
                  @if($r->catatan)
                    <div class="text-xs text-slate-400 mt-0.5 truncate max-w-[160px]">{{ $r->catatan }}</div>
                  @endif
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  <div>{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}</div>
                  <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                    {{ $r->lapangan ? $r->lapangan->nama : 'Lapangan Utama' }}
                  </span>
                </td>
                <td class="py-3.5 px-4 font-mono text-xs text-slate-700">
                  {{ \Carbon\Carbon::parse($r->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($r->jam_selesai)->format('H:i') }}
                </td>
                <td class="py-3.5 px-4">
                  <div class="font-bold text-ink">Rp{{ number_format($r->total_harga, 0, ',', '.') }}</div>
                  <div class="mt-1">
                    @if($r->bukti_pembayaran)
                      <button type="button" 
                              onclick="viewProof('{{ asset('storage/' . $r->bukti_pembayaran) }}')" 
                              class="inline-flex items-center gap-1 text-[11px] font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200/80 px-2 py-0.5 rounded-lg transition">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Cek Bukti</span>
                      </button>
                    @elseif($r->metode_pembayaran === 'Cash')
                      <span class="inline-block text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                        Cash Kasir
                      </span>
                    @else
                      <span class="inline-block text-[11px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-lg">
                        Belum Upload
                      </span>
                    @endif
                  </div>
                </td>
                <td class="py-3.5 px-4">
                  @if($r->status === 'menunggu')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                      Menunggu Pembayaran
                    </span>
                    @php
                      $batasWaktu = \Carbon\Carbon::parse($r->created_at)->addHour();
                      $sisaWaktu = (int) now()->diffInMinutes($batasWaktu, false);
                    @endphp
                    <div class="mt-1 text-[11px]">
                      @if($sisaWaktu > 0)
                        <span class="text-slate-500">Sisa {{ $sisaWaktu }} menit</span>
                      @else
                        <span class="text-rose-600 font-bold">Kadaluarsa</span>
                      @endif
                    </div>
                  @elseif($r->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                      Menunggu Konfirmasi
                    </span>
                  @elseif(in_array($r->status, ['disetujui', 'dibayar']))
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      Disetujui
                    </span>
                  @endif
                </td>
                <td class="py-3.5 px-5 sm:px-6 text-right">
                  <div class="inline-flex items-center gap-1.5 justify-end">
                    @if($r->status === 'pending')
                      <form action="{{ route('admin.reservasi.terima', $r->id) }}" method="POST">
                        @csrf
                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-2xs" type="submit">
                          Terima
                        </button>
                      </form>
                      <form action="{{ route('admin.reservasi.tolak', $r->id) }}" method="POST">
                        @csrf
                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition" type="submit" onclick="return confirm('Tolak & Batalkan reservasi ini?')">
                          Tolak
                        </button>
                      </form>
                    @elseif(in_array($r->status, ['disetujui', 'dibayar']))
                      <form action="{{ route('admin.reservasi.selesai', $r->id) }}" method="POST">
                        @csrf
                        <button class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-teal-600 hover:bg-teal-700 text-white transition shadow-2xs" type="submit" onclick="return confirm('Tandai sesi ini telah selesai bertanding?')">
                          Selesai Main
                        </button>
                      </form>
                    @elseif($r->status === 'menunggu')
                      <form action="{{ route('admin.reservasi.tolak', $r->id) }}" method="POST">
                        @csrf
                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-rose-600 hover:bg-rose-50 border border-rose-200 transition" type="submit" onclick="return confirm('Batalkan reservasi ini?')">
                          Batalkan
                        </button>
                      </form>
                    @else
                      <span class="text-slate-400">-</span>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada reservasi yang memerlukan tindakan saat ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($validasi->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-100">
          {{ $validasi->links() }}
        </div>
      @endif
    </section>

    <!-- Section: Histori Riwayat Selesai & Batal -->
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
      <div class="flex items-center justify-between gap-3 pb-5 border-b border-slate-100">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-ink">Histori Riwayat Selesai & Dibatalkan</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Catatan riwayat pertandingan yang sudah rampung.</p>
        </div>
        <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium">Arsip</span>
      </div>

      <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">No Transaksi</th>
              <th class="py-3 px-4">Tanggal Main</th>
              <th class="py-3 px-4">PIC / Tim</th>
              <th class="py-3 px-4">Lapangan</th>
              <th class="py-3 px-4">Total Bayar</th>
              <th class="py-3 px-5 sm:px-6">Status Akhir</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($riwayat as $rev)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-mono text-xs text-slate-500 font-medium">
                  #RSV-{{ str_pad($rev->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ \Carbon\Carbon::parse($rev->tanggal)->translatedFormat('d M Y') }}
                </td>
                <td class="py-3.5 px-4 font-semibold text-ink">
                  {{ $rev->user->nama ?? '-' }}
                </td>
                <td class="py-3.5 px-4">
                  <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                    {{ $rev->lapangan ? $rev->lapangan->nama : 'Lapangan Utama' }}
                  </span>
                </td>
                <td class="py-3.5 px-4 font-bold text-ink">
                  Rp{{ number_format($rev->total_harga, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-5 sm:px-6">
                  @if($rev->status === 'selesai')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">
                      Selesai
                    </span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                      Batal
                    </span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data riwayat selesai/batal.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($riwayat->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-100">
          {{ $riwayat->links() }}
        </div>
      @endif
    </section>
  </main>

  <!-- Modern Modal Bukti Pembayaran -->
  <div id="modalBuktiPembayaran" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative max-h-[90vh] flex flex-col">
      <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <h3 class="font-bold text-lg text-ink">Bukti Pembayaran</h3>
        <button type="button" onclick="closeModal('modalBuktiPembayaran')" class="p-1 rounded-lg text-slate-400 hover:text-ink hover:bg-slate-100 transition">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="py-4 flex-1 overflow-y-auto text-center flex items-center justify-center min-h-[300px]">
        <img id="buktiPembayaranImg" src="" alt="Bukti Pembayaran" class="max-h-[65vh] rounded-xl object-contain mx-auto shadow-sm hidden" />
        <iframe id="buktiPembayaranFrame" class="w-full h-[65vh] rounded-xl border border-slate-200 hidden" title="Bukti PDF"></iframe>
        <div id="buktiPembayaranFallback" class="text-sm text-slate-500 hidden">
          File dokumen tidak dapat ditampilkan langsung.
          <a id="buktiPembayaranLink" href="#" target="_blank" class="text-teal-600 underline font-semibold block mt-2">Buka / Unduh Bukti di Tab Baru</a>
        </div>
      </div>

      <div class="pt-4 border-t border-slate-100 flex justify-end">
        <button type="button" onclick="closeModal('modalBuktiPembayaran')" class="px-5 py-2 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-800 transition">
          Tutup
        </button>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      function viewProof(fileUrl) {
        const img = document.getElementById('buktiPembayaranImg');
        const frame = document.getElementById('buktiPembayaranFrame');
        const fallback = document.getElementById('buktiPembayaranFallback');
        const link = document.getElementById('buktiPembayaranLink');

        img.classList.add('hidden');
        frame.classList.add('hidden');
        fallback.classList.add('hidden');

        if (!fileUrl) {
          fallback.classList.remove('hidden');
          link.setAttribute('href', '#');
          openModal('modalBuktiPembayaran');
          return;
        }

        link.setAttribute('href', fileUrl);
        const isPdf = fileUrl.toLowerCase().endsWith('.pdf');

        if (isPdf) {
          frame.src = fileUrl;
          frame.classList.remove('hidden');
        } else {
          img.onerror = () => {
            img.classList.add('hidden');
            fallback.classList.remove('hidden');
          };
          img.src = fileUrl;
          img.classList.remove('hidden');
        }

        openModal('modalBuktiPembayaran');
      }
    </script>
  @endpush
@endsection
