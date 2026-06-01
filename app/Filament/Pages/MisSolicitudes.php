<?php

namespace App\Filament\Pages;

use App\Models\Solicitud;
use Filament\Pages\Page;

class MisSolicitudes extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Mis Solicitudes';
    protected static ?string $title           = 'Mis Solicitudes';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int    $navigationSort  = 2;
    protected static string  $view            = 'filament.pages.mis-solicitudes';

    public function getViewData(): array
    {
        $userId = auth()->id();

        $solicitudes = Solicitud::where('creado_por', $userId)
            ->orderByDesc('created_at')
            ->paginate(25);

        $stats = Solicitud::statsPorEstado($userId);

        return compact('solicitudes', 'stats');
    }
}
