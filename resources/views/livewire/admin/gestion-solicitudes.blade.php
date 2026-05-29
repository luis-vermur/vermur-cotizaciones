<div class="space-y-5">

    {{-- Header --}}
    <div>
        <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2rem; letter-spacing:.04em; color:#3d2372; line-height:1;">
            Solicitudes
        </h1>
        <p style="font-size:.82rem; color:#9490b0; margin-top:.15rem;">Todas las solicitudes del sistema</p>
    </div>

    @if(session('success'))
    <div style="padding:.75rem 1rem; background:#d1fae5; border:1px solid #6ee7b7; border-radius:8px;
                color:#065f46; font-size:.85rem; font-weight:600;">✓ {{ session('success') }}</div>
    @endif

    {{-- Filtros --}}
    <div style="background:white; border-radius:10px; padding:1rem 1.25rem;
                border:1px solid rgba(61,35,114,0.08); display:flex; gap:.75rem; flex-wrap:wrap; align-items:center;">
        <input wire:model.live="busqueda" type="text"
            style="border:1px solid rgba(61,35,114,0.18); border-radius:6px; padding:.45rem .85rem;
                   font-size:.85rem; font-family:'DM Sans',sans-serif; flex:1; min-width:180px; outline:none;"
            onfocus="this.style.borderColor='#3d2372';" onblur="this.style.borderColor='rgba(61,35,114,0.18)';"
            placeholder="Buscar folio o cliente...">
        <select wire:model.live="filtroEstado"
            style="border:1px solid rgba(61,35,114,0.18); border-radius:6px; padding:.45rem .85rem;
                   font-size:.85rem; font-family:'DM Sans',sans-serif; background:white; outline:none;">
            <option value="">Todos los estados</option>
            <option value="nueva">Nueva</option>
            <option value="en_revision">En revisión</option>
            <option value="cotizada">Cotizada</option>
            <option value="enviada">Enviada</option>
            <option value="rechazada">Rechazada</option>
        </select>
        <select wire:model.live="filtroTransporte"
            style="border:1px solid rgba(61,35,114,0.18); border-radius:6px; padding:.45rem .85rem;
                   font-size:.85rem; font-family:'DM Sans',sans-serif; background:white; outline:none;">
            <option value="">Todo transporte</option>
            <option value="maritimo">Marítimo</option>
            <option value="aereo">Aéreo</option>
            <option value="terrestre">Terrestre</option>
        </select>
    </div>

    {{-- Tabla --}}
    @php
    $estadoBadge = [
    'nueva' => 'background:#ede9fe;color:#3d1a8e;',
    'en_revision' => 'background:#fef3c7;color:#92400e;',
    'cotizada' => 'background:#d1fae5;color:#065f46;',
    'enviada' => 'background:#dbeafe;color:#1e40af;',
    'rechazada' => 'background:#fee2e2;color:#991b1b;',
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
                    <th style="text-align:center; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Estado</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Transporte</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Creado por</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Asignado</th>
                    <th style="text-align:left; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Fecha</th>
                    <th style="padding:.75rem .75rem; width:5rem;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitudes as $sol)
                <tr style="border-bottom:1px solid rgba(61,35,114,0.06);"
                    onmouseover="this.style.background='rgba(61,35,114,0.02)';"
                    onmouseout="this.style.background='';">
                    <td style="padding:.7rem 1.25rem;">
                        <a href="{{ route('pricing.solicitud', $sol->id) }}"
                            style="font-family:monospace; font-weight:700; color:#3d2372; font-size:.82rem; text-decoration:none;"
                            onmouseover="this.style.textDecoration='underline';"
                            onmouseout="this.style.textDecoration='none';">
                            {{ $sol->folio }}
                        </a>
                    </td>
                    <td style="padding:.7rem .75rem; color:#1f103b; font-weight:500;">{{ $sol->cliente_nombre }}</td>
                    <td style="padding:.7rem .75rem; text-align:center;">
                        <span style="font-size:.72rem; font-weight:700; padding:.2rem .55rem; border-radius:999px; {{ $estadoBadge[$sol->estado] ?? '' }}">
                            {{ $estadoLabel[$sol->estado] ?? $sol->estado }}
                        </span>
                    </td>
                    <td style="padding:.7rem .75rem; color:#5a4e80; text-transform:capitalize;">{{ $sol->tipo_transporte }}</td>
                    <td style="padding:.7rem .75rem; color:#5a4e80; font-size:.8rem;">{{ $sol->creadoPor?->name ?? '—' }}</td>
                    <td style="padding:.7rem .75rem; color:#5a4e80; font-size:.8rem;">{{ $sol->asignadoA?->name ?? '—' }}</td>
                    <td style="padding:.7rem .75rem; color:#9490b0; font-size:.78rem; white-space:nowrap;">
                        {{ $sol->created_at->format('d/m/Y') }}
                    </td>
                    <td style="padding:.7rem .75rem; text-align:right;">
                        <button wire:click="abrirGestionar({{ $sol->id }})"
                            style="padding:.3rem .75rem; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                                   font-size:.75rem; color:#3d2372; background:white; cursor:pointer; font-family:'DM Sans',sans-serif;"
                            onmouseover="this.style.background='#f0ecf8';" onmouseout="this.style.background='white';">
                            Gestionar
                        </button>
                        <button wire:click="eliminarSolicitud({{ $sol->id }})"
                            wire:confirm="¿Eliminar esta solicitud? Esta acción no se puede deshacer."
                            style="padding:.3rem .75rem; border:1px solid rgba(205,53,41,0.3); border-radius:4px;
                                font-size:.75rem; color:#cd3529; background:white; cursor:pointer; font-family:'DM Sans',sans-serif;"
                            onmouseover="this.style.background='#fff5f5';" onmouseout="this.style.background='white';">
                            Eliminar
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:3rem; text-align:center; color:#9490b0;">No se encontraron solicitudes.</td>
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

    {{-- Modal gestionar --}}
    @if($mostrarModal)
    <div style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center;
                background:rgba(0,0,0,0.45);" wire:click.self="$set('mostrarModal', false)">
        <div style="background:white; border-radius:16px; width:100%; max-width:26rem; margin:0 1rem;
                    box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
            <div style="padding:1rem 1.5rem; background:#3d2372; display:flex; align-items:center; justify-content:space-between;">
                <h3 style="color:white; font-weight:700; font-size:.95rem; font-family:'DM Sans',sans-serif;">
                    Gestionar solicitud
                </h3>
                <button wire:click="$set('mostrarModal', false)"
                    style="color:rgba(255,255,255,0.7); background:none; border:none; cursor:pointer; font-size:1.5rem; line-height:1;">×</button>
            </div>
            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">
                <div>
                    <label style="display:block; font-size:.72rem; font-weight:700; color:#9490b0;
                                   letter-spacing:.08em; text-transform:uppercase; margin-bottom:.35rem;">Estado</label>
                    <select wire:model="nuevoEstado"
                        style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:6px;
                               padding:.55rem .85rem; font-size:.875rem; font-family:'DM Sans',sans-serif; background:white;">
                        <option value="nueva">Nueva</option>
                        <option value="en_revision">En revisión</option>
                        <option value="cotizada">Cotizada</option>
                        <option value="enviada">Enviada</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:.72rem; font-weight:700; color:#9490b0;
                                   letter-spacing:.08em; text-transform:uppercase; margin-bottom:.35rem;">Asignar a Pricing</label>
                    <select wire:model="asignadoA"
                        style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:6px;
                               padding:.55rem .85rem; font-size:.875rem; font-family:'DM Sans',sans-serif; background:white;">
                        <option value="">— Sin asignar —</option>
                        @foreach($pricingUsers as $pu)
                        <option value="{{ $pu->id }}">{{ $pu->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:.72rem; font-weight:700; color:#9490b0;
                                   letter-spacing:.08em; text-transform:uppercase; margin-bottom:.35rem;">
                        Motivo {{ $nuevoEstado === 'rechazada' ? '(requerido)' : '(opcional)' }}
                    </label>
                    <textarea wire:model="motivo" rows="2"
                        style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:6px;
                               padding:.55rem .85rem; font-size:.875rem; font-family:'DM Sans',sans-serif; resize:vertical;"
                        placeholder="Motivo del cambio..."></textarea>
                    @error('motivo')<p style="color:#cd3529;font-size:.72rem;margin-top:.2rem;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="padding:.85rem 1.5rem; border-top:1px solid rgba(61,35,114,0.1);
                        background:#f8f7fc; display:flex; justify-content:flex-end; gap:.75rem;">
                <button wire:click="$set('mostrarModal', false)"
                    style="padding:.5rem 1.1rem; background:white; border:1px solid rgba(61,35,114,0.2);
                           border-radius:6px; font-size:.875rem; color:#5a4e80; cursor:pointer; font-family:'DM Sans',sans-serif;">
                    Cancelar
                </button>
                <button wire:click="guardarGestion"
                    style="padding:.5rem 1.4rem; background:#3d2372; color:white; border:none;
                           border-radius:6px; font-size:.875rem; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif;"
                    onmouseover="this.style.background='#5035a0';" onmouseout="this.style.background='#3d2372';">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>