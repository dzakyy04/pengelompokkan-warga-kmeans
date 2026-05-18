<x-filament-panels::page>
    {{-- ═══ Inline Styles for Gradient Cards ═══ --}}
    <style>
        /* Stat Cards */
        .em-stat-card {
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            padding: 1.5rem;
            color: #fff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .em-stat-card:hover {
            box-shadow: 0 20px 40px -8px rgba(0,0,0,0.15);
            transform: scale(1.02);
        }
        .em-stat-card .em-circle {
            position: absolute; top: 0; right: 0;
            width: 6rem; height: 6rem;
            background: rgba(255,255,255,0.1);
            border-radius: 9999px;
            margin-right: -3rem; margin-top: -3rem;
        }
        .em-stat-card .em-inner { position: relative; z-index: 10; }
        .em-stat-card .em-icon-box {
            width: 3rem; height: 3rem;
            background: rgba(255,255,255,0.2);
            border-radius: 0.75rem;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.75rem;
        }
        .em-stat-card .em-icon-box svg { width: 1.5rem; height: 1.5rem; }
        .em-stat-card .em-value { font-size: 2.25rem; font-weight: 700; margin-bottom: 0.25rem; line-height: 1; }
        .em-stat-card .em-label { font-size: 0.875rem; font-weight: 600; opacity: 0.9; }
        .em-stat-card .em-desc { font-size: 0.75rem; opacity: 0.7; margin-top: 0.25rem; display: block; }

        .em-card-emerald { background: linear-gradient(135deg, #10b981 0%, #047857 100%); }
        .em-card-rose    { background: linear-gradient(135deg, #f43f5e 0%, #be123c 100%); }
        .em-card-amber   { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .em-card-teal    { background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); }

        /* Chart & Content Cards */
        .em-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border: 1px solid rgba(203,213,225,0.7);
            padding: 1.25rem;
            transition: all 0.3s ease;
            height: 100%;
        }
        .em-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .em-card h2 { font-size: 1.125rem; font-weight: 700; color: #111827; margin-bottom: 1rem; }

        /* Session Info Items */
        .em-info-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.875rem 1rem;
            background: #f8fafc;
            border-radius: 1rem;
            border: 1px solid #f1f5f9;
            margin-bottom: 0.5rem;
        }
        .em-info-row .em-info-label { font-size: 0.875rem; color: #64748b; }
        .em-info-row .em-info-value { font-size: 0.875rem; font-weight: 700; color: #111827; }

        /* Table */
        .em-table-wrap {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border: 1px solid rgba(203,213,225,0.7);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .em-table-wrap:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .em-table-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex; align-items: center; justify-content: space-between;
        }
        .em-table-header h2 { font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0; }
        .em-badge-sm {
            padding: 0.375rem 0.75rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .em-badge-emerald { background: #ecfdf5; color: #047857; }
        .em-badge-blue    { background: #eff6ff; color: #1d4ed8; }
        .em-badge-rose    { background: #fff1f2; color: #be123c; }
        .em-badge-amber-b { background: #fffbeb; color: #b45309; }

        .em-table { width: 100%; font-size: 0.875rem; border-collapse: collapse; }
        .em-table thead { background: #f8fafc; }
        .em-table th {
            padding: 0.75rem 1rem;
            font-weight: 600; font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            text-align: center;
        }
        .em-table th:first-child { text-align: left; }
        .em-table td { padding: 0.75rem 1rem; text-align: center; color: #4b5563; }
        .em-table td:first-child { text-align: left; }
        .em-table tbody tr { border-top: 1px solid #e5e7eb; transition: background 0.15s ease; }
        .em-table tbody tr:hover { background: rgba(236,253,245,0.5); }

        .em-cluster-badge {
            display: inline-flex; align-items: center;
            padding: 0.25rem 0.75rem; border-radius: 0.75rem;
            font-size: 0.75rem; font-weight: 600;
        }
        .em-cluster-rendah { background: #fff1f2; color: #be123c; }
        .em-cluster-sedang { background: #fffbeb; color: #b45309; }
        .em-cluster-tinggi { background: #f0fdfa; color: #0f766e; }

        /* Pulse dot */
        .em-pulse { width: 0.5rem; height: 0.5rem; background: #10b981; border-radius: 9999px; animation: emPulse 2s infinite; }
        @keyframes emPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

        /* Status badge */
        .em-status { padding: 0.25rem 0.75rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 600; }

        /* Empty state */
        .em-empty { text-align: center; padding: 3rem 1rem; color: #9ca3af; }
        .em-empty svg { width: 4rem; height: 4rem; margin: 0 auto 0.75rem; color: #d1d5db; }
        .em-empty p { font-size: 0.875rem; font-weight: 500; }

        /* Warning box */
        .em-warning-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-radius: 1.5rem; padding: 1.5rem;
            border: 1px solid #fde68a;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .em-warning-icon {
            width: 2.5rem; height: 2.5rem; flex-shrink: 0;
            background: rgba(245,158,11,0.15); border-radius: 0.75rem;
            display: flex; align-items: center; justify-content: center;
        }
        .em-warning-icon svg { width: 1.25rem; height: 1.25rem; color: #d97706; }

        /* Grid helpers */
        .em-grid-4 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .em-grid-12 { display: grid; grid-template-columns: repeat(12, 1fr); gap: 1rem; }
        .em-col-4 { grid-column: span 12; }
        .em-col-5 { grid-column: span 12; }
        .em-col-7 { grid-column: span 12; }
        .em-col-8 { grid-column: span 12; }

        @media (min-width: 768px) {
            .em-grid-4 { grid-template-columns: repeat(4, 1fr); }
        }
        @media (min-width: 1024px) {
            .em-col-4 { grid-column: span 4; }
            .em-col-5 { grid-column: span 5; }
            .em-col-7 { grid-column: span 7; }
            .em-col-8 { grid-column: span 8; }
        }

        /* Fade in animation */
        .em-animate > * { animation: emFadeUp 0.4s ease-out both; }
        .em-animate > *:nth-child(2) { animation-delay: 0.05s; }
        .em-animate > *:nth-child(3) { animation-delay: 0.1s; }
        .em-animate > *:nth-child(4) { animation-delay: 0.15s; }
        .em-animate > *:nth-child(5) { animation-delay: 0.2s; }
        @keyframes emFadeUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Scrollbar */
        .em-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .em-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .em-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <div class="em-animate" style="display:flex;flex-direction:column;gap:1.5rem;">

        {{-- Header --}}
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#111827;margin-bottom:0.25rem;">Dashboard Pengelompokan Warga</h1>
            <p style="font-size:0.875rem;color:#64748b;">Ringkasan data clustering dan statistik warga terkini.</p>
        </div>

        {{-- Stat Cards --}}
        <div class="em-grid-4">
            {{-- Total Warga --}}
            <div class="em-stat-card em-card-emerald">
                <div class="em-circle"></div>
                <div class="em-inner">
                    <div class="em-icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="em-value">{{ $totalWarga }}</div>
                    <div class="em-label">Total Warga</div>
                    <span class="em-desc">Data warga terdaftar</span>
                </div>
            </div>

            {{-- Cluster Rendah --}}
            <div class="em-stat-card em-card-rose">
                <div class="em-circle"></div>
                <div class="em-inner">
                    <div class="em-icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    </div>
                    <div class="em-value">{{ $clusterDistribution['Rendah'] ?? 0 }}</div>
                    <div class="em-label">Cluster Rendah</div>
                    <span class="em-desc">Bantuan bahan pokok</span>
                </div>
            </div>

            {{-- Cluster Sedang --}}
            <div class="em-stat-card em-card-amber">
                <div class="em-circle"></div>
                <div class="em-inner">
                    <div class="em-icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </div>
                    <div class="em-value">{{ $clusterDistribution['Sedang'] ?? 0 }}</div>
                    <div class="em-label">Cluster Sedang</div>
                    <span class="em-desc">Pelatihan UMKM</span>
                </div>
            </div>

            {{-- Cluster Tinggi --}}
            <div class="em-stat-card em-card-teal">
                <div class="em-circle"></div>
                <div class="em-inner">
                    <div class="em-icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div class="em-value">{{ $clusterDistribution['Tinggi'] ?? 0 }}</div>
                    <div class="em-label">Cluster Tinggi</div>
                    <span class="em-desc">Mentor masyarakat</span>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="em-grid-12">
            {{-- Doughnut --}}
            <div class="em-col-4">
                <div class="em-card">
                    <h2>Distribusi Cluster</h2>
                    <div style="height:260px;display:flex;align-items:center;justify-content:center;">
                        <canvas id="clusterDoughnutChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Bar --}}
            <div class="em-col-8">
                <div class="em-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                        <h2 style="margin-bottom:0;">Rata-rata Pendapatan per Cluster</h2>
                        <span class="em-badge-sm em-badge-emerald">{{ $totalWarga }} Warga</span>
                    </div>
                    <div style="height:260px;">
                        <canvas id="incomeBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Row --}}
        <div class="em-grid-12">
            {{-- Session Info --}}
            <div class="em-col-5">
                <div class="em-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                        <h2 style="margin-bottom:0;">Info Session Terakhir</h2>
                        <div class="em-pulse"></div>
                    </div>

                    @if($latestSession)
                    <div>
                        <div class="em-info-row">
                            <span class="em-info-label">Session ID</span>
                            <span class="em-info-value">#{{ $latestSession->id }}</span>
                        </div>
                        <div class="em-info-row">
                            <span class="em-info-label">Status</span>
                            <span class="em-status" style="
                                @if($latestSession->status === 'validated') background:#ecfdf5;color:#047857;
                                @elseif($latestSession->status === 'completed') background:#eff6ff;color:#1d4ed8;
                                @elseif($latestSession->status === 'rejected') background:#fff1f2;color:#be123c;
                                @else background:#fffbeb;color:#b45309;
                                @endif
                            ">{{ ucfirst($latestSession->status) }}</span>
                        </div>
                        <div class="em-info-row">
                            <span class="em-info-label">Tanggal Proses</span>
                            <span class="em-info-value">{{ $latestSession->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="em-info-row">
                            <span class="em-info-label">Jumlah Cluster</span>
                            <span class="em-info-value">{{ $latestSession->jumlah_cluster }}</span>
                        </div>
                        <div class="em-info-row">
                            <span class="em-info-label">Iterasi Tercapai</span>
                            <span class="em-info-value">{{ $latestSession->iterasi_tercapai }} / {{ $latestSession->max_iterasi }}</span>
                        </div>
                        <div class="em-info-row">
                            <span class="em-info-label">Total Session</span>
                            <span class="em-info-value">{{ $totalSessions }}</span>
                        </div>
                    </div>
                    @else
                    <div class="em-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p>Belum ada session clustering</p>
                        <p style="font-size:0.75rem;margin-top:0.25rem;">Jalankan proses K-Means untuk melihat data</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Centroid Table --}}
            <div class="em-col-7">
                <div class="em-table-wrap">
                    <div class="em-table-header">
                        <h2>Centroid Cluster</h2>
                        @if($latestSession && $latestSession->centroids->count() > 0)
                        <span class="em-badge-sm em-badge-emerald">Normalisasi</span>
                        @endif
                    </div>

                    @if($latestSession && $latestSession->centroids->count() > 0)
                    <div style="overflow-x:auto;" class="em-scroll">
                        <table class="em-table">
                            <thead>
                                <tr>
                                    <th>Cluster</th>
                                    <th>Anggota</th>
                                    <th>Pendapatan</th>
                                    <th>Pekerjaan</th>
                                    <th>Tanggungan</th>
                                    <th>Kond. Rumah</th>
                                    <th>Aset</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestSession->centroids->sortBy('cluster') as $c)
                                <tr>
                                    <td>
                                        <span class="em-cluster-badge {{ $c->label === 'Rendah' ? 'em-cluster-rendah' : ($c->label === 'Sedang' ? 'em-cluster-sedang' : 'em-cluster-tinggi') }}">{{ $c->label }}</span>
                                    </td>
                                    <td style="font-weight:700;color:#111827;">{{ $c->jumlah_anggota }}</td>
                                    <td>{{ number_format($c->centroid_pendapatan, 4) }}</td>
                                    <td>{{ number_format($c->centroid_pekerjaan, 4) }}</td>
                                    <td>{{ number_format($c->centroid_tanggungan, 4) }}</td>
                                    <td>{{ number_format($c->centroid_kondisi_rumah, 4) }}</td>
                                    <td>{{ number_format($c->centroid_aset, 4) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="em-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <p>Belum ada data centroid</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dCtx = document.getElementById('clusterDoughnutChart');
            if (dCtx) {
                new Chart(dCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Rendah', 'Sedang', 'Tinggi'],
                        datasets: [{
                            data: [{{ $clusterDistribution['Rendah'] ?? 0 }}, {{ $clusterDistribution['Sedang'] ?? 0 }}, {{ $clusterDistribution['Tinggi'] ?? 0 }}],
                            backgroundColor: ['rgba(244,63,94,0.85)', 'rgba(245,158,11,0.85)', 'rgba(20,184,166,0.85)'],
                            borderWidth: 3, borderColor: '#fff', hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 16, font: { size: 11, weight: '500' }, usePointStyle: true, pointStyle: 'circle', boxWidth: 8 } },
                            tooltip: { backgroundColor: 'rgba(0,0,0,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 13, weight: 'bold' }, bodyFont: { size: 12 } }
                        }
                    }
                });
            }

            const bCtx = document.getElementById('incomeBarChart');
            if (bCtx) {
                new Chart(bCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Rendah', 'Sedang', 'Tinggi'],
                        datasets: [{
                            label: 'Rata-rata Pendapatan (Rp)',
                            data: [{{ $avgIncomePerCluster['Rendah'] ?? 0 }}, {{ $avgIncomePerCluster['Sedang'] ?? 0 }}, {{ $avgIncomePerCluster['Tinggi'] ?? 0 }}],
                            backgroundColor: ['rgba(244,63,94,0.8)', 'rgba(245,158,11,0.8)', 'rgba(20,184,166,0.8)'],
                            borderColor: ['rgb(244,63,94)', 'rgb(245,158,11)', 'rgb(20,184,166)'],
                            borderWidth: 2, borderRadius: 12, borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: 'rgba(0,0,0,0.9)', padding: 12, cornerRadius: 10, displayColors: false, callbacks: { label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID') } }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { font: { size: 10 }, callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' }, grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false } },
                            x: { ticks: { font: { size: 11, weight: '600' } }, grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</x-filament-panels::page>
