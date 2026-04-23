<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'dias_credito'];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}