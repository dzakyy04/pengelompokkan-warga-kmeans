@extends('admin.layout.app')
@section('title', 'Proses Pengelompokan')
@section('page-title', 'Proses Pengelompokan')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Proses Pengelompokan Warga</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelompokkan warga berdasarkan data ekonomi secara otomatis</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 mb-6 transition-colors duration-200">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Siap Memproses</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $totalWarga }} warga tersedia untuk dikelompokkan</p>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 mb-4">
            <p class="text-sm text-blue-700 dark:text-blue-300">
                <strong class="dark:text-blue-200">Cara kerja:</strong> Sistem akan mengelompokkan warga secara otomatis berdasarkan pekerjaan (skor produktivitas), jumlah tanggungan, pendidikan kepala keluarga, kondisi rumah, dan jumlah bansos yang diterima.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.clustering.process') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Jumlah Kelompok</label>
                    <select name="jumlah_cluster" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-colors duration-200">
                        <option value="3" selected>3 Kelompok (Rendah, Menengah, Mampu)</option>
                    </select>
                    @error('jumlah_cluster')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Batas Pengulangan</label>
                    <input type="number" name="max_iterasi" value="100" min="10" max="500" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-colors duration-200">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Semakin tinggi, hasil semakin akurat</p>
                    @error('max_iterasi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl" onclick="this.disabled=true;this.innerText='Sedang memproses...';this.form.submit();">
                Mulai Proses Pengelompokan
            </button>
        </form>
    </div>

    {{-- Base Model Info --}}
    @php $activeBaseModel = \App\Models\ClusteringSession::getActiveBaseModel(); @endphp
    @if($activeBaseModel)
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-indigo-200 dark:border-indigo-800/50 p-6 mb-6 transition-colors duration-200">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                <span class="text-lg">🎯</span>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Base Model Aktif</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Session #{{ $activeBaseModel->id }} — {{ $activeBaseModel->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800/50 rounded-xl p-4">
            <p class="text-sm text-indigo-700 dark:text-indigo-300">
                Warga baru yang ditambahkan akan <strong>otomatis terkelompokkan</strong> menggunakan model ini dan menunggu validasi Kepala Desa.
            </p>
        </div>
        @php $pendingCount = \App\Models\WargaClassificationQueue::where('status', 'pending')->count(); @endphp
        @if($pendingCount > 0)
        <a href="{{ route('admin.clustering.pending-classifications') }}" class="inline-flex items-center mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
            {{ $pendingCount }} warga baru menunggu validasi &rarr;
        </a>
        @endif
    </div>
    @else
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl p-4 mb-6">
        <p class="text-sm text-amber-700 dark:text-amber-300">
            <strong>Belum ada Base Model aktif.</strong> Lakukan proses pengelompokan, validasi hasilnya, lalu aktifkan sebagai Base Model agar warga baru otomatis terkelompokkan.
        </p>
    </div>
    @endif

    @if($latestSession)
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Pengelompokan Terakhir</h3>
        <div class="space-y-2">
            <div class="flex justify-between px-3 py-2 bg-gray-50 dark:bg-slate-700/50 rounded-xl text-sm">
                <span class="text-gray-500 dark:text-gray-400">Tanggal</span>
                <span class="font-bold dark:text-white">{{ $latestSession->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex justify-between px-3 py-2 bg-gray-50 dark:bg-slate-700/50 rounded-xl text-sm">
                <span class="text-gray-500 dark:text-gray-400">Status</span>
                <span class="font-semibold px-2 py-0.5 rounded-lg text-xs {{ match($latestSession->status) { 'validated' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', default => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' } }}">
                    {{ match($latestSession->status) { 'validated' => 'Sudah Disetujui', 'completed' => 'Menunggu Persetujuan', 'rejected' => 'Ditolak', default => 'Diproses' } }}
                </span>
            </div>
        </div>
        <a href="{{ route('admin.clustering.show', $latestSession->id) }}" class="inline-flex items-center mt-4 text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat Hasil &rarr;</a>
    </div>
    @endif
</div>
@endsection
