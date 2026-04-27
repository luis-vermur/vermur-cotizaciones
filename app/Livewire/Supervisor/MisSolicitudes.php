<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Solicitud;

class MisSolicitudes extends Component
{
    use WithPagination;

    public function mount()
    {
        if (!in_array(auth()->user()->rol, ['supervisor', 'admin'])) abort(403);
    }

    public function render()
    {
        $stats = Solicitud::statsPorEstado(auth()->id());

        $solicitudes = Solicitud::where('creado_por', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('livewire.supervisor.mis-solicitudes', compact('solicitudes', 'stats'))
            ->layout('layouts.supervisor');
    }
}
