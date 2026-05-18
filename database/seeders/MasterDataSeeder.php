<?php
namespace Database\Seeders;
use App\Models\MasterPekerjaan;
use App\Models\MasterKondisiRumah;
use App\Models\MasterAset;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['PNS / ASN / TNI / Polri', 'Pegawai Swasta', 'Wiraswasta / Pedagang', 'Petani / Nelayan', 'Buruh Harian / Buruh Tani', 'Tidak Bekerja / IRT'] as $nama) {
            MasterPekerjaan::firstOrCreate(['nama' => $nama]);
        }

        foreach ([
            ['nama' => 'Milik Sendiri', 'skor' => 3],
            ['nama' => 'Sewa', 'skor' => 2],
            ['nama' => 'Menumpang', 'skor' => 1],
        ] as $data) {
            MasterKondisiRumah::firstOrCreate(['nama' => $data['nama']], $data);
        }

        foreach ([
            ['nama' => 'Mobil', 'estimasi_nilai' => 150000000],
            ['nama' => 'Tanah / Lahan', 'estimasi_nilai' => 100000000],
            ['nama' => 'Motor', 'estimasi_nilai' => 15000000],
            ['nama' => 'Laptop / Komputer', 'estimasi_nilai' => 5000000],
            ['nama' => 'AC', 'estimasi_nilai' => 4000000],
            ['nama' => 'Kulkas', 'estimasi_nilai' => 3000000],
            ['nama' => 'TV', 'estimasi_nilai' => 2000000],
        ] as $data) {
            MasterAset::firstOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
