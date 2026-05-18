@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Dashboard</h1>
    <p class="text-gray-600">Ringkasan data dan hasil pengelompokan warga.</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="text-4xl font-bold mb-1">{{ $totalWarga }}</h3>
            <p class="text-sm text-emerald-100 font-semibold">Total Warga</p>
            <span class="text-xs text-emerald-100 opacity-80 mt-2 block">Warga terdaftar</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-3xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
            </div>
            <h3 class="text-4xl font-bold mb-1">{{ $clusterDistribution['Rendah'] }}</h3>
            <p class="text-sm text-rose-100 font-semibold">Ekonomi Rendah</p>
            <span class="text-xs text-rose-100 opacity-80 mt-2 block">Perlu bantuan pokok</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-3xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </div>
            <h3 class="text-4xl font-bold mb-1">{{ $clusterDistribution['Sedang'] }}</h3>
            <p class="text-sm text-amber-100 font-semibold">Ekonomi Menengah</p>
            <span class="text-xs text-amber-100 opacity-80 mt-2 block">Pelatihan UMKM</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-3xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <h3 class="text-4xl font-bold mb-1">{{ $clusterDistribution['Tinggi'] }}</h3>
            <p class="text-sm text-teal-100 font-semibold">Ekonomi Mampu</p>
            <span class="text-xs text-teal-100 opacity-80 mt-2 block">Potensi mentor</span>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-12 gap-4 mb-6">
    <div class="col-span-12 lg:col-span-4">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 h-full">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Sebaran Kelompok Warga</h2>
            <div class="h-64 flex items-center justify-center"><canvas id="clusterDoughnut"></canvas></div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-8">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 h-full">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Rata-rata Pendapatan per Kelompok</h2>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-xl">{{ $totalWarga }} Warga</span>
            </div>
            <div class="h-64"><canvas id="incomeBar"></canvas></div>
        </div>
    </div>
</div>

{{-- Proses Terakhir --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">Pengelompokan Terakhir</h2>
        @if($latestSession)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold {{ match($latestSession->status) { 'validated' => 'bg-emerald-100 text-emerald-700', 'completed' => 'bg-blue-100 text-blue-700', 'rejected' => 'bg-red-100 text-red-700', default => 'bg-amber-100 text-amber-700' } }}">
            @if($latestSession->status === 'validated')<span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Sudah Disetujui
            @elseif($latestSession->status === 'completed')<span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> Menunggu Persetujuan
            @elseif($latestSession->status === 'rejected')<span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Ditolak
            @else Diproses
            @endif
        </span>
        @endif
    </div>
    @if($latestSession)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-xl">
            <span class="text-sm text-gray-500">Tanggal Proses</span>
            <span class="text-sm font-bold text-gray-900">{{ $latestSession->created_at->format('d M Y, H:i') }}</span>
        </div>
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-xl">
            <span class="text-sm text-gray-500">Jumlah Kelompok</span>
            <span class="text-sm font-bold text-gray-900">{{ $latestSession->jumlah_cluster }} Kelompok</span>
        </div>
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-xl">
            <span class="text-sm text-gray-500">Total Proses</span>
            <span class="text-sm font-bold text-gray-900">{{ $totalSessions }} kali</span>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.clustering.show', $latestSession->id) }}" class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-700 font-semibold">Lihat Detail Hasil &rarr;</a>
    </div>
    @else
    <div class="text-center py-8 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <p class="text-sm font-medium">Belum ada proses pengelompokan</p>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.clustering.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium mt-2 inline-block">Mulai Proses &rarr;</a>
        @endif
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('clusterDoughnut').getContext('2d'), {
        type: 'doughnut',
        data: { labels: ['Ekonomi Rendah','Ekonomi Menengah','Ekonomi Mampu'], datasets: [{ data: [{{ $clusterDistribution['Rendah'] }}, {{ $clusterDistribution['Sedang'] }}, {{ $clusterDistribution['Tinggi'] }}], backgroundColor: ['rgba(244,63,94,0.85)','rgba(245,158,11,0.85)','rgba(20,184,166,0.85)'], borderWidth: 3, borderColor: '#fff', hoverOffset: 8 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { padding: 16, font: { size: 11, weight: '500' }, usePointStyle: true, pointStyle: 'circle', boxWidth: 8 } }, tooltip: { backgroundColor: 'rgba(0,0,0,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 13, weight: 'bold' } } } }
    });
    new Chart(document.getElementById('incomeBar').getContext('2d'), {
        type: 'bar',
        data: { labels: ['Ekonomi Rendah','Ekonomi Menengah','Ekonomi Mampu'], datasets: [{ label: 'Rata-rata Pendapatan', data: [{{ $avgIncomePerCluster['Rendah'] }}, {{ $avgIncomePerCluster['Sedang'] }}, {{ $avgIncomePerCluster['Tinggi'] }}], backgroundColor: ['rgba(244,63,94,0.8)','rgba(245,158,11,0.8)','rgba(20,184,166,0.8)'], borderColor: ['rgb(244,63,94)','rgb(245,158,11)','rgb(20,184,166)'], borderWidth: 2, borderRadius: 12, borderSkipped: false }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(0,0,0,0.9)', padding: 12, cornerRadius: 10, displayColors: false, callbacks: { label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID') } } }, scales: { y: { beginAtZero: true, ticks: { font: { size: 10 }, callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' }, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { ticks: { font: { size: 11, weight: '600' } }, grid: { display: false } } } }
    });
});
</script>
@endpush
