<?php

namespace App\Livewire\Ventas;

use Livewire\Component;
use App\Models\Solicitud;

class DashboardVentas extends Component
{
    public function render()
    {
        $solicitudes = Solicitud::where('creado_por', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'nueva'       => $solicitudes->where('estado', 'nueva')->count(),
            'en_revision' => $solicitudes->where('estado', 'en_revision')->count(),
            'cotizada'    => $solicitudes->where('estado', 'cotizada')->count(),
            'enviada'     => $solicitudes->where('estado', 'enviada')->count(),
        ];

        return view('livewire.ventas.dashboard-ventas', compact('solicitudes', 'stats'))
            ->layout('layouts.ventas');
    }
}
