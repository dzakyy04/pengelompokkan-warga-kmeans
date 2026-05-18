<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClusterCentroid extends Model
{
    protected $fillable = ['session_id', 'cluster', 'label', 'centroid_pendapatan', 'centroid_pekerjaan', 'centroid_tanggungan', 'centroid_kondisi_rumah', 'centroid_aset', 'jumlah_anggota'];
    protected $casts = ['centroid_pendapatan' => 'decimal:6', 'centroid_pekerjaan' => 'decimal:6', 'centroid_tanggungan' => 'decimal:6', 'centroid_kondisi_rumah' => 'decimal:6', 'centroid_aset' => 'decimal:6'];

    public function session(): BelongsTo { return $this->belongsTo(ClusteringSession::class, 'session_id'); }
}
