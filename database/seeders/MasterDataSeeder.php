<?php
namespace Database\Seeders;
use App\Models\MasterPendidikan;
use App\Models\MasterKondisiRumah;
use App\Models\MasterBansos;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Master Pendidikan Kepala Keluarga (skor: semakin tinggi, semakin tinggi tingkat pendidikannya)
        foreach ([
            ['nama' => 'Tidak Sekolah', 'skor' => 1],
            ['nama' => 'SD / Sederajat', 'skor' => 2],
            ['nama' => 'SMP / Sederajat', 'skor' => 3],
            ['nama' => 'SMA / Sederajat', 'skor' => 4],
            ['nama' => 'D3 / Diploma', 'skor' => 5],
            ['nama' => 'S1 / Sarjana', 'skor' => 6],
            ['nama' => 'S2 / S3 / Pascasarjana', 'skor' => 7],
        ] as $data) {
            MasterPendidikan::firstOrCreate(['nama' => $data['nama']], $data);
        }

        // Master Kondisi Rumah
        foreach ([
            ['nama' => 'Milik Sendiri', 'skor' => 3],
            ['nama' => 'Sewa', 'skor' => 2],
            ['nama' => 'Menumpang', 'skor' => 1],
        ] as $data) {
            MasterKondisiRumah::firstOrCreate(['nama' => $data['nama']], $data);
        }

        // Master Bansos (Bantuan Sosial)
        foreach ([
            ['nama' => 'PKH', 'keterangan' => 'Program Keluarga Harapan'],
            ['nama' => 'BLT', 'keterangan' => 'Bantuan Langsung Tunai'],
            ['nama' => 'BPNT / Sembako', 'keterangan' => 'Bantuan Pangan Non-Tunai'],
            ['nama' => 'KIS', 'keterangan' => 'Kartu Indonesia Sehat'],
            ['nama' => 'KIP', 'keterangan' => 'Kartu Indonesia Pintar'],
            ['nama' => 'BSU', 'keterangan' => 'Bantuan Subsidi Upah'],
        ] as $data) {
            MasterBansos::firstOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
