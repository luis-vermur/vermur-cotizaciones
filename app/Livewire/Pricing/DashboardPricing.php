<?php

namespace App\Livewire\Pricing;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\User;

class DashboardPricing extends Component
{
    public string $filtroEstado = '';
    public string $filtroAsignado = '';

    public function render()
    {
        $user = auth()->user();

        $query = Solicitud::with(['cliente', 'asignadoA', 'cotizaciones'])
            ->orderBy('created_at', 'desc');

        // Filtro de visibilidad según rol
        if ($user->rol === 'pricing') {
            $query->where(function ($q) use ($user) {
                $q->where('asignado_a', $user->id)
                  ->orWhereNull('asignado_a');
            });
        }

        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->filtroAsignado) {
            $query->where('asignado_a', $this->filtroAsignado);
        }

        $solicitudes  = $query->get();
        $equipoPricing = User::where('rol', 'pricing')->where('activo', true)->get();

        // Stats sin filtros aplicados
        $statsQuery = Solicitud::query();
        if ($user->rol === 'pricing') {
            $statsQuery->where(function ($q) use ($user) {
                $q->where('asignado_a', $user->id)->orWhereNull('asignado_a');
            });
        }
        $todas = $statsQuery->get();
        $stats = [
            'nueva'       => $todas->where('estado', 'nueva')->count(),
            'en_revision' => $todas->where('estado', 'en_revision')->count(),
            'cotizada'    => $todas->where('estado', 'cotizada')->count(),
            'enviada'     => $todas->where('estado', 'enviada')->count(),
        ];

        return view('livewire.pricing.dashboard-pricing', compact('solicitudes', 'equipoPricing', 'stats'))
            ->layout('layouts.ventas');
    }
}