<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Warga</title>
    <style>
        @page {
            margin: 0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            line-height: 1.6;
            padding: 60px 50px 50px 50px;
        }

        /* Kop Surat */
        .kop-surat {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 4px solid #1a1a1a;
            margin-bottom: 6px;
        }
        .kop-surat .nama-instansi {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-surat .alamat {
            font-size: 9px;
            color: #555;
            margin-top: 2px;
        }
        .kop-border-double {
            border-bottom: 1.5px solid #1a1a1a;
            margin-bottom: 20px;
        }

        /* Judul Dokumen */
        .judul-dokumen {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        .judul-dokumen h1 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: underline;
            margin-bottom: 4px;
        }
        .judul-dokumen .nomor {
            font-size: 10px;
            color: #444;
        }

        /* Info Summary */
        .summary {
            margin-bottom: 18px;
        }
        .summary table {
            border-collapse: collapse;
            width: 100%;
        }
        .summary td {
            padding: 4px 8px;
            font-size: 10px;
            vertical-align: top;
        }
        .summary .label {
            font-weight: bold;
            width: 160px;
            color: #1a1a1a;
        }
        .summary .separator {
            width: 10px;
        }

        /* Data Table */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 9px;
        }
        table.data th,
        table.data td {
            border: 1px solid #333;
            padding: 5px 6px;
        }
        table.data th {
            background: #2d2d2d;
            color: #ffffff;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 0.3px;
        }
        table.data tbody tr:nth-child(even) {
            background: #f5f5f5;
        }
        table.data td {
            text-align: left;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-rendah { background: #fecaca; color: #991b1b; }
        .badge-sedang { background: #fde68a; color: #92400e; }
        .badge-tinggi { background: #bbf7d0; color: #166534; }

        /* Keterangan */
        .keterangan {
            margin-top: 14px;
            font-size: 9px;
            color: #333;
            padding: 10px 12px;
            border: 1px solid #ccc;
            background: #fafafa;
        }
        .keterangan strong {
            color: #1a1a1a;
        }

        /* Tanda Tangan */
        .signature-area {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-area table {
            width: 100%;
        }
        .signature-box {
            text-align: center;
            padding: 10px 20px;
            vertical-align: top;
        }
        .signature-box .tempat-tanggal {
            font-size: 10px;
            margin-bottom: 4px;
        }
        .signature-box .jabatan {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 60px;
        }
        .signature-box .nama {
            font-size: 10px;
            font-weight: bold;
            border-bottom: 1px solid #1a1a1a;
            display: inline-block;
            padding-bottom: 3px;
            min-width: 140px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    {{-- Kop Surat --}}
    <div class="kop-surat">
        <div class="nama-instansi">Pemerintah Desa</div>
        <div class="alamat">Alamat: Jl. Desa No. 01 — Telp: (021) 000-0000</div>
    </div>
    <div class="kop-border-double"></div>

    {{-- Judul Dokumen --}}
    <div class="judul-dokumen">
        <h1>Laporan Data Warga</h1>
        <div class="nomor">Sistem Pengelompokan Warga — Metode K-Means Clustering</div>
    </div>

    {{-- Informasi --}}
    <div class="summary">
        <table>
            <tr>
                <td class="label">Total Warga</td>
                <td class="separator">:</td>
                <td>{{ $wargas->count() }} orang</td>
            </tr>
            @if($search)
            <tr>
                <td class="label">Filter Pencarian</td>
                <td class="separator">:</td>
                <td>{{ $search }}</td>
            </tr>
            @endif
            @if($kelompok)
            <tr>
                <td class="label">Filter Kelompok</td>
                <td class="separator">:</td>
                <td>{{ match($kelompok) { 'Rendah' => 'Ekonomi Rendah', 'Sedang' => 'Ekonomi Menengah', 'Tinggi' => 'Ekonomi Mampu', default => $kelompok } }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td class="separator">:</td>
                <td>{{ now()->translatedFormat('l, d F Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- Tabel Data --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>Pendidikan KK</th>
                <th>Pekerjaan</th>
                <th>Status Produktivitas</th>
                <th>Tanggungan</th>
                <th>Kondisi Rumah</th>
                <th>Bansos</th>
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
                <td>{{ $w->pekerjaan ?? '-' }}</td>
                <td>{{ $w->status_produktivitas ?? '-' }}</td>
                <td class="text-center">{{ $w->jumlah_tanggungan }}</td>
                <td>{{ $w->kondisiRumah->nama ?? '-' }}</td>
                <td>{{ $w->bansos->nama ?? '-' }}</td>
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

    {{-- Keterangan --}}
    <div class="keterangan">
        <strong>Keterangan Kelompok Ekonomi:</strong><br>
        &bull; <strong>Rendah</strong> — Ekonomi rendah, prioritas penerima bantuan sosial<br>
        &bull; <strong>Menengah</strong> — Ekonomi menengah, target program pelatihan dan pemberdayaan<br>
        &bull; <strong>Mampu</strong> — Ekonomi mampu, potensi sebagai mentor dan pembina masyarakat
    </div>

    {{-- Tanda Tangan --}}
    <div class="signature-area">
        <table>
            <tr>
                <td class="signature-box" style="text-align: right; padding-right: 0;">
                    <div class="tempat-tanggal">................, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="jabatan">Mengetahui,<br>Kepala Desa</div>
                    <div class="nama">( ............................ )</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Pengelompokan Warga K-Means &mdash; {{ now()->translatedFormat('d F Y') }} pukul {{ now()->format('H:i') }} WIB
    </div>
</body>
</html>
