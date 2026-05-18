<?php
namespace Database\Seeders;
use App\Models\MasterAset;
use App\Models\MasterKondisiRumah;
use App\Models\MasterPekerjaan;
use App\Models\Warga;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $pekerjaan = MasterPekerjaan::all();
        $kondisiRumah = MasterKondisiRumah::all();
        $asets = MasterAset::all();
        $faker = \Faker\Factory::create('id_ID');

        $profiles = [
            ['pendapatan_range' => [500000, 2000000], 'pekerjaan' => ['Buruh Harian / Buruh Tani', 'Tidak Bekerja / IRT'], 'kondisi_rumah' => ['Menumpang', 'Sewa'], 'aset_count' => [0, 1], 'tanggungan' => [3, 7], 'count' => 18],
            ['pendapatan_range' => [2000000, 5000000], 'pekerjaan' => ['Petani / Nelayan', 'Wiraswasta / Pedagang'], 'kondisi_rumah' => ['Sewa', 'Milik Sendiri'], 'aset_count' => [1, 3], 'tanggungan' => [2, 5], 'count' => 17],
            ['pendapatan_range' => [5000000, 15000000], 'pekerjaan' => ['PNS / ASN / TNI / Polri', 'Pegawai Swasta'], 'kondisi_rumah' => ['Milik Sendiri'], 'aset_count' => [3, 6], 'tanggungan' => [1, 3], 'count' => 15],
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
                    'pekerjaan_id' => $pekerjaan->where('nama', $faker->randomElement($profile['pekerjaan']))->first()->id,
                    'pendapatan' => $faker->numberBetween($profile['pendapatan_range'][0], $profile['pendapatan_range'][1]),
                    'jumlah_tanggungan' => $faker->numberBetween($profile['tanggungan'][0], $profile['tanggungan'][1]),
                    'kondisi_rumah_id' => $kondisiRumah->where('nama', $faker->randomElement($profile['kondisi_rumah']))->first()->id,
                ]);

                $numAsets = $faker->numberBetween($profile['aset_count'][0], min($profile['aset_count'][1], $asets->count()));
                if ($numAsets > 0) {
                    $warga->asets()->attach($asets->random($numAsets)->pluck('id')->toArray());
                }
            }
        }
    }
}
