@extends('admin.layout.app')
@section('title', 'Validasi Warga Baru')
@section('page-title', 'Validasi Warga Baru')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Validasi Warga Baru</h1>
    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Tinjau dan setujui pengelompokan otomatis warga baru berdasarkan Base Model aktif</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
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

    <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-3xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-4xl font-bold mb-1">{{ $totalRejected }}</h3>
            <p class="text-sm text-rose-100 font-semibold">Ditolak</p>
            <span class="text-xs text-rose-100 opacity-80 mt-2 block">Perlu pengecekan ulang</span>
        </div>
    </div>
</div>

{{-- No Base Model Alert --}}
@if(!$baseModel)
<div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl p-4 mb-6">
    <p class="text-sm text-amber-700 dark:text-amber-300">
        <strong>Belum ada Base Model aktif.</strong> Klasifikasi otomatis tidak dapat berjalan tanpa Base Model. Hubungi admin untuk mengaktifkan Base Model terlebih dahulu.
    </p>
</div>
@endif

{{-- Main Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
    <div class="p-6 border-b border-gray-200 dark:border-slate-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Warga Menunggu Validasi</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $totalPending }} warga baru menunggu persetujuan Anda</p>
    </div>

    @if($pending->count() > 0)
    <div class="overflow-x-auto">
        <table id="pendingTable" class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-slate-700/50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">No</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Nama Warga</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">NIK</th>
                <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Pendapatan</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Tanggungan</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Kelompok Otomatis</th>
                <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Jarak</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase">Aksi</th>
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
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $item->warga->nama_lengkap ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $item->warga->nik ?? '-' }}</td>
                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($item->warga->pendapatan ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $item->warga->jumlah_tanggungan ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badgeClass }}">
                            {{ $labelFriendly }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">{{ number_format($item->distance_to_centroid, 4) }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Approve Button --}}
                            <form method="POST" action="{{ route('admin.clustering.approve-classification', $item->id) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition" onclick="return confirm('Setujui pengelompokan warga ini?')">
                                    ✓ Setujui
                                </button>
                            </form>
                            {{-- Reject/Revise Toggle --}}
                            <button type="button" onclick="toggleRejectForm({{ $item->id }})" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition">
                                ✗ Revisi/Tolak
                            </button>
                        </div>
                        {{-- Reject/Revise Form (hidden by default) --}}
                        <div id="reject-form-{{ $item->id }}" class="hidden mt-3 text-left">
                            <form method="POST" action="{{ route('admin.clustering.reject-classification', $item->id) }}">
                                @csrf
                                <div class="mb-2">
                                    <textarea name="rejection_reason" placeholder="Alasan penolakan/revisi (wajib diisi)" rows="2" required class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white rounded-xl text-xs focus:ring-2 focus:ring-rose-500 outline-none transition-colors"></textarea>
                                </div>
                                @if($baseModel && $baseModel->centroids->count() > 0)
                                <div class="mb-2">
                                    <select name="revised_cluster" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white rounded-xl text-xs focus:ring-2 focus:ring-rose-500 outline-none transition-colors">
                                        <option value="">— Tolak tanpa revisi —</option>
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
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Pilih kelompok untuk merevisi, atau kosongkan untuk menolak.</p>
                                </div>
                                @endif
                                <div class="flex gap-2">
                                    <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition">
                                        Kirim
                                    </button>
                                    <button type="button" onclick="toggleRejectForm({{ $item->id }})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-lg transition">
                                        Batal
                                    </button>
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
        <p class="text-sm font-medium">Tidak ada warga yang menunggu validasi</p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Semua pengelompokan otomatis sudah ditinjau</p>
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
        columnDefs: [
            { orderable: false, targets: [7] }
        ]
    });
});
</script>
@endpush
