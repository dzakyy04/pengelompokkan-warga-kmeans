<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterPekerjaan extends Model
{
    protected $table = 'master_pekerjaan';
    protected $fillable = ['nama'];
    public function wargas(): HasMany { return $this->hasMany(Warga::class, 'pekerjaan_id'); }
}
