<?php

namespace App\Filament\Widgets;

use App\Models\Solicitud;
use App\Models\Cotizacion;
use App\Models\Cliente;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalSolicitudes  = Solicitud::count();
        $nuevas            = Solicitud::where('estado', 'nueva')->count();
        $enRevision        = Solicitud::where('estado', 'en_revision')->count();
        $cotizadas         = Solicitud::where('estado', 'cotizada')->count();
        $enviadas          = Solicitud::where('estado', 'enviada')->count();

        $totalCotizaciones = Cotizacion::count();
        $gananciaProm      = Cotizacion::whereNotNull('ganancia_real')->avg('ganancia_real');
        $ventaTotal        = Cotizacion::whereNotNull('venta_total')->sum('venta_total');

        return [
            Stat::make('Solicitudes totales', $totalSolicitudes)
                ->description("{$nuevas} nuevas · {$enRevision} en revisión")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Cotizadas / Enviadas', "{$cotizadas} / {$enviadas}")
                ->description('Del total de solicitudes')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total cotizaciones', $totalCotizaciones)
                ->description('Versiones generadas')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),

            Stat::make('Ganancia promedio', '$' . number_format($gananciaProm ?? 0, 2))
                ->description('Por cotización')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Venta acumulada', '$' . number_format($ventaTotal ?? 0, 2))
                ->description('Suma de todas las cotizaciones')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Clientes registrados', Cliente::count())
                ->description('En el sistema')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('gray'),
        ];
    }
}