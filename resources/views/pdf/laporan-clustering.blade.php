<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Pengelompokan Warga</title>
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
            margin-bottom: 24px;
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

        /* Section */
        .section {
            margin-bottom: 22px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #1a1a1a;
        }

        /* Info Table (key-value) */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 5px 8px;
            border: 1px solid #555;
            font-size: 10px;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            background: #f0f0f0;
            width: 170px;
            color: #1a1a1a;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #555;
            padding: 5px 7px;
            text-align: left;
        }
        .data-table th {
            background: #2d2d2d;
            color: #ffffff;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 0.3px;
        }
        .data-table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }

        /* Cluster border */
        .cluster-rendah { border-left: 4px solid #dc2626; }
        .cluster-sedang { border-left: 4px solid #d97706; }
        .cluster-tinggi { border-left: 4px solid #16a34a; }

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

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        /* Rekomendasi */
        .rekomendasi {
            border: 1px solid #999;
            padding: 8px 12px;
            margin-bottom: 10px;
            font-size: 9px;
            color: #333;
            background: #fafafa;
        }
        .rekomendasi strong {
            display: block;
            margin-bottom: 2px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #1a1a1a;
        }

        /* Page break */
        .page-break { page-break-before: always; }

        /* Tanda Tangan */
        .signature-area {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-area table {
            width: 100%;
        }
        .signature-box {
            text-align: center;
            padding: 10px 15px;
            vertical-align: top;
        }
        .signature-box .tempat-tanggal {
            font-size: 10px;
            margin-bottom: 4px;
        }
        .signature-box .jabatan {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 65px;
        }
        .signature-box .nama {
            font-size: 10px;
            font-weight: bold;
            border-bottom: 1px solid #1a1a1a;
            display: inline-block;
            padding-bottom: 3px;
            min-width: 150px;
        }
        .signature-box .nip {
            font-size: 9px;
            color: #555;
            margin-top: 3px;
        }

        /* Status */
        .status-validated {
            font-weight: bold;
            color: #166534;
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

        /* Catatan */
        .catatan {
            font-size: 8px;
            color: #666;
            margin-top: 4px;
            font-style: italic;
        }
    </style>
</head>
<body>
    {{-- Kop Surat --}}
    <div class="kop-surat">
        <div class="nama-instansi">Pemerintah Desa Sungai Rebo</div>
        <div class="alamat">Jl. Sel., Sungai Pinang, Kec. Rambutan, Kab. Banyuasin, Sumatera Selatan</div>
    </div>
    <div class="kop-border-double"></div>

    {{-- Judul Dokumen --}}
    <div class="judul-dokumen">
        <h1>Laporan Hasil Pengelompokan Warga</h1>
        <div class="nomor">Nomor: LC/{{ str_pad($session->id, 3, '0', STR_PAD_LEFT) }}/{{ $session->created_at->format('m/Y') }}</div>
    </div>

    {{-- I. Informasi Umum --}}
    <div class="section">
        <div class="section-title">I. Informasi Umum</div>
        <table class="info-table">
            <tr>
                <td class="label">Metode</td>
                <td>Pengelompokan Otomatis</td>
                <td class="label">Jumlah Kelompok</td>
                <td>{{ $session->jumlah_cluster }} Kelompok</td>
            </tr>
            <tr>
                <td class="label">Tanggal Proses</td>
                <td>{{ $session->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
                <td class="label">Total Warga Diproses</td>
                <td>{{ $session->results->count() }} orang</td>
            </tr>
            <tr>
                <td class="label">Diproses Oleh</td>
                <td>{{ $session->user->name ?? '-' }}</td>
                <td class="label">Status</td>
                <td class="status-validated">Divalidasi / Disetujui</td>
            </tr>
            <tr>
                <td class="label">Divalidasi Oleh</td>
                <td>{{ $session->validatedByUser->name ?? '-' }}</td>
                <td class="label">Tanggal Validasi</td>
                <td>{{ $session->validated_at ? \Carbon\Carbon::parse($session->validated_at)->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- II. Parameter --}}
    <div class="section">
        <div class="section-title">II. Parameter / Fitur yang Digunakan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:25px;">No</th>
                    <th>Fitur</th>
                    <th>Keterangan</th>
                    <th>Skala</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Pekerjaan (Skor Produktivitas)</td>
                    <td>Status produktivitas pekerjaan kepala keluarga</td>
                    <td>Skor 1–4 (dinormalisasi 0–1)</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>Jumlah Tanggungan</td>
                    <td>Jumlah anggota keluarga yang ditanggung</td>
                    <td>Numerik (dinormalisasi 0–1)</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>Pendidikan Kepala Keluarga</td>
                    <td>Tingkat pendidikan terakhir kepala keluarga</td>
                    <td>Skor ordinal (dinormalisasi 0–1)</td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>Kondisi Rumah</td>
                    <td>Status kepemilikan dan kelayakan rumah</td>
                    <td>Skor ordinal (dinormalisasi 0–1)</td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td>Bantuan Sosial (Bansos)</td>
                    <td>Jenis bantuan sosial yang diterima</td>
                    <td>Skor ordinal (dinormalisasi 0–1)</td>
                </tr>
            </tbody>
        </table>
        <div class="catatan">* Seluruh fitur dinormalisasi menggunakan metode Min-Max Normalization (skala 0–1) sebelum proses clustering.</div>
    </div>

    {{-- III. Centroid --}}
    <div class="section">
        <div class="section-title">III. Nilai Centroid Akhir per Cluster</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Cluster</th>
                    <th>Jumlah Anggota</th>
                    <th>Pekerjaan</th>
                    <th>Tanggungan</th>
                    <th>Pendidikan</th>
                    <th>Kondisi Rumah</th>
                    <th>Bansos</th>
                </tr>
            </thead>
            <tbody>
            @foreach($session->centroids->sortBy('cluster') as $c)
            <tr class="cluster-{{ strtolower($c->label) }}">
                <td><span class="badge badge-{{ strtolower($c->label) }}">{{ $c->label }}</span></td>
                <td class="text-center">{{ $c->jumlah_anggota }} orang</td>
                <td class="text-right">{{ number_format($c->centroid_pekerjaan, 4) }}</td>
                <td class="text-right">{{ number_format($c->centroid_tanggungan, 4) }}</td>
                <td class="text-right">{{ number_format($c->centroid_pendidikan, 4) }}</td>
                <td class="text-right">{{ number_format($c->centroid_kondisi_rumah, 4) }}</td>
                <td class="text-right">{{ number_format($c->centroid_bansos, 4) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div class="catatan">* Nilai centroid ditampilkan dalam skala normalisasi Min-Max (0–1).</div>
    </div>

    {{-- Detail per Cluster --}}
    @php
        $rekomendasi = [
            'Tinggi' => [
                'title' => 'Rekomendasi Bantuan',
                'text' => 'Prioritas utama penerima bantuan sosial berupa bahan pokok, bantuan langsung tunai (BLT), Program Keluarga Harapan (PKH), dan program jaring pengaman sosial lainnya.',
            ],
            'Sedang' => [
                'title' => 'Rekomendasi Program',
                'text' => 'Target program pelatihan keterampilan, bantuan modal usaha mikro (UMKM), program pengembangan kapasitas, dan pendampingan ekonomi produktif.',
            ],
            'Rendah' => [
                'title' => 'Rekomendasi Peran',
                'text' => 'Tidak menjadi prioritas penerima bantuan. Potensi sebagai mentor dan pembina masyarakat, fasilitator pembangunan desa, serta mitra dalam program pemberdayaan ekonomi warga.',
            ],
        ];
        $clusterNames = [
            'Tinggi' => 'Tinggi',
            'Sedang' => 'Sedang',
            'Rendah' => 'Rendah',
        ];
        $romanNumerals = [4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII'];
        $sectionNum = 4;
    @endphp

    @foreach(['Rendah', 'Sedang', 'Tinggi'] as $label)
        @php $members = $session->results->where('label', $label); @endphp
        @if($members->count() > 0)
        <div class="page-break"></div>

        {{-- Repeat Kop on new page --}}
        <div class="kop-surat">
            <div class="nama-instansi">Pemerintah Desa Sungai Rebo</div>
            <div class="alamat">Jl. Sel., Sungai Pinang, Kec. Rambutan, Kab. Banyuasin, Sumatera Selatan</div>
        </div>
        <div class="kop-border-double"></div>

        <div class="section">
            <div class="section-title">{{ $romanNumerals[$sectionNum] }}. Daftar Anggota Cluster — {{ $clusterNames[$label] }} ({{ $members->count() }} Warga)</div>
            <div class="rekomendasi">
                <strong>{{ $rekomendasi[$label]['title'] }}:</strong>
                {{ $rekomendasi[$label]['text'] }}
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:25px;">No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Pendidikan KK</th>
                        <th>Pekerjaan</th>
                        <th>Status Produktivitas</th>
                        <th>Tanggungan</th>
                        <th>Kondisi Rumah</th>
                        <th>Bansos</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($members->values() as $i => $r)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $r->warga->nik }}</td>
                    <td>{{ $r->warga->nama_lengkap }}</td>
                    <td>{{ $r->warga->pendidikan->nama ?? '-' }}</td>
                    <td>{{ $r->warga->pekerjaan ?? '-' }}</td>
                    <td>{{ $r->warga->status_produktivitas ?? '-' }}</td>
                    <td class="text-center">{{ $r->warga->jumlah_tanggungan }}</td>
                    <td>{{ $r->warga->kondisiRumah->nama ?? '-' }}</td>
                    <td>{{ $r->warga->bansos->nama ?? '-' }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @php $sectionNum++; @endphp
        @endif
    @endforeach


    <div class="footer">
        Dokumen Resmi — Dihasilkan oleh Sistem Pengelompokan Warga Desa Sungai Rebo &mdash; {{ now()->translatedFormat('d F Y') }} pukul {{ now()->format('H:i') }} WIB<br>
        Dokumen ini bersifat rahasia dan hanya untuk keperluan internal pemerintah desa.
    </div>
</body>
</html>
