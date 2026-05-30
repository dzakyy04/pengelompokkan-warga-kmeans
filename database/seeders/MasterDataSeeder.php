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
            ['nama' => 'SD',            'skor' => 2],
            ['nama' => 'SMP',           'skor' => 3],
            ['nama' => 'SMA',           'skor' => 4],
            ['nama' => 'Kuliah',        'skor' => 5],
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

        // Master Bansos (skor: semakin tinggi = semakin sejahtera / tidak butuh bansos besar)
        foreach ([
            ['nama' => 'Tidak Menerima', 'skor' => 3],
            ['nama' => 'Sembako', 'skor' => 2],
            ['nama' => 'PKH atau BLT', 'skor' => 1],
        ] as $data) {
            MasterBansos::firstOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
