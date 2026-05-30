<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClusteringExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $session;
    protected $row = 0;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function collection()
    {
        // Sort results logically
        return $this->session->results->sortBy(fn($r) => match($r->label) { 
            'Rendah' => 0, 'Sedang' => 1, 'Tinggi' => 2, default => 3 
        });
    }

    public function headings(): array
    {
        return ['No', 'NIK', 'Nama Lengkap', 'Pendidikan KK', 'Pekerjaan', 'Status Produktivitas', 'Tanggungan', 'Kondisi Rumah', 'Bansos', 'Kelompok'];
    }

    public function map($result): array
    {
        $this->row++;

        $kelompok = match($result->label) {
            'Rendah' => 'Ekonomi Rendah',
            'Sedang' => 'Ekonomi Menengah',
            'Tinggi' => 'Ekonomi Mampu',
            default => $result->label,
        };

        $warga = $result->warga;

        return [
            $this->row,
            "'" . ($warga->nik ?? '-'),
            $warga->nama_lengkap ?? '-',
            $warga->pendidikan->nama ?? '-',
            $warga->pekerjaan ?? '-',
            $warga->status_produktivitas ?? '-',
            $warga->jumlah_tanggungan ?? '0',
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
        return 'Hasil Pengelompokan';
    }
}
