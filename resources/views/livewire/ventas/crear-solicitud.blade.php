<div style="max-width:52rem; margin:0 auto;">
    <div style="display:flex; align-items:center; gap:.75rem; margin-bottom:1.75rem; flex-wrap:wrap;">
        <a href="{{ route('ventas.dashboard') }}"
           style="font-size:.82rem; color:#9490b0; text-decoration:none; font-weight:500;"
           onmouseover="this.style.color='#3d2372';" onmouseout="this.style.color='#9490b0';">
           ← Volver
        </a>
        <div>
            <p class="v-tag" style="margin-bottom:.1rem;">Módulo Ventas</p>
            <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2rem;
                       letter-spacing:.04em; color:#3d2372; line-height:1.05;">
                Nueva solicitud
            </h1>
        </div>
    </div>

    <form wire:submit="guardar" class="space-y-6"
        onkeydown="if(event.key==='Enter'&&event.target.tagName!=='TEXTAREA'){event.preventDefault();}"
        >

        {{-- Info general --}}
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Información general</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
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
                        <button type="button" wire:click="$toggle('mostrarCrearCliente', !$mostrarCrearCliente)"
                            style="white-space:nowrap; padding:.45rem 1rem; background:#3d2372; color:white;
                                   border:none; border-radius:.375rem; font-size:.8rem; font-weight:600; cursor:pointer;
                                   transition:background .2s;"
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
                    @error('cliente_nombre')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de operación <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="tipo_operacion"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Seleccionar —</option>
                        <option value="importacion">Importación</option>
                        <option value="exportacion">Exportación</option>
                        <option value="nacional">Nacional / Local</option>
                    </select>
                    @error('tipo_operacion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de transporte <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="tipo_transporte"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Seleccionar —</option>
                        <option value="maritimo">Marítimo</option>
                        <option value="aereo">Aéreo</option>
                        <option value="terrestre">Terrestre</option>
                        <option value="multimodal">Multimodal</option>
                    </select>
                    @error('tipo_transporte')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de mercancía <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="tipo_mercancia" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Ej: Maquinaria industrial">
                    @error('tipo_mercancia')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Incoterm</label>
                    <select wire:model="incoterm"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Seleccionar —</option>
                        @foreach(['EXW','FCA','FAS','FOB','CFR','CIF','CPT','CIP','DPU','DAP','DDP'] as $inc)
                        <option value="{{ $inc }}">{{ $inc }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">POL / AOL (Origen)</label>
                    <input wire:model="pol_aol" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Ej: Shanghai, China">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">POD / ASD (Destino)</label>
                    <input wire:model="pod_asd" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Ej: Manzanillo, México">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asignar a Pricing</label>
                    <select wire:model="asignado_a"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Sin asignar —</option>
                        @foreach($equipoPricing as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Valor de factura
                    </label>
                    <input wire:model="valor_factura" type="number" step="0.01"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Margen profit deseado
                    </label>
                    <div class="relative">
                        <input wire:model="margen_profit" type="number" step="0.1" min="0" max="100"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm pr-8"
                            placeholder="0">
                        <span class="absolute right-3 top-2 text-gray-400 text-sm">%</span>
                    </div>
                </div>

                {{-- Volumen de operación --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Volumen de operación
                    </label>
                    <div style="display:flex; align-items:center; gap:.85rem;">
                        <input wire:model.live="volumen_operacion" type="range"
                            min="1" max="999" step="1"
                            class="accent-purple-700" style="flex:1; cursor:pointer;">
                        <input wire:model.live="volumen_operacion" type="number"
                            min="1" max="9999" step="1"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono font-semibold text-center"
                            style="width:5.5rem; color:#3d2372;">
                    </div>
                </div>

            </div>
        </div>

        {{-- Servicios --}}
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Servicios adicionales</div>

            {{-- Checkboxes simples --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3" style="margin-bottom:.75rem;">
                @foreach([
                ['model' => 'seguro_mercancia', 'label' => 'Seguro de mercancía'],
                ['model' => 'requiere_despacho', 'label' => 'Requiere despacho'],
                ['model' => 'embalaje', 'label' => 'Embalaje'],
                ['model' => 'target', 'label' => 'Target de precio'],
                ] as $toggle)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="{{ $toggle['model'] }}"
                        class="rounded border-gray-300"
                        style="accent-color: #3d2372;">
                    <span class="text-sm text-gray-700">{{ $toggle['label'] }}</span>
                </label>
                @endforeach
            </div>

            {{-- Recolección con dirección inline --}}
            <div style="margin-top:.4rem; padding:.6rem .75rem; background:#f8f7fc;
                        border-radius:6px; border:1px solid rgba(61,35,114,0.08);">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="recoleccion"
                        class="rounded border-gray-300" style="accent-color:#3d2372;">
                    <span class="text-sm font-medium text-gray-700">Recolección</span>
                </label>
                @if($recoleccion)
                <div style="margin-top:.5rem;">
                    <input wire:model="dir_recoleccion" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Dirección completa de recolección">
                </div>
                @endif
            </div>

            {{-- Entrega con dirección inline --}}
            <div style="margin-top:.4rem; padding:.6rem .75rem; background:#f8f7fc;
                        border-radius:6px; border:1px solid rgba(61,35,114,0.08);">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="entrega"
                        class="rounded border-gray-300" style="accent-color:#3d2372;">
                    <span class="text-sm font-medium text-gray-700">Entrega a domicilio</span>
                </label>
                @if($entrega)
                <div style="margin-top:.5rem;">
                    <input wire:model="dir_entrega" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Dirección completa de entrega">
                </div>
                @endif
            </div>

            {{-- Financiamiento inline --}}
            <div style="margin-top:.4rem; padding:.6rem .75rem; background:#f8f7fc;
                        border-radius:6px; border:1px solid rgba(61,35,114,0.08);">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="financiamiento"
                        class="rounded border-gray-300" style="accent-color:#3d2372;">
                    <span class="text-sm font-medium text-gray-700">Financiamiento</span>
                </label>
                @if($financiamiento)
                <div style="margin-top:.5rem; max-width:16rem;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Días de financiamiento
                        @if($dias_credito > 0)
                        <span class="text-xs ml-1" style="color:#3d2372;">(cliente: {{ $dias_credito }} días)</span>
                        @endif
                    </label>
                    <input wire:model="dias_financiamiento" type="number"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="{{ $dias_credito ?: 'Ej. 30' }}">
                </div>
                @endif
            </div>
        </div>

        {{-- Tipo de embarque --}}
        <div class="vcard" style="padding:1.5rem;">
            <div class="v-section-header">Tipo de embarque</div>

            <div class="flex gap-6 mb-4">
                @foreach(['ninguno' => 'Ninguno', 'FCL' => 'FCL', 'LCL' => 'LCL'] as $val => $label)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="tipo_embarque" wire:model.live="tipo_embarque" value="{{ $val }}"
                        style="accent-color: #3d2372;">
                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            {{-- FCL --}}
            @if($tipo_embarque === 'FCL')
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de contenedor</label>
                        <input wire:model="fcl_contenedor" type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="Ej. 20', 40', 40'HC, Reefer...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peso</label>
                        <input wire:model="fcl_peso" type="number" step="0.01"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unidad de peso</label>
                        <select wire:model="fcl_peso_unidad"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="kg">kg</option>
                            <option value="ton">ton</option>
                            <option value="lb">lb</option>
                            <option value="MT">MT</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Requerimientos adicionales</label>
                    <textarea wire:model="fcl_reqs" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Especificaciones adicionales del contenedor..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Características especiales</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach([
                        ['model' => 'fcl_food_grade', 'label' => 'Food Grade'],
                        ['model' => 'fcl_reforzado', 'label' => 'Reforzado'],
                        ['model' => 'fcl_sobredimension', 'label' => 'Sobredimensión'],
                        ['model' => 'fcl_enlonado', 'label' => 'Enlonado'],
                        ['model' => 'fcl_atmos_controlada', 'label' => 'Atmósfera controlada'],
                        ] as $toggle)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="{{ $toggle['model'] }}"
                                style="accent-color: #3d2372;">
                            <span class="text-sm text-gray-700">{{ $toggle['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Adjuntos FCL --}}
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#5a4e80; margin-bottom:.4rem;">
                        Documentos de referencia
                    </label>
                    <div onclick="document.getElementById('fclFiles').click()"
                        style="border:2px dashed rgba(61,35,114,0.25); border-radius:8px; padding:1.25rem;
                               text-align:center; cursor:pointer; transition:border-color .2s, background .2s;"
                        onmouseover="this.style.borderColor='#3d2372';this.style.background='#f0ecf8';"
                        onmouseout="this.style.borderColor='rgba(61,35,114,0.25)';this.style.background='';">
                        <p style="font-size:1.75rem; margin-bottom:.35rem;">📎</p>
                        <p style="font-size:.875rem; color:#5a4e80;">
                            <strong>Arrastra archivos</strong> o haz clic para seleccionar
                        </p>
                        <p style="font-size:.75rem; color:#9490b0; margin-top:.2rem;">PDF · Word · Excel · Imágenes</p>
                        <input type="file" id="fclFiles" wire:model="archivos_fcl" multiple
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.heic,.webp"
                            style="display:none;">
                    </div>
                    @if(count($archivos_fcl) > 0)
                    <div style="margin-top:.5rem; display:flex; flex-direction:column; gap:.3rem;">
                        @foreach($archivos_fcl as $archivo)
                        <div style="display:flex; align-items:center; justify-content:space-between;
                                    background:#f8f7fc; border-radius:6px; padding:.5rem .85rem; font-size:.82rem;
                                    border:1px solid rgba(61,35,114,0.1);">
                            <span style="color:#1f103b;">📄 {{ $archivo->getClientOriginalName() }}</span>
                            <span style="color:#9490b0; font-size:.72rem;">{{ number_format($archivo->getSize() / 1024, 1) }} KB</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @error('archivos_fcl.*')
                    <p style="color:#cd3529; font-size:.75rem; margin-top:.25rem;">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endif

            {{-- LCL --}}
            @if($tipo_embarque === 'LCL')
            <div class="space-y-4 col-span-full">

                {{-- Fila superior --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Núm. pallets</label>
                        <input wire:model.live="lcl_num_pallets" type="number" min="1" max="100"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="lcl_estibable"
                                style="accent-color: #3d2372;">
                            <span class="text-sm text-gray-700">Estibable</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cubicaje total (m³)</label>
                        <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm font-mono font-semibold"
                            style="color: #3d2372;">
                            {{ $lcl_cubicaje_total ?: '0.0000' }} m³
                        </div>
                    </div>
                </div>

                {{-- Tabla de pallets --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Detalle de pallets</span>
                        <div class="flex gap-2">
                            @if(count($pallets) > 1)
                            <button type="button" wire:click="copiarPrimerPallet"
                                class="text-xs px-3 py-1.5 rounded border border-gray-300 text-gray-500 hover:bg-gray-50">
                                Copiar 1° a todos
                            </button>
                            @endif
                            <button type="button" wire:click="agregarPallet"
                                class="text-xs px-3 py-1.5 rounded text-white font-medium"
                                style="background-color: #3d2372;">
                                + Pallet
                            </button>
                        </div>
                    </div>

                    @if(count($pallets) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-3 py-2 text-center font-medium text-gray-500 w-8">#</th>
                                    <th class="px-3 py-2 text-center font-medium text-gray-500">Largo (m)</th>
                                    <th class="px-3 py-2 text-center font-medium text-gray-500">Ancho (m)</th>
                                    <th class="px-3 py-2 text-center font-medium text-gray-500">Alto (m)</th>
                                    <th class="px-3 py-2 text-center font-medium text-gray-500">Peso</th>
                                    <th class="px-3 py-2 text-center font-medium text-gray-500">Unidad</th>
                                    <th class="px-3 py-2 text-center font-medium w-20" style="color: #3d2372; background-color: #f5f0ff;">m³</th>
                                    <th class="px-3 py-2 w-8"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" wire:key="pallets-body-{{ $palletsVersion }}">
                                @foreach($pallets as $i => $pallet)
                                <tr class="hover:bg-gray-50" wire:key="pallet-{{ $i }}-{{ $pallet['largo_cm'] }}-{{ $pallet['ancho_cm'] }}">
                                    <td class="px-3 py-2 text-center text-gray-400 font-medium">
                                        {{ $pallet['numero'] }}
                                    </td>
                                    <td class="px-2 py-1">
                                        <input wire:model.live="pallets.{{ $i }}.largo_cm"
                                            type="number" step="0.01" min="0"
                                            class="w-full text-center border border-gray-200 rounded px-2 py-1 focus:border-purple-400 focus:outline-none"
                                            placeholder="0">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input wire:model.live="pallets.{{ $i }}.ancho_cm"
                                            type="number" step="0.01" min="0"
                                            class="w-full text-center border border-gray-200 rounded px-2 py-1 focus:border-purple-400 focus:outline-none"
                                            placeholder="0">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input wire:model.live="pallets.{{ $i }}.alto_cm"
                                            type="number" step="0.01" min="0"
                                            class="w-full text-center border border-gray-200 rounded px-2 py-1 focus:border-purple-400 focus:outline-none"
                                            placeholder="0">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input wire:model.live="pallets.{{ $i }}.peso"
                                            type="number" step="0.01" min="0"
                                            class="w-full text-center border border-gray-200 rounded px-2 py-1 focus:border-purple-400 focus:outline-none"
                                            placeholder="0">
                                    </td>
                                    <td class="px-2 py-1">
                                        <select wire:model="pallets.{{ $i }}.peso_unidad"
                                            class="w-full border border-gray-200 rounded px-2 py-1 text-center">
                                            <option>kg</option>
                                            <option>ton</option>
                                            <option>lb</option>
                                            <option>MT</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 text-center font-mono font-semibold" style="color: #3d2372; background-color: #f5f0ff;">
                                        {{ $pallet['cubicaje_m3'] ?? '—' }}
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <button type="button" wire:click="eliminarPallet({{ $i }})"
                                            class="text-gray-300 hover:text-red-500 text-xl leading-none transition-colors">×</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f5f0ff;">
                                    <td colspan="6" class="px-3 py-2 text-right text-xs font-semibold text-gray-600">
                                        Cubicaje total:
                                    </td>
                                    <td class="px-3 py-2 text-center font-mono font-bold text-sm" style="color: #3d2372;">
                                        {{ $lcl_cubicaje_total ?: '0' }} m³
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-6 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                        No hay pallets. Escribe el número arriba o agrega uno con el botón.
                    </div>
                    @endif
                </div>

                {{-- Adjuntos LCL --}}
                <div>
                    <label style="display:block; font-size:.82rem; font-weight:600; color:#5a4e80; margin-bottom:.4rem;">
                        Documentos de referencia
                    </label>
                    <div onclick="document.getElementById('lclFiles').click()"
                        style="border:2px dashed rgba(61,35,114,0.25); border-radius:8px; padding:1.25rem;
                               text-align:center; cursor:pointer; transition:border-color .2s, background .2s;"
                        onmouseover="this.style.borderColor='#3d2372';this.style.background='#f0ecf8';"
                        onmouseout="this.style.borderColor='rgba(61,35,114,0.25)';this.style.background='';">
                        <p style="font-size:1.75rem; margin-bottom:.35rem;">📎</p>
                        <p style="font-size:.875rem; color:#5a4e80;">
                            <strong>Arrastra archivos</strong> o haz clic para seleccionar
                        </p>
                        <p style="font-size:.75rem; color:#9490b0; margin-top:.2rem;">PDF · Word · Excel · Imágenes</p>
                        <input type="file" id="lclFiles" wire:model="archivos_lcl" multiple
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.heic,.webp"
                            style="display:none;">
                    </div>
                    @if(count($archivos_lcl) > 0)
                    <div style="margin-top:.5rem; display:flex; flex-direction:column; gap:.3rem;">
                        @foreach($archivos_lcl as $archivo)
                        <div style="display:flex; align-items:center; justify-content:space-between;
                                    background:#f8f7fc; border-radius:6px; padding:.5rem .85rem; font-size:.82rem;
                                    border:1px solid rgba(61,35,114,0.1);">
                            <span style="color:#1f103b;">📄 {{ $archivo->getClientOriginalName() }}</span>
                            <span style="color:#9490b0; font-size:.72rem;">{{ number_format($archivo->getSize() / 1024, 1) }} KB</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>
            @endif
        </div>

        {{-- Botones --}}
        <div style="display:flex; justify-content:flex-end; gap:.75rem; padding-bottom:2rem; flex-wrap:wrap;">
            <a href="{{ route('ventas.dashboard') }}"
               style="padding:.6rem 1.25rem; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                      color:#5a4e80; font-size:.875rem; font-weight:600; text-decoration:none;
                      background:white; transition:background .2s, border-color .2s;"
               onmouseover="this.style.background='#f0ecf8';this.style.borderColor='#3d2372';"
               onmouseout="this.style.background='white';this.style.borderColor='rgba(61,35,114,0.2)';">
               Cancelar
            </a>
            <button type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75"
                style="padding:.65rem 1.75rem; background:#cd3529; color:white; border:none;
                       border-radius:4px; font-family:'DM Sans',sans-serif; font-size:.9rem;
                       font-weight:700; letter-spacing:.04em; cursor:pointer;
                       box-shadow:0 4px 14px rgba(205,53,41,0.3);
                       transition:background .25s, transform .2s;"
                onmouseover="this.style.background='#e04a3e';this.style.transform='translateY(-1px)';"
                onmouseout="this.style.background='#cd3529';this.style.transform='none';">
                <span wire:loading.remove>Enviar solicitud →</span>
                <span wire:loading>Guardando...</span>
            </button>
        </div>

    </form>
</div>