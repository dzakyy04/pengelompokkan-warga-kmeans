@extends('admin.layout.app')
@section('title', 'Validasi Warga Baru')
@section('page-title', 'Validasi Warga Baru')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Verifikasi Warga Baru</h1>
    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Tinjau dan verifikasi kelompok ekonomi warga baru yang ditambahkan ke sistem</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-3xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-4xl font-bold mb-1">{{ $totalPending }}</h3>
            <p class="text-sm text-amber-100 font-semibold">Menunggu Validasi</p>
            <span class="text-xs text-amber-100 opacity-80 mt-2 block">Perlu ditinjau</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-4xl font-bold mb-1">{{ $totalApproved }}</h3>
            <p class="text-sm text-emerald-100 font-semibold">Disetujui</p>
            <span class="text-xs text-emerald-100 opacity-80 mt-2 block">Sudah divalidasi</span>
        </div>
    </div>
</div>

{{-- No Active Alert --}}
@if(!$baseModel)
<div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl p-4 mb-6">
    <p class="text-sm text-amber-700 dark:text-amber-300">
        <strong>Belum ada acuan pengelompokan.</strong> Pengelompokan otomatis tidak dapat berjalan. Hubungi admin untuk menjalankan proses pengelompokan terlebih dahulu.
    </p>
</div>
@endif

{{-- Main Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden transition-colors duration-200" style="max-width: 100%; overflow-x: auto;">
    <div class="p-6 border-b border-gray-200 dark:border-slate-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Warga Menunggu Verifikasi</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $totalPending }} warga baru menunggu verifikasi Anda</p>
    </div>

    @if($pending->count() > 0)
    <div class="overflow-x-auto">
        <table id="pendingTable" class="w-full text-sm display" style="width:100%">
            <thead class="bg-gray-50 dark:bg-slate-700/50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">No</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">Nama & NIK</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">Pekerjaan & Status</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">Pendidikan KK</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">Kondisi Rumah</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">Bansos</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">Tanggungan</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">Kelompok K-Means</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase whitespace-nowrap">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @foreach($pending as $item)
                @php
                    $label = $item->assigned_label;
                    $labelFriendly = match($label) {
                        'Rendah' => 'Ekonomi Rendah',
                        'Sedang' => 'Ekonomi Menengah',
                        'Tinggi' => 'Ekonomi Mampu',
                        default => $label
                    };
                    $badgeClass = match($label) {
                        'Rendah' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                        'Sedang' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                        'Tinggi' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                        default => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300'
                    };
                @endphp
                <tr class="hover:bg-emerald-50/50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->warga->nama_lengkap ?? '-' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->warga->nik ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-gray-600 dark:text-gray-400 mb-1">{{ $item->warga->pekerjaan ?? '-' }}</div>
                        @php
                            $spbClass = match($item->warga->status_produktivitas ?? '') {
                                'Stabil'          => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                                'Cukup Stabil'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'Tidak Stabil'    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'Tidak Produktif' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                default           => 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400',
                            };
                        @endphp
                        @if($item->warga->status_produktivitas)
                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold {{ $spbClass }}">{{ $item->warga->status_produktivitas }}</span>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $item->warga->pendidikan->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $item->warga->kondisiRumah->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $item->warga->bansos->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $item->warga->jumlah_tanggungan ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badgeClass }}">
                            {{ $labelFriendly }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Approve Button --}}
                            <form method="POST" action="{{ route('admin.clustering.approve-classification', $item->id) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition" onclick="return confirm('Setujui pengelompokan warga ini?')">
                                    ✓ Setujui
                                </button>
                            </form>
                            {{-- Revise Toggle --}}
                            <button type="button" onclick="toggleRejectForm({{ $item->id }})" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                ✎ Revisi Kelompok
                            </button>
                        </div>
                        {{-- Revise Form (hidden by default) --}}
                        <div id="reject-form-{{ $item->id }}" class="hidden mt-3 text-left">
                            <form method="POST" action="{{ route('admin.clustering.reject-classification', $item->id) }}">
                                @csrf
                                @if($baseModel && $baseModel->centroids->count() > 0)
                                <div class="mb-2">
                                    <select name="revised_cluster" required class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none transition-colors">
                                        <option value="" disabled selected>— Pilih Kelompok Baru —</option>
                                        @foreach($baseModel->centroids->sortBy('cluster') as $centroid)
                                        @php
                                            $centroidLabel = match($centroid->label) {
                                                'Rendah' => 'Ekonomi Rendah',
                                                'Sedang' => 'Ekonomi Menengah',
                                                'Tinggi' => 'Ekonomi Mampu',
                                                default => $centroid->label
                                            };
                                        @endphp
                                        <option value="{{ $centroid->cluster }}">Pindahkan ke: {{ $centroidLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="flex gap-2">
                                    <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">Simpan Revisi</button>
                                    <button type="button" onclick="toggleRejectForm({{ $item->id }})" class="px-3 py-1.5 bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg transition">Batal</button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12 text-gray-400 dark:text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm font-medium">Tidak ada warga yang menunggu verifikasi</p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Semua warga baru sudah diverifikasi</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleRejectForm(id) {
    const form = document.getElementById('reject-form-' + id);
    if (form) {
        form.classList.toggle('hidden');
    }
}

$(document).ready(function() {
    $('#pendingTable').DataTable({
        language: {
            search: "Cari: ",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        },
        pageLength: 10,
        order: [[0, 'asc']],
        scrollX: true,
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: [8] }
        ]
    });
});
</script>
@endpush
