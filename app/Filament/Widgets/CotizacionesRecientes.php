<?php

namespace App\Filament\Widgets;

use App\Models\Cotizacion;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CotizacionesRecientes extends BaseWidget
{
    protected static ?string $heading = 'Cotizaciones recientes';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Cotizacion::with(['solicitud', 'creadoPor'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('folio_coti')
                    ->label('Folio')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('solicitud.folio')
                    ->label('Solicitud'),

                Tables\Columns\TextColumn::make('solicitud.cliente_nombre')
                    ->label('Cliente')
                    ->limit(30),

                Tables\Columns\TextColumn::make('tipo_plantilla')
                    ->label('Plantilla')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'USD'       => 'info',
                        'MXN'       => 'success',
                        'LCL'       => 'warning',
                        'terrestre' => 'gray',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('venta_total')
                    ->label('Venta total')
                    ->money('MXN'),

                Tables\Columns\TextColumn::make('ganancia_real')
                    ->label('Ganancia real')
                    ->money('MXN')
                    ->color(fn ($state) => ($state ?? 0) > 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('creadoPor.name')
                    ->label('Creado por'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date('d/m/Y'),
            ])
            ->paginated(false);
    }
}