<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Solicitud;

class MisSolicitudes extends Component
{
    use WithPagination;

    public function render()
    {
        $stats = Solicitud::statsPorEstado(auth()->id());

        $solicitudes = Solicitud::where('creado_por', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('livewire.admin.mis-solicitudes', compact('solicitudes', 'stats'))
            ->layout('layouts.admin');
    }
}
