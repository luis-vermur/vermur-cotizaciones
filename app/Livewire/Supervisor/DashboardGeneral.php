<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Solicitud;
use App\Models\User;

class DashboardGeneral extends Component
{
    use WithPagination;

    public string $busqueda      = '';
    public string $filtroEstado  = '';
    public string $filtroUsuario = '';

    public function mount()
    {
        if (!in_array(auth()->user()->rol, ['supervisor', 'admin'])) abort(403);
    }

    public function updatedBusqueda()     { $this->resetPage(); }
    public function updatedFiltroEstado() { $this->resetPage(); }
    public function updatedFiltroUsuario(){ $this->resetPage(); }

    public function render()
    {
        $stats = Solicitud::statsPorEstado();

        $vendedores = User::whereIn('rol', ['ventas', 'supervisor'])
            ->withCount([
                'solicitudesCreadas',
                'solicitudesCreadas as enviadas_count' => fn($q) => $q->where('estado', 'enviada'),
                'solicitudesCreadas as nuevas_count'   => fn($q) => $q->where('estado', 'nueva'),
                'solicitudesCreadas as revision_count' => fn($q) => $q->where('estado', 'en_revision'),
            ])
            ->having('solicitudes_creadas_count', '>', 0)
            ->orderByDesc('solicitudes_creadas_count')
            ->get();

        $solicitudes = Solicitud::with('creadoPor')
            ->when($this->busqueda, fn($q) => $q->where(function ($q) {
                $q->where('folio', 'like', "%{$this->busqueda}%")
                  ->orWhere('cliente_nombre', 'like', "%{$this->busqueda}%");
            }))
            ->when($this->filtroEstado,   fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroUsuario,  fn($q) => $q->where('creado_por', $this->filtroUsuario))
            ->orderByDesc('created_at')
            ->paginate(20);

        $usuariosVentas = User::whereIn('rol', ['ventas', 'supervisor'])->orderBy('name')->get();

        return view('livewire.supervisor.dashboard-general',
            compact('stats', 'vendedores', 'solicitudes', 'usuariosVentas'))
            ->layout('layouts.supervisor');
    }
}
