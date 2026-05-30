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

        // Preset pekerjaan berdasarkan status produktivitas
        $presetPekerjaan = Warga::presetPekerjaan();

        $profiles = [
            // Ekonomi rendah – pekerjaan tidak stabil / tidak produktif
            [
                'pekerjaan_status' => ['Tidak Stabil', 'Tidak Produktif'],
                'pendidikan'       => ['Tidak Sekolah', 'SD', 'SMP'],
                'kondisi_rumah'    => ['Menumpang', 'Sewa'],
                'bansos'           => ['PKH atau BLT', 'Sembako'],
                'tanggungan'       => [3, 7],
                'count'            => 18,
            ],
            // Ekonomi menengah – pekerjaan cukup stabil
            [
                'pekerjaan_status' => ['Cukup Stabil'],
                'pendidikan'       => ['SMP', 'SMA', 'Kuliah'],
                'kondisi_rumah'    => ['Sewa', 'Milik Sendiri'],
                'bansos'           => ['Sembako', 'Tidak Menerima'],
                'tanggungan'       => [2, 5],
                'count'            => 17,
            ],
            // Ekonomi mampu – pekerjaan stabil
            [
                'pekerjaan_status' => ['Stabil'],
                'pendidikan'       => ['SMA', 'Kuliah'],
                'kondisi_rumah'    => ['Milik Sendiri'],
                'bansos'           => ['Tidak Menerima'],
                'tanggungan'       => [1, 3],
                'count'            => 15,
            ],
        ];

        $usedNiks = [];
        foreach ($profiles as $profile) {
            // Filter preset pekerjaan yang sesuai status produktivitas profil ini
            $eligiblePresets = array_filter($presetPekerjaan, fn($p) => in_array($p['status'], $profile['pekerjaan_status']));
            $eligiblePresets = array_values($eligiblePresets);

            for ($i = 0; $i < $profile['count']; $i++) {
                do { $nik = $faker->numerify('################'); } while (in_array($nik, $usedNiks));
                $usedNiks[] = $nik;

                // Pilih pekerjaan acak dari preset yang sesuai
                $selectedPreset = $eligiblePresets[array_rand($eligiblePresets)];

                Warga::create([
                    'nama_lengkap'        => $faker->name(),
                    'nik'                 => $nik,
                    'rt_rw'               => sprintf('RT %02d / RW %02d', $faker->numberBetween(1, 10), $faker->numberBetween(1, 5)),
                    'pendidikan_id'       => $pendidikan->where('nama', $faker->randomElement($profile['pendidikan']))->first()->id,
                    'pekerjaan'           => $selectedPreset['pekerjaan'],
                    'status_produktivitas'=> $selectedPreset['status'],
                    'skor_produktivitas'  => $selectedPreset['skor'],
                    'jumlah_tanggungan'   => $faker->numberBetween($profile['tanggungan'][0], $profile['tanggungan'][1]),
                    'kondisi_rumah_id'    => $kondisiRumah->where('nama', $faker->randomElement($profile['kondisi_rumah']))->first()->id,
                    'bansos_id'           => $bansos->where('nama', $faker->randomElement($profile['bansos']))->first()->id,
                ]);
            }
        }
    }
}
