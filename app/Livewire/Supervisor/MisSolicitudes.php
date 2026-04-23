<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use App\Models\Solicitud;

class MisSolicitudes extends Component
{
    public function mount()
    {
        if (!in_array(auth()->user()->rol, ['supervisor', 'admin'])) abort(403);
    }

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
            'rechazada'   => $solicitudes->where('estado', 'rechazada')->count(),
        ];

        return view('livewire.supervisor.mis-solicitudes', compact('solicitudes', 'stats'))
            ->layout('layouts.supervisor');
    }
}
