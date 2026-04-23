<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;
use Illuminate\Validation\Rule;

class GestionProveedores extends Component
{
    use WithPagination;

    public string $busqueda     = '';
    public string $filtroActivo = '';
    public bool   $mostrarModal = false;
    public bool   $editando     = false;
    public ?int   $editId       = null;

    public string  $nombre        = '';
    public string  $correo        = '';
    public int     $terminosPago  = 30;
    public bool    $activo        = true;

    public function mount()
    {
        if (auth()->user()->rol !== 'admin') abort(403);
    }

    public function updatedBusqueda()    { $this->resetPage(); }
    public function updatedFiltroActivo(){ $this->resetPage(); }

    public function abrirCrear()
    {
        $this->resetForm();
        $this->editando     = false;
        $this->editId       = null;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id)
    {
        $p = Proveedor::findOrFail($id);
        $this->editando      = true;
        $this->editId        = $id;
        $this->nombre        = $p->nombre;
        $this->correo        = $p->correo ?? '';
        $this->terminosPago  = $p->terminos_pago ?? 30;
        $this->activo        = $p->activo;
        $this->mostrarModal  = true;
    }

    public function guardar()
    {
        $this->validate([
            'nombre'       => 'required|string|min:2',
            'correo'       => 'nullable|email',
            'terminosPago' => 'required|integer|min:0|max:365',
            'activo'       => 'boolean',
        ]);

        $datos = [
            'nombre'        => $this->nombre,
            'correo'        => $this->correo ?: null,
            'terminos_pago' => $this->terminosPago,
            'activo'        => $this->activo,
        ];

        if ($this->editando) {
            Proveedor::findOrFail($this->editId)->update($datos);
            session()->flash('success', 'Proveedor actualizado.');
        } else {
            Proveedor::create($datos);
            session()->flash('success', 'Proveedor creado.');
        }

        $this->mostrarModal = false;
        $this->resetForm();
    }

    public function toggleActivo(int $id)
    {
        $p = Proveedor::findOrFail($id);
        $p->update(['activo' => !$p->activo]);
    }

    public function eliminar(int $id)
    {
        Proveedor::findOrFail($id)->delete();
        session()->flash('success', 'Proveedor eliminado.');
    }

    private function resetForm()
    {
        $this->nombre       = '';
        $this->correo       = '';
        $this->terminosPago = 30;
        $this->activo       = true;
        $this->resetValidation();
    }

    public function render()
    {
        $proveedores = Proveedor::withCount('lineas')
            ->when($this->busqueda, fn($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->orderBy('nombre')
            ->paginate(20);

        return view('livewire.admin.gestion-proveedores', compact('proveedores'))
            ->layout('layouts.admin');
    }
}
