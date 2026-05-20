<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterBansos extends Model
{
    protected $table = 'master_bansos';
    protected $fillable = ['nama', 'skor'];
    protected $casts = ['skor' => 'integer'];
    public function wargas(): HasMany { return $this->hasMany(Warga::class, 'bansos_id'); }
}
