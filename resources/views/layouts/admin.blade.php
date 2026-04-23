<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vermur — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body style="background-color:#f0edf8; font-family:'DM Sans',sans-serif; color:#1f103b; margin:0;">

    {{-- Navbar --}}
    <nav id="admin-nav" style="position:sticky; top:0; z-index:50;
                background:rgba(255,255,255,0.97);
                backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
                border-bottom:1px solid rgba(61,35,114,0.1);
                box-shadow:0 1px 12px rgba(61,35,114,0.07);">
        <div style="max-width:none; padding:.7rem 1.5rem;
                    display:flex; align-items:center; justify-content:space-between;">

            <div style="display:flex; align-items:center; gap:1.25rem;">
                <a href="{{ route('admin.dashboard') }}" style="flex-shrink:0; display:block;">
                    <img src="{{ asset('images/logotipo.png') }}" alt="Vermur"
                         style="height:28px; width:auto; display:block;">
                </a>
                <div style="width:1px; height:1.25rem; background:rgba(61,35,114,0.15);"></div>
                <span style="font-size:.68rem; font-weight:700; letter-spacing:.18em;
                             text-transform:uppercase; color:#cd3529;">Administración</span>
            </div>

            @auth
            <div style="display:flex; align-items:center; gap:1.25rem;">
                <div style="display:flex; align-items:center; gap:.65rem;">
                    <div style="width:30px; height:30px; border-radius:50%; flex-shrink:0;
                                display:flex; align-items:center; justify-content:center;
                                background:linear-gradient(135deg,#cd3529,#3d2372);
                                color:white; font-size:.72rem; font-weight:700;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span style="font-size:.85rem; font-weight:500; color:#1f103b; display:none;"
                          class="sm-name">{{ auth()->user()->name }}</span>
                    <style>.sm-name{display:none;}@media(min-width:640px){.sm-name{display:block;}}</style>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="font-size:.75rem; font-weight:600; padding:.3rem .8rem;
                               border-radius:4px; color:#9490b0; cursor:pointer;
                               border:1px solid rgba(61,35,114,0.18); background:transparent;
                               font-family:'DM Sans',sans-serif; letter-spacing:.04em;"
                        onmouseover="this.style.color='#cd3529';this.style.borderColor='rgba(205,53,41,0.4)';"
                        onmouseout="this.style.color='#9490b0';this.style.borderColor='rgba(61,35,114,0.18)';">
                        Salir
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <div style="display:flex; min-height:calc(100vh - 57px);">

        {{-- Sidebar --}}
        <aside style="width:210px; flex-shrink:0; position:sticky; top:57px;
                      height:calc(100vh - 57px); overflow-y:auto;
                      background:white; border-right:1px solid rgba(61,35,114,0.09);
                      padding:1.25rem 0;">

            @php
            $navItems = [
                ['route' => 'admin.dashboard',   'label' => 'Dashboard',    'icon' => '▤'],
                ['route' => 'admin.solicitudes',  'label' => 'Solicitudes',  'icon' => '📋'],
                ['route' => 'admin.clientes',     'label' => 'Clientes',     'icon' => '🏢'],
                ['route' => 'admin.proveedores',  'label' => 'Proveedores',  'icon' => '🚚'],
                ['route' => 'admin.usuarios',     'label' => 'Usuarios',     'icon' => '👥'],
            ];
            @endphp

            <div style="padding:0 .75rem; margin-bottom:.5rem;">
                <p style="font-size:.62rem; font-weight:700; letter-spacing:.16em;
                           text-transform:uppercase; color:#cdc9e8; padding:0 .5rem; margin-bottom:.25rem;">
                    General
                </p>
                @foreach(array_slice($navItems, 0, 2) as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   style="display:flex; align-items:center; gap:.65rem; padding:.55rem .75rem;
                          border-radius:6px; text-decoration:none; font-size:.85rem; font-weight:{{ $active ? '700' : '500' }};
                          color:{{ $active ? '#3d2372' : '#5a4e80' }};
                          background:{{ $active ? '#f0ecf8' : 'transparent' }};
                          transition:background .15s, color .15s; margin-bottom:.1rem;"
                   onmouseover="if(!'{{ $active }}') { this.style.background='#f8f7fc'; this.style.color='#3d2372'; }"
                   onmouseout="if(!'{{ $active }}') { this.style.background='transparent'; this.style.color='#5a4e80'; }">
                    <span style="font-size:1rem; width:1.25rem; text-align:center; flex-shrink:0;">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                    @if($active)
                    <span style="margin-left:auto; width:3px; height:16px; background:#3d2372; border-radius:2px;"></span>
                    @endif
                </a>
                @endforeach
            </div>

            <div style="margin:.5rem .75rem; height:1px; background:rgba(61,35,114,0.07);"></div>

            <div style="padding:0 .75rem; margin-top:.5rem; margin-bottom:.5rem;">
                <p style="font-size:.62rem; font-weight:700; letter-spacing:.16em;
                           text-transform:uppercase; color:#cdc9e8; padding:0 .5rem; margin-bottom:.25rem;">
                    Catálogos
                </p>
                @foreach(array_slice($navItems, 2, 2) as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   style="display:flex; align-items:center; gap:.65rem; padding:.55rem .75rem;
                          border-radius:6px; text-decoration:none; font-size:.85rem; font-weight:{{ $active ? '700' : '500' }};
                          color:{{ $active ? '#3d2372' : '#5a4e80' }};
                          background:{{ $active ? '#f0ecf8' : 'transparent' }};
                          transition:background .15s, color .15s; margin-bottom:.1rem;"
                   onmouseover="if(!'{{ $active }}') { this.style.background='#f8f7fc'; this.style.color='#3d2372'; }"
                   onmouseout="if(!'{{ $active }}') { this.style.background='transparent'; this.style.color='#5a4e80'; }">
                    <span style="font-size:1rem; width:1.25rem; text-align:center; flex-shrink:0;">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                    @if($active)
                    <span style="margin-left:auto; width:3px; height:16px; background:#3d2372; border-radius:2px;"></span>
                    @endif
                </a>
                @endforeach
            </div>

            <div style="margin:.5rem .75rem; height:1px; background:rgba(61,35,114,0.07);"></div>

            <div style="padding:0 .75rem; margin-top:.5rem;">
                <p style="font-size:.62rem; font-weight:700; letter-spacing:.16em;
                           text-transform:uppercase; color:#cdc9e8; padding:0 .5rem; margin-bottom:.25rem;">
                    Sistema
                </p>
                @foreach(array_slice($navItems, 4) as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   style="display:flex; align-items:center; gap:.65rem; padding:.55rem .75rem;
                          border-radius:6px; text-decoration:none; font-size:.85rem; font-weight:{{ $active ? '700' : '500' }};
                          color:{{ $active ? '#3d2372' : '#5a4e80' }};
                          background:{{ $active ? '#f0ecf8' : 'transparent' }};
                          transition:background .15s, color .15s; margin-bottom:.1rem;"
                   onmouseover="if(!'{{ $active }}') { this.style.background='#f8f7fc'; this.style.color='#3d2372'; }"
                   onmouseout="if(!'{{ $active }}') { this.style.background='transparent'; this.style.color='#5a4e80'; }">
                    <span style="font-size:1rem; width:1.25rem; text-align:center; flex-shrink:0;">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                    @if($active)
                    <span style="margin-left:auto; width:3px; height:16px; background:#3d2372; border-radius:2px;"></span>
                    @endif
                </a>
                @endforeach
            </div>

        </aside>

        {{-- Contenido principal --}}
        <main style="flex:1; min-width:0; padding:2rem 2.5rem; overflow:auto;">
            {{ $slot }}
        </main>

    </div>

    @livewireScripts
    <script>
    document.addEventListener('DOMContentLoaded', formatLocalTimes);
    document.addEventListener('livewire:navigated', formatLocalTimes);
    document.addEventListener('livewire:update', formatLocalTimes);
    function formatLocalTimes() {
        document.querySelectorAll('[data-ts]').forEach(el => {
            const ts = el.dataset.ts;
            if (!ts) return;
            const d = new Date(ts);
            if (isNaN(d)) return;
            const opts = el.dataset.tsFormat === 'date'
                ? { day: '2-digit', month: '2-digit', year: 'numeric' }
                : { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            el.textContent = d.toLocaleString('es-MX', opts);
        });
    }
    </script>
</body>
</html>
