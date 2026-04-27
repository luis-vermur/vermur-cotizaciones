<div style="max-width:80rem; margin:0 auto;" class="space-y-5 pb-10">

    {{-- Header --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
            <a href="{{ route('pricing.solicitud', $solicitud->id) }}"
               style="font-size:.82rem; color:#9490b0; text-decoration:none; font-weight:500;"
               onmouseover="this.style.color='#3d2372';" onmouseout="this.style.color='#9490b0';">
               ← Volver
            </a>
            <div>
                <h1 style="font-family:'Bebas Neue',sans-serif; font-size:1.8rem;
                           letter-spacing:.04em; color:#3d2372; line-height:1;">
                    Cotizador
                </h1>
                <p style="font-size:.82rem; color:#9490b0; margin-top:.15rem;">
                    <span style="font-family:monospace; color:#3d2372; font-weight:700;">{{ $solicitud->folio }}</span>
                    — {{ $solicitud->cliente_nombre }} · {{ $solicitud->dias_credito }} días crédito
                </p>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:.75rem;">
            <div style="text-align:right;">
                <p style="font-size:.68rem; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:#9490b0;">
                    Versión activa
                </p>
                <p style="font-family:'Bebas Neue',sans-serif; font-size:2.2rem;
                           letter-spacing:.04em; color:#3d2372; line-height:1;">
                    V{{ $version }}
                </p>
            </div>
            <button wire:click="nuevaVersion"
                style="padding:.55rem 1.1rem; border:1px solid rgba(61,35,114,0.25);
                       border-radius:4px; color:#3d2372; background:white; font-size:.82rem;
                       font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif;
                       transition:background .2s, border-color .2s;"
                onmouseover="this.style.background='#f0ecf8';this.style.borderColor='#3d2372';"
                onmouseout="this.style.background='white';this.style.borderColor='rgba(61,35,114,0.25)';">
                + Nueva versión
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="v-alert v-alert-success"><span>✓</span><span>{{ session('success') }}</span></div>
    @endif
    @if(session('info'))
    <div class="v-alert v-alert-info"><span>ℹ</span><span>{{ session('info') }}</span></div>
    @endif
    @if(session('error'))
    <div class="v-alert" style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; border-radius:8px; padding:.75rem 1rem; font-size:.875rem; font-weight:600; display:flex; gap:.5rem; align-items:center;">
        <span>✕</span><span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Configuración --}}
    <div class="vcard" style="padding:1.25rem 1.5rem;">
        <div class="v-section-header">Configuración de cotización</div>
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem;">
            <style>@media(min-width:1024px){.cfg-grid{grid-template-columns:repeat(6,1fr)!important;}}</style>
            <div class="cfg-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; grid-column:1/-1;">
            <div>
                <label style="display:block; font-size:.72rem; font-weight:600; color:#9490b0;
                               letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem;">Plantilla</label>
                <select wire:model.live="tipo_plantilla"
                    style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.5rem .75rem; font-size:.875rem; font-family:'DM Sans',sans-serif; background:white;">
                    <option value="MXN">Nacional MXN</option>
                    <option value="USD">Internacional USD</option>
                    <option value="LCL">Marítimo LCL</option>
                    <option value="terrestre">Terrestre</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:.72rem; font-weight:600; color:#9490b0;
                               letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem;">TC (Tipo de cambio)</label>
                <input wire:model.live="tc" type="number" step="0.01"
                    style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.5rem .75rem; font-size:.875rem; font-family:'DM Sans',sans-serif;"
                    placeholder="0.00">
            </div>
            <div>
                <label style="display:block; font-size:.72rem; font-weight:600; color:#9490b0;
                               letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem;">Margen deseado %</label>
                <input wire:model.live="margen_deseado" type="number" step="0.1"
                    style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.5rem .75rem; font-size:.875rem; font-family:'DM Sans',sans-serif;"
                    placeholder="0">
            </div>
            <div>
                <label style="display:block; font-size:.72rem; font-weight:600; color:#9490b0;
                               letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem;">Costo operación</label>
                <input wire:model.live="costo_ope" type="number"
                    style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.5rem .75rem; font-size:.875rem; font-family:'DM Sans',sans-serif;">
            </div>
            <div>
                <label style="display:block; font-size:.72rem; font-weight:600; color:#9490b0;
                               letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem;">Validez</label>
                <input wire:model="validez" type="date"
                    style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.5rem .75rem; font-size:.875rem; font-family:'DM Sans',sans-serif;">
            </div>
            <div>
                <label style="display:block; font-size:.72rem; font-weight:600; color:#9490b0;
                               letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem;">Días crédito</label>
                <div style="border:1px solid rgba(61,35,114,0.12); background:#f8f7fc; border-radius:4px;
                            padding:.5rem .75rem; font-size:.875rem; color:#9490b0; font-family:monospace;">
                    {{ $solicitud->dias_credito }} días
                </div>
            </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN TARIFAS --}}
    <div class="vcard" id="seccionTarifas" style="overflow:hidden;">
        <div style="display:flex; justify-content:space-between; align-items:center;
                    padding:1rem 1.5rem; border-bottom:1px solid rgba(61,35,114,0.1);">
            <div>
                <p class="v-tag" style="margin-bottom:.1rem;">Tarifas</p>
                <h2 style="font-family:'Bebas Neue',sans-serif; font-size:1.3rem;
                           letter-spacing:.04em; color:#3d2372; line-height:1;">
                    Comparativa de agentes
                    <span style="font-family:'DM Sans',sans-serif; font-size:.78rem;
                                 color:#9490b0; font-weight:400; letter-spacing:0;">
                        ({{ count($agentes) }} agentes · {{ count($tarifas) }} conceptos)
                    </span>
                </h2>
            </div>
            <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                <button wire:click="abrirModalTarifasAnt"
                    style="padding:.35rem .85rem; background:white; border:1px solid rgba(61,35,114,0.2);
                           border-radius:4px; font-size:.75rem; color:#5a4e80; cursor:pointer;
                           font-family:'DM Sans',sans-serif;"
                    onmouseover="this.style.background='#f0ecf8';"
                    onmouseout="this.style.background='white';">
                    ↺ Desde anterior
                </button>
                <button wire:click="agregarConceptoTarifa"
                    style="padding:.35rem .85rem; background:white; border:1px solid rgba(61,35,114,0.2);
                           border-radius:4px; font-size:.75rem; color:#5a4e80; cursor:pointer;
                           font-family:'DM Sans',sans-serif;"
                    onmouseover="this.style.background='#f0ecf8';"
                    onmouseout="this.style.background='white';">
                    + Concepto
                </button>
                <button wire:click="$set('mostrarModalAgente', true)"
                    style="padding:.35rem .85rem; background:white; border:1px solid #3d2372;
                           border-radius:4px; font-size:.75rem; color:#3d2372; cursor:pointer;
                           font-family:'DM Sans',sans-serif; font-weight:600;"
                    onmouseover="this.style.background='#f0ecf8';"
                    onmouseout="this.style.background='white';">
                    + Agente
                </button>
                <button wire:click="guardarTarifas"
                    style="padding:.35rem .85rem; background:#3d2372; color:white; border:none;
                           border-radius:4px; font-size:.75rem; font-weight:600; cursor:pointer;
                           font-family:'DM Sans',sans-serif;"
                    onmouseover="this.style.background='#5035a0';"
                    onmouseout="this.style.background='#3d2372';">
                    Guardar tarifas
                </button>
            </div>
        </div>

        @if(session('success_tarifas'))
        <div style="padding:.4rem 1.25rem; background:#ecfdf5; border-bottom:1px solid #a7f3d0;
                    color:#065f46; font-size:.78rem; font-weight:600;">
            ✓ {{ session('success_tarifas') }}
        </div>
        @endif

        {{-- Modal: cargar tarifas de solicitud anterior --}}
        @if($mostrarModalTarifasAnt)
        <div style="position:fixed; inset:0; z-index:50; display:flex; align-items:center; justify-content:center;
                    background:rgba(0,0,0,0.4);">
            <div style="background:white; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2);
                        width:100%; max-width:32rem; margin:0 1rem; overflow:hidden;">
                <div style="padding:1rem 1.5rem; background:#3d2372; display:flex; align-items:center; justify-content:space-between;">
                    <h3 style="color:white; font-weight:600; font-size:.95rem; font-family:'DM Sans',sans-serif;">
                        Cargar tarifas de solicitud anterior
                    </h3>
                    <button wire:click="$set('mostrarModalTarifasAnt', false)"
                        style="color:rgba(255,255,255,0.7); background:none; border:none; cursor:pointer; font-size:1.4rem; line-height:1;"
                        onmouseover="this.style.color='white';" onmouseout="this.style.color='rgba(255,255,255,0.7)';">×</button>
                </div>
                <div style="padding:1.25rem; display:flex; flex-direction:column; gap:1rem;">
                    <input wire:model.live="busquedaTarifasAnt"
                        type="text"
                        style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:6px;
                               padding:.55rem 1rem; font-size:.875rem; font-family:'DM Sans',sans-serif; outline:none;"
                        onfocus="this.style.borderColor='#3d2372';" onblur="this.style.borderColor='rgba(61,35,114,0.2)';"
                        placeholder="Buscar por folio o cliente...">

                    <div style="max-height:16rem; overflow-y:auto; border-radius:8px; border:1px solid rgba(61,35,114,0.12);">
                        @forelse($solicitudesConTarifas as $t)
                        <label wire:key="ant-{{ $t->solicitud_id }}"
                            style="display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; cursor:pointer;
                                   border-bottom:1px solid rgba(61,35,114,0.07);
                                   background:{{ $solicitudAntId == $t->solicitud_id ? '#f0ecf8' : 'white' }};"
                            onmouseover="if('{{ $solicitudAntId }}' != '{{ $t->solicitud_id }}') this.style.background='#f8f7fc';"
                            onmouseout="if('{{ $solicitudAntId }}' != '{{ $t->solicitud_id }}') this.style.background='white';">
                            <input type="radio" wire:model.live="solicitudAntId" value="{{ $t->solicitud_id }}"
                                style="accent-color:#3d2372;">
                            <div style="min-width:0; overflow:hidden;">
                                <p style="font-size:.875rem; font-weight:700; font-family:monospace; color:#3d2372;">
                                    {{ $t->solicitud->folio }}
                                </p>
                                <p style="font-size:.75rem; color:#9490b0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $t->solicitud->cliente_nombre }}
                                    · actualizado {{ $t->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </label>
                        @empty
                        <div style="padding:1.5rem 1rem; text-align:center; color:#9490b0; font-size:.875rem;">
                            No hay solicitudes con tarifas guardadas.
                        </div>
                        @endforelse
                    </div>
                </div>
                <div style="padding:.85rem 1.5rem; border-top:1px solid rgba(61,35,114,0.1);
                            background:#f8f7fc; display:flex; justify-content:flex-end; gap:.75rem;">
                    <button wire:click="$set('mostrarModalTarifasAnt', false)"
                        style="padding:.5rem 1.1rem; background:white; border:1px solid rgba(61,35,114,0.2);
                               border-radius:4px; font-size:.875rem; color:#5a4e80; cursor:pointer;
                               font-family:'DM Sans',sans-serif;">
                        Cancelar
                    </button>
                    <button wire:click="cargarTarifasDeAnterior"
                        @if(!$solicitudAntId) disabled @endif
                        style="padding:.5rem 1.25rem; background:#3d2372; color:white; border:none;
                               border-radius:4px; font-size:.875rem; font-weight:600; cursor:pointer;
                               font-family:'DM Sans',sans-serif; opacity:{{ $solicitudAntId ? '1' : '.4' }};">
                        Cargar tarifas
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Modal agregar agente --}}
        @if($mostrarModalAgente)
        <div style="padding:.85rem 1.25rem; background:#f0ecf8; border-bottom:1px solid rgba(61,35,114,0.12);">
            <p style="font-size:.72rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
                       color:#3d2372; margin-bottom:.6rem;">Agregar agente</p>
            <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
                <select wire:model.live="nuevoAgenteProveedorId"
                    style="border:1px solid rgba(61,35,114,0.25); border-radius:4px; padding:.4rem .75rem;
                           font-size:.82rem; background:white; font-family:'DM Sans',sans-serif; min-width:18rem;">
                    <option value="">— Seleccionar proveedor —</option>
                    @foreach($proveedores as $p)
                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
                <button wire:click="agregarAgente"
                    @if(!$nuevoAgenteProveedorId) disabled @endif
                    style="padding:.4rem 1rem; background:#3d2372; color:white; border:none;
                           border-radius:4px; font-size:.82rem; font-weight:600; cursor:pointer;
                           font-family:'DM Sans',sans-serif;
                           opacity:{{ $nuevoAgenteProveedorId ? '1' : '.4' }};">
                    Agregar
                </button>
                <button wire:click="$set('mostrarModalAgente', false)"
                    style="padding:.4rem .85rem; background:white; border:1px solid rgba(61,35,114,0.2);
                           border-radius:4px; font-size:.82rem; color:#5a4e80; cursor:pointer;
                           font-family:'DM Sans',sans-serif;">
                    Cancelar
                </button>
                <button wire:click="$toggle('mostrarCrearProveedor')"
                    style="padding:.4rem .85rem; background:transparent; border:1px dashed rgba(61,35,114,0.3);
                           border-radius:4px; font-size:.78rem; color:#5a4e80; cursor:pointer;
                           font-family:'DM Sans',sans-serif;"
                    onmouseover="this.style.borderColor='#3d2372';this.style.color='#3d2372';"
                    onmouseout="this.style.borderColor='rgba(61,35,114,0.3)';this.style.color='#5a4e80';">
                    + Nuevo proveedor
                </button>
            </div>
            @if($mostrarCrearProveedor)
            <div style="margin-top:.65rem; padding:.65rem .85rem; background:white;
                        border-radius:6px; border:1px solid rgba(61,35,114,0.18);
                        display:flex; align-items:center; gap:.6rem; flex-wrap:wrap;">
                <input wire:model="nuevoProveedorNombre"
                    type="text"
                    style="flex:1; min-width:12rem; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.38rem .65rem; font-size:.82rem; font-family:'DM Sans',sans-serif;"
                    placeholder="Nombre del proveedor"
                    wire:keydown.enter="crearProveedor">
                <button wire:click="crearProveedor"
                    style="padding:.38rem .9rem; background:#3d2372; color:white; border:none;
                           border-radius:4px; font-size:.78rem; font-weight:600; cursor:pointer;
                           font-family:'DM Sans',sans-serif;">
                    Crear y seleccionar
                </button>
                @error('nuevoProveedorNombre')
                <span style="color:#cd3529; font-size:.72rem; width:100%;">{{ $message }}</span>
                @enderror
            </div>
            @endif
        </div>
        @endif

        @if(count($agentes) === 0)
        <div style="padding:3rem 1rem; text-align:center;">
            <p style="font-size:2rem; margin-bottom:.5rem; opacity:.3;">📊</p>
            <p style="color:#9490b0; font-size:.875rem; margin-bottom:.75rem;">Agrega agentes para comparar tarifas.</p>
            <button wire:click="$set('mostrarModalAgente', true)"
                style="padding:.45rem 1rem; background:#3d2372; color:white; border:none;
                       border-radius:4px; font-size:.82rem; font-weight:600; cursor:pointer;
                       font-family:'DM Sans',sans-serif;">
                + Agregar primer agente
            </button>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table style="width:100%; font-size:.78rem; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f7fc; border-bottom:1px solid rgba(61,35,114,0.1);">
                        <th style="padding:.65rem 1rem; text-align:left; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#9490b0; width:12rem;">Concepto</th>
                        @foreach($agentes as $ai => $agente)
                        <th style="padding:.65rem .75rem; text-align:center; font-weight:700; min-width:8rem; color:#3d2372;">
                            <div style="display:flex; align-items:center; justify-content:center; gap:.25rem;">
                                <span>{{ $agente }}</span>
                                <button wire:click="eliminarAgente({{ $ai }})"
                                    style="color:#cdc9e8; background:none; border:none; cursor:pointer;
                                           font-size:1rem; line-height:1; margin-left:.2rem;"
                                    onmouseover="this.style.color='#cd3529';"
                                    onmouseout="this.style.color='#cdc9e8';">×</button>
                            </div>
                        </th>
                        @endforeach
                        <th style="padding:.65rem .75rem; width:2rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tarifas as $key => $fila)
                    <tr style="border-bottom:1px solid rgba(61,35,114,0.06);" wire:key="tarifa-{{ $key }}"
                        onmouseover="this.style.background='rgba(61,35,114,0.03)';"
                        onmouseout="this.style.background='';">
                        <td style="padding:.5rem .75rem;">
                            <input wire:model.live="etiquetas.{{ $key }}"
                                type="text"
                                style="width:100%; border:1px solid rgba(61,35,114,0.15); border-radius:3px;
                                       padding:.3rem .5rem; font-size:.78rem; font-weight:500;
                                       font-family:'DM Sans',sans-serif;"
                                placeholder="Concepto">
                        </td>
                        @foreach($agentes as $ai => $agente)
                        <td style="padding:.4rem .5rem;">
                            @php $esValidez = strtoupper(trim($etiquetas[$key] ?? '')) === 'VALIDEZ'; @endphp
                            <input wire:model.live="tarifas.{{ $key }}.{{ $ai }}"
                                type="{{ $esValidez ? 'date' : 'number' }}"
                                step="{{ $esValidez ? '' : '0.01' }}"
                                style="width:100%; border:1px solid rgba(61,35,114,0.15); border-radius:3px;
                                       padding:.3rem .5rem; font-size:.78rem;
                                       text-align:{{ $esValidez ? 'left' : 'right' }}; font-family:monospace;"
                                placeholder="{{ $esValidez ? '' : '0.00' }}">
                        </td>
                        @endforeach
                        <td style="padding:.4rem .5rem; text-align:center;">
                            <button wire:click="eliminarConceptoTarifa('{{ $key }}')"
                                style="color:#cdc9e8; background:none; border:none; cursor:pointer; font-size:1.1rem; line-height:1;"
                                onmouseover="this.style.color='#cd3529';"
                                onmouseout="this.style.color='#cdc9e8';">×</button>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Totales --}}
                    @if(count($tarifas) > 0)
                    <tr style="border-top:2px solid rgba(61,35,114,0.15); background:#f8f7fc; font-weight:700;">
                        <td style="padding:.65rem 1rem; font-size:.75rem; color:#9490b0; letter-spacing:.08em; text-transform:uppercase;">TOTAL</td>
                        @foreach($agentes as $ai => $agente)
                        @php
                        $total = collect($tarifas)->sum(fn($f) => floatval($f[$ai] ?? 0));
                        $totales = collect($agentes)->keys()
                        ->map(fn($i) => collect($tarifas)->sum(fn($f) => floatval($f[$i] ?? 0)));
                        $minTotal = $totales->min();
                        @endphp
                        <td style="padding:.65rem .75rem; text-align:center; font-family:monospace; font-size:.875rem;
                                   color:{{ $total === $minTotal && $total > 0 ? '#065f46' : '#1f103b' }};">
                            ${{ number_format($total, 2) }}
                            @if($total === $minTotal && $total > 0)
                            <span style="display:block; font-size:.7rem; color:#059669; font-weight:600;">✓ Menor</span>
                            @endif
                        </td>
                        @endforeach
                        <td></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Botón cargar en líneas --}}
        @if(count($tarifas) > 0)
        <div style="padding:.65rem 1.25rem; border-top:1px solid rgba(61,35,114,0.08); display:flex; justify-content:flex-end;">
            <button wire:click="cargarTarifasEnLineas"
                style="padding:.45rem 1.1rem; background:#059669; color:white; border:none;
                       border-radius:4px; font-size:.82rem; font-weight:600; cursor:pointer;
                       font-family:'DM Sans',sans-serif;">
                ↓ Cargar mejor tarifa en líneas de cotización
            </button>
        </div>
        @endif
        @endif
    </div>

    {{-- Imágenes / documentos de referencia (solo pricing/admin) --}}
    @if(in_array(auth()->user()->rol, ['pricing','admin']))
    <div class="vcard" style="overflow:hidden;">
        <div style="display:flex; justify-content:space-between; align-items:center;
                    padding:1rem 1.5rem; border-bottom:1px solid rgba(61,35,114,0.1);">
            <div>
                <p class="v-tag" style="margin-bottom:.1rem;">Referencia</p>
                <h2 style="font-family:'Bebas Neue',sans-serif; font-size:1.3rem;
                           letter-spacing:.04em; color:#3d2372; line-height:1;">
                    Imágenes y documentos
                    <span style="font-family:'DM Sans',sans-serif; font-size:.78rem;
                                 color:#9490b0; font-weight:400; letter-spacing:0;">
                        ({{ $adjuntosRef->count() }})
                    </span>
                </h2>
            </div>
            <div style="display:flex; align-items:center; gap:.5rem;">
                <label style="padding:.45rem 1rem; background:#3d2372; color:white; border-radius:4px;
                               font-size:.78rem; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif;
                               display:inline-flex; align-items:center; gap:.4rem;"
                    onmouseover="this.style.background='#5035a0';"
                    onmouseout="this.style.background='#3d2372';">
                    📎 Seleccionar archivos
                    <input type="file" wire:model="imagenesSubir" multiple
                        accept=".jpg,.jpeg,.png,.webp,.heic,.pdf"
                        style="display:none;">
                </label>
                @if(count($imagenesSubir) > 0)
                <button wire:click="subirImagenes"
                    wire:loading.attr="disabled"
                    style="padding:.45rem 1rem; background:#059669; color:white; border:none;
                           border-radius:4px; font-size:.78rem; font-weight:600; cursor:pointer;
                           font-family:'DM Sans',sans-serif; display:inline-flex; align-items:center; gap:.4rem;"
                    onmouseover="this.style.background='#047857';"
                    onmouseout="this.style.background='#059669';">
                    <span wire:loading.remove wire:target="subirImagenes">↑ Subir ({{ count($imagenesSubir) }})</span>
                    <span wire:loading wire:target="subirImagenes">Subiendo...</span>
                </button>
                @endif
                <span wire:loading wire:target="imagenesSubir"
                    style="font-size:.75rem; color:#7c6fb0;">Procesando...</span>
            </div>
        </div>

        @if(session('success_img'))
        <div style="padding:.4rem 1.25rem; background:#ecfdf5; border-bottom:1px solid #a7f3d0;
                    color:#065f46; font-size:.78rem; font-weight:600;">
            ✓ {{ session('success_img') }}
        </div>
        @endif

        {{-- Drag-drop area (si no hay adjuntos) --}}
        @if($adjuntosRef->count() === 0)
        <div style="padding:2.5rem 1.5rem; text-align:center;">
            <p style="font-size:2rem; margin-bottom:.5rem; opacity:.3;">🖼️</p>
            <p style="color:#9490b0; font-size:.875rem; margin-bottom:.4rem;">Sin archivos adjuntos.</p>
            <p style="color:#cdc9e8; font-size:.78rem;">Sube imágenes o PDFs de referencia para esta cotización.</p>
        </div>
        @else

        {{-- Galería con lightbox --}}
        <div x-data="{ lightboxOpen: false, lightboxSrc: '', lightboxName: '' }"
             style="padding:1.25rem 1.5rem;">
            <style>@media(min-width:768px){.ref-grid{grid-template-columns:repeat(5,1fr)!important;}}</style>
            <div class="ref-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:.75rem;">
            @foreach($adjuntosRef as $adj)
            @php
                $ext = strtolower(pathinfo($adj->ruta, PATHINFO_EXTENSION));
                $esImagen = in_array($ext, ['jpg','jpeg','png','webp','heic']);
            @endphp
            <div style="position:relative; border-radius:8px; overflow:hidden;
                        border:1px solid rgba(61,35,114,0.1); background:#f8f7fc;"
                onmouseover="this.querySelector('.adj-actions').style.opacity='1';"
                onmouseout="this.querySelector('.adj-actions').style.opacity='0';">
                @if($esImagen)
                <img src="{{ Storage::url($adj->ruta) }}" alt="{{ $adj->nombre_archivo }}"
                    style="width:100%; aspect-ratio:1; object-fit:cover; display:block; cursor:zoom-in;"
                    @click="lightboxOpen = true; lightboxSrc = '{{ Storage::url($adj->ruta) }}'; lightboxName = '{{ $adj->nombre_archivo }}'">
                @else
                <a href="{{ Storage::url($adj->ruta) }}" target="_blank"
                    style="display:flex; flex-direction:column; align-items:center; justify-content:center;
                           aspect-ratio:1; text-decoration:none;">
                    <span style="font-size:2rem; margin-bottom:.3rem;">📄</span>
                    <span style="font-size:.68rem; color:#9490b0; text-align:center; padding:0 .4rem;
                                 overflow:hidden; text-overflow:ellipsis; white-space:nowrap; width:100%;">
                        {{ $adj->nombre_archivo }}
                    </span>
                </a>
                @endif

                {{-- Botón eliminar --}}
                <div class="adj-actions" style="position:absolute; top:.3rem; right:.3rem; opacity:0; transition:opacity .2s;">
                    <button wire:click="eliminarAdjunto({{ $adj->id }})"
                        wire:confirm="¿Eliminar este archivo?"
                        style="background:rgba(205,53,41,0.9); color:white; border:none; border-radius:50%;
                               width:22px; height:22px; font-size:.85rem; cursor:pointer; line-height:1;
                               display:flex; align-items:center; justify-content:center;">×</button>
                </div>
            </div>
            @endforeach
            </div>

            {{-- Lightbox overlay --}}
            <div x-show="lightboxOpen"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click.self="lightboxOpen = false"
                 @keydown.escape.window="lightboxOpen = false"
                 style="display:none; position:fixed; inset:0; z-index:9999;
                        background:rgba(0,0,0,0.88); align-items:center; justify-content:center;"
                 x-bind:style="lightboxOpen ? 'display:flex' : 'display:none'">
                <div style="position:relative; max-width:90vw; max-height:90vh;">
                    <img :src="lightboxSrc" :alt="lightboxName"
                        style="max-width:90vw; max-height:85vh; border-radius:8px;
                               box-shadow:0 25px 60px rgba(0,0,0,0.5); object-fit:contain;">
                    <p x-text="lightboxName"
                       style="text-align:center; color:rgba(255,255,255,0.6); font-size:.75rem;
                              margin-top:.5rem; font-family:'DM Sans',sans-serif;"></p>
                </div>
                <button @click="lightboxOpen = false"
                    style="position:absolute; top:1.25rem; right:1.25rem; color:white; background:rgba(0,0,0,0.5);
                           border:none; border-radius:50%; width:36px; height:36px; font-size:1.4rem;
                           cursor:pointer; display:flex; align-items:center; justify-content:center;
                           line-height:1;">×</button>
            </div>
        </div>
        @endif
    </div>
    @endif {{-- /pricing only --}}

    {{-- Campos específicos LCL --}}
    @if($tipo_plantilla === 'LCL')
    <div class="vcard" style="padding:1.25rem 1.5rem;">
        <div class="v-section-header">Detalle de embarque LCL</div>

        {{-- Fila 1: datos del embarque --}}
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; margin-bottom:1rem;">
            <style>@media(min-width:768px){.lcl-top{grid-template-columns:repeat(3,1fr)!important;}}</style>
            <div class="lcl-top" style="display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; grid-column:1/-1;">
            @foreach([
                ['wire'=>'lcl_pol','label'=>'POL (Puerto origen)','type'=>'text','placeholder'=>'Ej: Shanghai'],
                ['wire'=>'lcl_pod','label'=>'POD (Puerto destino)','type'=>'text','placeholder'=>'Ej: Manzanillo'],
                ['wire'=>'lcl_incoterm','label'=>'Incoterm','type'=>'text','placeholder'=>'FOB, CIF...'],
                ['wire'=>'lcl_piezas','label'=>'Piezas','type'=>'number','placeholder'=>'0'],
                ['wire'=>'lcl_peso_tons','label'=>'Peso (tons)','type'=>'number','placeholder'=>'0.00'],
                ['wire'=>'lcl_medidas_cbm','label'=>'Medidas (CBM)','type'=>'number','placeholder'=>'0.00'],
            ] as $f)
            <div>
                <label style="display:block; font-size:.72rem; font-weight:600; color:#9490b0;
                               letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem;">{{ $f['label'] }}</label>
                <input wire:model.live="{{ $f['wire'] }}" type="{{ $f['type'] }}"
                    style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.5rem .75rem; font-size:.875rem; font-family:'DM Sans',sans-serif;"
                    placeholder="{{ $f['placeholder'] ?? '' }}">
            </div>
            @endforeach
            </div>
        </div>

        {{-- Costos LCL --}}
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; margin-bottom:1rem;">
            <style>@media(min-width:768px){.lcl-costs{grid-template-columns:repeat(3,1fr)!important;}}</style>
            <div class="lcl-costs" style="display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; grid-column:1/-1;">
            @foreach([
                ['wire'=>'lcl_pickup','label'=>'Pickup / Flete local'],
                ['wire'=>'lcl_despacho_mxn','label'=>'Despacho aduanal (MXN)'],
                ['wire'=>'lcl_maniobras_mxn','label'=>'Maniobras (MXN)'],
                ['wire'=>'lcl_desconsolidacion','label'=>'Desconsolidación'],
                ['wire'=>'lcl_transfer_fee','label'=>'Transfer fee'],
                ['wire'=>'lcl_revalidacion','label'=>'Revalidación'],
                ['wire'=>'lcl_transmision','label'=>'Transmisión'],
                ['wire'=>'lcl_admon_fee','label'=>'Admon. fee'],
                ['wire'=>'lcl_recargo_imo','label'=>'Recargo IMO'],
            ] as $f)
            <div>
                <label style="display:block; font-size:.72rem; font-weight:600; color:#9490b0;
                               letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem;">{{ $f['label'] }}</label>
                <input wire:model="{{ $f['wire'] }}" wire:change="recalcularLcl" type="number" step="0.01"
                    style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.5rem .75rem; font-size:.875rem; font-family:monospace; text-align:right;"
                    placeholder="0.00">
            </div>
            @endforeach
            </div>
        </div>

        {{-- Totales LCL --}}
        <div style="background:#f0ecf8; border-radius:8px; padding:1rem 1.25rem;
                    display:flex; gap:1.5rem; flex-wrap:wrap; align-items:center; justify-content:flex-end;">
            <div style="display:flex; align-items:center; gap:.5rem;">
                <span style="font-size:.75rem; color:#7c6fb0; text-transform:uppercase; letter-spacing:.08em;">Total local:</span>
                <span style="font-family:monospace; font-weight:700; color:#3d2372; font-size:1rem;">${{ number_format($lcl_total_local, 2) }}</span>
            </div>
            <div style="display:flex; align-items:center; gap:.5rem;">
                <span style="font-size:.75rem; color:#7c6fb0; text-transform:uppercase; letter-spacing:.08em;">IVA {{ $lcl_iva_pct }}%:</span>
                <input wire:model.live="lcl_iva_pct" type="number" min="0" max="100"
                    style="width:4rem; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                           padding:.3rem .5rem; font-size:.82rem; font-family:monospace; text-align:right; background:white;">
                <span style="font-family:monospace; font-weight:600; color:#3d2372;">${{ number_format($lcl_iva, 2) }}</span>
            </div>
            <div style="display:flex; align-items:center; gap:.5rem; padding-left:.75rem;
                        border-left:2px solid rgba(61,35,114,0.2);">
                <span style="font-size:.75rem; font-weight:700; color:#3d2372; text-transform:uppercase; letter-spacing:.08em;">Total + IVA:</span>
                <span style="font-family:monospace; font-weight:700; color:#cd3529; font-size:1.15rem;">${{ number_format($lcl_total_iva, 2) }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Layout 2 columnas: Líneas (izq) + Panel de profit sticky (der) --}}
    <style>@media(max-width:1100px){.coti-2col{grid-template-columns:1fr!important;}}</style>
    <div class="coti-2col" style="display:grid; grid-template-columns:1fr 290px; gap:1.25rem; align-items:start;">
    <div>{{-- Columna izquierda: tabla de líneas --}}

    {{-- Tabla de líneas --}}
    <div class="vcard" style="overflow:hidden;">
        <div style="display:flex; justify-content:space-between; align-items:center;
                    padding:1rem 1.5rem; border-bottom:1px solid rgba(61,35,114,0.1);">
            <div>
                <p class="v-tag" style="margin-bottom:.1rem;">Cotización</p>
                <h2 style="font-family:'Bebas Neue',sans-serif; font-size:1.3rem;
                           letter-spacing:.04em; color:#3d2372; line-height:1;">
                    Líneas de cotización
                    <span style="font-family:'DM Sans',sans-serif; font-size:.78rem;
                                 color:#9490b0; font-weight:400; letter-spacing:0;">
                        ({{ count($lineas) }})
                    </span>
                </h2>
            </div>
            <button wire:click="agregarLinea"
                style="padding:.55rem 1.2rem; background:#3d2372; color:white; border:none;
                       border-radius:4px; font-size:.82rem; font-weight:700; cursor:pointer;
                       font-family:'DM Sans',sans-serif; transition:background .2s;"
                onmouseover="this.style.background='#5035a0';"
                onmouseout="this.style.background='#3d2372';">
                + Agregar línea
            </button>
        </div>

        @if(count($lineas) === 0)
        <div style="padding:3rem 1rem; text-align:center;">
            <p style="font-size:2.5rem; margin-bottom:.75rem; opacity:.3;">📋</p>
            <p style="color:#9490b0; font-size:.875rem;">No hay líneas. Agrega la primera para comenzar.</p>
        </div>
        @else
        <div style="overflow-x:auto;">
            {{-- Proveedor global --}}
            <div style="padding:.65rem 1.25rem; background:#f0ecf8; border-bottom:1px solid rgba(61,35,114,0.12);
                        display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
                <span style="font-size:.72rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#3d2372;">
                    Proveedor global:
                </span>
                <select wire:model.live="proveedor_global_id"
                    style="border:1px solid rgba(61,35,114,0.2); border-radius:4px; padding:.35rem .75rem;
                           font-size:.82rem; background:white; min-width:16rem; font-family:'DM Sans',sans-serif;">
                    <option value="">— Sin proveedor global —</option>
                    @foreach($proveedores as $p)
                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
                <span style="font-size:.75rem; color:#7c6fb0;">
                    Se aplica a todas las líneas y a las nuevas que agregues.
                </span>
            </div>
            <table style="width:100%; font-size:.82rem; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f7fc; border-bottom:1px solid rgba(61,35,114,0.1);">
                        <th style="text-align:left; padding:.65rem 1rem; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#9490b0; width:2rem;">#</th>
                        <th style="text-align:left; padding:.65rem .5rem; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#9490b0; width:11rem;">Proveedor</th>
                        <th style="text-align:left; padding:.65rem .5rem; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Concepto</th>
                        <th style="text-align:right; padding:.65rem .5rem; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#9490b0; width:8rem;">Costo</th>
                        <th style="text-align:right; padding:.65rem .5rem; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#9490b0; width:8rem;">Profit</th>
                        <th style="text-align:right; padding:.65rem .5rem; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#3d2372; width:8rem;
                                   background:#f0ecf8;">Venta</th>
                        <th style="text-align:right; padding:.65rem .5rem; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#3d2372; width:6rem;
                                   background:#f0ecf8;">Margen</th>
                        <th style="text-align:right; padding:.65rem .5rem; font-size:.7rem; font-weight:700;
                                   letter-spacing:.1em; text-transform:uppercase; color:#9490b0; width:7rem;">Target</th>
                        <th style="padding:.65rem .5rem; width:2.5rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lineas as $i => $linea)
                    <tr style="border-bottom:1px solid rgba(61,35,114,0.06);"
                        onmouseover="this.style.background='rgba(61,35,114,0.025)';"
                        onmouseout="this.style.background='';">
                        <td style="padding:.5rem 1rem; color:#9490b0; font-size:.75rem;">{{ $i + 1 }}</td>
                        <td style="padding:.4rem .5rem; min-width:9rem;">
                            <input wire:model.live="lineas.{{ $i }}.proveedor_nombre"
                                type="text"
                                style="width:100%; border:1px solid rgba(61,35,114,0.15); border-radius:3px;
                                       padding:.3rem .4rem; font-size:.78rem; font-family:'DM Sans',sans-serif;"
                                placeholder="Proveedor">
                        </td>
                        <td style="padding:.4rem .5rem;">
                            <input wire:model.live="lineas.{{ $i }}.concepto"
                                type="text"
                                style="width:100%; border:1px solid rgba(61,35,114,0.15); border-radius:3px;
                                       padding:.3rem .4rem; font-size:.82rem; font-family:'DM Sans',sans-serif;"
                                placeholder="Concepto del servicio">
                        </td>
                        <td style="padding:.4rem .5rem;">
                            <input wire:model.live="lineas.{{ $i }}.costo"
                                type="number" step="0.01"
                                style="width:100%; border:1px solid rgba(61,35,114,0.15); border-radius:3px;
                                       padding:.3rem .4rem; font-size:.82rem; text-align:right; font-family:monospace;">
                        </td>
                        <td style="padding:.4rem .5rem;">
                            <input wire:model.live="lineas.{{ $i }}.profit"
                                type="number" step="0.01"
                                style="width:100%; border:1px solid rgba(61,35,114,0.15); border-radius:3px;
                                       padding:.3rem .4rem; font-size:.82rem; text-align:right; font-family:monospace;">
                        </td>
                        <td style="padding:.4rem .5rem; background:#f0ecf8;">
                            <div style="width:100%; padding:.3rem .4rem; font-size:.82rem; text-align:right;
                                        font-family:monospace; font-weight:700; color:#3d2372;">
                                ${{ number_format($linea['venta'] ?? 0, 2) }}
                            </div>
                        </td>
                        <td style="padding:.4rem .5rem; background:#f0ecf8;">
                            <div style="width:100%; padding:.3rem .4rem; font-size:.82rem; text-align:right;
                                        font-family:monospace; color:#3d2372;">
                                {{ $linea['margen'] ?? 0 }}%
                            </div>
                        </td>
                        <td style="padding:.4rem .5rem;">
                            <input wire:model.live="lineas.{{ $i }}.target"
                                type="number" step="0.01"
                                style="width:100%; border:1px solid rgba(61,35,114,0.15); border-radius:3px;
                                       padding:.3rem .4rem; font-size:.82rem; text-align:right;
                                       font-family:monospace; color:#d97706;"
                                placeholder="—">
                        </td>
                        <td style="padding:.4rem .5rem; text-align:center;">
                            <button wire:click="eliminarLinea({{ $i }})"
                                style="color:#cdc9e8; background:none; border:none; cursor:pointer; font-size:1.25rem; line-height:1;"
                                onmouseover="this.style.color='#cd3529';"
                                onmouseout="this.style.color='#cdc9e8';">
                                ×
                            </button>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Fila de totales --}}
                    <tr style="background:#f8f7fc; border-top:2px solid rgba(61,35,114,0.15); font-weight:700;">
                        <td colspan="3" style="padding:.65rem 1rem; font-size:.75rem; letter-spacing:.08em;
                                               text-transform:uppercase; color:#9490b0;">TOTALES</td>
                        <td style="padding:.5rem .5rem; text-align:right; font-family:monospace; font-size:.875rem; color:#1f103b;">
                            ${{ number_format($costo_total, 2) }}
                        </td>
                        <td style="padding:.5rem .5rem; text-align:right; font-family:monospace; font-size:.875rem; color:#1f103b;">
                            ${{ number_format($profit_total, 2) }}
                        </td>
                        <td style="padding:.5rem .5rem; text-align:right; font-family:monospace; font-size:.875rem;
                                   color:#3d2372; background:#e8e0f5;">
                            ${{ number_format($venta_total, 2) }}
                        </td>
                        <td style="padding:.5rem .5rem; text-align:right; font-family:monospace; font-size:.875rem;
                                   color:#3d2372; background:#e8e0f5;">
                            {{ $margen_real }}%
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>

    </div>{{-- /left column --}}

    {{-- Columna derecha: panel de profit sticky --}}
    <div style="position:sticky; top:80px; display:flex; flex-direction:column; gap:.75rem;">

        {{-- Panel financiero --}}
        <div style="background:linear-gradient(160deg,#1f103b 0%,#3d2372 100%); border-radius:12px;
                    padding:1.25rem 1.4rem; color:#fff;">
            <p style="font-size:.65rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
                       color:rgba(255,255,255,0.4); margin-bottom:.85rem;">Resumen financiero</p>

            <div style="display:flex; justify-content:space-between; align-items:baseline; padding:.28rem 0; border-bottom:1px solid rgba(255,255,255,0.06);">
                <span style="font-size:.73rem; color:rgba(255,255,255,0.55);">Costo total</span>
                <span style="font-family:monospace; font-size:.82rem; font-weight:600;">${{ number_format($costo_total, 2) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:baseline; padding:.28rem 0; border-bottom:1px solid rgba(255,255,255,0.06);">
                <span style="font-size:.73rem; color:rgba(255,255,255,0.55);">Profit total</span>
                <span style="font-family:monospace; font-size:.82rem; font-weight:600;">${{ number_format($profit_total, 2) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:baseline; padding:.3rem 0; border-bottom:1px solid rgba(255,255,255,0.1);">
                <span style="font-size:.73rem; color:rgba(255,255,255,0.55);">Venta total</span>
                <span style="font-family:monospace; font-size:.98rem; font-weight:700;">${{ number_format($venta_total, 2) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:baseline; padding:.28rem 0; border-bottom:1px solid rgba(255,255,255,0.1);">
                <span style="font-size:.73rem; color:rgba(255,255,255,0.55);">Margen real</span>
                <span style="font-family:monospace; font-size:.85rem; font-weight:600; color:#fbbf24;">{{ $margen_real }}%</span>
            </div>

            <div style="border-top:1px solid rgba(255,255,255,0.15); margin:.6rem 0;"></div>

            <div style="display:flex; justify-content:space-between; align-items:baseline; padding:.22rem 0;">
                <span style="font-size:.7rem; color:rgba(255,255,255,0.45);">(-) Comisión 10%</span>
                <span style="font-family:monospace; font-size:.78rem; color:#fca5a5;">${{ number_format($comision_monto, 2) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:baseline; padding:.22rem 0; border-bottom:1px solid rgba(255,255,255,0.07);">
                <span style="font-size:.7rem; color:rgba(255,255,255,0.45);">(-) Financiamiento {{ $solicitud->dias_credito }}d</span>
                <span style="font-family:monospace; font-size:.78rem; color:#fca5a5;">${{ number_format($financiamiento_monto, 2) }}</span>
            </div>

            <div style="border-top:1px solid rgba(255,255,255,0.15); margin:.6rem 0;"></div>

            <div style="display:flex; justify-content:space-between; align-items:baseline; padding:.28rem 0;">
                <span style="font-size:.73rem; color:rgba(255,255,255,0.55);">Profit real</span>
                <span style="font-family:monospace; font-size:.85rem; font-weight:600;
                             color:{{ $profit_real_monto >= 0 ? '#4ade80' : '#f87171' }};">${{ number_format($profit_real_monto, 2) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:baseline; padding:.22rem 0;">
                <span style="font-size:.7rem; color:rgba(255,255,255,0.45);">(-) Costo operación</span>
                <span style="font-family:monospace; font-size:.78rem; color:rgba(255,255,255,0.55);">${{ number_format($costo_ope, 2) }}</span>
            </div>

            <div style="background:rgba(0,0,0,0.3); border-radius:8px; padding:.65rem .85rem; margin-top:.6rem;
                        display:flex; justify-content:space-between; align-items:baseline;">
                <span style="font-size:.75rem; font-weight:700; color:rgba(255,255,255,0.8); text-transform:uppercase; letter-spacing:.06em;">Ganancia real</span>
                <span style="font-family:monospace; font-size:1.1rem; font-weight:800;
                             color:{{ $ganancia_real >= 0 ? '#4ade80' : '#f87171' }};">${{ number_format($ganancia_real, 2) }}</span>
            </div>

            @if($margen_deseado > 0)
            <div style="border-top:1px solid rgba(255,255,255,0.15); margin:.65rem 0;"></div>
            <div>
                <p style="font-size:.65rem; color:rgba(255,255,255,0.4); margin-bottom:.2rem;">Profit para margen {{ $margen_deseado }}%</p>
                <p style="font-family:monospace; font-size:.92rem; font-weight:700;
                           color:{{ $profit_a_sumar <= 0 ? '#4ade80' : '#fbbf24' }};">
                    {{ $profit_a_sumar > 0 ? '+' : '' }}${{ number_format(abs($profit_a_sumar), 2) }}
                </p>
            </div>
            @endif
        </div>

        {{-- Botón guardar --}}
        <button wire:click="guardar"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-75"
            style="width:100%; padding:.8rem 1.25rem; background:#cd3529; color:white; border:none;
                   border-radius:8px; font-family:'DM Sans',sans-serif; font-size:.9rem;
                   font-weight:700; letter-spacing:.04em; cursor:pointer;
                   box-shadow:0 4px 14px rgba(205,53,41,0.3); transition:background .2s;"
            onmouseover="this.style.background='#e04a3e';"
            onmouseout="this.style.background='#cd3529';">
            <span wire:loading.remove>Guardar V{{ $version }} →</span>
            <span wire:loading>Guardando...</span>
        </button>

        {{-- Entregar a Ventas --}}
        @if(in_array($solicitud->estado, ['cotizada','en_revision']))
        <div style="border:1px solid rgba(61,35,114,0.2); border-radius:8px; overflow:hidden;">
            @if(!$mostrarEntrega)
            <button wire:click="$set('mostrarEntrega', true)"
                style="width:100%; padding:.7rem 1rem; background:white; color:#3d2372;
                       border:none; font-family:'DM Sans',sans-serif; font-size:.82rem;
                       font-weight:700; cursor:pointer; transition:background .2s;"
                onmouseover="this.style.background='#f0ecf8';"
                onmouseout="this.style.background='white';">
                📬 Entregar a Ventas
            </button>
            @else
            <div style="padding:.85rem 1rem; background:#f0ecf8;">
                <p style="font-size:.72rem; font-weight:700; color:#3d2372;
                           text-transform:uppercase; letter-spacing:.1em; margin-bottom:.6rem;">
                    Entregar a Ventas
                </p>
                <label style="display:block; font-size:.72rem; color:#5a4e80; margin-bottom:.4rem;">
                    PDF de cotización (opcional)
                </label>
                <input wire:model="pdfEntrega" type="file" accept=".pdf"
                    style="width:100%; font-size:.78rem; border:1px solid rgba(61,35,114,0.2);
                           border-radius:4px; padding:.35rem .5rem; background:white; color:#1f103b;">
                @error('pdfEntrega')
                <p style="color:#cd3529; font-size:.72rem; margin-top:.2rem;">{{ $message }}</p>
                @enderror
                <div style="display:flex; gap:.5rem; margin-top:.65rem;">
                    <button wire:click="entregarAVentas"
                        wire:loading.attr="disabled"
                        style="flex:1; padding:.6rem; background:#3d2372; color:white; border:none;
                               border-radius:4px; font-size:.82rem; font-weight:700; cursor:pointer;
                               font-family:'DM Sans',sans-serif;">
                        <span wire:loading.remove>✓ Confirmar</span>
                        <span wire:loading>...</span>
                    </button>
                    <button wire:click="$set('mostrarEntrega', false)"
                        style="padding:.6rem .85rem; background:white; color:#5a4e80;
                               border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                               font-size:.82rem; cursor:pointer; font-family:'DM Sans',sans-serif;">
                        Cancelar
                    </button>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Chat con Ventas --}}
        <div style="background:white; border:1px solid rgba(61,35,114,0.15); border-radius:8px; overflow:hidden;">
            <div style="padding:.65rem 1rem; background:#f8f7fc; border-bottom:1px solid rgba(61,35,114,0.1);
                        display:flex; align-items:center; justify-content:space-between;">
                <p style="font-size:.68rem; font-weight:700; color:#3d2372; text-transform:uppercase; letter-spacing:.1em;">
                    Chat con Ventas
                </p>
                @php $pendientes = $solicitud->comentarios->where('resuelto', false)->where('rol','ventas')->count(); @endphp
                @if($pendientes > 0)
                <span style="background:#cd3529; color:white; font-size:.65rem; font-weight:700;
                              border-radius:999px; padding:.1rem .45rem;">{{ $pendientes }}</span>
                @endif
            </div>

            {{-- Lista de mensajes --}}
            <div id="chatScrollP" style="max-height:260px; overflow-y:auto; padding:.6rem .75rem;
                                          display:flex; flex-direction:column; gap:.4rem;">
                @forelse($solicitud->comentarios->sortBy('created_at') as $com)
                @php $mio = $com->rol === 'pricing'; @endphp
                <div wire:key="pchat-{{ $com->id }}"
                     style="display:flex; flex-direction:column; align-items:{{ $mio ? 'flex-end' : 'flex-start' }};">
                    <div style="max-width:90%; padding:.4rem .65rem; border-radius:12px;
                                background:{{ $mio ? '#ede9fe' : ($com->resuelto ? '#f0fdf4' : '#fffbeb') }};
                                border:1px solid {{ $mio ? 'rgba(61,35,114,0.2)' : ($com->resuelto ? '#a7f3d0' : '#fde68a') }};">
                        <p style="font-size:.8rem; color:#1f103b; word-break:break-word; margin:0;">{{ $com->texto }}</p>
                        <p style="font-size:.65rem; color:#9490b0; margin:.2rem 0 0;">
                            {{ $com->user->name }} · {{ $com->created_at->format('H:i') }}
                            @if($com->resuelto && !$mio) · <span style="color:#059669;">✓</span>@endif
                        </p>
                    </div>
                    @if(!$com->resuelto && !$mio && in_array(auth()->user()->rol, ['pricing','admin']))
                    <button wire:click="marcarResuelto({{ $com->id }})"
                        style="font-size:.65rem; color:#9490b0; background:none; border:none; cursor:pointer; margin-top:.1rem; padding:0;"
                        onmouseover="this.style.color='#059669';" onmouseout="this.style.color='#9490b0';">
                        ✓ Marcar atendido
                    </button>
                    @endif
                </div>
                @empty
                <p style="text-align:center; color:#cdc9e8; font-size:.75rem; padding:.75rem 0;">Sin mensajes aún.</p>
                @endforelse
            </div>

            {{-- Input — wire:model puro, sin Alpine --}}
            @if(in_array(auth()->user()->rol, ['pricing','admin']))
            <div style="padding:.55rem .75rem; border-top:1px solid #f0edf8; display:flex; gap:.4rem;">
                <input type="text"
                       wire:model="msgPricing"
                       wire:keydown.enter="enviarMensajePricing"
                       style="flex:1; border:1.5px solid #e9e5f5; border-radius:8px; padding:.45rem .8rem;
                              font-size:.82rem; font-family:'DM Sans',sans-serif; outline:none; background:#faf9ff;"
                       onfocus="this.style.borderColor='#3d2372';"
                       onblur="this.style.borderColor='#e9e5f5';"
                       placeholder="Responder a Ventas...">
                <button wire:click="enviarMensajePricing"
                        style="padding:.45rem 1rem; background:#3d2372; color:white; border:none;
                               border-radius:8px; font-size:.82rem; font-weight:600; cursor:pointer;
                               white-space:nowrap;"
                        onmouseover="this.style.background='#5035a0';"
                        onmouseout="this.style.background='#3d2372';">
                    Enviar
                </button>
            </div>
            @endif
        </div>

    </div>{{-- /right column --}}
    </div>{{-- /2-col grid --}}

    <script>
    document.addEventListener('livewire:updated', function () {
        var el = document.getElementById('chatScrollP');
        if (el) el.scrollTop = el.scrollHeight;
    });
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('chatScrollP');
        if (el) el.scrollTop = el.scrollHeight;
    });
    </script>

{{-- Notas (solo pricing/admin) --}}
@if(in_array(auth()->user()->rol, ['pricing','admin']))
<div class="vcard" style="padding:1.25rem 1.5rem;">
    <div class="v-section-header">Notas internas</div>
    <textarea wire:model="notas" rows="3"
        style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:6px; padding:.65rem 1rem;
               font-size:.875rem; font-family:'DM Sans',sans-serif; color:#1f103b; resize:vertical; outline:none;"
        onfocus="this.style.borderColor='#3d2372';this.style.boxShadow='0 0 0 3px rgba(61,35,114,0.07)';"
        onblur="this.style.borderColor='rgba(61,35,114,0.2)';this.style.boxShadow='none';"
        placeholder="Notas internas de la cotización..."></textarea>
</div>
@endif

{{-- Volver --}}
<div style="padding-bottom:2rem;">
    <a href="{{ route('pricing.solicitud', $solicitud->id) }}"
       style="padding:.6rem 1.25rem; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
              color:#5a4e80; font-size:.875rem; font-weight:600; text-decoration:none;
              background:white; transition:background .2s, border-color .2s;"
       onmouseover="this.style.background='#f0ecf8';this.style.borderColor='#3d2372';"
       onmouseout="this.style.background='white';this.style.borderColor='rgba(61,35,114,0.2)';">
       ← Volver a solicitud
    </a>
</div>


</div>