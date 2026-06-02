<div>
    {{-- Encabezado --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <p class="v-tag">Módulo Pricing</p>
            <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2.2rem; letter-spacing:.04em;
                       color:#3d2372; line-height:1.1;">Nueva cotización directa</h1>
            <p style="font-size:.875rem; color:#9490b0; margin-top:.25rem;">
                Captura la información del embarque para ir al cotizador.
            </p>
        </div>
        <a href="{{ route('pricing.dashboard') }}"
           style="font-size:.82rem; color:#9490b0; text-decoration:none; font-weight:500;">
            ← Volver al panel
        </a>
    </div>

    <form wire:submit="crear" class="space-y-5">

        {{-- Cliente --}}
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Cliente</div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Cliente <span class="text-red-500">*</span>
                </label>
                <div style="display:flex; gap:.5rem; align-items:flex-start;">
                    <select wire:model.live="cliente_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Seleccionar cliente —</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="$toggle('mostrarCrearCliente')"
                        style="white-space:nowrap; padding:.45rem 1rem; background:#3d2372; color:white;
                               border:none; border-radius:.375rem; font-size:.8rem; font-weight:600; cursor:pointer;"
                        onmouseover="this.style.background='#5035a0';"
                        onmouseout="this.style.background='#3d2372';">
                        + Nuevo
                    </button>
                </div>
                @if($cliente_id && !$mostrarCrearCliente)
                <p style="font-size:.75rem; color:#059669; margin-top:.3rem; font-weight:600;">
                    ✓ {{ $cliente_nombre }} · {{ $dias_credito }} días de crédito
                </p>
                @endif
                @error('cliente_nombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                @if($mostrarCrearCliente)
                <div style="margin-top:.6rem; padding:.85rem 1rem; background:#f0ecf8;
                            border-radius:8px; border:1px solid rgba(61,35,114,0.2);">
                    <p style="font-size:.72rem; font-weight:700; color:#3d2372;
                               text-transform:uppercase; letter-spacing:.1em; margin-bottom:.6rem;">
                        Nuevo cliente
                    </p>
                    <div style="display:grid; grid-template-columns:1fr 8rem; gap:.5rem;">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Nombre</label>
                            <input wire:model="nuevoClienteNombre" type="text"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                placeholder="Nombre del cliente">
                            @error('nuevoClienteNombre')
                            <p style="color:#cd3529; font-size:.72rem; margin-top:.2rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Días crédito</label>
                            <input wire:model="nuevoClienteDias" type="number" min="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                placeholder="30">
                        </div>
                    </div>
                    <div style="display:flex; gap:.5rem; margin-top:.6rem;">
                        <button type="button" wire:click="crearCliente"
                            style="padding:.45rem 1rem; background:#3d2372; color:white; border:none;
                                   border-radius:.375rem; font-size:.82rem; font-weight:600; cursor:pointer;">
                            Crear cliente
                        </button>
                        <button type="button" wire:click="$set('mostrarCrearCliente', false)"
                            style="padding:.45rem .85rem; background:white; color:#5a4e80;
                                   border:1px solid rgba(61,35,114,0.2); border-radius:.375rem;
                                   font-size:.82rem; cursor:pointer;">
                            Cancelar
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Info general --}}
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Información del embarque</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de operación <span style="color:#cd3529;">*</span></label>
                    <select wire:model="tipo_operacion"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Seleccionar...</option>
                        <option value="importacion">Importación</option>
                        <option value="exportacion">Exportación</option>
                        <option value="nacional">Nacional</option>
                    </select>
                    @error('tipo_operacion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de transporte <span style="color:#cd3529;">*</span></label>
                    <select wire:model.live="tipo_transporte"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Seleccionar...</option>
                        <option value="maritimo">Marítimo</option>
                        <option value="aereo">Aéreo</option>
                        <option value="terrestre">Terrestre</option>
                        <option value="multimodal">Multimodal</option>
                    </select>
                    @error('tipo_transporte')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de mercancía <span style="color:#cd3529;">*</span></label>
                    <input wire:model="tipo_mercancia" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Ej. Electrónicos, alimentos, maquinaria...">
                    @error('tipo_mercancia')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Incoterm</label>
                    <select wire:model="incoterm"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Sin especificar</option>
                        @foreach(['EXW','FOB','CIF','CFR','DAP','DDP','FCA','CPT','CIP','DPU','FAS'] as $inc)
                        <option value="{{ $inc }}">{{ $inc }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Origen (POL / AOL)</label>
                    <input wire:model="pol_aol" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Ej. Shanghai, AICM...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Destino (POD / ASD)</label>
                    <input wire:model="pod_asd" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Ej. Manzanillo, NAICM...">
                </div>

            </div>
        </div>

        {{-- Embarque según transporte --}}
        @if($tipo_transporte === 'terrestre')
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Detalles Terrestre</div>
            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo</label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="ter_tipo" wire:model.live="ter_tipo" value="FTL" style="accent-color:#3d2372;">
                            <span class="text-sm font-medium text-gray-700">FTL (carga completa)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="ter_tipo" wire:model.live="ter_tipo" value="LTL" style="accent-color:#3d2372;">
                            <span class="text-sm font-medium text-gray-700">LTL (carga consolidada)</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de unidad</label>
                        <select wire:model="ter_unidad"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Seleccionar...</option>
                            @if($ter_tipo === 'FTL')
                                <option value="Caja 53'">Caja 53'</option>
                                <option value="Caja 48'">Caja 48'</option>
                                <option value="Plataforma 40'">Plataforma 40'</option>
                            @else
                                <option value="Nissan">Nissan</option>
                                <option value="Torton">Torton</option>
                                <option value="Camioneta 3.5T">Camioneta 3.5T</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mercancía</label>
                        <input wire:model="ter_mercancia" type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="Ej. Motores eléctricos">
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Núm. pallets</label>
                        <input wire:model="ter_num_pallets" type="number" min="1"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peso</label>
                        <div class="flex gap-1">
                            <input wire:model="ter_peso" type="number" step="0.01"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                placeholder="0.00">
                            <select wire:model="ter_peso_unidad"
                                class="border border-gray-300 rounded-lg px-2 py-2 text-sm">
                                <option value="kg">kg</option>
                                <option value="ton">ton</option>
                                <option value="lb">lb</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Medidas x pallet</label>
                        <input wire:model="ter_medidas" type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="1.20 x 1.0 x 1.15">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Volumen (CBM)</label>
                        <input wire:model="ter_volumen" type="number" step="0.0001"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="0.00">
                    </div>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="ter_estibable" style="accent-color:#3d2372;">
                    <span class="text-sm font-medium text-gray-700">Estibable</span>
                </label>
            </div>
        </div>
        @elseif(in_array($tipo_transporte, ['maritimo','multimodal']))
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Tipo de embarque</div>
            <div class="flex gap-6 mb-4">
                @foreach(['ninguno'=>'Ninguno','FCL'=>'FCL','LCL'=>'LCL'] as $val=>$label)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipo_embarque" wire:model.live="tipo_embarque" value="{{ $val }}" style="accent-color:#3d2372;">
                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>
            @if($tipo_embarque === 'FCL')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de contenedor</label>
                    <input wire:model="fcl_contenedor" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="20', 40', 40'HC...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso</label>
                    <input wire:model="fcl_peso" type="number" step="0.01"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unidad</label>
                    <select wire:model="fcl_peso_unidad"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="kg">kg</option>
                        <option value="ton">ton</option>
                        <option value="lb">lb</option>
                    </select>
                </div>
            </div>
            @elseif($tipo_embarque === 'LCL')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Núm. pallets</label>
                    <input wire:model="lcl_num_pallets" type="number"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cubicaje total (m³)</label>
                    <input wire:model="lcl_cubicaje_total" type="number" step="0.01"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="lcl_estibable" style="accent-color:#3d2372;">
                        <span class="text-sm text-gray-700">Estibable</span>
                    </label>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Recolección / Entrega --}}
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Servicios adicionales</div>
            <div class="space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="recoleccion" style="accent-color:#3d2372;">
                    <span class="text-sm font-medium text-gray-700">Recolección</span>
                </label>
                @if($recoleccion)
                <input wire:model="dir_recoleccion" type="text"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                    placeholder="Dirección de recolección (con CP)">
                @endif

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="entrega" style="accent-color:#3d2372;">
                    <span class="text-sm font-medium text-gray-700">Entrega</span>
                </label>
                @if($entrega)
                <input wire:model="dir_entrega" type="text"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                    placeholder="Dirección de entrega (con CP)">
                @endif
            </div>
        </div>

        {{-- Nota interna --}}
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Nota interna</div>
            <textarea wire:model="nota_interna" rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                placeholder="Observaciones o datos adicionales para la cotización..."></textarea>
        </div>

        {{-- Botones --}}
        <div style="display:flex; justify-content:flex-end; gap:.75rem; padding-bottom:2rem;">
            <a href="{{ route('pricing.dashboard') }}"
               style="padding:.6rem 1.25rem; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                      color:#5a4e80; font-size:.875rem; font-weight:600; text-decoration:none; background:white;">
                Cancelar
            </a>
            <button type="submit"
                style="padding:.65rem 1.6rem; background:#cd3529; color:white; border:none;
                       border-radius:4px; font-size:.875rem; font-weight:700; cursor:pointer;
                       letter-spacing:.04em; font-family:'DM Sans',sans-serif;"
                onmouseover="this.style.background='#e04a3e';"
                onmouseout="this.style.background='#cd3529';">
                Ir al cotizador →
            </button>
        </div>

    </form>
</div>
