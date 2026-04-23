<div style="min-height:100vh; display:flex; font-family:'DM Sans',sans-serif;">

    {{-- Panel izquierdo: branding Vermur --}}
    <div style="display:none; flex:1; flex-direction:column; justify-content:center; align-items:center;
                padding:4rem; background:linear-gradient(160deg,#1f103b 0%,#3d2372 55%,#2e1a57 100%);
                position:relative; overflow:hidden;"
         class="login-left">
        <style>
            @media(min-width:1024px){ .login-left{ display:flex !important; } .login-right{ width:480px !important; } }
        </style>

        {{-- Decorative circles --}}
        <div style="position:absolute; top:-80px; right:-80px; width:320px; height:320px;
                    border-radius:50%; background:rgba(205,53,41,0.08);"></div>
        <div style="position:absolute; bottom:-60px; left:-60px; width:240px; height:240px;
                    border-radius:50%; background:rgba(255,255,255,0.04);"></div>

        <div style="position:relative; z-index:1; text-align:center; max-width:380px;">
            <img src="{{ asset('images/logoblanco.png') }}" alt="Vermur"
                 style="height:52px; width:auto; margin:0 auto 2.5rem; display:block;">

            <h1 style="font-family:'Bebas Neue',sans-serif; font-size:3.4rem; letter-spacing:.04em;
                       line-height:1; color:white; margin-bottom:1rem;">
                Sistema de<br>Cotizaciones
            </h1>
            <p style="color:rgba(205,201,232,0.75); font-size:.95rem; line-height:1.65; margin-bottom:3rem;">
                Gestiona solicitudes de carga,<br>tarifas y cotizaciones en un solo lugar.
            </p>

            {{-- Servicios / tags --}}
            <div style="display:flex; flex-wrap:wrap; gap:.5rem; justify-content:center;">
                @foreach(['Marítimo','Aéreo','Terrestre','Multimodal','Despacho'] as $svc)
                <span style="font-size:.72rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase;
                             padding:.3rem .8rem; border-radius:99px;
                             background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.7);
                             border:1px solid rgba(255,255,255,0.15);">{{ $svc }}</span>
                @endforeach
            </div>
        </div>

        {{-- Decorative lines bottom --}}
        <div style="position:absolute; bottom:2.5rem; left:50%; transform:translateX(-50%);
                    display:flex; gap:.5rem; align-items:center;">
            <div style="width:48px; height:3px; background:#cd3529; border-radius:2px;"></div>
            <div style="width:24px; height:3px; background:rgba(255,255,255,0.3); border-radius:2px;"></div>
            <div style="width:12px; height:3px; background:rgba(255,255,255,0.15); border-radius:2px;"></div>
        </div>
    </div>

    {{-- Panel derecho: formulario --}}
    <div class="login-right"
         style="width:100%; display:flex; align-items:center; justify-content:center; padding:2rem;
                background:linear-gradient(160deg,#1f103b 0%,#3d2372 100%);">

        <div style="background:white; border-radius:16px; padding:2.5rem 2.25rem; width:100%; max-width:400px;
                    box-shadow:0 24px 80px rgba(0,0,0,0.35);">

            {{-- Logo visible solo en mobile --}}
            <div style="text-align:center; margin-bottom:2rem;" class="login-mobile-logo">
                <style>.login-mobile-logo{ display:block; } @media(min-width:1024px){.login-mobile-logo{display:none !important;}}</style>
                <img src="{{ asset('images/logotipo.png') }}" alt="Vermur"
                     style="height:36px; width:auto; margin:0 auto 1rem; display:block;">
                <p style="font-size:.78rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase;
                           color:#9490b0;">Sistema de Cotizaciones</p>
            </div>

            {{-- Heading desktop --}}
            <div style="margin-bottom:2rem;" class="login-desktop-heading">
                <style>.login-desktop-heading{display:none;} @media(min-width:1024px){.login-desktop-heading{display:block;}}</style>
                <p style="font-size:.7rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase;
                           color:#cd3529; margin-bottom:.4rem;">Bienvenido</p>
                <h2 style="font-family:'Bebas Neue',sans-serif; font-size:2.4rem; letter-spacing:.04em;
                           color:#1f103b; line-height:1.05;">Iniciar sesión</h2>
            </div>

            {{-- Error --}}
            @if($error)
            <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b;
                        border-radius:8px; padding:.75rem 1rem; margin-bottom:1.25rem;
                        font-size:.875rem; display:flex; align-items:center; gap:.5rem;">
                <span>⚠</span> {{ $error }}
            </div>
            @endif

            <form wire:submit="login">

                <div style="margin-bottom:1.1rem;">
                    <label style="display:block; font-size:.75rem; font-weight:700; letter-spacing:.1em;
                                  text-transform:uppercase; color:#5a4e80; margin-bottom:.4rem;">
                        Correo electrónico
                    </label>
                    <input wire:model="email" type="email"
                        style="background:white; border:1px solid rgba(61,35,114,0.22); border-radius:4px;
                               padding:.7rem 1rem; color:#1f103b; font-family:'DM Sans',sans-serif;
                               font-size:.9rem; width:100%; outline:none;
                               transition:border-color .25s, box-shadow .25s;"
                        onfocus="this.style.borderColor='#3d2372';this.style.boxShadow='0 0 0 3px rgba(61,35,114,0.08)';"
                        onblur="this.style.borderColor='rgba(61,35,114,0.22)';this.style.boxShadow='none';"
                        placeholder="usuario@vermur.com">
                    @error('email')
                    <p style="color:#cd3529; font-size:.75rem; margin-top:.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-size:.75rem; font-weight:700; letter-spacing:.1em;
                                  text-transform:uppercase; color:#5a4e80; margin-bottom:.4rem;">
                        Contraseña
                    </label>
                    <input wire:model="password" type="password"
                        style="background:white; border:1px solid rgba(61,35,114,0.22); border-radius:4px;
                               padding:.7rem 1rem; color:#1f103b; font-family:'DM Sans',sans-serif;
                               font-size:.9rem; width:100%; outline:none;
                               transition:border-color .25s, box-shadow .25s;"
                        onfocus="this.style.borderColor='#3d2372';this.style.boxShadow='0 0 0 3px rgba(61,35,114,0.08)';"
                        onblur="this.style.borderColor='rgba(61,35,114,0.22)';this.style.boxShadow='none';"
                        placeholder="••••••••">
                    @error('password')
                    <p style="color:#cd3529; font-size:.75rem; margin-top:.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    wire:loading.attr="disabled"
                    style="width:100%; padding:.78rem 1.5rem; background:#cd3529; color:white;
                           border:none; border-radius:4px; font-family:'DM Sans',sans-serif;
                           font-size:.9rem; font-weight:700; letter-spacing:.06em;
                           cursor:pointer; transition:background .25s, transform .2s, box-shadow .25s;
                           display:flex; align-items:center; justify-content:center; gap:.5rem;"
                    onmouseover="this.style.background='#e04a3e';this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 14px rgba(205,53,41,0.4)';"
                    onmouseout="this.style.background='#cd3529';this.style.transform='none';this.style.boxShadow='none';">
                    <span wire:loading.remove>Iniciar sesión →</span>
                    <span wire:loading>Verificando...</span>
                </button>

            </form>

            <p style="text-align:center; margin-top:1.5rem; font-size:.75rem; color:#9490b0;">
                Vermur © {{ date('Y') }} — Sistema interno
            </p>
        </div>
    </div>

</div>
