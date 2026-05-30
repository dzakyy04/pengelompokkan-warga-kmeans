<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClusterCentroid extends Model
{
    protected $fillable = ['session_id', 'cluster', 'label', 'centroid_pekerjaan', 'centroid_tanggungan', 'centroid_pendidikan', 'centroid_kondisi_rumah', 'centroid_bansos', 'jumlah_anggota'];
    protected $casts = [
        'centroid_pekerjaan' => 'decimal:6',
        'centroid_tanggungan' => 'decimal:6',
        'centroid_pendidikan' => 'decimal:6',
        'centroid_kondisi_rumah' => 'decimal:6',
        'centroid_bansos' => 'decimal:6',
    ];

    public function session(): BelongsTo { return $this->belongsTo(ClusteringSession::class, 'session_id'); }
}
