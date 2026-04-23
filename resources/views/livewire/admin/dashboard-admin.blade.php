<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2rem; letter-spacing:.04em; color:#3d2372; line-height:1;">
            Dashboard
        </h1>
        <p style="font-size:.82rem; color:#9490b0; margin-top:.2rem;">
            Resumen general del sistema · {{ now()->format('d/m/Y') }}
        </p>
    </div>

    {{-- KPIs fila 1 --}}
    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem;">
        <style>@media(min-width:900px){.kpi-grid{grid-template-columns:repeat(4,1fr)!important;}}</style>
        <div class="kpi-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; grid-column:1/-1;">

        @php
        $kpis = [
            ['label'=>'Solicitudes total',  'value'=>$stats['solicitudes_total'],  'sub'=>'+'.$stats['solicitudes_mes'].' este mes', 'color'=>'#3d2372', 'bg'=>'#ede9fe'],
            ['label'=>'Cotizaciones',        'value'=>$stats['cotizaciones_total'], 'sub'=>'generadas en total',                      'color'=>'#1e40af', 'bg'=>'#dbeafe'],
            ['label'=>'Clientes',            'value'=>$stats['clientes'],           'sub'=>$stats['proveedores'].' proveedores activos','color'=>'#065f46','bg'=>'#d1fae5'],
            ['label'=>'Usuarios activos',    'value'=>$stats['usuarios_activos'],   'sub'=>'en el sistema',                            'color'=>'#92400e','bg'=>'#fef3c7'],
        ];
        @endphp

        @foreach($kpis as $k)
        <div style="background:white; border-radius:12px; padding:1.25rem 1.5rem;
                    border:1px solid rgba(61,35,114,0.08); box-shadow:0 1px 4px rgba(61,35,114,0.05);">
            <p style="font-size:.72rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
                       color:#9490b0; margin-bottom:.5rem;">{{ $k['label'] }}</p>
            <p style="font-family:'Bebas Neue',sans-serif; font-size:2.5rem; letter-spacing:.04em;
                       color:{{ $k['color'] }}; line-height:1;">{{ $k['value'] }}</p>
            <p style="font-size:.75rem; color:#9490b0; margin-top:.25rem;">{{ $k['sub'] }}</p>
        </div>
        @endforeach

        </div>
    </div>

    {{-- KPIs financieros --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div style="background:linear-gradient(135deg,#1f103b,#3d2372); border-radius:12px; padding:1.5rem;
                    display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:.7rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
                           color:rgba(255,255,255,0.45); margin-bottom:.4rem;">Ventas del mes</p>
                <p style="font-family:'Bebas Neue',sans-serif; font-size:2rem; letter-spacing:.04em;
                           color:white; line-height:1;">${{ number_format($stats['ventas_mes'], 0) }}</p>
            </div>
            <span style="font-size:2.5rem; opacity:.3;">💰</span>
        </div>
        <div style="background:linear-gradient(135deg,#064e3b,#059669); border-radius:12px; padding:1.5rem;
                    display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:.7rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
                           color:rgba(255,255,255,0.45); margin-bottom:.4rem;">Ganancia real del mes</p>
                <p style="font-family:'Bebas Neue',sans-serif; font-size:2rem; letter-spacing:.04em;
                           color:white; line-height:1;">${{ number_format($stats['ganancia_mes'], 0) }}</p>
            </div>
            <span style="font-size:2.5rem; opacity:.3;">📈</span>
        </div>
    </div>

    {{-- Estado de solicitudes --}}
    <div style="background:white; border-radius:12px; padding:1.5rem;
                border:1px solid rgba(61,35,114,0.08); box-shadow:0 1px 4px rgba(61,35,114,0.05);">
        <h2 style="font-size:.78rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
                   color:#9490b0; margin-bottom:1rem;">Solicitudes por estado</h2>
        @php
        $totalSol = array_sum($solicitudesPorEstado) ?: 1;
        $barColors = [
            'nueva'       => ['bg'=>'#ede9fe','bar'=>'#7c3aed','label'=>'Nueva'],
            'en_revision' => ['bg'=>'#fef3c7','bar'=>'#d97706','label'=>'En revisión'],
            'cotizada'    => ['bg'=>'#d1fae5','bar'=>'#059669','label'=>'Cotizada'],
            'enviada'     => ['bg'=>'#dbeafe','bar'=>'#2563eb','label'=>'Enviada'],
            'rechazada'   => ['bg'=>'#fee2e2','bar'=>'#dc2626','label'=>'Rechazada'],
        ];
        @endphp
        <div style="display:flex; flex-direction:column; gap:.65rem;">
            @foreach($solicitudesPorEstado as $estado => $count)
            @php
                $pct = round($count / $totalSol * 100);
                $c = $barColors[$estado] ?? ['bg'=>'#f3f4f6','bar'=>'#9ca3af','label'=>ucfirst($estado)];
            @endphp
            <div style="display:flex; align-items:center; gap:.75rem;">
                <span style="font-size:.78rem; font-weight:600; color:#5a4e80; width:7rem; flex-shrink:0;">
                    {{ $c['label'] }}
                </span>
                <div style="flex:1; background:#f3f4f6; border-radius:999px; height:8px; overflow:hidden;">
                    <div style="width:{{ $pct }}%; background:{{ $c['bar'] }}; height:100%; border-radius:999px;
                                transition:width .4s ease;"></div>
                </div>
                <span style="font-family:monospace; font-size:.82rem; font-weight:700; color:#1f103b; width:2rem; text-align:right;">
                    {{ $count }}
                </span>
                <span style="font-size:.72rem; color:#9490b0; width:2.5rem;">{{ $pct }}%</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Dos columnas: solicitudes recientes + cotizaciones recientes --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <style>@media(max-width:900px){.dash-2col{grid-template-columns:1fr!important;}}</style>
        <div class="dash-2col" style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; grid-column:1/-1;">

        {{-- Solicitudes recientes --}}
        <div style="background:white; border-radius:12px; border:1px solid rgba(61,35,114,0.08);
                    box-shadow:0 1px 4px rgba(61,35,114,0.05); overflow:hidden;">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid rgba(61,35,114,0.08);
                        display:flex; align-items:center; justify-content:space-between;">
                <h2 style="font-size:.78rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">
                    Solicitudes recientes
                </h2>
                <a href="{{ route('admin.solicitudes') }}"
                   style="font-size:.72rem; color:#3d2372; font-weight:600; text-decoration:none;">
                    Ver todas →
                </a>
            </div>
            @php
            $estadoBadge = [
                'nueva'       => 'background:#ede9fe;color:#3d1a8e;',
                'en_revision' => 'background:#fef3c7;color:#92400e;',
                'cotizada'    => 'background:#d1fae5;color:#065f46;',
                'enviada'     => 'background:#dbeafe;color:#1e40af;',
                'rechazada'   => 'background:#fee2e2;color:#991b1b;',
            ];
            $estadoLabel = [
                'nueva'=>'Nueva','en_revision'=>'En revisión','cotizada'=>'Cotizada',
                'enviada'=>'Enviada','rechazada'=>'Rechazada',
            ];
            @endphp
            @forelse($solicitudesRecientes as $sol)
            <div style="padding:.75rem 1.25rem; border-bottom:1px solid rgba(61,35,114,0.05);
                        display:flex; align-items:center; justify-content:space-between; gap:.5rem;">
                <div style="min-width:0;">
                    <p style="font-family:monospace; font-size:.8rem; font-weight:700; color:#3d2372;
                               white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $sol->folio }}
                    </p>
                    <p style="font-size:.72rem; color:#9490b0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $sol->cliente_nombre }}
                    </p>
                </div>
                <span style="font-size:.68rem; font-weight:600; padding:.2rem .55rem; border-radius:999px;
                              flex-shrink:0; {{ $estadoBadge[$sol->estado] ?? '' }}">
                    {{ $estadoLabel[$sol->estado] ?? $sol->estado }}
                </span>
            </div>
            @empty
            <p style="padding:2rem; text-align:center; color:#9490b0; font-size:.85rem;">Sin solicitudes.</p>
            @endforelse
        </div>

        {{-- Cotizaciones recientes --}}
        <div style="background:white; border-radius:12px; border:1px solid rgba(61,35,114,0.08);
                    box-shadow:0 1px 4px rgba(61,35,114,0.05); overflow:hidden;">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid rgba(61,35,114,0.08);">
                <h2 style="font-size:.78rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">
                    Cotizaciones recientes
                </h2>
            </div>
            @forelse($cotizacionesRecientes as $coti)
            <div style="padding:.75rem 1.25rem; border-bottom:1px solid rgba(61,35,114,0.05);
                        display:flex; align-items:center; justify-content:space-between; gap:.5rem;">
                <div style="min-width:0;">
                    <p style="font-family:monospace; font-size:.8rem; font-weight:700; color:#3d2372;">
                        {{ $coti->folio_coti }}
                    </p>
                    <p style="font-size:.72rem; color:#9490b0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $coti->solicitud?->cliente_nombre ?? '—' }}
                    </p>
                </div>
                <span style="font-family:monospace; font-size:.82rem; font-weight:700; color:#059669; flex-shrink:0;">
                    ${{ number_format($coti->venta_total, 0) }}
                </span>
            </div>
            @empty
            <p style="padding:2rem; text-align:center; color:#9490b0; font-size:.85rem;">Sin cotizaciones.</p>
            @endforelse
        </div>

        </div>
    </div>

</div>
