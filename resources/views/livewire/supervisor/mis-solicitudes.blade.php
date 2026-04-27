<div class="space-y-6">

    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
        <div>
            <p style="font-size:.68rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:#cd3529; margin-bottom:.2rem;">
                Mis solicitudes
            </p>
            <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2.2rem; letter-spacing:.04em; color:#3d2372; line-height:1;">
                {{ auth()->user()->name }}
            </h1>
            <p style="font-size:.875rem; color:#9490b0; margin-top:.2rem;">
                {{ $solicitudes->total() }} {{ $solicitudes->total() === 1 ? 'solicitud' : 'solicitudes' }} propias
            </p>
        </div>
    </div>

    {{-- Stats --}}
    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:.85rem;">
        <style>@media(min-width:768px){.mis-stats{grid-template-columns:repeat(5,1fr)!important;}}</style>
        <div class="mis-stats" style="display:grid; grid-template-columns:repeat(2,1fr); gap:.85rem; grid-column:1/-1;">
        @foreach([
            ['label'=>'Nuevas',      'key'=>'nueva',       'color'=>'#3d2372', 'border'=>'#3d2372'],
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

    {{-- Alerta cotizaciones listas --}}
    @if($stats['enviada'] > 0)
    <div style="padding:1rem 1.25rem; background:#eff6ff; border:1px solid #93c5fd; border-radius:10px;
                display:flex; align-items:center; gap:.85rem;">
        <span style="font-size:1.4rem; flex-shrink:0;">📬</span>
        <p style="font-size:.875rem; color:#1e40af; font-weight:500; margin:0;">
            <strong>{{ $stats['enviada'] }} {{ $stats['enviada'] === 1 ? 'cotización lista' : 'cotizaciones listas' }}</strong>
            para revisar. Haz clic en la solicitud para ver los detalles.
        </p>
    </div>
    @endif

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
                    <td colspan="6" style="padding:4rem; text-align:center;">
                        <div style="font-size:3rem; margin-bottom:.75rem; opacity:.3;">📋</div>
                        <p style="color:#9490b0; font-size:.9rem; margin-bottom:1rem;">Aún no tienes solicitudes propias.</p>
                        <a href="{{ route('ventas.crear') }}"
                           style="display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.25rem;
                                  background:#cd3529; color:white; border-radius:4px; font-size:.85rem;
                                  font-weight:700; text-decoration:none;">
                            + Crear primera solicitud
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($solicitudes->hasPages())
    <div style="margin-top:1.25rem;">
        {{ $solicitudes->links() }}
    </div>
    @endif

</div>
