<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Validation\Rule;

class GestionUsuarios extends Component
{
    use WithPagination;

    public string $busqueda = '';
    public string $filtroRol = '';

    // Modal
    public bool   $mostrarModal = false;
    public bool   $editando     = false;
    public ?int   $editId       = null;

    // Campos form
    public string $nombre   = '';
    public string $email    = '';
    public string $rol      = 'ventas';
    public bool   $activo   = true;
    public string $password = '';

    public function mount()
    {
        if (auth()->user()->rol !== 'admin') abort(403);
    }

    public function updatedBusqueda() { $this->resetPage(); }
    public function updatedFiltroRol() { $this->resetPage(); }

    public function abrirCrear()
    {
        $this->resetForm();
        $this->editando     = false;
        $this->editId       = null;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id)
    {
        $user = User::findOrFail($id);
        $this->resetForm();
        $this->editando  = true;
        $this->editId    = $id;
        $this->nombre    = $user->name;
        $this->email     = $user->email;
        $this->rol       = $user->rol;
        $this->activo    = $user->activo;
        $this->mostrarModal = true;
    }

    public function guardar()
    {
        $emailRule = $this->editando
            ? Rule::unique('users', 'email')->ignore($this->editId)
            : Rule::unique('users', 'email');

        $rules = [
            'nombre' => 'required|string|min:2|max:255',
            'email'  => ['required', 'email', $emailRule],
            'rol'    => 'required|in:ventas,pricing,supervisor,admin',
            'activo' => 'boolean',
        ];

        if (!$this->editando || $this->password) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        $datos = [
            'name'   => $this->nombre,
            'email'  => $this->email,
            'rol'    => $this->rol,
            'activo' => $this->activo,
        ];

        if ($this->password) {
            $datos['password'] = $this->password;
        }

        if ($this->editando) {
            if ($this->editId === auth()->id() && $this->rol !== 'admin') {
                session()->flash('error', 'No puedes cambiar tu propio rol.');
                $this->mostrarModal = false;
                return;
            }
            User::findOrFail($this->editId)->update($datos);
            session()->flash('success', 'Usuario actualizado correctamente.');
        } else {
            if (!isset($datos['password'])) {
                $datos['password'] = 'password';
            }
            User::create($datos);
            session()->flash('success', 'Usuario creado. Contraseña por defecto: "password".');
        }

        $this->mostrarModal = false;
        $this->resetForm();
    }

    public function toggleActivo(int $id)
    {
        $user = User::findOrFail($id);
        $user->update(['activo' => !$user->activo]);
    }

    public function eliminar(int $id)
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta.');
            return;
        }
        User::findOrFail($id)->delete();
        session()->flash('success', 'Usuario eliminado.');
    }

    private function resetForm()
    {
        $this->nombre   = '';
        $this->email    = '';
        $this->rol      = 'ventas';
        $this->activo   = true;
        $this->password = '';
        $this->resetValidation();
    }

    public function render()
    {
        $usuarios = User::query()
            ->when($this->busqueda, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->busqueda}%")
                  ->orWhere('email', 'like', "%{$this->busqueda}%");
            }))
            ->when($this->filtroRol, fn($q) => $q->where('rol', $this->filtroRol))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.gestion-usuarios', compact('usuarios'))
            ->layout('layouts.admin');
    }
}
