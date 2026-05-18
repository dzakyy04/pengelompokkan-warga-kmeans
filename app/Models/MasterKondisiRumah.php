<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterKondisiRumah extends Model
{
    protected $table = 'master_kondisi_rumah';
    protected $fillable = ['nama', 'skor'];
    public function wargas(): HasMany { return $this->hasMany(Warga::class, 'kondisi_rumah_id'); }
}
