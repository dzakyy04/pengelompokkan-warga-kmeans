<?php

namespace App\Exports;

use App\Models\Warga;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WargaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $wargas;
    protected $row = 0;

    public function __construct($wargas = null)
    {
        $this->wargas = $wargas;
    }

    public function collection()
    {
        if ($this->wargas) {
            return $this->wargas;
        }

        return Warga::with(['pendidikan', 'kondisiRumah', 'bansos', 'latestClusteringResult'])
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'NIK', 'Nama Lengkap', 'Pendidikan KK', 'Pekerjaan', 'Status Produktivitas', 'Tanggungan', 'Kondisi Rumah', 'Bansos', 'Kelompok'];
    }

    public function map($warga): array
    {
        $this->row++;

        $kelompok = '-';
        if ($warga->latestClusteringResult) {
            $kelompok = match($warga->latestClusteringResult->label) {
                'Rendah' => 'Ekonomi Rendah',
                'Sedang' => 'Ekonomi Menengah',
                'Tinggi' => 'Ekonomi Mampu',
                default => $warga->latestClusteringResult->label,
            };
        }

        return [
            $this->row,
            "'" . $warga->nik,
            $warga->nama_lengkap,
            $warga->pendidikan->nama ?? '-',
            $warga->pekerjaan ?? '-',
            $warga->status_produktivitas ?? '-',
            $warga->jumlah_tanggungan,
            $warga->kondisiRumah->nama ?? '-',
            $warga->bansos->nama ?? '-',
            $kelompok,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 25,
            'D' => 15,
            'E' => 18,
            'F' => 18,
            'G' => 12,
            'H' => 15,
            'I' => 18,
            'J' => 18,
        ];
    }

    public function title(): string
    {
        return 'Data Warga';
    }
}
