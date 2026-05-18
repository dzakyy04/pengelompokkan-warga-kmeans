@extends('admin.layout.app')
@section('title', 'Proses Pengelompokan')
@section('page-title', 'Proses Pengelompokan')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Proses Pengelompokan Warga</h1>
        <p class="text-gray-500 text-sm mt-1">Kelompokkan warga berdasarkan data ekonomi secara otomatis</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Siap Memproses</h3>
                <p class="text-sm text-gray-500">{{ $totalWarga }} warga tersedia untuk dikelompokkan</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
            <p class="text-sm text-blue-700">
                <strong>Cara kerja:</strong> Sistem akan mengelompokkan warga secara otomatis berdasarkan pendapatan, pekerjaan, jumlah tanggungan, kondisi rumah, dan aset yang dimiliki.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.clustering.process') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Kelompok</label>
                    <select name="jumlah_cluster" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="3" selected>3 Kelompok (Rendah, Menengah, Mampu)</option>
                    </select>
                    @error('jumlah_cluster')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Batas Pengulangan</label>
                    <input type="number" name="max_iterasi" value="100" min="10" max="500" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Semakin tinggi, hasil semakin akurat</p>
                    @error('max_iterasi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl" onclick="this.disabled=true;this.innerText='Sedang memproses...';this.form.submit();">
                Mulai Proses Pengelompokan
            </button>
        </form>
    </div>

    @if($latestSession)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-3">Pengelompokan Terakhir</h3>
        <div class="space-y-2">
            <div class="flex justify-between px-3 py-2 bg-gray-50 rounded-xl text-sm">
                <span class="text-gray-500">Tanggal</span>
                <span class="font-bold">{{ $latestSession->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex justify-between px-3 py-2 bg-gray-50 rounded-xl text-sm">
                <span class="text-gray-500">Status</span>
                <span class="font-semibold px-2 py-0.5 rounded-lg text-xs {{ match($latestSession->status) { 'validated' => 'bg-emerald-100 text-emerald-700', 'completed' => 'bg-blue-100 text-blue-700', 'rejected' => 'bg-red-100 text-red-700', default => 'bg-amber-100 text-amber-700' } }}">
                    {{ match($latestSession->status) { 'validated' => 'Sudah Disetujui', 'completed' => 'Menunggu Persetujuan', 'rejected' => 'Ditolak', default => 'Diproses' } }}
                </span>
            </div>
        </div>
        <a href="{{ route('admin.clustering.show', $latestSession->id) }}" class="inline-flex items-center mt-4 text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat Hasil &rarr;</a>
    </div>
    @endif
</div>
@endsection
