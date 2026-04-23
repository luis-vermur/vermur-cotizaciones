<?php

namespace App\Services;

class CotizacionCalculator
{
    /**
     * Calcula venta y margen de una línea individual.
     */
    public static function calcLinea(float $costo, float $profit): array
    {
        $venta  = $costo + $profit;
        $margen = $venta > 0 ? $profit / $venta : 0.0;

        return [
            'venta'  => round($venta, 4),
            'margen' => round($margen, 6),
        ];
    }

    /**
     * Calcula todos los totales del bloque PROFIT REAL.
     * Replica exactamente las fórmulas del Excel original.
     *
     * @param array $lineas  Cada elemento debe tener 'costo' y 'profit'
     * @param int   $diasCredito
     * @param float $costoOpe  Costo interno de operación (default $1,500)
     */
    public static function calcTotales(array $lineas, int $diasCredito, float $costoOpe = 1500.0): array
    {
        $costoTotal  = collect($lineas)->sum(fn ($l) => $l['costo'] ?? 0.0);
        $profitTotal = collect($lineas)->sum(fn ($l) => $l['profit'] ?? 0.0);
        $ventaTotal  = $costoTotal + $profitTotal;
        $margenReal  = $ventaTotal > 0 ? $profitTotal / $ventaTotal : 0.0;

        // Financiamiento: 0.05% por día sobre venta total
        // 30d → 1.5%, 45d → 2.25%, 90d → 4.5%, 120d → 6%
        $financiamientoPct   = $diasCredito / 2000.0;
        $financiamientoMonto = $ventaTotal * $financiamientoPct;

        // Comisión fija 10% sobre profit
        $comisionPct   = 0.10;
        $comisionMonto = $profitTotal * $comisionPct;

        $profitRealMonto = $profitTotal - $comisionMonto - $financiamientoMonto;
        $profitRealPct   = $ventaTotal > 0 ? $profitRealMonto / $ventaTotal : 0.0;

        $gananciaReal = $profitRealMonto - $costoOpe;

        return [
            'costo_total'          => round($costoTotal, 4),
            'profit_total'         => round($profitTotal, 4),
            'venta_total'          => round($ventaTotal, 4),
            'margen_real'          => round($margenReal, 6),
            'comision_pct'         => $comisionPct,
            'comision_monto'       => round($comisionMonto, 4),
            'financiamiento_pct'   => round($financiamientoPct, 6),
            'financiamiento_monto' => round($financiamientoMonto, 4),
            'profit_real_pct'      => round($profitRealPct, 6),
            'profit_real_monto'    => round($profitRealMonto, 4),
            'ganancia_real'        => round($gananciaReal, 4),
        ];
    }

    /**
     * Cuánto profit falta para alcanzar el margen deseado.
     */
    public static function calcProfitFaltante(float $costoTotal, float $ventaTotal, float $margenDeseado): float
    {
        if ($margenDeseado >= 1.0) return 0.0;
        $ventaObjetivo = $costoTotal / (1.0 - $margenDeseado);
        return round($ventaObjetivo - $ventaTotal, 4);
    }

    /**
     * Genera folio de solicitud tipo VRM-XXXXAÑO.
     */
    public static function generarFolio(int $numero): string
    {
        $año = date('Y');
        return sprintf('VRM-%04d%s', $numero, $año);
    }

    /**
     * Genera folio de cotización.
     */
    public static function generarFolioCoti(int $solicitudId, int $version): string
    {
        return sprintf('COTI-%d-V%d', $solicitudId, $version);
    }
}