@extends('admin.layout.app')
@section('title', 'Daftar Warga')
@section('page-title', 'Daftar Warga')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Warga</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola data warga untuk proses pengelompokan</p>
    </div>
    <button type="button" onclick="openModal('createWargaModal')" class="w-full md:w-auto justify-center inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Warga
    </button>
</div>

{{-- Filter --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-4 mb-4 transition-colors duration-200">
    <form method="GET" class="flex flex-col md:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK..."
            class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors duration-200">
        <div class="relative">
            <select name="kelompok" class="appearance-none pl-4 pr-9 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition-colors duration-200">
                <option value="">Semua Kelompok</option>
                <option value="Rendah" {{ request('kelompok') == 'Rendah' ? 'selected' : '' }}>Ekonomi Rendah</option>
                <option value="Sedang" {{ request('kelompok') == 'Sedang' ? 'selected' : '' }}>Ekonomi Menengah</option>
                <option value="Tinggi" {{ request('kelompok') == 'Tinggi' ? 'selected' : '' }}>Ekonomi Mampu</option>
            </select>
            <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </span>
        </div>
        <button type="submit" class="px-4 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-medium transition">Cari</button>
        @if(request()->hasAny(['search', 'kelompok']))
        <a href="{{ route('admin.warga.index') }}" class="px-4 py-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm font-medium">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">NIK</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Pendidikan KK</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Kondisi Rumah</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Bansos</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Pendapatan</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Tanggungan</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Kelompok</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($wargas as $w)
                <tr class="hover:bg-emerald-50/50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-400">{{ $w->nik }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $w->nama_lengkap }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $w->pendidikan->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $w->kondisiRumah->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 truncate max-w-[150px]" title="{{ $w->bansos->nama ?? '-' }}">{{ $w->bansos->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($w->pendapatan, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $w->jumlah_tanggungan }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($w->latestClusteringResult)
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ match($w->latestClusteringResult->label) { 'Rendah' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400', 'Sedang' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Tinggi' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400', default => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300' } }}">{{ match($w->latestClusteringResult->label) { 'Rendah' => 'Ekonomi Rendah', 'Sedang' => 'Menengah', 'Tinggi' => 'Mampu', default => $w->latestClusteringResult->label } }}</span>
                        @else
                        <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button type="button" onclick="openModal('editWargaModal-{{ $w->id }}')" class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.warga.destroy', $w) }}" onsubmit="return confirm('Hapus data warga ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">Belum ada data warga.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($wargas->hasPages())
    <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-400">{{ $wargas->links() }}</div>
    @endif
</div>

{{-- Create Modal --}}
<div id="createWargaModal" class="hidden fixed inset-0 z-[60] overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeModal('createWargaModal')">
            <div class="absolute inset-0 bg-gray-500 dark:bg-slate-900 opacity-75 dark:opacity-80"></div>
        </div>
        <div class="relative z-10 w-full transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:max-w-3xl border border-gray-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
            <div class="flex-shrink-0 px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 z-20">
                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white">Tambah Warga</h3>
                <button type="button" onclick="closeModal('createWargaModal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.warga.store') }}" class="flex flex-col overflow-hidden flex-1">
                @csrf
                <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                    @include('admin.warga._form')
                </div>
                <div class="flex-shrink-0 px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-3 bg-white dark:bg-slate-800 z-20">
                    <button type="button" onclick="closeModal('createWargaModal')" class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition">Batal</button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modals --}}
@foreach($wargas as $w)
<div id="editWargaModal-{{ $w->id }}" class="hidden fixed inset-0 z-[60] overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeModal('editWargaModal-{{ $w->id }}')">
            <div class="absolute inset-0 bg-gray-500 dark:bg-slate-900 opacity-75 dark:opacity-80"></div>
        </div>
        <div class="relative z-10 w-full transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:max-w-3xl border border-gray-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
            <div class="flex-shrink-0 px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 z-20">
                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white">Edit Warga</h3>
                <button type="button" onclick="closeModal('editWargaModal-{{ $w->id }}')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.warga.update', $w) }}" class="flex flex-col overflow-hidden flex-1">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $w->id }}">
                <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                    @include('admin.warga._form', ['warga' => $w])
                </div>
                <div class="flex-shrink-0 px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-3 bg-white dark:bg-slate-800 z-20">
                    <button type="button" onclick="closeModal('editWargaModal-{{ $w->id }}')" class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition">Batal</button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->any())
            @if(old('_method') == 'PUT' && old('id'))
                openModal('editWargaModal-{{ old("id") }}');
            @else
                openModal('createWargaModal');
            @endif
        @endif
    });
</script>
@endpush
@endsection
