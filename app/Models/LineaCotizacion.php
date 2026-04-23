<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineaCotizacion extends Model
{
    protected $table = 'lineas_cotizacion';

    protected $fillable = [
        'cotizacion_id', 'proveedor_id', 'proveedor_nombre',
        'concepto', 'costo', 'profit', 'venta', 'margen', 'target', 'orden',
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}