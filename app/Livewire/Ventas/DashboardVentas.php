<?php

namespace App\Livewire\Ventas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Solicitud;

class DashboardVentas extends Component
{
    use WithPagination;

    public function render()
    {
        $stats = Solicitud::statsPorEstado(auth()->id());

        $solicitudes = Solicitud::where('creado_por', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('livewire.ventas.dashboard-ventas', compact('solicitudes', 'stats'))
            ->layout('layouts.ventas');
    }
}
