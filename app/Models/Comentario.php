<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = [
        'solicitud_id', 'user_id', 'texto', 'rol', 'resuelto', 'resuelto_en',
    ];

    protected $casts = [
        'resuelto'    => 'boolean',
        'resuelto_en' => 'datetime',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}