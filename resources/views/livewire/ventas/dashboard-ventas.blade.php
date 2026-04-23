<div>

    {{-- Encabezado --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <p class="v-tag">Módulo Ventas</p>
            <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2.2rem; letter-spacing:.04em;
                       color:#3d2372; line-height:1.1;">
                Mis solicitudes
            </h1>
            <p style="font-size:.875rem; color:#9490b0; margin-top:.25rem;">
                Hola, <strong style="color:#5a4e80;">{{ auth()->user()->name }}</strong> —
                {{ $solicitudes->count() }} {{ $solicitudes->count() === 1 ? 'solicitud' : 'solicitudes' }} en total
            </p>
        </div>
        <a href="{{ route('ventas.crear') }}"
           style="display:inline-flex; align-items:center; gap:.45rem;
                  padding:.65rem 1.4rem; background:#cd3529; color:white;
                  border-radius:4px; font-size:.875rem; font-weight:700;
                  letter-spacing:.04em; text-decoration:none;"
           onmouseover="this.style.background='#e04a3e';this.style.transform='translateY(-1px)';"
           onmouseout="this.style.background='#cd3529';this.style.transform='none';">
            + Nueva solicitud
        </a>
    </div>

    @if(session('success'))
    <div class="v-alert v-alert-success" style="margin-bottom:1.5rem;">
        <span>✓</span><span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Stats --}}
    <div style="margin-bottom:2rem;">
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem;">
            <style>@media(min-width:768px){.stats-grid{grid-template-columns:repeat(4,1fr)!important;}}</style>
            <div class="stats-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; grid-column:1/-1;">
            @foreach([
                ['label'=>'Nuevas',      'key'=>'nueva',       'color'=>'#3d2372', 'bg'=>'#ede9fe'],
                ['label'=>'En revisión', 'key'=>'en_revision', 'color'=>'#92400e', 'bg'=>'#fef3c7'],
                ['label'=>'Cotizadas',   'key'=>'cotizada',    'color'=>'#065f46', 'bg'=>'#d1fae5'],
                ['label'=>'Enviadas',    'key'=>'enviada',     'color'=>'#1e40af', 'bg'=>'#dbeafe'],
            ] as $s)
            <div class="v-stat" style="border-left:3px solid {{ $s['color'] }};">
                <div class="v-stat__number" style="color:{{ $s['color'] }};">{{ $stats[$s['key']] }}</div>
                <div class="v-stat__label">{{ $s['label'] }}</div>
                <div class="v-stat__accent" style="background:{{ $s['bg'] }};"></div>
            </div>
            @endforeach
            </div>
        </div>
    </div>

    {{-- Alerta cotizaciones listas --}}
    @if($stats['enviada'] > 0)
    <div class="v-alert v-alert-info" style="margin-bottom:1.5rem;">
        <span style="font-size:1.25rem; flex-shrink:0;">📬</span>
        <div>
            <strong>{{ $stats['enviada'] }} {{ $stats['enviada'] === 1 ? 'cotización lista' : 'cotizaciones listas' }} para revisar.</strong>
            <span style="opacity:.8;"> Haz clic en la solicitud correspondiente para ver los detalles.</span>
        </div>
    </div>
    @endif

    {{-- Tabla --}}
    <div class="v-table-wrap">
        <table style="width:100%; border-collapse:collapse;">
            <thead class="v-table-head">
                <tr>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th style="display:none;" class="th-transport">Transporte</th>
                    <th>Estado</th>
                    <th style="display:none;" class="th-date">Fecha</th>
                    <th style="width:2rem;"></th>
                </tr>
                <style>
                    @media(min-width:768px){.th-transport,.th-date{display:table-cell!important;}}
                </style>
            </thead>
            <tbody>
                @forelse($solicitudes as $sol)
                @php
                $badgeClass = [
                    'nueva'       => 'v-badge-nueva',
                    'en_revision' => 'v-badge-revision',
                    'cotizada'    => 'v-badge-cotizada',
                    'enviada'     => 'v-badge-enviada',
                    'rechazada'   => 'v-badge-rechazada',
                ][$sol->estado] ?? 'v-badge-nueva';
                $estadoLabel = [
                    'nueva'=>'Nueva','en_revision'=>'En revisión',
                    'cotizada'=>'Cotizada','enviada'=>'Enviada','rechazada'=>'Rechazada',
                ][$sol->estado] ?? ucfirst($sol->estado);
                @endphp
                <tr class="v-table-row" style="cursor:pointer;"
                    onclick="window.location='{{ route('ventas.solicitud', $sol->id) }}'">
                    <td>
                        <span style="font-family:monospace; font-weight:700; font-size:.875rem; color:#3d2372;">
                            {{ $sol->folio }}
                        </span>
                        @if($sol->estado === 'enviada')
                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%;
                                     background:#3b82f6; margin-left:.35rem; vertical-align:middle;"></span>
                        @endif
                    </td>
                    <td style="font-weight:500; color:#1f103b;">{{ $sol->cliente_nombre }}</td>
                    <td style="color:#5a4e80; text-transform:capitalize; display:none;" class="td-transport">
                        {{ $sol->tipo_transporte }}
                    </td>
                    <td><span class="v-badge {{ $badgeClass }}">{{ $estadoLabel }}</span></td>
                    <td style="color:#9490b0; font-size:.8rem; display:none;" class="td-date">
                        {{ $sol->created_at->format('d/m/Y') }}
                    </td>
                    <td style="color:#cdc9e8; font-size:.9rem; text-align:center;">→</td>
                </tr>
                <style>@media(min-width:768px){.td-transport,.td-date{display:table-cell!important;}}</style>
                @empty
                <tr>
                    <td colspan="6" style="padding:4rem 1rem; text-align:center;">
                        <div style="font-size:3rem; margin-bottom:.75rem; opacity:.3;">📋</div>
                        <p style="color:#9490b0; font-size:.9rem; margin-bottom:1rem;">
                            Aún no tienes solicitudes.
                        </p>
                        <a href="{{ route('ventas.crear') }}"
                           style="display:inline-flex; align-items:center; gap:.4rem;
                                  padding:.6rem 1.25rem; background:#cd3529; color:white;
                                  border-radius:4px; font-size:.85rem; font-weight:700; text-decoration:none;">
                            + Crear solicitud
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
