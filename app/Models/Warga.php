<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    protected $fillable = ['nama_lengkap', 'nik', 'rt_rw', 'pekerjaan_id', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id'];
    protected $casts = ['pendapatan' => 'decimal:0', 'jumlah_tanggungan' => 'integer'];

    public function pekerjaan(): BelongsTo { return $this->belongsTo(MasterPekerjaan::class, 'pekerjaan_id'); }
    public function kondisiRumah(): BelongsTo { return $this->belongsTo(MasterKondisiRumah::class, 'kondisi_rumah_id'); }
    public function asets(): BelongsToMany { return $this->belongsToMany(MasterAset::class, 'warga_asets', 'warga_id', 'master_aset_id'); }
    public function clusteringResults(): HasMany { return $this->hasMany(ClusteringResult::class); }
    public function latestClusteringResult() { return $this->hasOne(ClusteringResult::class)->latestOfMany(); }
    public function getTotalNilaiAsetAttribute(): float { return $this->asets->sum('estimasi_nilai'); }
}
