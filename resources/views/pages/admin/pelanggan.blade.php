@extends('layouts.admin')

@section('title', 'Kelola Daftar Pelanggan | Admin Jaya Futsal')

@section('content')
  @include('partials.admin.header', [
    'title' => 'Kelola Daftar Pelanggan',
    'active' => 'pelanggan',
  ])

  <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
    <section class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm overflow-hidden">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-ink">Daftar Pelanggan Terdaftar</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Kelola data akun penyewa, status member, dan kontak pelanggan.
          </p>
        </div>
        <a href="{{ route('admin.pelanggan.form') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white transition self-start sm:self-auto shadow-sm">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Tambah Pelanggan</span>
        </a>
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
              <th class="py-3 px-5 sm:px-6">No ID</th>
              <th class="py-3 px-4">Nama Pelanggan</th>
              <th class="py-3 px-4">Telepon / WhatsApp</th>
              <th class="py-3 px-4">Email</th>
              <th class="py-3 px-4">Status Membership</th>
              <th class="py-3 px-5 sm:px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($pelanggan ?? [] as $item)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-5 sm:px-6 font-mono text-xs font-semibold text-slate-500">
                  #PLG-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="py-3.5 px-4 font-bold text-ink">
                  {{ $item->nama }}
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ $item->no_hp ?: '-' }}
                </td>
                <td class="py-3.5 px-4 text-slate-600 font-mono text-xs">
                  {{ $item->email }}
                </td>
                <td class="py-3.5 px-4">
                  @if($item->status_member)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      Member Aktif
                    </span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                      Non Member
                    </span>
                  @endif
                </td>
                <td class="py-3.5 px-5 sm:px-6 text-right">
                  <div class="inline-flex items-center gap-1.5 justify-end">
                    <a href="{{ route('admin.pelanggan.edit', $item->id) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                      Edit
                    </a>
                    <form action="{{ route('admin.pelanggan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun pelanggan ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data pelanggan yang terdaftar.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($pelanggan->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-100">
          {{ $pelanggan->links() }}
        </div>
      @endif
    </section>
  </main>
@endsection
