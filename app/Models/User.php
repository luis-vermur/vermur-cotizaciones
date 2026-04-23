<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'rol', 'activo',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'activo'            => 'boolean',
        ];
    }

    // Controla quién puede acceder al panel Filament
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->rol, ['admin', 'pricing']);
    }

    // Relaciones
    public function solicitudesCreadas()
    {
        return $this->hasMany(Solicitud::class, 'creado_por');
    }

    public function solicitudesAsignadas()
    {
        return $this->hasMany(Solicitud::class, 'asignado_a');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }
}