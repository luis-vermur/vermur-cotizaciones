<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nombre completo')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Correo electrónico')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\Select::make('rol')
                ->label('Rol')
                ->options([
                    'ventas'  => 'Ventas',
                    'pricing' => 'Pricing',
                    'admin'   => 'Administrador',
                ])
                ->required(),

            Forms\Components\Toggle::make('activo')
                ->label('Usuario activo')
                ->default(true),

            Forms\Components\TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation) => $operation === 'create')
                ->minLength(8)
                ->hint('Dejar vacío para no cambiar la contraseña'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rol')
                    ->label('Rol')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'admin'   => 'danger',
                        'pricing' => 'warning',
                        'ventas'  => 'success',
                        default   => 'gray',
                    }),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('solicitudesCreadas_count')
                    ->label('Solicitudes creadas')
                    ->counts('solicitudesCreadas'),

                Tables\Columns\TextColumn::make('solicitudesAsignadas_count')
                    ->label('Asignadas')
                    ->counts('solicitudesAsignadas'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rol')
                    ->label('Rol')
                    ->options([
                        'ventas'  => 'Ventas',
                        'pricing' => 'Pricing',
                        'admin'   => 'Administrador',
                    ]),

                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Estado')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}