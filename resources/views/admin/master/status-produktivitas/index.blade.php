@extends('admin.layout.app')
@section('title', 'Status Produktivitas')
@section('page-title', 'Status Produktivitas')
@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Status Produktivitas Pekerjaan</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Daftar referensi status produktivitas dan skor yang digunakan dalam pengelompokan warga</p>
    </div>
    <span class="inline-flex items-center px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-semibold rounded-xl border border-blue-200 dark:border-blue-800">
        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Data Referensi (Read-only)
    </span>
</div>

{{-- Info card --}}
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-2xl p-4 mb-6">
    <div class="flex gap-3">
        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-sm text-blue-700 dark:text-blue-300">
            <strong>Cara penggunaan:</strong> Saat menambah data warga, pilih pekerjaan dari daftar preset — status produktivitas dan skor akan terisi otomatis.
            Jika pekerjaan tidak ada dalam daftar, pilih <em>"Lainnya"</em> dan tentukan status produktivitasnya secara manual.
            Skor ini digunakan sebagai fitur clustering K-Means.
        </div>
    </div>
</div>

{{-- Stat summary cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
        $badgeConfig = [
            'Stabil'          => ['from-teal-500 to-teal-600',  'text-teal-100',  'bg-teal-100  text-teal-700  dark:bg-teal-900/30  dark:text-teal-400'],
            'Cukup Stabil'    => ['from-blue-500 to-blue-600',  'text-blue-100',  'bg-blue-100  text-blue-700  dark:bg-blue-900/30  dark:text-blue-400'],
            'Tidak Stabil'    => ['from-amber-500 to-amber-600','text-amber-100', 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
            'Tidak Produktif' => ['from-rose-500 to-rose-600',  'text-rose-100',  'bg-rose-100  text-rose-700  dark:bg-rose-900/30  dark:text-rose-400'],
        ];
    @endphp
    @foreach($statusList as $sp)
    @php $cfg = $badgeConfig[$sp['status']] ?? ['from-gray-500 to-gray-600','text-gray-100','bg-gray-100 text-gray-700']; @endphp
    <div class="bg-gradient-to-br {{ $cfg[0] }} rounded-2xl p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <p class="text-3xl font-extrabold mb-0.5">{{ $counts[$sp['status']] ?? 0 }}</p>
            <p class="text-sm font-semibold {{ $cfg[1] }}">{{ $sp['status'] }}</p>
            <p class="text-xs {{ $cfg[1] }} opacity-75 mt-1">Skor: {{ $sp['skor'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Tabel preset pekerjaan --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
        <h2 class="text-base font-bold text-gray-900 dark:text-white">Daftar Preset Pekerjaan & Status Produktivitas</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pekerjaan di bawah ini sudah memiliki status dan skor otomatis saat dipilih pada form data warga</p>
    </div>
    <table id="statusProdTable" class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider w-12">No</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Pekerjaan</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Status Produktivitas</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider w-24">Skor</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider w-32">Warga</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            @php $presetPekerjaan = \App\Models\Warga::presetPekerjaan(); @endphp
            @foreach($presetPekerjaan as $i => $preset)
            @php
                $badge = match($preset['status']) {
                    'Stabil'          => 'bg-teal-100  text-teal-700  dark:bg-teal-900/30  dark:text-teal-400',
                    'Cukup Stabil'    => 'bg-blue-100  text-blue-700  dark:bg-blue-900/30  dark:text-blue-400',
                    'Tidak Stabil'    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                    'Tidak Produktif' => 'bg-rose-100  text-rose-700  dark:bg-rose-900/30  dark:text-rose-400',
                    default           => 'bg-gray-100  text-gray-600  dark:bg-slate-700    dark:text-gray-300',
                };
                // Hitung warga per pekerjaan
                $wargaCount = \App\Models\Warga::where('pekerjaan', $preset['pekerjaan'])->count();
            @endphp
            <tr class="hover:bg-emerald-50/50 dark:hover:bg-slate-700/50 transition-colors">
                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $i + 1 }}</td>
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $preset['pekerjaan'] }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $badge }}">
                        {{ $preset['status'] }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                        {{ match($preset['skor']) {
                            4 => 'bg-teal-100  text-teal-700  dark:bg-teal-900/30  dark:text-teal-400',
                            3 => 'bg-blue-100  text-blue-700  dark:bg-blue-900/30  dark:text-blue-400',
                            2 => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            1 => 'bg-rose-100  text-rose-700  dark:bg-rose-900/30  dark:text-rose-400',
                            default => 'bg-gray-100 text-gray-600',
                        } }}">
                        {{ $preset['skor'] }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2.5 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-semibold">
                        {{ $wargaCount }}
                    </span>
                </td>
            </tr>
            @endforeach
            {{-- Baris khusus "Lainnya" --}}
            @php $wargaLainnya = \App\Models\Warga::whereNotIn('pekerjaan', collect($presetPekerjaan)->pluck('pekerjaan')->toArray())->whereNotNull('pekerjaan')->count(); @endphp
            <tr class="bg-gray-50/50 dark:bg-slate-700/20 hover:bg-gray-100/50 dark:hover:bg-slate-700/40 transition-colors">
                <td class="px-4 py-3 text-gray-400 dark:text-gray-500">—</td>
                <td class="px-4 py-3 italic text-gray-500 dark:text-gray-400">Lainnya (diisi manual)</td>
                <td class="px-4 py-3 text-gray-400 dark:text-gray-500 text-xs">Ditentukan oleh admin</td>
                <td class="px-4 py-3 text-center text-gray-400 dark:text-gray-500 text-xs">1–4</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2.5 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-semibold">{{ $wargaLainnya }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#statusProdTable').DataTable({
        language: {
            search: "Cari: ",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        },
        pageLength: 25,
        order: [[3, 'desc']],
        columnDefs: [{ orderable: false, targets: [0] }]
    });
});
</script>
@endpush
@endsection
