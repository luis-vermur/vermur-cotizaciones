<?php

namespace App\Livewire\Ventas;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public string $error    = '';

    public function login()
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->error = 'Credenciales incorrectas.';
            return;
        }

        $user = Auth::user();

        if (!$user->activo) {
            Auth::logout();
            $this->error = 'Tu cuenta está desactivada. Contacta al administrador.';
            return;
        }

        // Redirigir según rol
        if ($user->rol === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->rol === 'pricing') {
            return redirect()->route('pricing.dashboard');
        }

        if ($user->rol === 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        }

        return redirect()->route('ventas.dashboard');
    }

    public function render()
    {
        return view('livewire.ventas.login')
            ->layout('layouts.ventas');
    }
}
