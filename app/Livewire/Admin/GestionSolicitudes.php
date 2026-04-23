<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\HistorialEstado;

class GestionSolicitudes extends Component
{
    use WithPagination;

    public string  $busqueda       = '';
    public string  $filtroEstado   = '';
    public string  $filtroTransporte = '';

    // Modal gestionar
    public bool    $mostrarModal   = false;
    public ?int    $solicitudId    = null;
    public string  $nuevoEstado    = '';
    public ?int    $asignadoA      = null;
    public string  $motivo         = '';

    public function mount()
    {
        if (auth()->user()->rol !== 'admin') abort(403);
    }

    public function updatedBusqueda()    { $this->resetPage(); }
    public function updatedFiltroEstado(){ $this->resetPage(); }

    public function abrirGestionar(int $id)
    {
        $sol = Solicitud::findOrFail($id);
        $this->solicitudId  = $id;
        $this->nuevoEstado  = $sol->estado;
        $this->asignadoA    = $sol->asignado_a;
        $this->motivo       = '';
        $this->mostrarModal = true;
    }

    public function guardarGestion()
    {
        $this->validate([
            'nuevoEstado' => 'required|in:nueva,en_revision,cotizada,enviada,rechazada',
            'motivo'      => $this->nuevoEstado === 'rechazada' ? 'required|string|min:5' : 'nullable',
        ]);

        $sol = Solicitud::findOrFail($this->solicitudId);
        $estadoAnterior = $sol->estado;

        $sol->update([
            'estado'      => $this->nuevoEstado,
            'asignado_a'  => $this->asignadoA ?: null,
        ]);

        if ($estadoAnterior !== $this->nuevoEstado) {
            HistorialEstado::create([
                'solicitud_id'    => $sol->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => $this->nuevoEstado,
                'user_id'         => auth()->id(),
                'motivo'          => $this->motivo ?: 'Cambio manual por Admin',
            ]);
        }

        $this->mostrarModal = false;
        session()->flash('success', "Solicitud {$sol->folio} actualizada.");
    }

    public function render()
    {
        $solicitudes = Solicitud::with(['creadoPor', 'asignadoA'])
            ->when($this->busqueda, fn($q) => $q->where(function ($q) {
                $q->where('folio', 'like', "%{$this->busqueda}%")
                  ->orWhere('cliente_nombre', 'like', "%{$this->busqueda}%");
            }))
            ->when($this->filtroEstado,    fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroTransporte, fn($q) => $q->where('tipo_transporte', $this->filtroTransporte))
            ->orderByDesc('created_at')
            ->paginate(20);

        $pricingUsers = User::where('rol', 'pricing')->where('activo', true)->orderBy('name')->get();

        return view('livewire.admin.gestion-solicitudes', compact('solicitudes', 'pricingUsers'))
            ->layout('layouts.admin');
    }
}
