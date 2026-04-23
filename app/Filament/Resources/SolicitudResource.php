<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudResource\Pages;
use App\Models\Solicitud;
use App\Models\Cliente;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class SolicitudResource extends Resource
{
    protected static ?string $model = Solicitud::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Solicitudes';
    protected static ?string $modelLabel = 'Solicitud';
    protected static ?string $pluralModelLabel = 'Solicitudes';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Información General')
                ->schema([
                    Forms\Components\Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(Cliente::orderBy('nombre')->pluck('nombre', 'id'))
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            $cliente = Cliente::find($state);
                            if ($cliente) {
                                $set('cliente_nombre', $cliente->nombre);
                                $set('dias_credito', $cliente->dias_credito);
                            }
                        }),

                    Forms\Components\TextInput::make('cliente_nombre')
                        ->label('Nombre del cliente')
                        ->required(),

                    Forms\Components\TextInput::make('dias_credito')
                        ->label('Días de crédito')
                        ->numeric()
                        ->default(0)
                        ->suffix('días'),

                    Forms\Components\Select::make('asignado_a')
                        ->label('Asignar a Pricing')
                        ->options(
                            User::where('rol', 'pricing')->where('activo', true)
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->nullable()
                        ->placeholder('Sin asignar'),

                    Forms\Components\Select::make('tipo_operacion')
                        ->label('Tipo de operación')
                        ->options([
                            'importacion' => 'Importación',
                            'exportacion' => 'Exportación',
                            'nacional'    => 'Nacional',
                        ])
                        ->required(),

                    Forms\Components\Select::make('tipo_transporte')
                        ->label('Tipo de transporte')
                        ->options([
                            'maritimo'   => 'Marítimo',
                            'aereo'      => 'Aéreo',
                            'terrestre'  => 'Terrestre',
                            'multimodal' => 'Multimodal',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('tipo_mercancia')
                        ->label('Tipo de mercancía')
                        ->required(),

                    Forms\Components\Select::make('incoterm')
                        ->label('Incoterm')
                        ->options([
                            'EXW' => 'EXW',
                            'FOB' => 'FOB',
                            'CIF' => 'CIF',
                            'CFR' => 'CFR',
                            'DAP' => 'DAP',
                            'DDP' => 'DDP',
                            'FCA' => 'FCA',
                            'CPT' => 'CPT',
                            'CIP' => 'CIP',
                            'DPU' => 'DPU',
                            'FAS' => 'FAS',
                        ])
                        ->nullable(),

                    Forms\Components\TextInput::make('pol_aol')
                        ->label('POL / AOL (Origen)'),

                    Forms\Components\TextInput::make('pod_asd')
                        ->label('POD / ASD (Destino)'),
                ])->columns(2),

            Forms\Components\Section::make('Servicios Adicionales')
                ->schema([
                    Forms\Components\Toggle::make('recoleccion')
                        ->label('Recolección')
                        ->live(),
                    Forms\Components\TextInput::make('dir_recoleccion')
                        ->label('Dirección de recolección')
                        ->visible(fn(Get $get) => $get('recoleccion'))
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('entrega')
                        ->label('Entrega')
                        ->live(),
                    Forms\Components\TextInput::make('dir_entrega')
                        ->label('Dirección de entrega')
                        ->visible(fn(Get $get) => $get('entrega'))
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('seguro_mercancia')->label('Seguro de mercancía'),
                    Forms\Components\Toggle::make('requiere_despacho')->label('Requiere despacho aduanal'),
                    Forms\Components\Toggle::make('embalaje')->label('Embalaje'),
                    Forms\Components\Toggle::make('target')->label('Target de precio'),

                    Forms\Components\Toggle::make('financiamiento')
                        ->label('Financiamiento')
                        ->live(),
                    Forms\Components\TextInput::make('dias_financiamiento')
                        ->label('Días de financiamiento')
                        ->numeric()
                        ->visible(fn(Get $get) => $get('financiamiento')),
                ])->columns(2),

            Forms\Components\Section::make('Condiciones Comerciales')
                ->schema([
                    Forms\Components\TextInput::make('volumen_operacion')
                        ->label('Volumen de operación')
                        ->numeric()
                        ->default(1),

                    Forms\Components\TextInput::make('valor_factura')
                        ->label('Valor de factura')
                        ->numeric()
                        ->prefix('$'),

                    Forms\Components\TextInput::make('margen_profit')
                        ->label('Margen de profit deseado')
                        ->numeric()
                        ->suffix('%'),
                ])->columns(3),

            Forms\Components\Section::make('Tipo de Embarque')
                ->schema([
                    Forms\Components\Select::make('tipo_embarque')
                        ->label('Tipo de embarque')
                        ->options([
                            'ninguno' => 'Ninguno',
                            'FCL'     => 'FCL (Full Container Load)',
                            'LCL'     => 'LCL (Less than Container Load)',
                        ])
                        ->default('ninguno')
                        ->live(),

                    // FCL
                    Forms\Components\Fieldset::make('Detalles FCL')
                        ->schema([
                            Forms\Components\Select::make('fcl_contenedor')
                                ->label('Tipo de contenedor')
                                ->options([
                                    '20ST' => '20\' Standard',
                                    '40ST' => '40\' Standard',
                                    '40HC' => '40\' High Cube',
                                    '45HC' => '45\' High Cube',
                                ]),
                            Forms\Components\TextInput::make('fcl_peso')->label('Peso')->numeric(),
                            Forms\Components\Select::make('fcl_peso_unidad')
                                ->label('Unidad de peso')
                                ->options(['kg' => 'kg', 'ton' => 'ton', 'lb' => 'lb']),
                            Forms\Components\Textarea::make('fcl_reqs')->label('Requerimientos especiales')->columnSpanFull(),
                            Forms\Components\Toggle::make('fcl_food_grade')->label('Food Grade'),
                            Forms\Components\Toggle::make('fcl_reforzado')->label('Reforzado'),
                            Forms\Components\Toggle::make('fcl_sobredimension')->label('Sobredimensión'),
                            Forms\Components\Toggle::make('fcl_enlonado')->label('Enlonado'),
                            Forms\Components\Toggle::make('fcl_atmos_controlada')->label('Atmósfera controlada'),
                        ])
                        ->columns(2)
                        ->visible(fn(Get $get) => $get('tipo_embarque') === 'FCL'),

                    // LCL
                    Forms\Components\Fieldset::make('Detalles LCL')
                        ->schema([
                            Forms\Components\TextInput::make('lcl_num_pallets')->label('Número de pallets')->numeric(),
                            Forms\Components\TextInput::make('lcl_cubicaje_total')->label('Cubicaje total (m³)')->numeric(),
                            Forms\Components\Toggle::make('lcl_estibable')->label('Estibable'),
                        ])
                        ->columns(2)
                        ->visible(fn(Get $get) => $get('tipo_embarque') === 'LCL'),
                ]),

            Forms\Components\Section::make('Notas Internas')
                ->schema([
                    Forms\Components\Textarea::make('nota_interna')
                        ->label('Nota interna (solo visible para Pricing y Admin)')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('folio')
                    ->label('Folio')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('cliente_nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->limit(35),

                Tables\Columns\TextColumn::make('tipo_transporte')
                    ->label('Transporte')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => \App\Helpers\Formato::transporte($state))
                    ->color(fn(string $state): string => match ($state) {
                        'maritimo'   => 'info',
                        'aereo'      => 'warning',
                        'terrestre'  => 'success',
                        'multimodal' => 'danger',
                        default      => 'gray',
                    }),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => \App\Helpers\Formato::estado($state))
                    ->color(fn(string $state): string => match ($state) {
                        'nueva'       => 'info',
                        'en_revision' => 'warning',
                        'cotizada'    => 'success',
                        'enviada'     => 'primary',
                        'rechazada'   => 'danger',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('asignadoA.name')
                    ->label('Asignado a')
                    ->default('Sin asignar')
                    ->sortable(),

                Tables\Columns\TextColumn::make('creadoPor.name')
                    ->label('Creado por')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options(Solicitud::ESTADOS),

                SelectFilter::make('tipo_transporte')
                    ->label('Transporte')
                    ->options([
                        'maritimo'   => 'Marítimo',
                        'aereo'      => 'Aéreo',
                        'terrestre'  => 'Terrestre',
                        'multimodal' => 'Multimodal',
                    ]),

                SelectFilter::make('asignado_a')
                    ->label('Asignado a')
                    ->options(
                        User::where('rol', 'pricing')->pluck('name', 'id')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn() => auth()->user()->rol === 'admin'),
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
            'index'  => Pages\ListSolicitudes::route('/'),
            'create' => Pages\CreateSolicitud::route('/create'),
            'edit'   => Pages\EditSolicitud::route('/{record}/edit'),
            'view'   => Pages\ViewSolicitud::route('/{record}'),
        ];
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        // Solo ventas y admin pueden editar solicitudes
        return $user->rol === 'admin';
    }

    public static function canCreate(): bool
    {
        // Solo admin puede crear desde Filament
        // Ventas crea desde su módulo propio
        return auth()->user()->rol === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->rol === 'admin';
    }
}
