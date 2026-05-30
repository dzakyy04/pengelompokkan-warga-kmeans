<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    protected $fillable = ['nama_lengkap', 'nik', 'rt_rw', 'pekerjaan', 'status_produktivitas', 'skor_produktivitas', 'pendidikan_id', 'jumlah_tanggungan', 'kondisi_rumah_id', 'bansos_id'];
    protected $casts = ['jumlah_tanggungan' => 'integer', 'skor_produktivitas' => 'integer'];

    /**
     * Daftar pekerjaan preset beserta status dan skor produktivitasnya.
     */
    public static function presetPekerjaan(): array
    {
        return [
            ['pekerjaan' => 'PNS',            'status' => 'Stabil',           'skor' => 4],
            ['pekerjaan' => 'Guru',           'status' => 'Stabil',           'skor' => 4],
            ['pekerjaan' => 'Pegawai Tetap',  'status' => 'Stabil',           'skor' => 4],
            ['pekerjaan' => 'Pedagang',       'status' => 'Cukup Stabil',     'skor' => 3],
            ['pekerjaan' => 'Swasta',         'status' => 'Cukup Stabil',     'skor' => 3],
            ['pekerjaan' => 'Buruh',          'status' => 'Tidak Stabil',     'skor' => 2],
            ['pekerjaan' => 'Nelayan',        'status' => 'Tidak Stabil',     'skor' => 2],
            ['pekerjaan' => 'Petani',         'status' => 'Tidak Stabil',     'skor' => 2],
            ['pekerjaan' => 'IRT',            'status' => 'Tidak Produktif',  'skor' => 1],
            ['pekerjaan' => 'Belum Bekerja',  'status' => 'Tidak Produktif',  'skor' => 1],
        ];
    }

    /**
     * Daftar status produktivitas beserta skornya.
     */
    public static function statusProduktivitas(): array
    {
        return [
            ['status' => 'Stabil',          'skor' => 4],
            ['status' => 'Cukup Stabil',    'skor' => 3],
            ['status' => 'Tidak Stabil',    'skor' => 2],
            ['status' => 'Tidak Produktif', 'skor' => 1],
        ];
    }

    public function pendidikan(): BelongsTo { return $this->belongsTo(MasterPendidikan::class, 'pendidikan_id'); }
    public function kondisiRumah(): BelongsTo { return $this->belongsTo(MasterKondisiRumah::class, 'kondisi_rumah_id'); }
    public function bansos(): BelongsTo { return $this->belongsTo(MasterBansos::class, 'bansos_id'); }
    public function clusteringResults(): HasMany { return $this->hasMany(ClusteringResult::class); }
    public function latestClusteringResult() { return $this->hasOne(ClusteringResult::class)->latestOfMany(); }
    public function classificationQueue(): HasMany { return $this->hasMany(WargaClassificationQueue::class); }
    public function latestClassification() { return $this->hasOne(WargaClassificationQueue::class)->latestOfMany(); }
}
