<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterBansos extends Model
{
    protected $table = 'master_bansos';
    protected $fillable = ['nama', 'keterangan'];
    public function wargas(): BelongsToMany { return $this->belongsToMany(Warga::class, 'warga_bansos', 'master_bansos_id', 'warga_id'); }
}
