@extends('layouts.admin')

@section('title', 'Kelola Jadwal Lapangan | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Kelola Jadwal Lapangan',
    'active' => 'jadwal',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-ink">Data Jadwal Lapangan</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Daftar slot operasional dan tarif per sesi lapangan.
          </p>
        </div>
        <a href="{{ url('/admin/jadwal/form') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition self-start sm:self-auto shadow-sm">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Tambah Jadwal</span>
        </a>
      </div>

      <div class="mt-4 -mx-5 sm:-mx-6 overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-600 font-semibold text-xs uppercase tracking-wider">
              <th class="py-3 px-5 sm:px-6">No</th>
              <th class="py-3 px-4">Tanggal</th>
              <th class="py-3 px-4">Jam Sesi</th>
              <th class="py-3 px-4">Lapangan</th>
              <th class="py-3 px-4">Harga / Jam</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-5 sm:px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse ($jadwals as $item)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-mono text-xs text-slate-500">{{ $loop->iteration }}</td>
                <td class="py-3.5 px-4 text-slate-600">{{ $item->tanggal }}</td>
                <td class="py-3.5 px-4 font-mono text-xs text-slate-700">{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                <td class="py-3.5 px-4 font-semibold text-ink">{{ $item->nama_lapangan }}</td>
                <td class="py-3.5 px-4 font-bold text-teal-700">Rp {{ number_format($item->harga_per_jam, 0, ',', '.') }}</td>
                <td class="py-3.5 px-4">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $item->status }}
                  </span>
                </td>
                <td class="py-3.5 px-5 sm:px-6 text-right">
                  <div class="inline-flex items-center gap-1.5 justify-end">
                    <a href="#" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">Edit</a>
                    <a href="#" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition">Hapus</a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-8 text-center text-slate-400">Belum ada jadwal yang tersimpan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
@endsection
