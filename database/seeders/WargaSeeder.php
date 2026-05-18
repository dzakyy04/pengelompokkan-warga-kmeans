<?php
namespace Database\Seeders;
use App\Models\MasterBansos;
use App\Models\MasterKondisiRumah;
use App\Models\MasterPendidikan;
use App\Models\Warga;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $pendidikan = MasterPendidikan::all();
        $kondisiRumah = MasterKondisiRumah::all();
        $bansos = MasterBansos::all();
        $faker = \Faker\Factory::create('id_ID');

        $profiles = [
            // Ekonomi rendah: pendidikan rendah, banyak tanggungan, kondisi rumah lemah, banyak menerima bansos
            [
                'pendapatan_range' => [500000, 2000000],
                'pendidikan' => ['Tidak Sekolah', 'SD / Sederajat', 'SMP / Sederajat'],
                'kondisi_rumah' => ['Menumpang', 'Sewa'],
                'bansos_count' => [2, 4],
                'tanggungan' => [3, 7],
                'count' => 18,
            ],
            // Ekonomi menengah
            [
                'pendapatan_range' => [2000000, 5000000],
                'pendidikan' => ['SMP / Sederajat', 'SMA / Sederajat', 'D3 / Diploma'],
                'kondisi_rumah' => ['Sewa', 'Milik Sendiri'],
                'bansos_count' => [0, 2],
                'tanggungan' => [2, 5],
                'count' => 17,
            ],
            // Ekonomi mampu
            [
                'pendapatan_range' => [5000000, 15000000],
                'pendidikan' => ['SMA / Sederajat', 'D3 / Diploma', 'S1 / Sarjana', 'S2 / S3 / Pascasarjana'],
                'kondisi_rumah' => ['Milik Sendiri'],
                'bansos_count' => [0, 1],
                'tanggungan' => [1, 3],
                'count' => 15,
            ],
        ];

        $usedNiks = [];
        foreach ($profiles as $profile) {
            for ($i = 0; $i < $profile['count']; $i++) {
                do { $nik = $faker->numerify('################'); } while (in_array($nik, $usedNiks));
                $usedNiks[] = $nik;

                $warga = Warga::create([
                    'nama_lengkap' => $faker->name(),
                    'nik' => $nik,
                    'rt_rw' => sprintf('RT %02d / RW %02d', $faker->numberBetween(1, 10), $faker->numberBetween(1, 5)),
                    'pendidikan_id' => $pendidikan->where('nama', $faker->randomElement($profile['pendidikan']))->first()->id,
                    'pendapatan' => $faker->numberBetween($profile['pendapatan_range'][0], $profile['pendapatan_range'][1]),
                    'jumlah_tanggungan' => $faker->numberBetween($profile['tanggungan'][0], $profile['tanggungan'][1]),
                    'kondisi_rumah_id' => $kondisiRumah->where('nama', $faker->randomElement($profile['kondisi_rumah']))->first()->id,
                ]);

                $numBansos = $faker->numberBetween($profile['bansos_count'][0], min($profile['bansos_count'][1], $bansos->count()));
                if ($numBansos > 0) {
                    $warga->bansos()->attach($bansos->random($numBansos)->pluck('id')->toArray());
                }
            }
        }
    }
}
