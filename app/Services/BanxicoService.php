<?php

namespace App\Services;

use App\Models\TipoCambio;
use Illuminate\Support\Facades\Http;

class BanxicoService
{
    // Serie SF43718 = USD/MXN tipo de cambio para solventar obligaciones (FIX)
    const SERIE = 'SF43718';
    const URL   = 'https://www.banxico.org.mx/SieAPIRest/service/v1/series/{serie}/datos/oportuno';

    public function actualizar(): float
    {
        $token = config('services.banxico.token');

        $response = Http::withHeaders([
            'Bmx-Token' => $token,
        ])->timeout(10)->get(str_replace('{serie}', self::SERIE, self::URL));

        if (!$response->successful()) {
            throw new \RuntimeException('Banxico no respondió correctamente: ' . $response->status());
        }

        $data = $response->json();
        $valor = (float) data_get($data, 'bmx.series.0.datos.0.dato');

        if ($valor <= 0) {
            throw new \RuntimeException('TC inválido recibido de Banxico: ' . $valor);
        }

        TipoCambio::create([
            'valor'         => $valor,
            'fuente'        => 'banxico',
            'actualizado_en' => now(),
        ]);

        return $valor;
    }
}
