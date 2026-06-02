<div>

    {{-- Encabezado --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
        <div>
            <p class="v-tag">Módulo Pricing</p>
            <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2.2rem; letter-spacing:.04em;
                       color:#3d2372; line-height:1.1;">
                Panel de solicitudes
            </h1>
            <p style="font-size:.875rem; color:#9490b0; margin-top:.25rem;">
                {{ $solicitudes->total() }} {{ $solicitudes->total() === 1 ? 'solicitud' : 'solicitudes' }} visibles según filtros actuales
            </p>
        </div>

        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
            <a href="{{ route('pricing.nueva-cotizacion') }}"
               style="display:inline-flex; align-items:center; gap:.45rem;
                      padding:.65rem 1.4rem; background:#cd3529; color:white;
                      border-radius:4px; font-size:.875rem; font-weight:700;
                      letter-spacing:.04em; text-decoration:none; white-space:nowrap;"
               onmouseover="this.style.background='#e04a3e';"
               onmouseout="this.style.background='#cd3529';">
                + Nueva cotización
            </a>

        {{-- Recuadro TC --}}
        <div style="background:linear-gradient(135deg,#1f103b,#3d2372); border-radius:10px;
                    padding:.9rem 1.25rem; display:flex; flex-direction:column; align-items:flex-end; min-width:150px;">
            <p style="font-size:.6rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase;
                       color:rgba(255,255,255,0.45); margin-bottom:.25rem;">USD / MXN</p>
            <p style="font-family:'Bebas Neue',sans-serif; font-size:2rem; letter-spacing:.04em;
                       color:white; line-height:1;">
                ${{ $tc ? number_format($tc->valor, 4) : '—' }}
            </p>
            <p style="font-size:.62rem; color:rgba(255,255,255,0.35); margin-top:.2rem;">
                {{ $tc ? 'Banxico · ' . $tc->actualizado_en->format('d/m/Y') : 'Sin datos' }}
            </p>
        </div>
        </div>{{-- /flex wrapper --}}
    </div>

    {{-- Stats --}}
    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; margin-bottom:2rem;">
        <style>@media(min-width:768px){.pricing-stats{grid-template-columns:repeat(4,1fr)!important;}}</style>
        <div class="pricing-stats" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; grid-column:1/-1;">
            @foreach([
                ['label'=>'Nuevas',       'key'=>'nueva',       'color'=>'#3d2372', 'icon'=>'🆕'],
                ['label'=>'En revisión',  'key'=>'en_revision', 'color'=>'#92400e', 'icon'=>'🔍'],
                ['label'=>'Cotizadas',    'key'=>'cotizada',    'color'=>'#065f46', 'icon'=>'✅'],
                ['label'=>'Enviadas',     'key'=>'enviada',     'color'=>'#1e40af', 'icon'=>'📬'],
            ] as $s)
            <div class="v-stat" style="border-left:3px solid {{ $s['color'] }};">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div class="v-stat__number" style="color:{{ $s['color'] }};">{{ $stats[$s['key']] }}</div>
                    <span style="font-size:1.4rem; opacity:.5;">{{ $s['icon'] }}</span>
                </div>
                <div class="v-stat__label">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Filtros --}}
    <div style="background:white; border:1px solid rgba(61,35,114,0.1); border-radius:12px;
                padding:1rem 1.25rem; margin-bottom:1.25rem;
                display:flex; gap:1rem; flex-wrap:wrap; align-items:center;
                box-shadow:0 2px 8px rgba(61,35,114,0.06);">
        <span style="font-size:.72rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
                     color:#9490b0; white-space:nowrap;">Filtrar:</span>

        <select wire:model.live="filtroEstado"
            style="border:1px solid rgba(61,35,114,0.2); border-radius:4px; padding:.5rem .9rem;
                   font-size:.85rem; color:#1f103b; background:white; font-family:'DM Sans',sans-serif;
                   cursor:pointer; outline:none;">
            <option value="">Todos los estados</option>
            @foreach(['nueva'=>'Nueva','en_revision'=>'En revisión','cotizada'=>'Cotizada','enviada'=>'Enviada','rechazada'=>'Rechazada'] as $val=>$label)
            <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>

        @if(auth()->user()->rol === 'admin')
        <select wire:model.live="filtroAsignado"
            style="border:1px solid rgba(61,35,114,0.2); border-radius:4px; padding:.5rem .9rem;
                   font-size:.85rem; color:#1f103b; background:white; font-family:'DM Sans',sans-serif;
                   cursor:pointer; outline:none;">
            <option value="">Todos los asignados</option>
            @foreach($equipoPricing as $u)
            <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>
        @endif

        @if($filtroEstado || $filtroAsignado)
        <button wire:click="$set('filtroEstado',''); $set('filtroAsignado','')"
            style="font-size:.78rem; color:#cd3529; background:none; border:none;
                   cursor:pointer; font-family:'DM Sans',sans-serif; font-weight:600;">
            × Limpiar
        </button>
        @endif
    </div>

    {{-- Tabla --}}
    <div class="v-table-wrap">
        <table style="width:100%; border-collapse:collapse;">
            <thead class="v-table-head">
                <tr>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Transporte</th>
                    <th>Estado</th>
                    <th>Asignado a</th>
                    <th style="display:none;" class="th-date-p">Fecha</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
                <style>@media(min-width:1024px){.th-date-p{display:table-cell!important;}}</style>
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
                @endphp
                <tr class="v-table-row">
                    <td>
                        <a href="{{ route('pricing.solicitud', $sol->id) }}"
                           style="font-family:monospace; font-weight:700; color:#3d2372;
                                  text-decoration:none; font-size:.875rem;">
                            {{ $sol->folio }}
                        </a>
                    </td>
                    <td style="font-weight:500; color:#1f103b;">
                        {{ Str::limit($sol->cliente_nombre, 28) }}
                    </td>
                    <td style="color:#5a4e80; font-size:.85rem;">
                        {{ \App\Helpers\Formato::transporte($sol->tipo_transporte) }}
                    </td>
                    <td>
                        <span class="v-badge {{ $badgeClass }}">
                            {{ \App\Helpers\Formato::estado($sol->estado) }}
                        </span>
                    </td>
                    <td style="color:#9490b0; font-size:.85rem;">
                        @if($sol->asignadoA)
                        <div style="display:flex; align-items:center; gap:.4rem;">
                            <div style="width:22px; height:22px; border-radius:50%; flex-shrink:0;
                                        background:linear-gradient(135deg,#cd3529,#3d2372);
                                        display:flex; align-items:center; justify-content:center;
                                        color:white; font-size:.6rem; font-weight:700;">
                                {{ strtoupper(substr($sol->asignadoA->name,0,1)) }}
                            </div>
                            {{ $sol->asignadoA->name }}
                        </div>
                        @else
                        <span style="color:#cdc9e8;">— Sin asignar</span>
                        @endif
                    </td>
                    <td style="display:none; font-size:.8rem; color:#9490b0;" class="td-date-p">
                        {{ $sol->created_at->format('d/m/Y') }}
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex; gap:.5rem; justify-content:center; flex-wrap:wrap;">
                            <a href="{{ route('pricing.solicitud', $sol->id) }}"
                               style="font-size:.75rem; font-weight:700; letter-spacing:.06em;
                                      padding:.35rem .9rem; border-radius:4px;
                                      background:#3d2372; color:white; text-decoration:none;
                                      transition:background .2s;"
                               onmouseover="this.style.background='#5035a0';"
                               onmouseout="this.style.background='#3d2372';">
                                Ver
                            </a>
                            <a href="{{ route('pricing.cotizador', $sol->id) }}"
                               style="font-size:.75rem; font-weight:700; letter-spacing:.06em;
                                      padding:.35rem .9rem; border-radius:4px;
                                      background:#cd3529; color:white; text-decoration:none;
                                      transition:background .2s;"
                               onmouseover="this.style.background='#e04a3e';"
                               onmouseout="this.style.background='#cd3529';">
                                Cotizar
                            </a>
                        </div>
                    </td>
                </tr>
                <style>@media(min-width:1024px){.td-date-p{display:table-cell!important;}}</style>
                @empty
                <tr>
                    <td colspan="7" style="padding:4rem 1rem; text-align:center;">
                        <div style="font-size:3rem; margin-bottom:.75rem; opacity:.3;">📋</div>
                        <p style="color:#9490b0; font-size:.9rem;">No hay solicitudes disponibles.</p>
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
