<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pallet extends Model
{
    protected $fillable = [
        'solicitud_id', 'numero',
        'largo_cm', 'ancho_cm', 'alto_cm',
        'peso', 'peso_unidad', 'cubicaje_m3',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}