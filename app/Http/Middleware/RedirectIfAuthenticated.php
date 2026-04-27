<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $rol = Auth::user()->rol;
                if ($rol === 'admin') return redirect('/admin');
                if ($rol === 'pricing') return redirect()->route('pricing.dashboard');
                return redirect()->route('ventas.dashboard');
            }
        }

        return $next($request);
    }
}