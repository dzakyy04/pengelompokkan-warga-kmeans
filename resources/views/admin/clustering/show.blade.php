@extends('admin.layout.app')
@section('title', 'Hasil Pengelompokan')
@section('page-title', 'Hasil Pengelompokan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.clustering.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">&larr; Kembali</a>
</div>

{{-- Info + Aksi --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Informasi Proses</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Diproses Oleh</span><span class="font-medium dark:text-gray-200">{{ $session->user->name ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Tanggal</span><span class="font-medium dark:text-gray-200">{{ $session->created_at->format('d M Y, H:i') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Jumlah Kelompok</span><span class="font-bold dark:text-white">{{ $session->jumlah_cluster }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Total Warga</span><span class="font-bold dark:text-white">{{ $session->results->count() }} orang</span></div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Status</h3>
        <div class="mb-3">
            @if($session->is_base_model)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                ✓ Acuan Pengelompokan Aktif
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300">
                Tidak Aktif
            </span>
            @endif
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            @if($session->is_base_model)
                Warga baru yang ditambahkan akan otomatis terkelompokkan berdasarkan acuan ini.
            @else
                Acuan ini sudah digantikan oleh pengelompokan yang lebih baru.
            @endif
        </p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Unduh Laporan</h3>
        <div class="flex flex-col gap-2">
            <a href="{{ route('admin.clustering.pdf', $session->id) }}" class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition w-full justify-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan PDF
            </a>
            <a href="{{ route('admin.clustering.excel', $session->id) }}" class="inline-flex items-center px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-semibold rounded-xl transition w-full justify-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Excel
            </a>
        </div>
    </div>
</div>

{{-- Ringkasan per Kelompok --}}
@if($session->centroids->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @foreach($session->centroids->sortBy(fn($c) => match($c->label) { 'Rendah' => 0, 'Sedang' => 1, 'Tinggi' => 2, default => 3 }) as $c)
    @php
        $colors = match($c->label) {
            'Rendah' => ['from-rose-500 to-rose-600', 'text-rose-100'],
            'Sedang' => ['from-amber-500 to-amber-600', 'text-amber-100'],
            'Tinggi' => ['from-teal-500 to-teal-600', 'text-teal-100'],
            default  => ['from-gray-500 to-gray-600', 'text-gray-100'],
        };
        $labelFriendly = match($c->label) { 'Rendah' => 'Ekonomi Rendah', 'Sedang' => 'Ekonomi Menengah', 'Tinggi' => 'Ekonomi Mampu', default => $c->label };
    @endphp
    <div class="bg-gradient-to-br {{ $colors[0] }} rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="relative z-10">
            <h4 class="text-lg font-bold mb-1">{{ $labelFriendly }}</h4>
            <p class="text-4xl font-extrabold my-2">{{ $c->jumlah_anggota }} <span class="text-base font-semibold {{ $colors[1] }}">orang</span></p>
            <p class="text-sm {{ $colors[1] }} opacity-80">
                {{ match($c->label) { 'Rendah' => 'Memerlukan bantuan bahan pokok', 'Sedang' => 'Cocok untuk pelatihan UMKM', 'Tinggi' => 'Potensi sebagai mentor', default => '' } }}
            </p>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Daftar Warga per Kelompok --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
    <div class="p-6 border-b border-gray-200 dark:border-slate-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Warga per Kelompok</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $session->results->count() }} warga telah dikelompokkan</p>
    </div>
    <div class="overflow-x-auto">
        <table id="clusterResultTable" class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-slate-700/50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">No</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">NIK</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Nama</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Pendidikan KK</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Pekerjaan</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Status Produktivitas</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Tanggungan</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Kondisi Rumah</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Bansos</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Kelompok</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @foreach($session->results->sortBy(fn($r) => match($r->label) { 'Rendah' => 0, 'Sedang' => 1, 'Tinggi' => 2, default => 3 }) as $r)
                <tr class="hover:bg-emerald-50/50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-400">{{ $r->warga->nik ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $r->warga->nama_lengkap ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $r->warga->pendidikan->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $r->warga->pekerjaan ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $spBadge = match($r->warga->status_produktivitas ?? '') {
                                'Stabil'          => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                                'Cukup Stabil'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'Tidak Stabil'    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'Tidak Produktif' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                default           => 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400',
                            };
                        @endphp
                        @if($r->warga->status_produktivitas)
                        <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-semibold {{ $spBadge }}">{{ $r->warga->status_produktivitas }}</span>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $r->warga->jumlah_tanggungan ?? 0 }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $r->warga->kondisiRumah->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $r->warga->bansos->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ match($r->label) { 'Rendah' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400', 'Sedang' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Tinggi' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400', default => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300' } }}">
                            {{ match($r->label) { 'Rendah' => 'Ekonomi Rendah', 'Sedang' => 'Ekonomi Menengah', 'Tinggi' => 'Ekonomi Mampu', default => $r->label } }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#clusterResultTable').DataTable({
        language: {
            search: "Cari: ",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        },
        pageLength: 15,
        order: [[9, 'asc']],
    });
});
</script>
@endpush
@endsection
