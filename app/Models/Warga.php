<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    protected $fillable = ['nama_lengkap', 'nik', 'rt_rw', 'pendidikan_id', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id', 'bansos_id'];
    protected $casts = ['pendapatan' => 'decimal:0', 'jumlah_tanggungan' => 'integer'];

    public function pendidikan(): BelongsTo { return $this->belongsTo(MasterPendidikan::class, 'pendidikan_id'); }
    public function kondisiRumah(): BelongsTo { return $this->belongsTo(MasterKondisiRumah::class, 'kondisi_rumah_id'); }
    public function bansos(): BelongsTo { return $this->belongsTo(MasterBansos::class, 'bansos_id'); }
    public function clusteringResults(): HasMany { return $this->hasMany(ClusteringResult::class); }
    public function latestClusteringResult() { return $this->hasOne(ClusteringResult::class)->latestOfMany(); }
    public function classificationQueue(): HasMany { return $this->hasMany(WargaClassificationQueue::class); }
    public function latestClassification() { return $this->hasOne(WargaClassificationQueue::class)->latestOfMany(); }
}
