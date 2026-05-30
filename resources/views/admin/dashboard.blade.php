@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Dashboard</h1>
    <p class="text-gray-600 dark:text-gray-400">Ringkasan data dan hasil pengelompokan warga.</p>
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

{{-- Pending Classification Alert (for Kades) --}}
@if(auth()->user()->isKepalaDesa())
@php $pendingClassifications = \App\Models\WargaClassificationQueue::where('status', 'pending')->count(); @endphp
@if($pendingClassifications > 0)
<div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl shadow-lg p-5 mb-6 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">{{ $pendingClassifications }} Warga Baru Menunggu Validasi</h3>
                <p class="text-sm text-indigo-100">Warga baru telah otomatis terkelompokkan dan membutuhkan persetujuan Anda.</p>
            </div>
        </div>
        <a href="{{ route('admin.clustering.pending-classifications') }}" class="px-5 py-2.5 bg-white text-indigo-600 font-semibold text-sm rounded-xl hover:bg-indigo-50 transition flex-shrink-0">
            Validasi Sekarang
        </a>
    </div>
</div>
@endif
@endif

{{-- Charts --}}
<div class="grid grid-cols-12 gap-4 mb-6">
    <div class="col-span-12 lg:col-span-4">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 h-full transition-colors duration-200">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Sebaran Kelompok Warga</h2>
            <div class="h-64 flex items-center justify-center"><canvas id="clusterDoughnut"></canvas></div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-8">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 h-full transition-colors duration-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Distribusi Status Produktivitas per Kelompok</h2>
                <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-semibold rounded-xl">{{ $totalWarga }} Warga</span>
            </div>
            <div class="h-64"><canvas id="incomeBar"></canvas></div>
        </div>
    </div>
</div>

{{-- Proses Terakhir --}}
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pengelompokan Terakhir</h2>
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
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
            <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Proses</span>
            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $latestSession->created_at->format('d M Y, H:i') }}</span>
        </div>
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
            <span class="text-sm text-gray-500 dark:text-gray-400">Jumlah Kelompok</span>
            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $latestSession->jumlah_cluster }} Kelompok</span>
        </div>
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Proses</span>
            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $totalSessions }} kali</span>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.clustering.show', $latestSession->id) }}" class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-700 font-semibold">Lihat Detail Hasil &rarr;</a>
    </div>
    @else
    <div class="text-center py-8 text-gray-400 dark:text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
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
    
    function getThemeConfig() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            textColor: isDark ? '#94a3b8' : '#64748b',
            gridColor: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
            borderColor: isDark ? '#1e293b' : '#ffffff'
        };
    }

    let theme = getThemeConfig();

    const doughnutChart = new Chart(document.getElementById('clusterDoughnut').getContext('2d'), {
        type: 'doughnut',
        data: { labels: ['Ekonomi Rendah','Ekonomi Menengah','Ekonomi Mampu'], datasets: [{ data: [{{ $clusterDistribution['Rendah'] }}, {{ $clusterDistribution['Sedang'] }}, {{ $clusterDistribution['Tinggi'] }}], backgroundColor: ['rgba(244,63,94,0.85)','rgba(245,158,11,0.85)','rgba(20,184,166,0.85)'], borderWidth: 3, borderColor: theme.borderColor, hoverOffset: 8 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: theme.textColor, padding: 16, font: { size: 11, weight: '500' }, usePointStyle: true, pointStyle: 'circle', boxWidth: 8 } }, tooltip: { backgroundColor: 'rgba(0,0,0,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 13, weight: 'bold' } } } }
    });

    const barChart = new Chart(document.getElementById('incomeBar').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Ekonomi Rendah', 'Ekonomi Menengah', 'Ekonomi Mampu'],
            datasets: [
                {
                    label: 'Stabil',
                    data: [{{ $statusPerCluster['Rendah']['Stabil'] }}, {{ $statusPerCluster['Sedang']['Stabil'] }}, {{ $statusPerCluster['Tinggi']['Stabil'] }}],
                    backgroundColor: 'rgba(20,184,166,0.8)',
                    borderColor: 'rgb(20,184,166)',
                    borderWidth: 1, borderRadius: 4, borderSkipped: false,
                },
                {
                    label: 'Cukup Stabil',
                    data: [{{ $statusPerCluster['Rendah']['Cukup Stabil'] }}, {{ $statusPerCluster['Sedang']['Cukup Stabil'] }}, {{ $statusPerCluster['Tinggi']['Cukup Stabil'] }}],
                    backgroundColor: 'rgba(59,130,246,0.8)',
                    borderColor: 'rgb(59,130,246)',
                    borderWidth: 1, borderRadius: 4, borderSkipped: false,
                },
                {
                    label: 'Tidak Stabil',
                    data: [{{ $statusPerCluster['Rendah']['Tidak Stabil'] }}, {{ $statusPerCluster['Sedang']['Tidak Stabil'] }}, {{ $statusPerCluster['Tinggi']['Tidak Stabil'] }}],
                    backgroundColor: 'rgba(245,158,11,0.8)',
                    borderColor: 'rgb(245,158,11)',
                    borderWidth: 1, borderRadius: 4, borderSkipped: false,
                },
                {
                    label: 'Tidak Produktif',
                    data: [{{ $statusPerCluster['Rendah']['Tidak Produktif'] }}, {{ $statusPerCluster['Sedang']['Tidak Produktif'] }}, {{ $statusPerCluster['Tinggi']['Tidak Produktif'] }}],
                    backgroundColor: 'rgba(244,63,94,0.8)',
                    borderColor: 'rgb(244,63,94)',
                    borderWidth: 1, borderRadius: 4, borderSkipped: false,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: theme.textColor, font: { size: 11 }, usePointStyle: true, pointStyle: 'circle', padding: 12 } },
                tooltip: { backgroundColor: 'rgba(0,0,0,0.9)', padding: 12, cornerRadius: 10, callbacks: { label: ctx => ctx.dataset.label + ': ' + ctx.raw + ' orang' } }
            },
            scales: {
                x: { ticks: { color: theme.textColor, font: { size: 11, weight: '600' } }, grid: { display: false } },
                y: { beginAtZero: true, ticks: { color: theme.textColor, font: { size: 10 }, stepSize: 1 }, grid: { color: theme.gridColor } }
            }
        }
    });

    // Listen for theme changes to dynamically update chart colors
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                const newTheme = getThemeConfig();
                
                // Update Doughnut
                doughnutChart.data.datasets[0].borderColor = newTheme.borderColor;
                doughnutChart.options.plugins.legend.labels.color = newTheme.textColor;
                doughnutChart.update();

                // Update Bar
                barChart.options.scales.y.ticks.color = newTheme.textColor;
                barChart.options.scales.x.ticks.color = newTheme.textColor;
                barChart.options.scales.y.grid.color = newTheme.gridColor;
                barChart.update();
            }
        });
    });
    observer.observe(document.documentElement, { attributes: true });
});
</script>
@endpush
