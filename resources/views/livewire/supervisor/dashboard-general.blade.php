<div class="space-y-6">

    {{-- Header --}}
    <div>
        <p style="font-size:.68rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:#cd3529; margin-bottom:.2rem;">
            Supervisión de Ventas
        </p>
        <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2.2rem; letter-spacing:.04em; color:#3d2372; line-height:1;">
            Dashboard general
        </h1>
        <p style="font-size:.875rem; color:#9490b0; margin-top:.2rem;">
            Vista completa del equipo de ventas · {{ now()->format('d/m/Y') }}
        </p>
    </div>

    {{-- KPIs --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:.85rem;">
        <style>@media(min-width:900px){.sup-kpis{grid-template-columns:repeat(6,1fr)!important;}}</style>
        <div class="sup-kpis" style="display:grid; grid-template-columns:repeat(3,1fr); gap:.85rem; grid-column:1/-1;">
        @foreach([
            ['label'=>'Total',       'key'=>'total',       'color'=>'#3d2372', 'border'=>'#3d2372'],
            ['label'=>'Nuevas',      'key'=>'nueva',       'color'=>'#7c3aed', 'border'=>'#7c3aed'],
            ['label'=>'En revisión', 'key'=>'en_revision', 'color'=>'#92400e', 'border'=>'#d97706'],
            ['label'=>'Cotizadas',   'key'=>'cotizada',    'color'=>'#065f46', 'border'=>'#059669'],
            ['label'=>'Enviadas',    'key'=>'enviada',     'color'=>'#1e40af', 'border'=>'#3b82f6'],
            ['label'=>'Rechazadas',  'key'=>'rechazada',   'color'=>'#991b1b', 'border'=>'#dc2626'],
        ] as $s)
        <div style="background:white; border-radius:10px; padding:1rem 1.1rem;
                    border:1px solid rgba(61,35,114,0.08); border-left:3px solid {{ $s['border'] }};
                    box-shadow:0 1px 3px rgba(61,35,114,0.05);">
            <p style="font-size:.65rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
                       color:#9490b0; margin-bottom:.35rem;">{{ $s['label'] }}</p>
            <p style="font-family:'Bebas Neue',sans-serif; font-size:2.2rem; letter-spacing:.04em;
                       color:{{ $s['color'] }}; line-height:1;">{{ $stats[$s['key']] }}</p>
        </div>
        @endforeach
        </div>
    </div>

    {{-- Actividad por vendedor --}}
    @if($vendedores->count() > 0)
    <div style="background:white; border-radius:12px; padding:1.25rem 1.5rem;
                border:1px solid rgba(61,35,114,0.08); box-shadow:0 1px 3px rgba(61,35,114,0.05);">
        <h2 style="font-size:.7rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
                   color:#9490b0; margin-bottom:1.1rem;">Actividad por vendedor</h2>
        <div style="display:flex; flex-direction:column; gap:.85rem;">
            @foreach($vendedores as $v)
            @php $pct = $stats['total'] > 0 ? round($v->solicitudes_creadas_count / $stats['total'] * 100) : 0; @endphp
            <div style="display:flex; align-items:center; gap:.9rem;">
                <div style="width:32px; height:32px; border-radius:50%; flex-shrink:0;
                            display:flex; align-items:center; justify-content:center;
                            background:linear-gradient(135deg,#cd3529,#3d2372);
                            color:white; font-size:.75rem; font-weight:700;">
                    {{ strtoupper(substr($v->name, 0, 1)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:.3rem;">
                        <span style="font-size:.85rem; font-weight:600; color:#1f103b;">{{ $v->name }}</span>
                        <span style="font-size:.75rem; font-family:monospace; color:#5a4e80; flex-shrink:0;">
                            {{ $v->solicitudes_creadas_count }} sol.
                            @if($v->enviadas_count > 0)
                            · <span style="color:#2563eb;">{{ $v->enviadas_count }} env.</span>
                            @endif
                            @if($v->nuevas_count > 0)
                            · <span style="color:#7c3aed;">{{ $v->nuevas_count }} pend.</span>
                            @endif
                        </span>
                    </div>
                    <div style="background:#f0edf8; border-radius:999px; height:7px; overflow:hidden;">
                        <div style="width:{{ $pct }}%; background:linear-gradient(90deg,#3d2372,#cd3529);
                                    height:100%; border-radius:999px;"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Filtros --}}
    <div style="display:flex; gap:.75rem; flex-wrap:wrap; align-items:center;">
        <input wire:model.live="busqueda" type="text"
            style="border:1px solid rgba(61,35,114,0.18); border-radius:8px; padding:.5rem .9rem;
                   font-size:.85rem; font-family:'DM Sans',sans-serif; flex:1; min-width:180px; outline:none;
                   background:white; box-shadow:0 1px 2px rgba(61,35,114,0.04);"
            onfocus="this.style.borderColor='#3d2372';" onblur="this.style.borderColor='rgba(61,35,114,0.18)';"
            placeholder="Buscar folio o cliente...">
        <select wire:model.live="filtroEstado"
            style="border:1px solid rgba(61,35,114,0.18); border-radius:8px; padding:.5rem .9rem;
                   font-size:.85rem; font-family:'DM Sans',sans-serif; background:white; outline:none;
                   box-shadow:0 1px 2px rgba(61,35,114,0.04);">
            <option value="">Todos los estados</option>
            <option value="nueva">Nueva</option>
            <option value="en_revision">En revisión</option>
            <option value="cotizada">Cotizada</option>
            <option value="enviada">Enviada</option>
            <option value="rechazada">Rechazada</option>
        </select>
        <select wire:model.live="filtroUsuario"
            style="border:1px solid rgba(61,35,114,0.18); border-radius:8px; padding:.5rem .9rem;
                   font-size:.85rem; font-family:'DM Sans',sans-serif; background:white; outline:none;
                   box-shadow:0 1px 2px rgba(61,35,114,0.04);">
            <option value="">Todos los vendedores</option>
            @foreach($usuariosVentas as $uv)
            <option value="{{ $uv->id }}">{{ $uv->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tabla --}}
    @php
    $badgeStyle = [
        'nueva'       => 'background:#ede9fe;color:#3d1a8e;',
        'en_revision' => 'background:#fef3c7;color:#92400e;',
        'cotizada'    => 'background:#d1fae5;color:#065f46;',
        'enviada'     => 'background:#dbeafe;color:#1e40af;',
        'rechazada'   => 'background:#fee2e2;color:#991b1b;',
    ];
    $estadoLabel = ['nueva'=>'Nueva','en_revision'=>'En revisión','cotizada'=>'Cotizada','enviada'=>'Enviada','rechazada'=>'Rechazada'];
    @endphp
    <div style="background:white; border-radius:12px; border:1px solid rgba(61,35,114,0.08);
                box-shadow:0 1px 4px rgba(61,35,114,0.05); overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; font-size:.85rem;">
            <thead>
                <tr style="background:#f8f7fc; border-bottom:1px solid rgba(61,35,114,0.1);">
                    <th style="text-align:left; padding:.75rem 1.25rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Folio</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Cliente</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Vendedor</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Transporte</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Estado</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Fecha</th>
                    <th style="width:2rem;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitudes as $sol)
                <tr style="border-bottom:1px solid rgba(61,35,114,0.06); cursor:pointer;"
                    onclick="window.location='{{ route('ventas.solicitud', $sol->id) }}'"
                    onmouseover="this.style.background='rgba(61,35,114,0.025)';"
                    onmouseout="this.style.background='';">
                    <td style="padding:.75rem 1.25rem;">
                        <span style="font-family:monospace; font-weight:700; color:#3d2372;">{{ $sol->folio }}</span>
                        @if($sol->estado === 'enviada')
                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%;
                                     background:#3b82f6; margin-left:.35rem; vertical-align:middle;"></span>
                        @endif
                    </td>
                    <td style="padding:.75rem .75rem; font-weight:500; color:#1f103b;">{{ $sol->cliente_nombre }}</td>
                    <td style="padding:.75rem .75rem; color:#5a4e80; font-size:.82rem;">{{ $sol->creadoPor?->name ?? '—' }}</td>
                    <td style="padding:.75rem .75rem; color:#5a4e80; text-transform:capitalize;">{{ $sol->tipo_transporte }}</td>
                    <td style="padding:.75rem .75rem;">
                        <span style="font-size:.72rem; font-weight:700; padding:.25rem .6rem; border-radius:999px;
                                     {{ $badgeStyle[$sol->estado] ?? '' }}">
                            {{ $estadoLabel[$sol->estado] ?? $sol->estado }}
                        </span>
                    </td>
                    <td style="padding:.75rem .75rem; color:#9490b0; font-size:.78rem; white-space:nowrap;">
                        {{ $sol->created_at->format('d/m/Y') }}
                    </td>
                    <td style="padding:.75rem .75rem; color:#cdc9e8; text-align:center;">→</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:3rem; text-align:center; color:#9490b0;">
                        No hay solicitudes con ese filtro.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($solicitudes->hasPages())
        <div style="padding:.75rem 1.25rem; border-top:1px solid rgba(61,35,114,0.08);">
            {{ $solicitudes->links() }}
        </div>
        @endif
    </div>

</div>
