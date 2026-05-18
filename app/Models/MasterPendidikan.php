<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterPendidikan extends Model
{
    protected $table = 'master_pendidikan';
    protected $fillable = ['nama', 'skor'];
    protected $casts = ['skor' => 'integer'];
    public function wargas(): HasMany { return $this->hasMany(Warga::class, 'pendidikan_id'); }
}
