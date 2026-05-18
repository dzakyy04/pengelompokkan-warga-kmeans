<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }
        if ($panel->getId() === 'kepala-desa') {
            return $this->role === 'kepala_desa';
        }
        return false;
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isKepalaDesa(): bool { return $this->role === 'kepala_desa'; }

    public function clusteringSessions() { return $this->hasMany(ClusteringSession::class); }
}
