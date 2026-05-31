<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->comment('Nama pekerjaan');
            $table->string('status_produktivitas')->comment('Status: Stabil, Cukup Stabil, Tidak Stabil, Tidak Produktif');
            $table->integer('skor')->default(1)->comment('Skor produktivitas: 1-4');
            $table->timestamps();
        });

        // Seed data awal dari preset
        $presets = [
            ['nama' => 'PNS', 'status_produktivitas' => 'Stabil', 'skor' => 4],
            ['nama' => 'Guru', 'status_produktivitas' => 'Stabil', 'skor' => 4],
            ['nama' => 'Pegawai Tetap', 'status_produktivitas' => 'Stabil', 'skor' => 4],
            ['nama' => 'Pedagang', 'status_produktivitas' => 'Cukup Stabil', 'skor' => 3],
            ['nama' => 'Swasta', 'status_produktivitas' => 'Cukup Stabil', 'skor' => 3],
            ['nama' => 'Buruh', 'status_produktivitas' => 'Tidak Stabil', 'skor' => 2],
            ['nama' => 'Nelayan', 'status_produktivitas' => 'Tidak Stabil', 'skor' => 2],
            ['nama' => 'Petani', 'status_produktivitas' => 'Tidak Stabil', 'skor' => 2],
            ['nama' => 'IRT', 'status_produktivitas' => 'Tidak Produktif', 'skor' => 1],
            ['nama' => 'Belum Bekerja', 'status_produktivitas' => 'Tidak Produktif', 'skor' => 1],
        ];

        $now = now();
        foreach ($presets as $preset) {
            DB::table('master_pekerjaan')->insert(array_merge($preset, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('master_pekerjaan');
    }
};
