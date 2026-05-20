<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Clustering</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px double #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { font-size: 13px; font-weight: normal; margin-top: 5px; }
        .header .date { font-size: 10px; color: #666; margin-top: 5px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; background: #2563eb; color: white; padding: 6px 12px; margin-bottom: 10px; border-radius: 3px; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .summary-table th, .summary-table td { border: 1px solid #ddd; padding: 6px 10px; text-align: left; }
        .summary-table th { background: #f3f4f6; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .cluster-rendah { border-left: 4px solid #ef4444; }
        .cluster-sedang { border-left: 4px solid #f59e0b; }
        .cluster-tinggi { border-left: 4px solid #22c55e; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .badge-rendah { background: #fef2f2; color: #dc2626; }
        .badge-sedang { background: #fffbeb; color: #d97706; }
        .badge-tinggi { background: #f0fdf4; color: #16a34a; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .rekomendasi { background: #f0f9ff; border: 1px solid #bae6fd; padding: 8px 12px; border-radius: 4px; margin-bottom: 10px; font-size: 10px; }
        .page-break { page-break-before: always; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        .signature-area { margin-top: 40px; }
        .signature-area table { width: 100%; }
        .signature-box { text-align: center; padding: 20px; }
        .signature-box .title { font-size: 10px; font-weight: bold; margin-bottom: 60px; }
        .signature-box .name { font-size: 11px; font-weight: bold; border-top: 1px solid #333; display: inline-block; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Hasil Pengelompokan Warga</h1>
        <h2>Metode K-Means Clustering</h2>
        <div class="date">Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</div>
    </div>

    <div class="section">
        <div class="section-title">Ringkasan</div>
        <table class="summary-table">
            <tr><td><strong>Total Warga</strong></td><td>{{ $session->results->count() }}</td><td><strong>Jumlah Cluster</strong></td><td>{{ $session->jumlah_cluster }}</td></tr>
            <tr><td><strong>Tanggal Proses</strong></td><td>{{ $session->created_at->format('d M Y H:i') }}</td><td><strong>Status</strong></td><td style="color:#16a34a;font-weight:bold">Validated</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Fitur K-Means yang Digunakan</div>
        <table class="summary-table">
            <thead><tr><th>No</th><th>Fitur</th><th>Keterangan</th></tr></thead>
            <tbody>
                <tr><td class="text-center">1</td><td>Pendapatan</td><td>Pendapatan rata-rata per bulan (Rupiah)</td></tr>
                <tr><td class="text-center">2</td><td>Jumlah Tanggungan</td><td>Jumlah anggota keluarga yang ditanggung</td></tr>
                <tr><td class="text-center">3</td><td>Pendidikan Kepala Keluarga</td><td>Skor tingkat pendidikan kepala keluarga (semakin tinggi, semakin baik)</td></tr>
                <tr><td class="text-center">4</td><td>Kondisi Rumah</td><td>Skor kelayakan kondisi rumah (1=Menumpang, 3=Milik Sendiri)</td></tr>
                <tr><td class="text-center">5</td><td>Bansos</td><td>Skor jenis bansos yang diterima (3=Tidak Menerima, 2=Sembako, 1=PKH/BLT)</td></tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Nilai Centroid per Cluster</div>
        <table class="summary-table">
            <thead><tr><th>Cluster</th><th>Anggota</th><th>Pendapatan</th><th>Tanggungan</th><th>Pendidikan</th><th>Kondisi Rumah</th><th>Bansos</th></tr></thead>
            <tbody>
            @foreach($session->centroids->sortBy('cluster') as $c)
            <tr class="cluster-{{ strtolower($c->label) }}">
                <td><span class="badge badge-{{ strtolower($c->label) }}">{{ $c->label }}</span></td>
                <td class="text-center">{{ $c->jumlah_anggota }}</td>
                <td class="text-right">{{ number_format($c->centroid_pendapatan, 4) }}</td>
                <td class="text-right">{{ number_format($c->centroid_tanggungan, 4) }}</td>
                <td class="text-right">{{ number_format($c->centroid_pendidikan, 4) }}</td>
                <td class="text-right">{{ number_format($c->centroid_kondisi_rumah, 4) }}</td>
                <td class="text-right">{{ number_format($c->centroid_bansos, 4) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <p style="font-size:9px;color:#999;">* Nilai centroid dalam skala normalisasi Min-Max (0-1)</p>
    </div>

    @php
        $rekomendasi = [
            'Rendah' => 'Rekomendasi: Bantuan bahan pokok, bantuan uang tunai, program jaring pengaman sosial.',
            'Sedang' => 'Rekomendasi: Pelatihan keterampilan, bantuan modal UMKM, program pengembangan kapasitas.',
            'Tinggi' => 'Rekomendasi: Program mentor dan pembina masyarakat, fasilitator pembangunan desa.',
        ];
    @endphp

    @foreach(['Rendah', 'Sedang', 'Tinggi'] as $label)
        @php $members = $session->results->where('label', $label); @endphp
        @if($members->count() > 0)
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">Cluster {{ $label }} — {{ $members->count() }} Warga</div>
            <div class="rekomendasi">{{ $rekomendasi[$label] }}</div>
            <table class="summary-table">
                <thead><tr><th>No</th><th>NIK</th><th>Nama</th><th>Pendidikan KK</th><th>Pendapatan</th><th>Tanggungan</th><th>Kondisi Rumah</th><th>Bansos</th></tr></thead>
                <tbody>
                @foreach($members->values() as $i => $r)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $r->warga->nik }}</td>
                    <td>{{ $r->warga->nama_lengkap }}</td>
                    <td>{{ $r->warga->pendidikan->nama ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($r->warga->pendapatan, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $r->warga->jumlah_tanggungan }}</td>
                    <td>{{ $r->warga->kondisiRumah->nama ?? '-' }}</td>
                    <td>{{ $r->warga->bansos->nama ?? '-' }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @endforeach

    <div class="signature-area">
        <table><tr>
            <td class="signature-box"><div class="title">Administrator</div><div class="name">{{ $session->user->name ?? '_______________' }}</div></td>
            <td class="signature-box"><div class="title">Kepala Desa</div><div class="name">{{ $session->validatedByUser->name ?? '_______________' }}</div></td>
        </tr></table>
    </div>

    <div class="footer">Dokumen ini dihasilkan otomatis oleh Sistem Pengelompokan Warga K-Means pada {{ now()->format('d F Y H:i') }} WIB</div>
</body>
</html>
