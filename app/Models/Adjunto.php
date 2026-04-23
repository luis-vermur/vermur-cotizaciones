<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjunto extends Model
{
    protected $fillable = [
        'solicitud_id', 'nombre_archivo', 'ruta', 'tipo',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}