<x-filament-panels::page>
    <style>
        .em-stat-card {
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            padding: 1.5rem; color: #fff;
            transition: all 0.3s ease;
            position: relative; overflow: hidden; height: 100%;
        }
        .em-stat-card:hover { box-shadow: 0 20px 40px -8px rgba(0,0,0,0.15); transform: scale(1.02); }
        .em-stat-card .em-circle { position: absolute; top: 0; right: 0; width: 6rem; height: 6rem; background: rgba(255,255,255,0.1); border-radius: 9999px; margin-right: -3rem; margin-top: -3rem; }
        .em-stat-card .em-inner { position: relative; z-index: 10; }
        .em-stat-card .em-icon-box { width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem; }
        .em-stat-card .em-icon-box svg { width: 1.5rem; height: 1.5rem; }
        .em-stat-card .em-value { font-size: 2.25rem; font-weight: 700; margin-bottom: 0.25rem; line-height: 1; }
        .em-stat-card .em-value-sm { font-size: 1.125rem; font-weight: 700; margin-bottom: 0.25rem; }
        .em-stat-card .em-label { font-size: 0.875rem; font-weight: 600; opacity: 0.9; }
        .em-stat-card .em-desc { font-size: 0.75rem; opacity: 0.7; margin-top: 0.25rem; display: block; }
        .em-card-emerald { background: linear-gradient(135deg, #10b981 0%, #047857 100%); }
        .em-card-cyan    { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
        .em-card-amber   { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .em-warning-box { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 1.5rem; padding: 1.5rem; border: 1px solid #fde68a; display: flex; align-items: center; gap: 0.75rem; }
        .em-warning-icon { width: 2.5rem; height: 2.5rem; flex-shrink: 0; background: rgba(245,158,11,0.15); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; }
        .em-warning-icon svg { width: 1.25rem; height: 1.25rem; color: #d97706; }
        .em-table-wrap { background: #fff; border-radius: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid rgba(203,213,225,0.7); overflow: hidden; transition: all 0.3s ease; }
        .em-table-wrap:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .em-table-header { padding: 1.25rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; }
        .em-table-header h3 { font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0; }
        .em-badge-sm { padding: 0.375rem 0.75rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 600; }
        .em-badge-emerald { background: #ecfdf5; color: #047857; }
        .em-table { width: 100%; font-size: 0.875rem; border-collapse: collapse; }
        .em-table thead { background: #f8fafc; }
        .em-table th { padding: 0.75rem 1rem; font-weight: 600; font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; text-align: center; }
        .em-table th:first-child { text-align: left; }
        .em-table td { padding: 0.75rem 1rem; text-align: center; color: #4b5563; }
        .em-table td:first-child { text-align: left; }
        .em-table tbody tr { border-top: 1px solid #e5e7eb; transition: background 0.15s ease; }
        .em-table tbody tr:hover { background: rgba(236,253,245,0.5); }
        .em-cluster-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 600; }
        .em-cluster-rendah { background: #fff1f2; color: #be123c; }
        .em-cluster-sedang { background: #fffbeb; color: #b45309; }
        .em-cluster-tinggi { background: #f0fdfa; color: #0f766e; }
        .em-grid-3 { display: grid; grid-template-columns: repeat(1, 1fr); gap: 1rem; }
        @media (min-width: 768px) { .em-grid-3 { grid-template-columns: repeat(3, 1fr); } }
        .em-animate > * { animation: emFadeUp 0.4s ease-out both; }
        .em-animate > *:nth-child(2) { animation-delay: 0.1s; }
        @keyframes emFadeUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="em-animate" style="display:flex;flex-direction:column;gap:1.5rem;">
        {{-- Stats --}}
        <div class="em-grid-3">
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

            @if($latestSession)
            <div class="em-stat-card em-card-cyan">
                <div class="em-circle"></div>
                <div class="em-inner">
                    <div class="em-icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    </div>
                    <div class="em-value">#{{ $latestSession->id }}</div>
                    <div class="em-label">Session Terakhir</div>
                    <span class="em-desc">Status: <strong>{{ ucfirst($latestSession->status) }}</strong></span>
                </div>
            </div>

            <div class="em-stat-card em-card-amber">
                <div class="em-circle"></div>
                <div class="em-inner">
                    <div class="em-icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="em-value-sm">{{ $latestSession->created_at->format('d M Y H:i') }}</div>
                    <div class="em-label">Tanggal Proses</div>
                    <span class="em-desc">Iterasi: {{ $latestSession->iterasi_tercapai ?? '-' }}</span>
                </div>
            </div>
            @else
            <div style="grid-column: span 2;">
                <div class="em-warning-box">
                    <div class="em-warning-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <p style="color:#92400e;font-size:0.875rem;">Belum ada hasil clustering. Klik tombol <strong>"Proses K-Means"</strong> untuk memulai.</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Results Table --}}
        @if($latestSession && $latestSession->centroids->count() > 0)
        <div class="em-table-wrap">
            <div class="em-table-header">
                <h3>Centroid Cluster (Normalisasi)</h3>
                <span class="em-badge-sm em-badge-emerald">{{ $latestSession->centroids->count() }} Cluster</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="em-table">
                    <thead>
                        <tr>
                            <th>Cluster</th><th>Anggota</th><th>Pendapatan</th><th>Pekerjaan</th><th>Tanggungan</th><th>Kond. Rumah</th><th>Aset</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestSession->centroids->sortBy('cluster') as $c)
                        <tr>
                            <td><span class="em-cluster-badge {{ $c->label === 'Rendah' ? 'em-cluster-rendah' : ($c->label === 'Sedang' ? 'em-cluster-sedang' : 'em-cluster-tinggi') }}">{{ $c->label }}</span></td>
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
        </div>
        @endif
    </div>
</x-filament-panels::page>
