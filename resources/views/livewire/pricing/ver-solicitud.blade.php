<div style="max-width:56rem; margin:0 auto;" class="space-y-5">

    {{-- Header --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
            <a href="{{ route('pricing.dashboard') }}"
                style="font-size:.82rem; color:#9490b0; text-decoration:none; font-weight:500;"
                onmouseover="this.style.color='#3d2372';" onmouseout="this.style.color='#9490b0';">
                ← Volver
            </a>
            <h1 style="font-family:monospace; font-size:1.3rem; font-weight:700; color:#3d2372;">
                {{ $solicitud->folio }}
            </h1>
            @php
            $badgeClass = [
            'nueva'=>'v-badge-nueva','en_revision'=>'v-badge-revision',
            'cotizada'=>'v-badge-cotizada','enviada'=>'v-badge-enviada','rechazada'=>'v-badge-rechazada',
            ][$solicitud->estado] ?? 'v-badge-nueva';
            $estadoLabel = ['nueva'=>'Nueva','en_revision'=>'En revisión','cotizada'=>'Cotizada',
            'enviada'=>'Enviada','rechazada'=>'Rechazada'][$solicitud->estado] ?? '-';
            @endphp
            <span class="v-badge {{ $badgeClass }}">{{ $estadoLabel }}</span>
        </div>
        <div style="display:flex; gap:.5rem;">
            @if($solicitud->estado !== 'rechazada' && $solicitud->estado !== 'enviada')
            <a href="{{ route('pricing.cotizador', $solicitud->id) }}"
                style="display:inline-flex; align-items:center; gap:.4rem;
                      padding:.55rem 1.2rem; background:#cd3529; color:white;
                      border-radius:4px; font-size:.82rem; font-weight:700;
                      text-decoration:none; transition:background .2s, transform .2s;"
                onmouseover="this.style.background='#e04a3e';this.style.transform='translateY(-1px)';"
                onmouseout="this.style.background='#cd3529';this.style.transform='none';">
                Cotizar →
            </a>
            <button wire:click="$set('mostrarRechazo', true)"
                style="display:inline-flex; align-items:center; gap:.4rem;
                      padding:.55rem 1.2rem; background:transparent; color:#cd3529;
                      border:1px solid rgba(205,53,41,0.4);
                      border-radius:4px; font-size:.82rem; font-weight:700; cursor:pointer;
                      transition:background .2s, border-color .2s; font-family:'DM Sans',sans-serif;"
                onmouseover="this.style.background='rgba(205,53,41,0.06)';this.style.borderColor='#cd3529';"
                onmouseout="this.style.background='transparent';this.style.borderColor='rgba(205,53,41,0.4)';">
                Rechazar
            </button>
            @endif
        </div>
        @if($solicitud->estado === 'cotizada')
        <button wire:click="$set('mostrarEntrega', true)"
            style="display:inline-flex; align-items:center; gap:.4rem;
          padding:.55rem 1.2rem; background:#059669; color:white;
          border:none; border-radius:4px; font-size:.82rem; font-weight:700;
          cursor:pointer; transition:background .2s; font-family:'DM Sans',sans-serif;"
            onmouseover="this.style.background='#047857';"
            onmouseout="this.style.background='#059669';">
            Entregar a Ventas
        </button>
        @endif
    </div>

    @if(session('success'))
    <div class="v-alert v-alert-success">
        <span>✓</span><span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="v-alert" style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; border-radius:8px; padding:.75rem 1rem; font-size:.875rem; font-weight:600; display:flex; gap:.5rem; align-items:center; margin-bottom:1rem;">
        <span>✕</span><span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Rechazo --}}
    @if($mostrarRechazo)
    <div style="background:#fff5f5; border:1px solid rgba(205,53,41,0.3); border-radius:12px; padding:1.25rem;">
        <h3 style="font-weight:700; color:#cd3529; margin-bottom:.75rem; font-size:.95rem;">
            Motivo de rechazo
        </h3>
        <textarea wire:model="motivoRechazo" rows="3"
            style="width:100%; border:1px solid rgba(205,53,41,0.35); border-radius:4px;
                   padding:.65rem 1rem; font-family:'DM Sans',sans-serif; font-size:.875rem;
                   color:#1f103b; margin-bottom:.75rem; resize:vertical; outline:none;"
            placeholder="Explica el motivo del rechazo..."></textarea>
        @error('motivoRechazo')
        <p style="color:#cd3529; font-size:.75rem; margin-bottom:.5rem;">{{ $message }}</p>
        @enderror
        <div style="display:flex; gap:.5rem;">
            <button wire:click="rechazar"
                style="padding:.55rem 1.2rem; background:#cd3529; color:white; border:none;
                      border-radius:4px; font-size:.82rem; font-weight:700; cursor:pointer;
                      font-family:'DM Sans',sans-serif;">
                Confirmar rechazo
            </button>
            <button wire:click="$set('mostrarRechazo', false)"
                style="padding:.55rem 1.2rem; background:transparent; border:1px solid rgba(61,35,114,0.18);
                      color:#5a4e80; border-radius:4px; font-size:.82rem; font-weight:600;
                      cursor:pointer; font-family:'DM Sans',sans-serif;">
                Cancelar
            </button>
        </div>
    </div>
    @endif

    @if($mostrarEntrega)
    <div style="background:#f0fdf4; border:1px solid rgba(5,150,105,0.3); border-radius:12px; padding:1.25rem;">
        <h3 style="font-weight:700; color:#059669; margin-bottom:.75rem; font-size:.95rem;">
            Entregar cotización a Ventas
        </h3>
        <p style="font-size:.85rem; color:#065f46; margin-bottom:.75rem;">
            Sube el PDF final. El estado cambiará a <strong>Enviada</strong>.
        </p>
        <div style="border:2px dashed rgba(5,150,105,0.4); border-radius:8px; padding:1rem;
                text-align:center; cursor:pointer; margin-bottom:.75rem;"
            onclick="document.getElementById('pdfEntregaInput').click()">
            <p style="font-size:1.5rem; margin-bottom:.25rem;">📄</p>
            <p style="font-size:.85rem; color:#6b7280;">
                <strong>Seleccionar PDF</strong> o arrastra aquí
            </p>
            <input type="file" id="pdfEntregaInput" wire:model="pdfEntrega"
                accept=".pdf" class="hidden">
        </div>
        @if($pdfEntrega)
        <div style="background:white; border-radius:6px; padding:.5rem .75rem; font-size:.85rem;
                    color:#059669; margin-bottom:.75rem; border:1px solid rgba(5,150,105,0.2);">
            ✓ {{ $pdfEntrega->getClientOriginalName() }}
        </div>
        @endif
        @error('pdfEntrega')
        <p style="color:#cd3529; font-size:.75rem; margin-bottom:.5rem;">{{ $message }}</p>
        @enderror
        <div style="display:flex; gap:.5rem;">
            <button wire:click="entregarAVentas"
                style="padding:.55rem 1.2rem; background:#059669; color:white; border:none;
                   border-radius:4px; font-size:.82rem; font-weight:700; cursor:pointer;
                   font-family:'DM Sans',sans-serif;"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Confirmar entrega</span>
                <span wire:loading>Subiendo...</span>
            </button>
            <button wire:click="$set('mostrarEntrega', false)"
                style="padding:.55rem 1.2rem; background:transparent; border:1px solid rgba(61,35,114,0.2);
                   color:#5a4e80; border-radius:4px; font-size:.82rem; cursor:pointer;
                   font-family:'DM Sans',sans-serif;">
                Cancelar
            </button>
        </div>
    </div>
    @endif

    {{-- Info general --}}
    <div class="vcard" style="padding:1.5rem;">
        <div class="v-section-header">Información general</div>
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; font-size:.875rem;">
            <style>
                @media(min-width:768px) {
                    .info-grid {
                        grid-template-columns: repeat(3, 1fr) !important;
                    }
                }
            </style>
            <div class="info-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; grid-column:1/-1;">
                @foreach([
                ['label'=>'Cliente', 'value'=>$solicitud->cliente_nombre],
                ['label'=>'Días crédito', 'value'=>$solicitud->dias_credito.' días'],
                ['label'=>'Operación', 'value'=>ucfirst($solicitud->tipo_operacion)],
                ['label'=>'Transporte', 'value'=>ucfirst($solicitud->tipo_transporte)],
                ['label'=>'Mercancía', 'value'=>$solicitud->tipo_mercancia ?: '—'],
                ['label'=>'Incoterm', 'value'=>$solicitud->incoterm ?: '—'],
                ['label'=>'Origen POL/AOL','value'=>$solicitud->pol_aol ?: '—'],
                ['label'=>'Destino POD/ASD','value'=>$solicitud->pod_asd ?: '—'],
                ['label'=>'Asignado a', 'value'=>$solicitud->asignadoA?->name ?? 'Sin asignar'],
                ] as $field)
                <div>
                    <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase;
                           color:#9490b0; margin-bottom:.2rem;">{{ $field['label'] }}</p>
                    <p style="font-weight:600; color:#1f103b;">{{ $field['value'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Pallets --}}
    @if($solicitud->pallets->count() > 0)
    <div class="vcard" style="padding:1.5rem;">
        <div class="v-section-header">
            Detalle de pallets
            <span style="font-size:.75rem; color:#9490b0; font-weight:400; text-transform:none; letter-spacing:0; margin-left:.25rem;">
                {{ $solicitud->pallets->count() }} pallets · {{ $solicitud->lcl_cubicaje_total }} m³
            </span>
        </div>
        <div style="overflow-x:auto; border-radius:8px; border:1px solid rgba(61,35,114,0.12);">
            <table style="width:100%; font-size:.82rem; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f7fc; border-bottom:1px solid rgba(61,35,114,0.1);">
                        <th style="padding:.5rem .75rem; text-align:center; font-size:.7rem; font-weight:700;
                                   letter-spacing:.08em; text-transform:uppercase; color:#9490b0; width:2rem;">#</th>
                        <th style="padding:.5rem .75rem; text-align:center; font-size:.7rem; font-weight:700;
                                   letter-spacing:.08em; text-transform:uppercase; color:#9490b0;">Largo (m)</th>
                        <th style="padding:.5rem .75rem; text-align:center; font-size:.7rem; font-weight:700;
                                   letter-spacing:.08em; text-transform:uppercase; color:#9490b0;">Ancho (m)</th>
                        <th style="padding:.5rem .75rem; text-align:center; font-size:.7rem; font-weight:700;
                                   letter-spacing:.08em; text-transform:uppercase; color:#9490b0;">Alto (m)</th>
                        <th style="padding:.5rem .75rem; text-align:center; font-size:.7rem; font-weight:700;
                                   letter-spacing:.08em; text-transform:uppercase; color:#9490b0;">Peso</th>
                        <th style="padding:.5rem .75rem; text-align:center; font-size:.7rem; font-weight:700;
                                   letter-spacing:.08em; text-transform:uppercase; color:#9490b0;">Unidad</th>
                        <th style="padding:.5rem .75rem; text-align:center; font-size:.7rem; font-weight:700;
                                   letter-spacing:.08em; text-transform:uppercase; color:#3d2372;
                                   background:#f0ecf8;">m³</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitud->pallets as $pallet)
                    <tr style="border-bottom:1px solid rgba(61,35,114,0.06);"
                        onmouseover="this.style.background='rgba(61,35,114,0.025)';"
                        onmouseout="this.style.background='';">
                        <td style="padding:.5rem .75rem; text-align:center; color:#9490b0; font-weight:600;">{{ $pallet->numero }}</td>
                        <td style="padding:.5rem .75rem; text-align:center; font-family:monospace;">{{ $pallet->largo_cm ?? '—' }}</td>
                        <td style="padding:.5rem .75rem; text-align:center; font-family:monospace;">{{ $pallet->ancho_cm ?? '—' }}</td>
                        <td style="padding:.5rem .75rem; text-align:center; font-family:monospace;">{{ $pallet->alto_cm ?? '—' }}</td>
                        <td style="padding:.5rem .75rem; text-align:center; font-family:monospace;">{{ $pallet->peso ?? '—' }}</td>
                        <td style="padding:.5rem .75rem; text-align:center; color:#9490b0;">{{ $pallet->peso_unidad ?? '—' }}</td>
                        <td style="padding:.5rem .75rem; text-align:center; font-family:monospace; font-weight:700;
                                   color:#3d2372; background:#f0ecf8;">
                            {{ $pallet->cubicaje_m3 ?? '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f0ecf8;">
                        <td colspan="6" style="padding:.5rem .75rem; text-align:right; font-size:.75rem;
                                               font-weight:700; color:#5a4e80;">
                            Cubicaje total:
                        </td>
                        <td style="padding:.5rem .75rem; text-align:center; font-family:monospace;
                                   font-weight:700; color:#3d2372;">
                            {{ $solicitud->lcl_cubicaje_total }} m³
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    {{-- Adjuntos de la solicitud (solo los subidos en crear-solicitud) --}}
    @php $adjuntosSolicitud = $solicitud->adjuntos->filter(fn($a) => str_starts_with($a->ruta, 'adjuntos/')); @endphp
    @if($adjuntosSolicitud->count() > 0)
    <div class="vcard" style="padding:1.5rem;">
        <div class="v-section-header">
            Documentos adjuntos
            <span style="font-size:.75rem; color:#9490b0; font-weight:400; text-transform:none; letter-spacing:0;">
                ({{ $adjuntosSolicitud->count() }})
            </span>
        </div>
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem;">
            <style>
                @media(min-width:768px) {
                    .adj-grid {
                        grid-template-columns: repeat(3, 1fr) !important;
                    }
                }
            </style>
            <div class="adj-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; grid-column:1/-1;">
                @foreach($adjuntosSolicitud as $adjunto)
                @php
                $iconos = ['pdf'=>'📄','doc'=>'📝','docx'=>'📝','xls'=>'📊','xlsx'=>'📊','png'=>'🖼','jpg'=>'🖼','jpeg'=>'🖼','heic'=>'🖼'];
                @endphp
                <a href="{{ Storage::disk('public')->url($adjunto->ruta) }}" target="_blank"
                    style="display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem;
                       background:#f8f7fc; border-radius:8px; border:1px solid rgba(61,35,114,0.1);
                       text-decoration:none; transition:border-color .2s, background .2s;"
                    onmouseover="this.style.borderColor='#3d2372';this.style.background='#f0ecf8';"
                    onmouseout="this.style.borderColor='rgba(61,35,114,0.1)';this.style.background='#f8f7fc';">
                    <span style="font-size:1.4rem;">{{ $iconos[strtolower($adjunto->tipo ?? '')] ?? '📎' }}</span>
                    <div style="min-width:0; overflow:hidden;">
                        <p style="font-size:.82rem; font-weight:600; color:#1f103b;
                               white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $adjunto->nombre_archivo }}
                        </p>
                        <p style="font-size:.7rem; color:#9490b0; text-transform:uppercase; letter-spacing:.06em;">{{ $adjunto->tipo }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Detalle completo del embarque (siempre visible si hay datos) --}}
    @php
    $fcl_flags = array_keys(array_filter([
        'Food Grade'      => $solicitud->fcl_food_grade,
        'Reforzado'       => $solicitud->fcl_reforzado,
        'Sobredimensión'  => $solicitud->fcl_sobredimension,
        'Enlonado'        => $solicitud->fcl_enlonado,
        'Atmósfera ctrl.' => $solicitud->fcl_atmos_controlada,
    ]));
    $hayDetalle = $solicitud->volumen_operacion || $solicitud->fcl_contenedor || $solicitud->fcl_peso
        || $solicitud->fcl_reqs || count($fcl_flags)
        || $solicitud->lcl_num_pallets || $solicitud->lcl_cubicaje_total
        || $solicitud->ter_tipo || $solicitud->ter_mercancia || $solicitud->ter_num_pallets
        || $solicitud->valor_factura || $solicitud->margen_profit;
    @endphp
    @if($hayDetalle)
    <div class="vcard" style="padding:1.5rem;">
        <div class="v-section-header">
            Detalle del embarque
            @if($solicitud->tipo_embarque && $solicitud->tipo_embarque !== 'ninguno')
            <span style="font-size:.75rem; color:#9490b0; font-weight:400; text-transform:none; letter-spacing:0; margin-left:.5rem;">
                · {{ $solicitud->tipo_embarque }}
            </span>
            @endif
        </div>
        <style>@media(min-width:768px){.emb-grid{grid-template-columns:repeat(4,1fr)!important;}}</style>
        <div class="emb-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem;">

            {{-- Volumen / contenedores --}}
            @if($solicitud->volumen_operacion)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">
                    {{ $solicitud->tipo_embarque === 'FCL' ? 'Núm. contenedores' : 'Volumen de operación' }}
                </p>
                <p style="font-family:'Bebas Neue',sans-serif; font-size:1.6rem; color:#3d2372; line-height:1;">{{ $solicitud->volumen_operacion }}</p>
            </div>
            @endif

            {{-- FCL: tipo contenedor --}}
            @if($solicitud->fcl_contenedor)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Tipo de contenedor</p>
                <p style="font-weight:700; color:#1f103b;">{{ $solicitud->fcl_contenedor }}</p>
            </div>
            @endif

            {{-- FCL: peso --}}
            @if($solicitud->fcl_peso)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Peso FCL</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->fcl_peso }} {{ $solicitud->fcl_peso_unidad }}</p>
            </div>
            @endif

            {{-- LCL: pallets y cubicaje --}}
            @if($solicitud->lcl_num_pallets)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Pallets (LCL)</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->lcl_num_pallets }}</p>
            </div>
            @endif
            @if($solicitud->lcl_cubicaje_total)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Cubicaje total</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->lcl_cubicaje_total }} m³</p>
            </div>
            @endif
            @if($solicitud->lcl_estibable)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Estibable</p>
                <p style="font-weight:600; color:#059669;">✓ Sí</p>
            </div>
            @endif

            {{-- Terrestre --}}
            @if($solicitud->ter_tipo)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Tipo terrestre</p>
                <p style="font-weight:700; color:#3d2372;">{{ $solicitud->ter_tipo }}@if($solicitud->ter_unidad) — {{ $solicitud->ter_unidad }}@endif</p>
            </div>
            @endif
            @if($solicitud->ter_mercancia)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Mercancía</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->ter_mercancia }}</p>
            </div>
            @endif
            @if($solicitud->ter_num_pallets)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Pallets</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->ter_num_pallets }}</p>
            </div>
            @endif
            @if($solicitud->ter_peso)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Peso</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->ter_peso }} {{ $solicitud->ter_peso_unidad }}</p>
            </div>
            @endif
            @if($solicitud->ter_medidas)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Medidas x pallet</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->ter_medidas }}</p>
            </div>
            @endif
            @if($solicitud->ter_volumen)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Volumen</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->ter_volumen }} CBM</p>
            </div>
            @endif
            @if($solicitud->ter_estibable)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Estibable</p>
                <p style="font-weight:600; color:#059669;">✓ Sí</p>
            </div>
            @endif

            {{-- Comercial --}}
            @if($solicitud->valor_factura)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Valor de factura</p>
                <p style="font-weight:600; color:#1f103b;">${{ number_format($solicitud->valor_factura, 2) }}</p>
            </div>
            @endif
            @if($solicitud->margen_profit)
            <div>
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Margen deseado</p>
                <p style="font-weight:600; color:#1f103b;">{{ $solicitud->margen_profit }}%</p>
            </div>
            @endif

            {{-- FCL: reqs y flags --}}
            @if($solicitud->fcl_reqs)
            <div style="grid-column:1/-1;">
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.2rem;">Requerimientos especiales</p>
                <p style="font-weight:500; color:#1f103b;">{{ $solicitud->fcl_reqs }}</p>
            </div>
            @endif
            @if(count($fcl_flags))
            <div style="grid-column:1/-1;">
                <p style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#9490b0; margin-bottom:.4rem;">Características especiales</p>
                <div style="display:flex; flex-wrap:wrap; gap:.4rem;">
                    @foreach($fcl_flags as $f)
                    <span style="padding:.2rem .65rem; background:#ede9fe; border-radius:999px; font-size:.78rem; font-weight:600; color:#3d2372;">✓ {{ $f }}</span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
    @endif

    {{-- Servicios --}}
    <div class="vcard" style="padding:1.5rem;">
        <div class="v-section-header">Servicios solicitados</div>
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:.5rem .75rem;">
            <style>
                @media(min-width:768px) {
                    .srv-grid {
                        grid-template-columns: repeat(4, 1fr) !important;
                    }
                }
            </style>
            <div class="srv-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:.5rem .75rem; grid-column:1/-1;">
                @foreach([
                ['key' => 'recoleccion', 'label' => 'Recolección'],
                ['key' => 'entrega', 'label' => 'Entrega'],
                ['key' => 'seguro_mercancia', 'label' => 'Seguro'],
                ['key' => 'requiere_despacho', 'label' => 'Despacho aduanal'],
                ['key' => 'embalaje', 'label' => 'Embalaje'],
                ['key' => 'financiamiento', 'label' => 'Financiamiento'],
                ] as $s)
                <div style="display:flex; align-items:center; gap:.5rem; font-size:.875rem;">
                    <span style="font-weight:700; font-size:.9rem;
                             color:{{ $solicitud->{$s['key']} ? '#059669' : '#cdc9e8' }};">
                        {{ $solicitud->{$s['key']} ? '✓' : '✗' }}
                    </span>
                    <span style="color:{{ $solicitud->{$s['key']} ? '#1f103b' : '#9490b0' }};">
                        {{ $s['label'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Nota Interna (solo visible para pricing/admin) --}}
    @if(in_array(auth()->user()->rol, ['pricing','admin']))
    <div style="background:linear-gradient(135deg,#1f103b 0%,#3d2372 100%); border-radius:12px; padding:1.5rem; position:relative; overflow:hidden;">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px;
                    background:rgba(255,255,255,0.04); border-radius:50%;"></div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem; flex-wrap:wrap; gap:.5rem;">
            <div style="display:flex; align-items:center; gap:.6rem;">
                <span style="font-size:1rem;">🔒</span>
                <p style="font-size:.72rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:rgba(255,255,255,0.55);">
                    Nota interna (solo Pricing)
                </p>
            </div>
            @if(!$editandoNota)
            <button wire:click="$set('editandoNota', true)"
                style="padding:.3rem .85rem; background:rgba(255,255,255,0.12); color:rgba(255,255,255,0.8);
                       border:1px solid rgba(255,255,255,0.2); border-radius:4px; font-size:.75rem;
                       font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif;"
                onmouseover="this.style.background='rgba(255,255,255,0.2)';"
                onmouseout="this.style.background='rgba(255,255,255,0.12)';">
                Editar
            </button>
            @else
            <div style="display:flex; gap:.4rem;">
                <button wire:click="guardarNotaInterna"
                    style="padding:.3rem .85rem; background:#cd3529; color:white; border:none;
                           border-radius:4px; font-size:.75rem; font-weight:600; cursor:pointer;
                           font-family:'DM Sans',sans-serif;">
                    Guardar
                </button>
                <button wire:click="$set('editandoNota', false)"
                    style="padding:.3rem .75rem; background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.7);
                           border:1px solid rgba(255,255,255,0.15); border-radius:4px; font-size:.75rem;
                           cursor:pointer; font-family:'DM Sans',sans-serif;">
                    Cancelar
                </button>
            </div>
            @endif
        </div>

        @if($editandoNota)
        <textarea wire:model="notaInterna" rows="4"
            style="width:100%; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.2);
                   border-radius:6px; padding:.65rem 1rem; font-family:'DM Sans',sans-serif;
                   font-size:.875rem; color:white; resize:vertical; outline:none;
                   placeholder-color: rgba(255,255,255,0.4);"
            onfocus="this.style.borderColor='rgba(255,255,255,0.45)';"
            onblur="this.style.borderColor='rgba(255,255,255,0.2)';"
            placeholder="Escribe una nota interna visible solo para el equipo de Pricing..."></textarea>
        @elseif($notaInterna)
        <p style="color:rgba(255,255,255,0.85); font-size:.875rem; line-height:1.6; white-space:pre-line;">
            {{ $notaInterna }}
        </p>
        @else
        <p style="color:rgba(255,255,255,0.35); font-size:.875rem; font-style:italic;">
            Sin nota interna. Haz clic en Editar para agregar una.
        </p>
        @endif
    </div>
    @endif

    {{-- Cotizaciones existentes --}}
    @if($cotizaciones->count())
    <div class="vcard" style="padding:1.5rem;">
        <div class="v-section-header">Cotizaciones generadas</div>
        <div style="display:flex; flex-direction:column; gap:.6rem;">
            @foreach($cotizaciones as $coti)
            @php $ganancia = $coti->ganancia_real ?? 0; @endphp
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem;
                        padding:.85rem 1rem; border-radius:8px; font-size:.875rem;
                        background:#f8f7fc; border:1px solid rgba(61,35,114,0.1);">
                <span style="font-family:monospace; font-weight:700; color:#3d2372; font-size:.9rem;">
                    {{ $coti->folio_coti }}
                </span>
                <span style="color:#9490b0; font-size:.8rem;">{{ ucfirst($coti->tipo_plantilla) }} · V{{ $coti->version }}</span>
                <span style="font-weight:700; color:#1f103b;">
                    ${{ number_format($coti->venta_total, 2) }}
                </span>
                <span style="font-weight:700; color:{{ $ganancia > 0 ? '#065f46' : '#991b1b' }};
                             background:{{ $ganancia > 0 ? '#d1fae5' : '#fee2e2' }};
                             padding:.2rem .65rem; border-radius:99px; font-size:.78rem;">
                    ${{ number_format($ganancia, 2) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Comentarios —  estilo chat --}}
    <div class="vcard" style="padding:1.5rem;">
        <div class="v-section-header">Comentarios</div>

        <div style="display:flex; flex-direction:column; gap:.85rem; max-height:18rem;
                    overflow-y:auto; margin-bottom:1rem; padding-right:.25rem;">
            @forelse($comentarios as $com)
            @php $esPricing = $com->rol === 'pricing'; @endphp
            <div style="display:flex; gap:.65rem; align-items:flex-start;
                        {{ $esPricing ? '' : 'flex-direction:row-reverse;' }}">
                <div style="width:32px; height:32px; border-radius:50%; flex-shrink:0;
                             display:flex; align-items:center; justify-content:center;
                             color:white; font-size:.72rem; font-weight:700;
                             background:{{ $esPricing ? 'linear-gradient(135deg,#cd3529,#3d2372)' : 'linear-gradient(135deg,#059669,#0d9488)' }};">
                    {{ strtoupper(substr($com->user->name, 0, 1)) }}
                </div>
                <div style="max-width:75%; {{ $esPricing ? '' : 'text-align:right;' }}">
                    <p style="font-size:.7rem; color:#9490b0; margin-bottom:.2rem;
                               {{ $esPricing ? '' : 'text-align:right;' }}">
                        {{ $com->user->name }} · {{ $com->created_at->format('d/m H:i') }}
                    </p>
                    <div style="display:inline-block; padding:.55rem .85rem; border-radius:10px;
                                font-size:.875rem; line-height:1.5; color:#1f103b;
                                background:{{ $esPricing ? '#f0ecf8' : '#d1fae5' }};
                                border:1px solid {{ $esPricing ? 'rgba(61,35,114,0.1)' : 'rgba(16,185,129,0.15)' }};">
                        {{ $com->texto }}
                    </div>
                </div>
            </div>
            @empty
            <p style="color:#9490b0; font-size:.875rem; text-align:center; padding:1.5rem 0;">
                Sin comentarios aún.
            </p>
            @endforelse
        </div>

        <div style="display:flex; gap:.5rem;">
            <input wire:model="nuevoComentario" type="text"
                style="flex:1; border:1px solid rgba(61,35,114,0.2); border-radius:4px;
                       padding:.6rem 1rem; font-family:'DM Sans',sans-serif; font-size:.875rem;
                       color:#1f103b; outline:none;"
                onfocus="this.style.borderColor='#3d2372';this.style.boxShadow='0 0 0 3px rgba(61,35,114,0.07)';"
                onblur="this.style.borderColor='rgba(61,35,114,0.2)';this.style.boxShadow='none';"
                placeholder="Escribe un comentario..."
                wire:keydown.enter="agregarComentario">
            <button wire:click="agregarComentario"
                style="padding:.6rem 1.2rem; background:#3d2372; color:white;
                      border:none; border-radius:4px; font-family:'DM Sans',sans-serif;
                      font-size:.82rem; font-weight:700; cursor:pointer;
                      transition:background .2s;"
                onmouseover="this.style.background='#5035a0';"
                onmouseout="this.style.background='#3d2372';">
                Enviar
            </button>
        </div>
    </div>

    {{-- PDF de cotizaciones subidos (desde cotizador) --}}
    @php $pdfsCotizacion = $solicitud->adjuntos->filter(fn($a) => str_starts_with($a->ruta, 'cotizaciones/')); @endphp
    @if($pdfsCotizacion->count() > 0)
    <div class="vcard" style="padding:1.5rem;">
        <div class="v-section-header">PDF de cotización</div>
        <div style="display:flex; flex-direction:column; gap:.5rem;">
            @foreach($pdfsCotizacion as $adj)
            <a href="{{ Storage::disk('public')->url($adj->ruta) }}" target="_blank"
                style="display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem;
                       background:#f0ecf8; border-radius:8px; border:1px solid rgba(61,35,114,0.15);
                       text-decoration:none; transition:border-color .2s, background .2s;"
                onmouseover="this.style.borderColor='#3d2372';this.style.background='#e8e0f5';"
                onmouseout="this.style.borderColor='rgba(61,35,114,0.15)';this.style.background='#f0ecf8';">
                <span style="font-size:1.4rem;">📄</span>
                <div style="min-width:0; overflow:hidden; flex:1;">
                    <p style="font-size:.85rem; font-weight:600; color:#3d2372;
                               white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $adj->nombre_archivo }}
                    </p>
                    <p style="font-size:.72rem; color:#9490b0;">{{ $adj->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span style="font-size:.75rem; color:#3d2372; font-weight:600;">Ver →</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($solicitud->estado === 'enviada')
    <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:1rem 1.25rem;
                display:flex; align-items:center; gap:.85rem;">
        <span style="font-size:1.5rem;">📬</span>
        <div>
            <p style="font-size:.875rem; font-weight:600; color:#1e40af;">Cotización entregada a Ventas</p>
            <p style="font-size:.78rem; color:#3b82f6;">El equipo de Ventas ya puede ver esta cotización.</p>
        </div>
    </div>
    @endif

    {{-- Historial — Timeline visual --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-5 pb-2 border-b">Historial de estados</h2>
        @if($historial->isEmpty())
        <p class="text-gray-400 text-sm">Sin historial registrado.</p>
        @else
        <div class="relative">
            {{-- Línea vertical --}}
            <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-200"></div>

            <div class="space-y-5">
                @foreach($historial as $h)
                @php
                $dotColors = [
                'nueva' => '#3d1a8e',
                'en_revision' => '#f59e0b',
                'cotizada' => '#10b981',
                'enviada' => '#3b82f6',
                'rechazada' => '#e8392a',
                ];
                $badgeColors = [
                'nueva' => 'background-color:#ede9fe;color:#3d1a8e;',
                'en_revision' => 'background-color:#fef3c7;color:#92400e;',
                'cotizada' => 'background-color:#d1fae5;color:#065f46;',
                'enviada' => 'background-color:#dbeafe;color:#1e40af;',
                'rechazada' => 'background-color:#fee2e2;color:#991b1b;',
                ];
                $dotColor = $dotColors[$h->estado_nuevo] ?? '#9ca3af';
                $badgeStyle = $badgeColors[$h->estado_nuevo] ?? 'background-color:#f3f4f6;color:#374151;';
                @endphp
                <div class="relative flex gap-4 pl-9">
                    {{-- Dot --}}
                    <div class="absolute left-2 top-1.5 w-4 h-4 rounded-full border-2 border-white shadow-sm flex-shrink-0"
                        style="background-color: {{ $dotColor }}"></div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full capitalize" style="{{ $badgeStyle }}">
                                {{ str_replace('_', ' ', $h->estado_nuevo) }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $h->created_at->format('d/m/Y H:i') }}
                            </span>
                            <span class="text-xs text-gray-500 font-medium">
                                {{ $h->user?->name ?? 'Sistema' }}
                            </span>
                        </div>
                        @if($h->motivo)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $h->motivo }}</p>
                        @endif
                        @if($h->estado_anterior)
                        <p class="text-xs text-gray-400 mt-0.5">
                            anterior: {{ str_replace('_', ' ', $h->estado_anterior) }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>