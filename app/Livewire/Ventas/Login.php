<?php

namespace App\Livewire\Ventas;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

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

        $key = 'login:' . str($this->email)->lower() . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->error = "Demasiados intentos. Intenta en {$seconds} segundos.";
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($key, 60);
            $this->error = 'Credenciales incorrectas.';
            return;
        }

        $user = Auth::user();

        if (!$user->activo) {
            Auth::logout();
            $this->error = 'Tu cuenta está desactivada. Contacta al administrador.';
            return;
        }

        RateLimiter::clear($key);

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
