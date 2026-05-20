<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClusteringSession extends Model
{
    protected $fillable = ['user_id', 'jumlah_cluster', 'max_iterasi', 'iterasi_tercapai', 'status', 'catatan_validasi', 'validated_by', 'validated_at', 'is_base_model', 'normalization_params'];
    protected $casts = ['validated_at' => 'datetime', 'normalization_params' => 'array', 'is_base_model' => 'boolean'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function validatedByUser(): BelongsTo { return $this->belongsTo(User::class, 'validated_by'); }
    public function results(): HasMany { return $this->hasMany(ClusteringResult::class, 'session_id'); }
    public function centroids(): HasMany { return $this->hasMany(ClusterCentroid::class, 'session_id'); }
    public function classificationQueue(): HasMany { return $this->hasMany(WargaClassificationQueue::class, 'base_model_session_id'); }

    public function scopeBaseModel($query) { return $query->where('is_base_model', true); }

    public static function getActiveBaseModel()
    {
        return static::where('is_base_model', true)->where('status', 'validated')->latest()->first();
    }
}
