<?php

namespace App\Helpers;

class Formato
{
    const TRANSPORTE = [
        'maritimo'   => 'Marítimo',
        'aereo'      => 'Aéreo',
        'terrestre'  => 'Terrestre',
        'multimodal' => 'Multimodal',
    ];

    const OPERACION = [
        'importacion' => 'Importación',
        'exportacion' => 'Exportación',
        'nacional'    => 'Nacional',
    ];

    const ESTADO = [
        'nueva'       => 'Nueva',
        'en_revision' => 'En revisión',
        'cotizada'    => 'Cotizada',
        'enviada'     => 'Enviada',
        'rechazada'   => 'Rechazada',
    ];

    const EMBARQUE = [
        'ninguno' => 'Ninguno',
        'FCL'     => 'FCL',
        'LCL'     => 'LCL',
    ];

    public static function transporte(string $val): string
    {
        return self::TRANSPORTE[$val] ?? ucfirst($val);
    }

    public static function operacion(string $val): string
    {
        return self::OPERACION[$val] ?? ucfirst($val);
    }

    public static function estado(string $val): string
    {
        return self::ESTADO[$val] ?? ucfirst($val);
    }
}