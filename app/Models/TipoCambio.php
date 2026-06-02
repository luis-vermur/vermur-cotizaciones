<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCambio extends Model
{
    public $timestamps = false;

    protected $table = 'tipo_cambio';

    protected $fillable = ['valor', 'fuente', 'actualizado_en'];

    protected $casts = [
        'actualizado_en' => 'datetime',
        'valor'          => 'float',
    ];

    /**
     * Obtiene el TC vigente. Si no hay registro o tiene más de 24h, intenta actualizar.
     * Devuelve el valor como float.
     */
    public static function vigente(): float
    {
        $registro = static::orderByDesc('actualizado_en')->first();

        if (!$registro || $registro->actualizado_en->diffInHours(now()) >= 24) {
            try {
                app(\App\Services\BanxicoService::class)->actualizar();
                $registro = static::orderByDesc('actualizado_en')->first();
            } catch (\Throwable) {
                // Si falla Banxico, usar el último valor guardado o un fallback
            }
        }

        return $registro?->valor ?? 17.00;
    }
}
