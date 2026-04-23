<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifaSolicitud extends Model
{
    protected $table = 'tarifas_solicitud';

    protected $fillable = [
        'solicitud_id', 'datos_json', 'actualizado_por',
    ];

    protected $casts = [
        'datos_json' => 'array', // Eloquent serializa/deserializa automáticamente
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function actualizadoPor()
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }
}