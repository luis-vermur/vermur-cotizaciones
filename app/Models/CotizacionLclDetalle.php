<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionLclDetalle extends Model
{
    protected $table = 'cotizacion_lcl_detalle';

    protected $fillable = [
        'cotizacion_id',
        'pol', 'pod', 'incoterm', 'piezas', 'peso_tons', 'medidas_cbm',
        'pickup', 'despacho_mxn', 'maniobras_mxn', 'desconsolidacion',
        'transfer_fee', 'revalidacion', 'transmision', 'admon_fee',
        'recargo_imo', 'iva_pct', 'total_local', 'iva', 'total_iva',
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}