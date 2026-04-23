<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'solicitud_id', 'creado_por', 'folio_coti',
        'tipo_plantilla', 'version',
        'tc', 'margen_deseado', 'costo_ope',
        'costo_total', 'profit_total', 'venta_total', 'margen_real',
        'comision_pct', 'comision_monto',
        'financiamiento_pct', 'financiamiento_monto',
        'profit_real_pct', 'profit_real_monto', 'ganancia_real',
        'notas', 'validez',
    ];

    const PLANTILLAS = [
        'MXN'       => 'Nacional MXN',
        'USD'       => 'Internacional USD',
        'LCL'       => 'Marítimo LCL',
        'terrestre' => 'Terrestre',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function lineas()
    {
        return $this->hasMany(LineaCotizacion::class)->orderBy('orden');
    }

    public function lclDetalle()
    {
        return $this->hasOne(CotizacionLclDetalle::class);
    }
}