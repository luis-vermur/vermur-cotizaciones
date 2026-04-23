<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Cotizacion;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Proveedor;

class DashboardAdmin extends Component
{
    public function mount()
    {
        if (auth()->user()->rol !== 'admin') abort(403);
    }

    public function render()
    {
        $estados = ['nueva', 'en_revision', 'cotizada', 'enviada', 'rechazada'];
        $solicitudesPorEstado = [];
        foreach ($estados as $e) {
            $solicitudesPorEstado[$e] = Solicitud::where('estado', $e)->count();
        }

        $ahora = now();
        $inicioMes = $ahora->copy()->startOfMonth();

        $stats = [
            'solicitudes_total'  => Solicitud::count(),
            'solicitudes_mes'    => Solicitud::where('created_at', '>=', $inicioMes)->count(),
            'cotizaciones_total' => Cotizacion::count(),
            'ventas_mes'         => Cotizacion::where('created_at', '>=', $inicioMes)->sum('venta_total'),
            'ganancia_mes'       => Cotizacion::where('created_at', '>=', $inicioMes)->sum('ganancia_real'),
            'clientes'           => Cliente::count(),
            'proveedores'        => Proveedor::where('activo', true)->count(),
            'usuarios_activos'   => User::where('activo', true)->count(),
        ];

        $solicitudesRecientes = Solicitud::with('creadoPor')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $cotizacionesRecientes = Cotizacion::with('solicitud')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('livewire.admin.dashboard-admin', compact(
            'stats', 'solicitudesPorEstado', 'solicitudesRecientes', 'cotizacionesRecientes'
        ))->layout('layouts.admin');
    }
}
