<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClusteringResult extends Model
{
    protected $fillable = ['session_id', 'warga_id', 'cluster', 'label', 'jarak_ke_centroid'];
    protected $casts = ['jarak_ke_centroid' => 'decimal:6'];

    public function session(): BelongsTo { return $this->belongsTo(ClusteringSession::class, 'session_id'); }
    public function warga(): BelongsTo { return $this->belongsTo(Warga::class); }
}
