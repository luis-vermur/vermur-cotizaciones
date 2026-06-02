<?php

namespace App\Livewire\Pricing;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\TipoCambio;

class DashboardPricing extends Component
{
    use WithPagination;

    public string $filtroEstado    = '';
    public string $filtroAsignado  = '';

    public function updatedFiltroEstado()   { $this->resetPage(); }
    public function updatedFiltroAsignado() { $this->resetPage(); }

    public function render()
    {
        $user = auth()->user();

        $query = Solicitud::with(['cliente', 'asignadoA', 'cotizaciones'])
            ->orderBy('created_at', 'desc');

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

        $solicitudes   = $query->paginate(25);
        $equipoPricing = User::where('rol', 'pricing')->where('activo', true)->get();

        $rows = Solicitud::selectRaw('estado, COUNT(*) as total')
            ->when($user->rol === 'pricing', fn($q) => $q->where(function ($q) use ($user) {
                $q->where('asignado_a', $user->id)->orWhereNull('asignado_a');
            }))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $stats = [
            'nueva'       => (int) ($rows['nueva'] ?? 0),
            'en_revision' => (int) ($rows['en_revision'] ?? 0),
            'cotizada'    => (int) ($rows['cotizada'] ?? 0),
            'enviada'     => (int) ($rows['enviada'] ?? 0),
        ];

        $tc = TipoCambio::orderByDesc('actualizado_en')->first();

        return view('livewire.pricing.dashboard-pricing', compact('solicitudes', 'equipoPricing', 'stats', 'tc'))
            ->layout('layouts.ventas');
    }
}
