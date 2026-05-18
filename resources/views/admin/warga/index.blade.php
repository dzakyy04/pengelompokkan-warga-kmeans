@extends('admin.layout.app')
@section('title', 'Daftar Warga')
@section('page-title', 'Daftar Warga')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Data Warga</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola data warga untuk proses pengelompokan</p>
    </div>
    <a href="{{ route('admin.warga.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Warga
    </a>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-col md:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK..."
            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        <select name="pekerjaan_id" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            <option value="">Semua Pekerjaan</option>
            @foreach($pekerjaans as $p)
            <option value="{{ $p->id }}" {{ request('pekerjaan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">Cari</button>
        @if(request()->hasAny(['search', 'pekerjaan_id']))
        <a href="{{ route('admin.warga.index') }}" class="px-4 py-2.5 text-gray-500 hover:text-gray-700 text-sm font-medium">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">NIK</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Pekerjaan</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600 text-xs uppercase tracking-wider">Pendapatan</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider">Tanggungan</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider">Kelompok</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($wargas as $w)
                <tr class="hover:bg-emerald-50/50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $w->nik }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $w->nama_lengkap }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $w->pekerjaan->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-right text-gray-600">Rp {{ number_format($w->pendapatan, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center text-gray-600">{{ $w->jumlah_tanggungan }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($w->latestClusteringResult)
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ match($w->latestClusteringResult->label) { 'Rendah' => 'bg-rose-100 text-rose-700', 'Sedang' => 'bg-amber-100 text-amber-700', 'Tinggi' => 'bg-teal-100 text-teal-700', default => 'bg-gray-100 text-gray-700' } }}">{{ match($w->latestClusteringResult->label) { 'Rendah' => 'Ekonomi Rendah', 'Sedang' => 'Menengah', 'Tinggi' => 'Mampu', default => $w->latestClusteringResult->label } }}</span>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('admin.warga.edit', $w) }}" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.warga.destroy', $w) }}" onsubmit="return confirm('Hapus data warga ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data warga.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($wargas->hasPages())
    <div class="px-4 py-3 border-t border-gray-200">{{ $wargas->links() }}</div>
    @endif
</div>
@endsection
