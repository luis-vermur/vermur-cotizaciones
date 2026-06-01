<x-filament-panels::page>

    {{-- Encabezado --}}
    <div class="flex items-start justify-between flex-wrap gap-4 mb-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-primary-600 mb-1">Mis solicitudes</p>
            <p class="text-sm text-gray-500">
                Hola, <strong class="text-gray-700">{{ auth()->user()->name }}</strong> —
                {{ $solicitudes->total() }} {{ $solicitudes->total() === 1 ? 'solicitud' : 'solicitudes' }} en total
            </p>
        </div>
        <a href="{{ \App\Filament\Resources\SolicitudResource::getUrl('create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-danger-600 hover:bg-danger-500 text-white text-sm font-bold rounded-lg transition">
            <x-heroicon-o-plus class="w-4 h-4" />
            Nueva solicitud
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'Nuevas',       'key' => 'nueva',       'color' => 'text-violet-700',  'bg' => 'bg-violet-50',  'border' => 'border-violet-500'],
            ['label' => 'En revisión',  'key' => 'en_revision', 'color' => 'text-amber-700',   'bg' => 'bg-amber-50',   'border' => 'border-amber-400'],
            ['label' => 'Cotizadas',    'key' => 'cotizada',    'color' => 'text-emerald-700',  'bg' => 'bg-emerald-50', 'border' => 'border-emerald-500'],
            ['label' => 'Enviadas',     'key' => 'enviada',     'color' => 'text-blue-700',     'bg' => 'bg-blue-50',    'border' => 'border-blue-500'],
        ] as $s)
        <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-4 shadow-sm border-l-4 {{ $s['border'] }}">
            <div class="text-3xl font-black {{ $s['color'] }}">{{ $stats[$s['key']] }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mt-1">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Alerta cotizaciones enviadas --}}
    @if($stats['enviada'] > 0)
    <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm mb-6">
        <span class="text-xl">📬</span>
        <span>
            <strong>{{ $stats['enviada'] }} {{ $stats['enviada'] === 1 ? 'cotización lista' : 'cotizaciones listas' }} para revisar.</strong>
            Haz clic en la solicitud para ver los detalles.
        </span>
    </div>
    @endif

    {{-- Tabla --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">Folio</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">Cliente</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider hidden sm:table-cell">Transporte</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider">Estado</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase text-xs tracking-wider hidden md:table-cell">Fecha</th>
                    <th class="w-8"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($solicitudes as $sol)
                @php
                    $badgeClasses = [
                        'nueva'       => 'bg-violet-100 text-violet-700',
                        'en_revision' => 'bg-amber-100 text-amber-700',
                        'cotizada'    => 'bg-emerald-100 text-emerald-700',
                        'enviada'     => 'bg-blue-100 text-blue-700',
                        'rechazada'   => 'bg-red-100 text-red-700',
                    ][$sol->estado] ?? 'bg-gray-100 text-gray-600';

                    $estadoLabel = [
                        'nueva'       => 'Nueva',
                        'en_revision' => 'En revisión',
                        'cotizada'    => 'Cotizada',
                        'enviada'     => 'Enviada',
                        'rechazada'   => 'Rechazada',
                    ][$sol->estado] ?? ucfirst($sol->estado);
                @endphp
                <tr class="hover:bg-gray-50 cursor-pointer transition"
                    onclick="window.location='{{ \App\Filament\Resources\SolicitudResource::getUrl('view', ['record' => $sol->id]) }}'">
                    <td class="px-4 py-3">
                        <span class="font-mono font-bold text-primary-700">{{ $sol->folio }}</span>
                        @if($sol->estado === 'enviada')
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500 ml-1 align-middle"></span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $sol->cliente_nombre }}</td>
                    <td class="px-4 py-3 text-gray-500 capitalize hidden sm:table-cell">{{ $sol->tipo_transporte }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                            {{ $estadoLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs hidden md:table-cell">
                        {{ $sol->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-gray-300 text-center">→</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="text-5xl mb-3 opacity-30">📋</div>
                        <p class="text-gray-400 text-sm mb-4">Aún no tienes solicitudes creadas.</p>
                        <a href="{{ \App\Filament\Resources\SolicitudResource::getUrl('create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-danger-600 hover:bg-danger-500 text-white text-sm font-bold rounded-lg transition">
                            + Crear solicitud
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($solicitudes->hasPages())
    <div class="mt-4">
        {{ $solicitudes->links() }}
    </div>
    @endif

</x-filament-panels::page>
