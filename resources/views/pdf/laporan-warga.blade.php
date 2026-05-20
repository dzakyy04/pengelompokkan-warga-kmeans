<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Warga</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px double #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { font-size: 12px; font-weight: normal; margin-top: 5px; }
        .header .date { font-size: 9px; color: #666; margin-top: 5px; }
        .summary { margin-bottom: 15px; }
        .summary table { border-collapse: collapse; }
        .summary td { padding: 3px 10px 3px 0; font-size: 10px; }
        .summary .label { font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 9px; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 4px 6px; }
        table.data th { background: #059669; color: white; font-weight: bold; font-size: 8px; text-transform: uppercase; text-align: center; }
        table.data td { text-align: left; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: 8px; font-weight: bold; }
        .badge-rendah { background: #fef2f2; color: #dc2626; }
        .badge-sedang { background: #fffbeb; color: #d97706; }
        .badge-tinggi { background: #f0fdf4; color: #16a34a; }
        .footer { margin-top: 25px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Warga</h1>
        <h2>Sistem Pengelompokan Warga K-Means</h2>
        <div class="date">Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</div>
    </div>

    <div class="summary">
        <table>
            <tr><td class="label">Total Warga:</td><td>{{ $wargas->count() }} orang</td></tr>
            @if($search)<tr><td class="label">Filter Pencarian:</td><td>{{ $search }}</td></tr>@endif
            @if($kelompok)<tr><td class="label">Filter Kelompok:</td><td>{{ match($kelompok) { 'Rendah' => 'Ekonomi Rendah', 'Sedang' => 'Ekonomi Menengah', 'Tinggi' => 'Ekonomi Mampu', default => $kelompok } }}</td></tr>@endif
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>Pendidikan KK</th>
                <th>Kondisi Rumah</th>
                <th>Bansos</th>
                <th>Pendapatan</th>
                <th>Tanggungan</th>
                <th>Kelompok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wargas as $i => $w)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $w->nik }}</td>
                <td>{{ $w->nama_lengkap }}</td>
                <td>{{ $w->pendidikan->nama ?? '-' }}</td>
                <td>{{ $w->kondisiRumah->nama ?? '-' }}</td>
                <td>{{ $w->bansos->nama ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($w->pendapatan, 0, ',', '.') }}</td>
                <td class="text-center">{{ $w->jumlah_tanggungan }}</td>
                <td class="text-center">
                    @if($w->latestClusteringResult)
                    <span class="badge badge-{{ strtolower($w->latestClusteringResult->label) }}">
                        {{ match($w->latestClusteringResult->label) { 'Rendah' => 'Rendah', 'Sedang' => 'Menengah', 'Tinggi' => 'Mampu', default => $w->latestClusteringResult->label } }}
                    </span>
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Dokumen ini dihasilkan otomatis oleh Sistem Pengelompokan Warga K-Means pada {{ now()->format('d F Y H:i') }} WIB</div>
</body>
</html>
