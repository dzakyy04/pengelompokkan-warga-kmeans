<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClusteringSession extends Model
{
    protected $fillable = ['user_id', 'jumlah_cluster', 'max_iterasi', 'iterasi_tercapai', 'status', 'catatan_validasi', 'validated_by', 'validated_at'];
    protected $casts = ['validated_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function validatedByUser(): BelongsTo { return $this->belongsTo(User::class, 'validated_by'); }
    public function results(): HasMany { return $this->hasMany(ClusteringResult::class, 'session_id'); }
    public function centroids(): HasMany { return $this->hasMany(ClusterCentroid::class, 'session_id'); }
}
