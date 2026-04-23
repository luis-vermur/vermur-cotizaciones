<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores'; 
    
    protected $fillable = ['nombre', 'terminos_pago', 'correo', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function lineas()
    {
        return $this->hasMany(LineaCotizacion::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}