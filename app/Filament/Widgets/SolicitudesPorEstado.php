<?php

namespace App\Filament\Widgets;

use App\Models\Solicitud;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SolicitudesPorEstado extends ChartWidget
{
    protected static ?string $heading = 'Solicitudes por estado';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $estados = ['nueva', 'en_revision', 'cotizada', 'enviada', 'rechazada'];
        $counts  = [];

        foreach ($estados as $estado) {
            $counts[] = Solicitud::where('estado', $estado)->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Solicitudes',
                    'data'            => $counts,
                    'backgroundColor' => [
                        '#3d1a8e', // nueva — morado Vermur
                        '#f59e0b', // en_revision — amarillo
                        '#10b981', // cotizada — verde
                        '#3b82f6', // enviada — azul
                        '#e8392a', // rechazada — rojo Vermur
                    ],
                ],
            ],
            'labels' => ['Nueva', 'En revisión', 'Cotizada', 'Enviada', 'Rechazada'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}