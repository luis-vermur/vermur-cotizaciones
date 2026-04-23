<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;
use Illuminate\Validation\Rule;

class GestionClientes extends Component
{
    use WithPagination;

    public string $busqueda    = '';
    public bool   $mostrarModal = false;
    public bool   $editando    = false;
    public ?int   $editId      = null;

    public string $nombre      = '';
    public int    $diasCredito = 0;

    public function mount()
    {
        if (auth()->user()->rol !== 'admin') abort(403);
    }

    public function updatedBusqueda() { $this->resetPage(); }

    public function abrirCrear()
    {
        $this->resetForm();
        $this->editando     = false;
        $this->editId       = null;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id)
    {
        $c = Cliente::findOrFail($id);
        $this->editando     = true;
        $this->editId       = $id;
        $this->nombre       = $c->nombre;
        $this->diasCredito  = $c->dias_credito;
        $this->mostrarModal = true;
    }

    public function guardar()
    {
        $nombreRule = $this->editando
            ? Rule::unique('clientes', 'nombre')->ignore($this->editId)
            : Rule::unique('clientes', 'nombre');

        $this->validate([
            'nombre'      => ['required', 'string', 'min:2', $nombreRule],
            'diasCredito' => 'required|integer|min:0|max:365',
        ]);

        $datos = ['nombre' => $this->nombre, 'dias_credito' => $this->diasCredito];

        if ($this->editando) {
            Cliente::findOrFail($this->editId)->update($datos);
            session()->flash('success', 'Cliente actualizado.');
        } else {
            Cliente::create($datos);
            session()->flash('success', 'Cliente creado.');
        }

        $this->mostrarModal = false;
        $this->resetForm();
    }

    public function eliminar(int $id)
    {
        Cliente::findOrFail($id)->delete();
        session()->flash('success', 'Cliente eliminado.');
    }

    private function resetForm()
    {
        $this->nombre      = '';
        $this->diasCredito = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $clientes = Cliente::withCount('solicitudes')
            ->when($this->busqueda, fn($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->orderBy('nombre')
            ->paginate(20);

        return view('livewire.admin.gestion-clientes', compact('clientes'))
            ->layout('layouts.admin');
    }
}
