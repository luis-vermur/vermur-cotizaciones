<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vermur — Supervisión de Ventas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body style="background-color:#f8f7fc; font-family:'DM Sans',sans-serif; color:#1f103b; margin:0;">

    {{-- Navbar --}}
    <nav style="position:sticky; top:0; z-index:50;
                background:rgba(255,255,255,0.97);
                backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
                border-bottom:1px solid rgba(61,35,114,0.08);
                box-shadow:0 1px 12px rgba(61,35,114,0.07);">
        <div style="padding:.7rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:1.25rem;">
                <a href="{{ route('supervisor.dashboard') }}" style="flex-shrink:0; display:block;">
                    <img src="{{ asset('images/logotipo.png') }}" alt="Vermur"
                         style="height:28px; width:auto; display:block;">
                </a>
                <div style="width:1px; height:1.25rem; background:rgba(61,35,114,0.15);"></div>
                <span style="font-size:.68rem; font-weight:700; letter-spacing:.18em;
                             text-transform:uppercase; color:#cd3529;">Supervisión de Ventas</span>
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
                      padding:1.5rem 0; display:flex; flex-direction:column; gap:.25rem;">

            {{-- Nueva solicitud --}}
            <div style="padding:0 .85rem; margin-bottom:.75rem;">
                <a href="{{ route('ventas.crear') }}"
                   style="display:flex; align-items:center; justify-content:center; gap:.5rem;
                          padding:.6rem 1rem; background:#cd3529; color:white; border-radius:6px;
                          font-size:.82rem; font-weight:700; text-decoration:none; letter-spacing:.02em;"
                   onmouseover="this.style.background='#e04a3e';"
                   onmouseout="this.style.background='#cd3529';">
                    + Nueva solicitud
                </a>
            </div>

            <div style="margin:.25rem .85rem .5rem; height:1px; background:rgba(61,35,114,0.07);"></div>

            @php
            $navItems = [
                ['route'=>'supervisor.dashboard',       'label'=>'Dashboard general', 'icon'=>'▤',  'desc'=>'Todo el equipo'],
                ['route'=>'supervisor.mis-solicitudes',  'label'=>'Mis solicitudes',   'icon'=>'📋', 'desc'=>'Solo las mías'],
            ];
            @endphp

            <div style="padding:0 .75rem;">
                <p style="font-size:.6rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase;
                           color:#cdc9e8; padding:0 .5rem; margin-bottom:.35rem;">Vistas</p>
                @foreach($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   style="display:flex; align-items:center; gap:.65rem; padding:.6rem .75rem;
                          border-radius:8px; text-decoration:none; margin-bottom:.2rem;
                          background:{{ $active ? '#f0ecf8' : 'transparent' }};
                          border:{{ $active ? '1px solid rgba(61,35,114,0.12)' : '1px solid transparent' }};
                          transition:background .15s;"
                   onmouseover="this.style.background='{{ $active ? '#f0ecf8' : '#f8f7fc' }}';"
                   onmouseout="this.style.background='{{ $active ? '#f0ecf8' : 'transparent' }}';">
                    <span style="font-size:1.05rem; width:1.3rem; text-align:center; flex-shrink:0;">{{ $item['icon'] }}</span>
                    <div style="min-width:0;">
                        <p style="font-size:.83rem; font-weight:{{ $active ? '700' : '500' }};
                                   color:{{ $active ? '#3d2372' : '#5a4e80' }}; margin:0; line-height:1.2;">
                            {{ $item['label'] }}
                        </p>
                        <p style="font-size:.68rem; color:#9490b0; margin:0; margin-top:.1rem;">{{ $item['desc'] }}</p>
                    </div>
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
            const ts = el.dataset.ts; if (!ts) return;
            const d = new Date(ts); if (isNaN(d)) return;
            const opts = el.dataset.tsFormat === 'date'
                ? { day:'2-digit', month:'2-digit', year:'numeric' }
                : { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' };
            el.textContent = d.toLocaleString('es-MX', opts);
        });
    }
    </script>
</body>
</html>
