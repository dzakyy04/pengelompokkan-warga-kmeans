<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WargaClassificationQueue extends Model
{
    protected $table = 'warga_classification_queue';
    protected $fillable = [
        'warga_id', 'base_model_session_id', 'assigned_cluster', 'assigned_label',
        'distance_to_centroid', 'distances_to_all_centroids', 'feature_values',
        'status', 'reviewed_by', 'reviewed_at', 'rejection_reason',
        'revised_cluster', 'revised_label',
    ];
    protected $casts = [
        'distances_to_all_centroids' => 'array',
        'feature_values' => 'array',
        'reviewed_at' => 'datetime',
        'distance_to_centroid' => 'decimal:6',
    ];

    public function warga(): BelongsTo { return $this->belongsTo(Warga::class); }
    public function baseModelSession(): BelongsTo { return $this->belongsTo(ClusteringSession::class, 'base_model_session_id'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}
