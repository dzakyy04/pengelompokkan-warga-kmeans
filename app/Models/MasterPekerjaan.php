<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPekerjaan extends Model
{
    protected $table = 'master_pekerjaan';

    protected $fillable = ['nama', 'status_produktivitas', 'skor'];

    protected $casts = ['skor' => 'integer'];

    /**
     * Daftar status produktivitas yang tersedia.
     */
    public static function statusProduktivitasList(): array
    {
        return [
            ['status' => 'Tidak Produktif', 'skor' => 1],
            ['status' => 'Tidak Stabil', 'skor' => 2],
            ['status' => 'Cukup Stabil', 'skor' => 3],
            ['status' => 'Stabil', 'skor' => 4],
        ];
    }
}
