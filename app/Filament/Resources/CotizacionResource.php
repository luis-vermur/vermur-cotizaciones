<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CotizacionResource\Pages;
use App\Models\Cotizacion;
use App\Models\Solicitud;
use App\Models\Proveedor;
use App\Services\CotizacionCalculator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CotizacionResource extends Resource
{
    protected static ?string $model = Cotizacion::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Cotizaciones';
    protected static ?string $modelLabel = 'Cotización';
    protected static ?string $pluralModelLabel = 'Cotizaciones';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Solicitud y configuración')
                ->schema([
                    Forms\Components\Select::make('solicitud_id')
                        ->label('Solicitud')
                        ->options(
                            Solicitud::whereIn('estado', ['nueva', 'en_revision', 'cotizada'])
                                ->get()
                                ->mapWithKeys(fn ($s) => [$s->id => "{$s->folio} — {$s->cliente_nombre}"])
                        )
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $solicitud = Solicitud::find($state);
                            if ($solicitud) {
                                $set('_dias_credito', $solicitud->dias_credito);
                            }
                        }),

                    Forms\Components\Select::make('tipo_plantilla')
                        ->label('Tipo de plantilla')
                        ->options(Cotizacion::PLANTILLAS)
                        ->required()
                        ->live(),

                    Forms\Components\TextInput::make('version')
                        ->label('Versión')
                        ->numeric()
                        ->default(1),

                    Forms\Components\TextInput::make('tc')
                        ->label('Tipo de cambio (TC)')
                        ->numeric()
                        ->prefix('$')
                        ->nullable(),

                    Forms\Components\TextInput::make('margen_deseado')
                        ->label('Margen deseado')
                        ->numeric()
                        ->suffix('%')
                        ->nullable(),

                    Forms\Components\TextInput::make('costo_ope')
                        ->label('Costo de operación')
                        ->numeric()
                        ->default(1500)
                        ->prefix('$'),

                    Forms\Components\TextInput::make('validez')
                        ->label('Validez de la cotización')
                        ->placeholder('Ej: 15 días'),

                    // Campo oculto para días de crédito
                    Forms\Components\Hidden::make('_dias_credito')->default(0),
                ])->columns(2),

            // Sección exclusiva LCL
            Forms\Components\Section::make('Detalle LCL')
                ->schema([
                    Forms\Components\TextInput::make('lcl_detalle.pol')->label('POL'),
                    Forms\Components\TextInput::make('lcl_detalle.pod')->label('POD'),
                    Forms\Components\TextInput::make('lcl_detalle.incoterm')->label('Incoterm'),
                    Forms\Components\TextInput::make('lcl_detalle.piezas')->label('Piezas')->numeric(),
                    Forms\Components\TextInput::make('lcl_detalle.peso_tons')->label('Peso (tons)')->numeric(),
                    Forms\Components\TextInput::make('lcl_detalle.medidas_cbm')->label('Medidas (CBM)')->numeric(),
                    Forms\Components\TextInput::make('lcl_detalle.pickup')->label('Pickup')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.despacho_mxn')->label('Despacho MXN')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.maniobras_mxn')->label('Maniobras MXN')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.desconsolidacion')->label('Desconsolidación')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.transfer_fee')->label('Transfer Fee')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.revalidacion')->label('Revalidación')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.transmision')->label('Transmisión')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.admon_fee')->label('Admon Fee')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.recargo_imo')->label('Recargo IMO')->numeric()->prefix('$'),
                    Forms\Components\TextInput::make('lcl_detalle.iva')->label('IVA %')->numeric()->suffix('%'),
                ])
                ->columns(3)
                ->visible(fn (Get $get) => $get('tipo_plantilla') === 'LCL'),

            // Líneas de cotización
            Forms\Components\Section::make('Líneas de cotización')
                ->schema([
                    Forms\Components\Repeater::make('lineas')
                        ->relationship('lineas')
                        ->schema([
                            Forms\Components\Select::make('proveedor_id')
                                ->label('Proveedor')
                                ->options(Proveedor::activos()->orderBy('nombre')->pluck('nombre', 'id'))
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $proveedor = Proveedor::find($state);
                                    if ($proveedor) {
                                        $set('proveedor_nombre', $proveedor->nombre);
                                    }
                                }),

                            Forms\Components\TextInput::make('proveedor_nombre')
                                ->label('Nombre proveedor')
                                ->required(),

                            Forms\Components\TextInput::make('concepto')
                                ->label('Concepto')
                                ->required(),

                            Forms\Components\TextInput::make('costo')
                                ->label('Costo')
                                ->numeric()
                                ->default(0)
                                ->prefix('$')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Get $get, Set $set) =>
                                    self::recalcularLinea($get, $set)
                                ),

                            Forms\Components\TextInput::make('profit')
                                ->label('Profit')
                                ->numeric()
                                ->default(0)
                                ->prefix('$')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Get $get, Set $set) =>
                                    self::recalcularLinea($get, $set)
                                ),

                            Forms\Components\TextInput::make('venta')
                                ->label('Venta')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated(),

                            Forms\Components\TextInput::make('margen')
                                ->label('Margen %')
                                ->numeric()
                                ->suffix('%')
                                ->disabled()
                                ->dehydrated(),

                            Forms\Components\TextInput::make('target')
                                ->label('Target')
                                ->numeric()
                                ->prefix('$')
                                ->nullable(),

                            Forms\Components\TextInput::make('orden')
                                ->label('Orden')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(3)
                        ->orderColumn('orden')
                        ->addActionLabel('+ Agregar línea')
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::recalcularTotales($get, $set)
                        ),
                ]),

            // Totales calculados (solo lectura)
            Forms\Components\Section::make('Resumen financiero')
                ->schema([
                    Forms\Components\TextInput::make('costo_total')
                        ->label('Costo total')->prefix('$')->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('profit_total')
                        ->label('Profit total')->prefix('$')->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('venta_total')
                        ->label('Venta total')->prefix('$')->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('margen_real')
                        ->label('Margen real')->suffix('%')->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('comision_monto')
                        ->label('Comisión (10%)')->prefix('$')->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('financiamiento_monto')
                        ->label('Financiamiento')->prefix('$')->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('profit_real_monto')
                        ->label('Profit real')->prefix('$')->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('ganancia_real')
                        ->label('Ganancia real')->prefix('$')->disabled()->dehydrated(),
                ])
                ->columns(4),

            Forms\Components\Section::make('Notas')
                ->schema([
                    Forms\Components\Textarea::make('notas')
                        ->label('Notas de la cotización')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    // Recalcula venta y margen de una línea individual
    protected static function recalcularLinea(Get $get, Set $set): void
    {
        $costo  = floatval($get('costo') ?? 0);
        $profit = floatval($get('profit') ?? 0);
        $result = CotizacionCalculator::calcLinea($costo, $profit);
        $set('venta',  $result['venta']);
        $set('margen', round($result['margen'] * 100, 2));
    }

    // Recalcula todos los totales del bloque financiero
    protected static function recalcularTotales(Get $get, Set $set): void
    {
        $lineas      = $get('lineas') ?? [];
        $diasCredito = intval($get('_dias_credito') ?? 0);
        $costoOpe    = floatval($get('costo_ope') ?? 1500);

        $lineasCalc = collect($lineas)->map(fn ($l) => [
            'costo'  => floatval($l['costo'] ?? 0),
            'profit' => floatval($l['profit'] ?? 0),
        ])->toArray();

        $totales = CotizacionCalculator::calcTotales($lineasCalc, $diasCredito, $costoOpe);

        $set('costo_total',          $totales['costo_total']);
        $set('profit_total',         $totales['profit_total']);
        $set('venta_total',          $totales['venta_total']);
        $set('margen_real',          round($totales['margen_real'] * 100, 2));
        $set('comision_monto',       $totales['comision_monto']);
        $set('financiamiento_monto', $totales['financiamiento_monto']);
        $set('profit_real_monto',    $totales['profit_real_monto']);
        $set('ganancia_real',        $totales['ganancia_real']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('folio_coti')
                    ->label('Folio')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('solicitud.folio')
                    ->label('Solicitud')
                    ->searchable(),

                Tables\Columns\TextColumn::make('solicitud.cliente_nombre')
                    ->label('Cliente')
                    ->searchable()
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
                    ->money('MXN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ganancia_real')
                    ->label('Ganancia real')
                    ->money('MXN')
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('version')
                    ->label('V')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCotizaciones::route('/'),
            'create' => Pages\CreateCotizacion::route('/create'),
            'edit'   => Pages\EditCotizacion::route('/{record}/edit'),
        ];
    }
}