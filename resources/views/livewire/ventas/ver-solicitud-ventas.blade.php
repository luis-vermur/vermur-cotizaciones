<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('ventas.dashboard') }}"
                class="text-gray-400 hover:text-gray-600 text-sm">← Mis solicitudes</a>
            <h1 class="text-xl font-bold text-gray-800">{{ $solicitud->folio }}</h1>
            @php
            $colores = [
                'nueva'       => 'background-color:#ede9fe;color:#3d1a8e;',
                'en_revision' => 'background-color:#fef3c7;color:#92400e;',
                'cotizada'    => 'background-color:#d1fae5;color:#065f46;',
                'enviada'     => 'background-color:#dbeafe;color:#1e40af;',
                'rechazada'   => 'background-color:#fee2e2;color:#991b1b;',
            ];
            $estadoStyle = $colores[$solicitud->estado] ?? 'background-color:#f3f4f6;color:#374151;';
            $estadoLabel = [
                'nueva'       => 'Nueva',
                'en_revision' => 'En revisión',
                'cotizada'    => 'Cotizada',
                'enviada'     => 'Enviada',
                'rechazada'   => 'Rechazada',
            ][$solicitud->estado] ?? ucfirst($solicitud->estado);
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold" style="{{ $estadoStyle }}">
                {{ $estadoLabel }}
            </span>
        </div>
        <p class="text-sm text-gray-400">{{ $solicitud->created_at->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Banner estado enviada --}}
    @if($solicitud->estado === 'enviada')
    <div class="rounded-xl p-5 border-2 flex items-start gap-4"
        style="background-color:#eff6ff; border-color:#3b82f6;">
        <span class="text-3xl mt-0.5">📬</span>
        <div>
            <p class="font-bold text-blue-800 text-lg">¡Tu cotización está lista!</p>
            <p class="text-sm text-blue-600 mt-1">
                El equipo de Pricing preparó tu cotización. Revisa los detalles y documentos adjuntos abajo.
            </p>
        </div>
    </div>
    @elseif($solicitud->estado === 'cotizada')
    <div class="rounded-xl p-4 border flex items-center gap-3"
        style="background-color:#d1fae5; border-color:#10b981;">
        <span class="text-xl">✅</span>
        <p class="text-sm font-medium text-green-800">
            Tu solicitud fue cotizada. Pronto la recibirás del equipo de Pricing.
        </p>
    </div>
    @elseif($solicitud->estado === 'en_revision')
    <div class="rounded-xl p-4 border flex items-center gap-3"
        style="background-color:#fef3c7; border-color:#f59e0b;">
        <span class="text-xl">🔍</span>
        <p class="text-sm font-medium text-yellow-800">
            Tu solicitud está siendo revisada por el equipo de Pricing.
        </p>
    </div>
    @elseif($solicitud->estado === 'rechazada')
    <div class="rounded-xl p-4 border flex items-center gap-3"
        style="background-color:#fee2e2; border-color:#e8392a;">
        <span class="text-xl">✗</span>
        <p class="text-sm font-medium text-red-800">
            Tu solicitud fue rechazada. Revisa el historial para ver el motivo.
        </p>
    </div>
    @endif

    {{-- Cotizaciones recibidas --}}
    @if($solicitud->cotizaciones->count() > 0)
    @php $pdfsCoti = $solicitud->adjuntos->filter(fn($a) => str_starts_with($a->ruta, 'cotizaciones/')); @endphp
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-4 pb-2 border-b flex items-center gap-2">
            <span>Cotizaciones generadas</span>
            <span class="text-xs font-normal text-gray-400">({{ $solicitud->cotizaciones->count() }})</span>
        </h2>
        <div class="space-y-3">
            @foreach($solicitud->cotizaciones->sortByDesc('version') as $coti)
            <div class="rounded-xl border p-4" style="border-color:#3d1a8e; background-color:#faf5ff;">
                <div class="flex items-start justify-between flex-wrap gap-3">
                    <div>
                        <p class="font-mono font-bold text-lg" style="color:#3d1a8e;">{{ $coti->folio_coti }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Versión {{ $coti->version }} · {{ ucfirst($coti->tipo_plantilla) }}
                            @if($coti->validez)
                            · Válida hasta: {{ $coti->validez }}
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Total de venta</p>
                        <p class="text-2xl font-bold font-mono" style="color:#3d1a8e;">
                            ${{ number_format($coti->venta_total, 2) }}
                        </p>
                    </div>
                </div>
                @if($coti->notas)
                <div class="mt-3 pt-3 border-t border-purple-100">
                    <p class="text-xs font-medium text-gray-500 mb-1">Notas:</p>
                    <p class="text-sm text-gray-700">{{ $coti->notas }}</p>
                </div>
                @endif
                {{-- PDFs de cotización (subidos por pricing) --}}
                @if($pdfsCoti->count() > 0)
                <div class="mt-3 pt-3 border-t border-purple-100">
                    <p class="text-xs font-medium mb-2" style="color:#3d1a8e;">📄 PDF de cotización</p>
                    <div class="flex flex-col gap-2">
                        @foreach($pdfsCoti as $pdf)
                        <a href="{{ Storage::url($pdf->ruta) }}" target="_blank"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            style="background-color:#ede9fe; color:#3d1a8e; text-decoration:none;"
                            onmouseover="this.style.background='#ddd6fe';"
                            onmouseout="this.style.background='#ede9fe';">
                            <span>📄</span>
                            <span class="truncate">{{ $pdf->nombre_archivo }}</span>
                            <span class="ml-auto text-xs opacity-60">↗</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Documentos adjuntos de la solicitud (solo los del cliente) --}}
    @php $adjuntosSolicitud = $solicitud->adjuntos->filter(fn($a) => str_starts_with($a->ruta, 'adjuntos/')); @endphp
    @if($adjuntosSolicitud->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-4 pb-2 border-b">
            Documentos adjuntos
            <span class="text-gray-400 font-normal text-sm ml-2">({{ $adjuntosSolicitud->count() }})</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($adjuntosSolicitud as $adjunto)
            @php $iconos = ['pdf'=>'📄','doc'=>'📝','docx'=>'📝','xls'=>'📊','xlsx'=>'📊','png'=>'🖼','jpg'=>'🖼','jpeg'=>'🖼']; @endphp
            <a href="{{ Storage::url($adjunto->ruta) }}" target="_blank"
                class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200
                       hover:border-purple-300 hover:bg-purple-50 transition-colors group">
                <span class="text-2xl">{{ $iconos[strtolower($adjunto->tipo ?? '')] ?? '📎' }}</span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-700 truncate group-hover:text-purple-700">
                        {{ $adjunto->nombre_archivo }}
                    </p>
                </div>
                <span class="text-gray-300 group-hover:text-purple-400 text-sm">↗</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Info general --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Detalle de la solicitud</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Cliente</p>
                <p class="font-medium text-gray-800">{{ $solicitud->cliente_nombre }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Días de crédito</p>
                <p class="font-medium text-gray-800">{{ $solicitud->dias_credito }} días</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Operación</p>
                <p class="font-medium text-gray-800 capitalize">{{ $solicitud->tipo_operacion }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Transporte</p>
                <p class="font-medium text-gray-800 capitalize">{{ $solicitud->tipo_transporte }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Mercancía</p>
                <p class="font-medium text-gray-800">{{ $solicitud->tipo_mercancia ?: '—' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Incoterm</p>
                <p class="font-medium text-gray-800">{{ $solicitud->incoterm ?: '—' }}</p>
            </div>
            @if($solicitud->pol_aol)
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Origen (POL/AOL)</p>
                <p class="font-medium text-gray-800">{{ $solicitud->pol_aol }}</p>
            </div>
            @endif
            @if($solicitud->pod_asd)
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Destino (POD/ASD)</p>
                <p class="font-medium text-gray-800">{{ $solicitud->pod_asd }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Chat --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-700">Mensajes con Pricing</h2>
            @php $dePricing = $solicitud->comentarios->where('rol','pricing')->count(); @endphp
            @if($dePricing > 0)
            <span class="text-xs font-bold px-2 py-0.5 rounded-full text-white" style="background:#3d2372;">
                {{ $dePricing }} de Pricing
            </span>
            @endif
        </div>

        {{-- Lista de mensajes --}}
        <div id="chatScrollV" style="max-height:320px; overflow-y:auto; padding:.75rem 1rem; display:flex; flex-direction:column; gap:.5rem;">
            @forelse($solicitud->comentarios->sortBy('created_at') as $com)
            @php $mio = $com->rol === 'ventas'; @endphp
            <div wire:key="vchat-{{ $com->id }}"
                 style="display:flex; flex-direction:column; align-items:{{ $mio ? 'flex-end' : 'flex-start' }};">
                <div style="max-width:76%; padding:.5rem .85rem; border-radius:12px;
                             background:{{ $mio ? '#ede9fe' : '#f0fdf4' }};
                             border:1px solid {{ $mio ? 'rgba(61,35,114,0.2)' : '#a7f3d0' }};">
                    <p style="font-size:.875rem; color:#1f103b; word-break:break-word; margin:0;">{{ $com->texto }}</p>
                    <p style="font-size:.68rem; color:#9490b0; margin:.2rem 0 0;">
                        {{ $com->user->name }} · {{ $com->created_at->format('H:i') }}
                        @if($com->resuelto) · <span style="color:#059669;">✓ atendido</span>@endif
                    </p>
                </div>
            </div>
            @empty
            <p style="text-align:center; font-size:.85rem; color:#cdc9e8; padding:2rem 0;">Sin mensajes aún.</p>
            @endforelse
        </div>

        {{-- Input — wire:model puro, sin Alpine --}}
        <div style="padding:.65rem 1rem; border-top:1px solid #f0edf8; display:flex; gap:.5rem;">
            <input type="text"
                   wire:model="nuevoComentario"
                   wire:keydown.enter="enviarMensaje"
                   style="flex:1; border:1.5px solid #e9e5f5; border-radius:8px; padding:.5rem .9rem;
                          font-size:.9rem; outline:none; font-family:inherit; background:#faf9ff;"
                   onfocus="this.style.borderColor='#3d2372';"
                   onblur="this.style.borderColor='#e9e5f5';"
                   placeholder="Escribe un mensaje a Pricing...">
            <button wire:click="enviarMensaje"
                    style="padding:.5rem 1.25rem; background:#3d2372; color:white; border:none;
                           border-radius:8px; font-size:.875rem; font-weight:600; cursor:pointer;
                           white-space:nowrap;"
                    onmouseover="this.style.background='#5035a0';"
                    onmouseout="this.style.background='#3d2372';">
                Enviar
            </button>
        </div>
    </div>
    <script>
    document.addEventListener('livewire:updated', function(){
        var el = document.getElementById('chatScrollV');
        if(el) el.scrollTop = el.scrollHeight;
    });
    document.addEventListener('DOMContentLoaded', function(){
        var el = document.getElementById('chatScrollV');
        if(el) el.scrollTop = el.scrollHeight;
    });
    </script>

    {{-- Timeline historial --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-5 pb-2 border-b">Seguimiento</h2>

        @if($solicitud->historial->isEmpty())
        <p class="text-gray-400 text-sm">Sin movimientos registrados aún.</p>
        @else
        <div class="relative">
            <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-200"></div>
            <div class="space-y-5">
                @foreach($solicitud->historial->sortBy('created_at') as $h)
                @php
                    $dotColors = [
                        'nueva'       => '#3d1a8e',
                        'en_revision' => '#f59e0b',
                        'cotizada'    => '#10b981',
                        'enviada'     => '#3b82f6',
                        'rechazada'   => '#e8392a',
                    ];
                    $badgeColors = [
                        'nueva'       => 'background-color:#ede9fe;color:#3d1a8e;',
                        'en_revision' => 'background-color:#fef3c7;color:#92400e;',
                        'cotizada'    => 'background-color:#d1fae5;color:#065f46;',
                        'enviada'     => 'background-color:#dbeafe;color:#1e40af;',
                        'rechazada'   => 'background-color:#fee2e2;color:#991b1b;',
                    ];
                    $dotColor   = $dotColors[$h->estado_nuevo]  ?? '#9ca3af';
                    $badgeStyle = $badgeColors[$h->estado_nuevo] ?? 'background-color:#f3f4f6;color:#374151;';
                    $labels = [
                        'nueva'       => 'Nueva',
                        'en_revision' => 'En revisión',
                        'cotizada'    => 'Cotizada',
                        'enviada'     => 'Enviada a Ventas',
                        'rechazada'   => 'Rechazada',
                    ];
                @endphp
                <div class="relative flex gap-4 pl-9">
                    <div class="absolute left-2 top-1.5 w-4 h-4 rounded-full border-2 border-white shadow-sm"
                        style="background-color: {{ $dotColor }}"></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                style="{{ $badgeStyle }}">
                                {{ $labels[$h->estado_nuevo] ?? ucfirst(str_replace('_', ' ', $h->estado_nuevo)) }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $h->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        @if($h->motivo && $h->estado_nuevo === 'rechazada')
                        <p class="text-xs text-red-600 mt-0.5">Motivo: {{ $h->motivo }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>
