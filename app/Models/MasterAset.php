<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterAset extends Model
{
    protected $table = 'master_aset';
    protected $fillable = ['nama', 'estimasi_nilai'];
    protected $casts = ['estimasi_nilai' => 'decimal:0'];
    public function wargas(): BelongsToMany { return $this->belongsToMany(Warga::class, 'warga_asets', 'master_aset_id', 'warga_id'); }
}
