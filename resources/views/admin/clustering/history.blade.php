@extends('admin.layout.app')
@section('title', 'Riwayat Pengelompokan')
@section('page-title', 'Riwayat Pengelompokan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Pengelompokan</h1>
    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Daftar semua proses pengelompokan warga yang pernah dilakukan</p>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">No</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Diproses Oleh</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Kelompok</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Jumlah Warga</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Status</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Tanggal</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
            @forelse($sessions as $s)
            <tr class="hover:bg-emerald-50/50 dark:hover:bg-slate-700/50 transition-colors">
                <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $loop->iteration + ($sessions->currentPage()-1) * $sessions->perPage() }}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $s->user->name ?? '-' }}</td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $s->jumlah_cluster }}</td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $s->results_count }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ match($s->status) { 'validated' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', default => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' } }}">
                        {{ match($s->status) { 'validated' => 'Disetujui', 'completed' => 'Menunggu Persetujuan', 'rejected' => 'Ditolak', default => 'Diproses' } }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $s->created_at->format('d M Y, H:i') }}</td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('admin.clustering.show', $s->id) }}" class="px-3 py-1.5 bg-gray-100 dark:bg-slate-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg text-xs font-medium transition">Lihat Hasil</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">Belum ada riwayat pengelompokan.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($sessions->hasPages())
    <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">{{ $sessions->links() }}</div>
    @endif
</div>
@endsection
