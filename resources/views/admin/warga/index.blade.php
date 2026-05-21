@extends('admin.layout.app')
@section('title', 'Daftar Warga')
@section('page-title', 'Daftar Warga')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Warga</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola data warga untuk proses pengelompokan</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('admin.warga.export-pdf', request()->query()) }}" class="inline-flex items-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            PDF
        </a>
        <a href="{{ route('admin.warga.export-excel', request()->query()) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-semibold rounded-xl transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Excel
        </a>
        <button type="button" onclick="openModal('createWargaModal')" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Warga
        </button>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-5 mb-4 transition-colors duration-200">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-4">
        <div class="relative">
            <select id="f_pendidikan" class="w-full appearance-none pl-3 pr-8 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                <option value="">Semua Pendidikan</option>
                @foreach($pendidikans as $p)
                <option value="{{ $p->nama }}">{{ $p->nama }}</option>
                @endforeach
            </select>
            <span class="absolute inset-y-0 right-2 flex items-center text-gray-400 pointer-events-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></span>
        </div>
        <div class="relative">
            <select id="f_kondisi" class="w-full appearance-none pl-3 pr-8 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                <option value="">Semua Kondisi Rumah</option>
                @foreach($kondisiRumahs as $kr)
                <option value="{{ $kr->nama }}">{{ $kr->nama }}</option>
                @endforeach
            </select>
            <span class="absolute inset-y-0 right-2 flex items-center text-gray-400 pointer-events-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></span>
        </div>
        <div class="relative">
            <select id="f_bansos" class="w-full appearance-none pl-3 pr-8 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                <option value="">Semua Bansos</option>
                @foreach($bansos as $b)
                <option value="{{ $b->nama }}">{{ $b->nama }}</option>
                @endforeach
            </select>
            <span class="absolute inset-y-0 right-2 flex items-center text-gray-400 pointer-events-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></span>
        </div>
        <div class="relative">
            <select id="f_kelompok" class="w-full appearance-none pl-3 pr-8 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                <option value="">Semua Kelompok</option>
                <option value="Rendah">Ekonomi Rendah</option>
                <option value="Menengah">Ekonomi Menengah</option>
                <option value="Mampu">Ekonomi Mampu</option>
            </select>
            <span class="absolute inset-y-0 right-2 flex items-center text-gray-400 pointer-events-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></span>
        </div>
        <div class="relative">
            <select id="f_status" class="w-full appearance-none pl-3 pr-8 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                <option value="">Semua Status</option>
                <option value="Menunggu Validasi">Menunggu Validasi</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Ditolak">Ditolak</option>
            </select>
            <span class="absolute inset-y-0 right-2 flex items-center text-gray-400 pointer-events-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></span>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
        <div>
            <label class="block text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Pendapatan</label>
            <div class="grid grid-cols-2 gap-2">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-xs pointer-events-none">Rp.</span>
                    <input type="text" id="f_pendapatan_min" placeholder="Min"
                        class="pendapatan-mask w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-xs pointer-events-none">Rp.</span>
                    <input type="text" id="f_pendapatan_max" placeholder="Max"
                        class="pendapatan-mask w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Tanggungan</label>
            <div class="grid grid-cols-2 gap-2">
                <input type="number" id="f_tanggungan_min" placeholder="Min" min="0"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                <input type="number" id="f_tanggungan_max" placeholder="Max" min="0"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
        </div>
    </div>
    <button type="button" id="resetFilter" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-600 dark:text-gray-300 rounded-lg text-sm font-medium transition hidden">
        <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        Reset Filter
    </button>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden transition-colors duration-200" style="max-width: 100%; overflow-x: auto;">
    <table id="wargaTable" class="w-full text-sm display" style="width:100%">
        <thead class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                <tr>
                    <th class="px-3 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">No</th>
                    <th class="px-3 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">NIK</th>
                    <th class="px-3 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Nama</th>
                    <th class="px-3 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Pendidikan KK</th>
                    <th class="px-3 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Kondisi Rumah</th>
                    <th class="px-3 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Bansos</th>
                    <th class="px-3 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Pendapatan</th>
                    <th class="px-3 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Tanggungan</th>
                    <th class="px-3 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Kelompok</th>
                    <th class="px-3 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($wargas as $w)
                <tr class="hover:bg-emerald-50/50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-3 py-3 text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-3 py-3 font-mono text-xs text-gray-600 dark:text-gray-400">{{ $w->nik }}</td>
                    <td class="px-3 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $w->nama_lengkap }}</td>
                    <td class="px-3 py-3 text-gray-600 dark:text-gray-400">{{ $w->pendidikan->nama ?? '-' }}</td>
                    <td class="px-3 py-3 text-gray-600 dark:text-gray-400">{{ $w->kondisiRumah->nama ?? '-' }}</td>
                    <td class="px-3 py-3 text-gray-600 dark:text-gray-400">{{ $w->bansos->nama ?? '-' }}</td>
                    <td class="px-3 py-3 text-right text-gray-600 dark:text-gray-400 whitespace-nowrap" data-order="{{ $w->pendapatan }}" data-search="{{ $w->pendapatan }}">Rp {{ number_format($w->pendapatan, 0, ',', '.') }}</td>
                    <td class="px-3 py-3 text-center text-gray-600 dark:text-gray-400">{{ $w->jumlah_tanggungan }}</td>
                    <td class="px-3 py-3 text-center">
                        @if($w->latestClusteringResult)
                        <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-semibold whitespace-nowrap {{ match($w->latestClusteringResult->label) { 'Rendah' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400', 'Sedang' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Tinggi' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400', default => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300' } }}">{{ match($w->latestClusteringResult->label) { 'Rendah' => 'Rendah', 'Sedang' => 'Menengah', 'Tinggi' => 'Mampu', default => $w->latestClusteringResult->label } }}</span>
                        @elseif($w->latestClassification)
                        @php $cls = $w->latestClassification; @endphp
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-semibold whitespace-nowrap {{ match($cls->assigned_label) { 'Rendah' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400', 'Sedang' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Tinggi' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400', default => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300' } }}">{{ match($cls->assigned_label) { 'Rendah' => 'Rendah', 'Sedang' => 'Menengah', 'Tinggi' => 'Mampu', default => $cls->assigned_label } }}</span>
                            @if($cls->status === 'pending')
                            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-medium whitespace-nowrap">Menunggu Validasi</span>
                            @elseif($cls->status === 'approved')
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">Disetujui</span>
                            @elseif($cls->status === 'rejected')
                            <span class="text-[10px] text-red-600 dark:text-red-400 font-medium">Ditolak</span>
                            @endif
                        </div>
                        @else
                        <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-center">
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
                <tr><td colspan="10" class="px-3 py-8 text-center text-gray-400 dark:text-gray-500">Belum ada data warga.</td></tr>
                @endforelse
            </tbody>
        </table>
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
    // Custom DataTables filter functions
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        if (settings.nTable.id !== 'wargaTable') return true;

        var pendidikan = $('#f_pendidikan').val();
        var kondisi    = $('#f_kondisi').val();
        var bansos     = $('#f_bansos').val();
        var kelompok   = $('#f_kelompok').val();
        var status     = $('#f_status').val();
        var pendMin    = parseInt($('#f_pendapatan_min').val().replace(/[^0-9]/g, '')) || 0;
        var pendMax    = parseInt($('#f_pendapatan_max').val().replace(/[^0-9]/g, '')) || Infinity;
        var tangMin    = parseInt($('#f_tanggungan_min').val()) || 0;
        var tangMax    = parseInt($('#f_tanggungan_max').val()) || Infinity;

        var colPendidikan = data[3] || '';
        var colKondisi    = data[4] || '';
        var colBansos     = data[5] || '';
        var colPendapatan = parseInt($(settings.aoData[dataIndex].nTr).find('td:eq(6)').data('order')) || 0;
        var colTanggungan = parseInt(data[7]) || 0;
        var colKelompok   = data[8] || '';
        var colStatus     = data[8] || '';

        if (pendidikan && colPendidikan.indexOf(pendidikan) === -1) return false;
        if (kondisi    && colKondisi.indexOf(kondisi) === -1)       return false;
        if (bansos     && colBansos.indexOf(bansos) === -1)         return false;
        if (kelompok   && colKelompok.indexOf(kelompok) === -1)     return false;
        if (status     && colStatus.indexOf(status) === -1)         return false;
        if (colPendapatan < pendMin || colPendapatan > pendMax)     return false;
        if (colTanggungan < tangMin || colTanggungan > tangMax)     return false;

        return true;
    });

    $(document).ready(function() {
        var table = $('#wargaTable').DataTable({
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
                { orderable: false, targets: [9] },
                { width: '30px', targets: 0 },
                { type: 'num', targets: [6, 7] }
            ]
        });

        // Trigger redraw on any filter change
        function applyFilters() {
            table.draw();
            var hasFilter = $('#f_pendidikan').val() || $('#f_kondisi').val() || $('#f_bansos').val() ||
                            $('#f_kelompok').val() || $('#f_status').val() ||
                            $('#f_pendapatan_min').val() || $('#f_pendapatan_max').val() ||
                            $('#f_tanggungan_min').val() || $('#f_tanggungan_max').val();
            $('#resetFilter').toggleClass('hidden', !hasFilter);
        }

        $('#f_pendidikan, #f_kondisi, #f_bansos, #f_kelompok, #f_status').on('change', applyFilters);
        $('#f_pendapatan_min, #f_pendapatan_max, #f_tanggungan_min, #f_tanggungan_max').on('input', applyFilters);

        // Reset all filters
        $('#resetFilter').on('click', function() {
            $('#f_pendidikan, #f_kondisi, #f_bansos, #f_kelompok, #f_status').val('');
            $('#f_pendapatan_min, #f_pendapatan_max, #f_tanggungan_min, #f_tanggungan_max').val('');
            $(this).addClass('hidden');
            table.draw();
        });

        // Auto format mask untuk input pendapatan
        document.querySelectorAll('.pendapatan-mask').forEach(function(input) {
            input.addEventListener('input', function() {
                var value = this.value.replace(/[^0-9]/g, '');
                this.value = value ? new Intl.NumberFormat('id-ID').format(parseInt(value)) : '';
            });
        });
    });

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
