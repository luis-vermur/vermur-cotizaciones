<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Cotizacion;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\TipoCambio;

class DashboardAdmin extends Component
{
    public function mount()
    {
        if (auth()->user()->rol !== 'admin') abort(403);
    }

    public function render()
    {
        $allStats = Solicitud::statsPorEstado();
        $solicitudesPorEstado = array_diff_key($allStats, ['total' => true]);

        $ahora = now();
        $inicioMes = $ahora->copy()->startOfMonth();

        $tc = TipoCambio::orderByDesc('actualizado_en')->first();
        $tcValor = $tc?->valor ?? 17.00;

        $ventasMXN = Cotizacion::where('created_at', '>=', $inicioMes)->where('moneda', 'MXN')->sum('venta_total');
        $ventasUSD = Cotizacion::where('created_at', '>=', $inicioMes)->where('moneda', 'USD')->sum('venta_total');
        $gananciaMXN = Cotizacion::where('created_at', '>=', $inicioMes)->where('moneda', 'MXN')->sum('ganancia_real');
        $gananciaUSD = Cotizacion::where('created_at', '>=', $inicioMes)->where('moneda', 'USD')->sum('ganancia_real');

        // Ejecutivos de pricing: cotizaciones en proceso y enviadas este mes
        $ejecutivosPricing = User::where('rol', 'pricing')->where('activo', true)->get()
            ->map(function ($u) use ($inicioMes) {
                return [
                    'name'        => $u->name,
                    'en_proceso'  => Cotizacion::where('creado_por', $u->id)
                                        ->whereHas('solicitud', fn($q) => $q->whereIn('estado', ['nueva', 'en_revision', 'cotizada']))
                                        ->count(),
                    'enviadas_mes' => Cotizacion::where('creado_por', $u->id)
                                        ->where('created_at', '>=', $inicioMes)
                                        ->whereHas('solicitud', fn($q) => $q->where('estado', 'enviada'))
                                        ->count(),
                ];
            });

        $stats = [
            'solicitudes_total'  => $allStats['total'],
            'solicitudes_mes'    => Solicitud::where('created_at', '>=', $inicioMes)->count(),
            'cotizaciones_total' => Cotizacion::count(),
            'ventas_mxn'         => $ventasMXN,
            'ventas_usd'         => $ventasUSD,
            'ganancia_mxn'       => $gananciaMXN,
            'ganancia_usd'       => $gananciaUSD,
            'ventas_total_mxn'   => $ventasMXN + ($ventasUSD * $tcValor),
            'ganancia_total_mxn' => $gananciaMXN + ($gananciaUSD * $tcValor),
            'tc'                 => $tcValor,
            'tc_fecha'           => $tc?->actualizado_en,
            'clientes'           => Cliente::count(),
            'proveedores'        => Proveedor::where('activo', true)->count(),
            'ejecutivos_pricing' => $ejecutivosPricing,
        ];

        $solicitudesRecientes = Solicitud::with('creadoPor')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $cotizacionesRecientes = Cotizacion::with(['solicitud', 'creadoPor'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('livewire.admin.dashboard-admin', compact(
            'stats', 'solicitudesPorEstado', 'solicitudesRecientes', 'cotizacionesRecientes'
        ))->layout('layouts.admin');
    }
}
