<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vermur — Sistema de Cotizaciones</title>
    {{-- Fonts: DM Sans + Bebas Neue + DM Serif Display (manual de identidad Vermur) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Barra de carga superior */
        [wire\:loading] {
            opacity: 0.6;
        }

        #loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #3d1a8e, #e8392a);
            z-index: 9999;
            transition: width 0.3s ease;
            width: 0%;
        }

        #loading-bar.active {
            width: 85%;
            animation: loading-pulse 1.5s ease-in-out infinite;
        }

        @keyframes loading-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Spinner global de Livewire */
        .livewire-loading-overlay {
            display: none;
        }
    </style>

    @livewireStyles
</head>

<body style="background-color:#f8f7fc; font-family:'DM Sans',sans-serif; color:#1f103b;">

    {{-- Navbar — blanca con frosted glass, igual que vermur.com --}}
    <nav style="position:sticky; top:0; z-index:50;
                background:rgba(255,255,255,0.97);
                backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
                border-bottom:1px solid rgba(61,35,114,0.08);
                box-shadow:0 1px 12px rgba(61,35,114,0.07);">
        <div style="max-width:80rem; margin:0 auto; padding:.75rem 1.5rem;
                    display:flex; align-items:center; justify-content:space-between;">

            {{-- Logo --}}
            <a href="/" style="flex-shrink:0; display:block;">
                <img src="{{ asset('images/logotipo.png') }}"
                    alt="Vermur"
                    style="height:30px; width:auto; display:block;">
            </a>

            @auth
            <div style="display:flex; align-items:center; gap:1.5rem;">

                {{-- Módulo activo --}}
                <div style="display:flex; align-items:center; gap:.4rem;">
                    @php
                    $rolLabel = match(auth()->user()->rol) {
                    'ventas' => 'Ventas',
                    'pricing' => 'Pricing',
                    'admin' => 'Admin',
                    default => ucfirst(auth()->user()->rol),
                    };
                    @endphp
                    <span style="font-size:.68rem; font-weight:700; letter-spacing:.18em;
                                 text-transform:uppercase; color:#9490b0;">Módulo</span>
                    <span style="font-size:.68rem; font-weight:700; letter-spacing:.18em;
                                 text-transform:uppercase; color:#3d2372;">{{ $rolLabel }}</span>
                </div>

                {{-- Divider --}}
                <div style="width:1px; height:1.5rem; background:rgba(61,35,114,0.12);"></div>

                {{-- Avatar + nombre --}}
                <div style="display:flex; align-items:center; gap:.65rem;">
                    <div style="width:32px; height:32px; border-radius:50%; flex-shrink:0;
                                display:flex; align-items:center; justify-content:center;
                                background:linear-gradient(135deg,#cd3529,#3d2372);
                                color:white; font-size:.75rem; font-weight:700;
                                letter-spacing:.04em;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span style="font-size:.875rem; font-weight:500; color:#1f103b; display:none;"
                        class="sm-name">{{ auth()->user()->name }}</span>
                    <style>
                        .sm-name {
                            display: none;
                        }

                        @media(min-width:640px) {
                            .sm-name {
                                display: block;
                            }
                        }
                    </style>
                </div>

                {{-- Cerrar sesión --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="font-size:.78rem; font-weight:600; padding:.35rem .9rem;
                               border-radius:4px; color:#9490b0; cursor:pointer;
                               border:1px solid rgba(61,35,114,0.18); background:transparent;
                               transition:color .2s, border-color .2s, background .2s;
                               font-family:'DM Sans',sans-serif; letter-spacing:.04em;"
                        onmouseover="this.style.color='#cd3529';this.style.borderColor='rgba(205,53,41,0.4)';this.style.background='rgba(205,53,41,0.04)';"
                        onmouseout="this.style.color='#9490b0';this.style.borderColor='rgba(61,35,114,0.18)';this.style.background='transparent';">
                        Salir
                    </button>
                </form>

            </div>
            @endauth

        </div>
    </nav>

    <main style="max-width:80rem; margin:0 auto; padding:2rem 1.5rem;">
        {{ $slot }}
    </main>


    <div id="loading-bar"></div>

    <script>
        const bar = document.getElementById('loading-bar');

        document.addEventListener('livewire:navigating', () => {
            bar.style.width = '0%';
            bar.classList.add('active');
        });

        document.addEventListener('livewire:navigated', () => {
            bar.style.width = '100%';
            bar.classList.remove('active');
            setTimeout(() => {
                bar.style.width = '0%';
            }, 300);
        });

        // También para requests normales de Livewire
        document.addEventListener('livewire:request', () => {
            bar.classList.add('active');
        });

        document.addEventListener('livewire:response', () => {
            bar.style.width = '100%';
            bar.classList.remove('active');
            setTimeout(() => {
                bar.style.width = '0%';
            }, 300);
        });
    </script>

    <div id="loading-bar"></div>

    <script>
        const bar = document.getElementById('loading-bar');

        document.addEventListener('livewire:navigating', () => {
            bar.style.width = '0%';
            bar.classList.add('active');
        });

        document.addEventListener('livewire:navigated', () => {
            bar.style.width = '100%';
            bar.classList.remove('active');
            setTimeout(() => {
                bar.style.width = '0%';
            }, 300);
        });

        // También para requests normales de Livewire
        document.addEventListener('livewire:request', () => {
            bar.classList.add('active');
        });

        document.addEventListener('livewire:response', () => {
            bar.style.width = '100%';
            bar.classList.remove('active');
            setTimeout(() => {
                bar.style.width = '0%';
            }, 300);
        });
    </script>

    @livewireScripts
    <script>
        // Mostrar fechas/horas en la zona horaria del dispositivo del usuario
        document.addEventListener('DOMContentLoaded', formatLocalTimes);
        document.addEventListener('livewire:navigated', formatLocalTimes);
        document.addEventListener('livewire:update', formatLocalTimes);

        function formatLocalTimes() {
            document.querySelectorAll('[data-ts]').forEach(el => {
                const ts = el.dataset.ts;
                if (!ts) return;
                const d = new Date(ts);
                if (isNaN(d)) return;
                const opts = el.dataset.tsFormat === 'date' ? {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                } : {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                el.textContent = d.toLocaleString('es-MX', opts);
            });
        }
    </script>
</body>

</html>