<?php

namespace App\Livewire\Ventas;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Comentario;

class VerSolicitudVentas extends Component
{
    public int    $solicitudId;
    public string $nuevoComentario = '';

    public function mount(int $solicitud)
    {
        $this->solicitudId = $solicitud;

        $sol = Solicitud::findOrFail($solicitud);
        $rol = auth()->user()->rol;
        if ($rol !== 'supervisor' && $rol !== 'admin' && $sol->creado_por !== auth()->id()) {
            abort(403);
        }
    }

    public function enviarMensaje(): void
    {
        $texto = trim($this->nuevoComentario);
        if (!$texto) return;

        Comentario::create([
            'solicitud_id' => $this->solicitudId,
            'user_id'      => auth()->id(),
            'texto'        => $texto,
            'rol'          => 'ventas',
        ]);

        $this->nuevoComentario = '';
    }

    public function render()
    {
        $solicitud = Solicitud::with([
            'adjuntos',
            'cotizaciones',
            'historial.user',
            'comentarios.user',
            'pallets',
        ])->findOrFail($this->solicitudId);

        return view('livewire.ventas.ver-solicitud-ventas', compact('solicitud'))
            ->layout('layouts.ventas');
    }
}
