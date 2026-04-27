<div class="space-y-5">

    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-family:'Bebas Neue',sans-serif; font-size:2rem; letter-spacing:.04em; color:#3d2372; line-height:1;">Clientes</h1>
            <p style="font-size:.82rem; color:#9490b0; margin-top:.15rem;">Catálogo de clientes del sistema</p>
        </div>
        <button wire:click="abrirCrear"
            style="padding:.6rem 1.25rem; background:#3d2372; color:white; border:none; border-radius:6px;
                   font-size:.85rem; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif;"
            onmouseover="this.style.background='#5035a0';" onmouseout="this.style.background='#3d2372';">
            + Nuevo cliente
        </button>
    </div>

    @if(session('success'))
    <div style="padding:.75rem 1rem; background:#d1fae5; border:1px solid #6ee7b7; border-radius:8px; color:#065f46; font-size:.85rem; font-weight:600;">
        ✓ {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="padding:.75rem 1rem; background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; color:#991b1b; font-size:.85rem; font-weight:600;">
        {{ session('error') }}
    </div>
    @endif

    <div style="background:white; border-radius:10px; padding:1rem 1.25rem; border:1px solid rgba(61,35,114,0.08);">
        <input wire:model.live="busqueda" type="text"
            style="border:1px solid rgba(61,35,114,0.18); border-radius:6px; padding:.45rem .85rem;
                   font-size:.85rem; font-family:'DM Sans',sans-serif; width:100%; max-width:320px; outline:none;"
            onfocus="this.style.borderColor='#3d2372';" onblur="this.style.borderColor='rgba(61,35,114,0.18)';"
            placeholder="Buscar cliente...">
    </div>

    <div style="background:white; border-radius:12px; border:1px solid rgba(61,35,114,0.08); box-shadow:0 1px 4px rgba(61,35,114,0.05); overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; font-size:.85rem;">
            <thead>
                <tr style="background:#f8f7fc; border-bottom:1px solid rgba(61,35,114,0.1);">
                    <th style="text-align:left; padding:.75rem 1.25rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Nombre</th>
                    <th style="text-align:center; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Días crédito</th>
                    <th style="text-align:center; padding:.75rem .75rem; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9490b0;">Solicitudes</th>
                    <th style="padding:.75rem .75rem; width:6rem;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $c)
                <tr style="border-bottom:1px solid rgba(61,35,114,0.06);"
                    onmouseover="this.style.background='rgba(61,35,114,0.02)';" onmouseout="this.style.background='';">
                    <td style="padding:.75rem 1.25rem; font-weight:600; color:#1f103b;">{{ $c->nombre }}</td>
                    <td style="padding:.75rem .75rem; text-align:center;">
                        <span style="font-family:monospace; font-size:.85rem; color:#5a4e80;">{{ $c->dias_credito }} días</span>
                    </td>
                    <td style="padding:.75rem .75rem; text-align:center;">
                        <span style="background:#ede9fe; color:#3d1a8e; font-size:.75rem; font-weight:700;
                                     padding:.2rem .6rem; border-radius:999px;">{{ $c->solicitudes_count }}</span>
                    </td>
                    <td style="padding:.75rem .75rem; text-align:right;">
                        <div style="display:flex; gap:.4rem; justify-content:flex-end;">
                            <button wire:click="abrirEditar({{ $c->id }})"
                                style="padding:.3rem .7rem; border:1px solid rgba(61,35,114,0.2); border-radius:4px; font-size:.75rem; color:#3d2372; background:white; cursor:pointer; font-family:'DM Sans',sans-serif;"
                                onmouseover="this.style.background='#f0ecf8';" onmouseout="this.style.background='white';">Editar</button>
                            <button wire:click="eliminar({{ $c->id }})" wire:confirm="¿Eliminar a {{ $c->nombre }}?"
                                style="padding:.3rem .7rem; border:1px solid rgba(205,53,41,0.2); border-radius:4px; font-size:.75rem; color:#cd3529; background:white; cursor:pointer; font-family:'DM Sans',sans-serif;"
                                onmouseover="this.style.background='#fff0ee';" onmouseout="this.style.background='white';">×</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding:3rem; text-align:center; color:#9490b0;">No se encontraron clientes.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($clientes->hasPages())
        <div style="padding:.75rem 1.25rem; border-top:1px solid rgba(61,35,114,0.08);">{{ $clientes->links() }}</div>
        @endif
    </div>

    @if($mostrarModal)
    <div style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.45);" wire:click.self="$set('mostrarModal', false)">
        <div style="background:white; border-radius:16px; width:100%; max-width:24rem; margin:0 1rem; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">
            <div style="padding:1rem 1.5rem; background:#3d2372; display:flex; align-items:center; justify-content:space-between;">
                <h3 style="color:white; font-weight:700; font-size:.95rem; font-family:'DM Sans',sans-serif;">{{ $editando ? 'Editar cliente' : 'Nuevo cliente' }}</h3>
                <button wire:click="$set('mostrarModal', false)" style="color:rgba(255,255,255,0.7); background:none; border:none; cursor:pointer; font-size:1.5rem; line-height:1;">×</button>
            </div>
            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">
                <div>
                    <label style="display:block; font-size:.72rem; font-weight:700; color:#9490b0; letter-spacing:.08em; text-transform:uppercase; margin-bottom:.35rem;">Nombre</label>
                    <input wire:model="nombre" type="text"
                        style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:6px; padding:.55rem .85rem; font-size:.875rem; font-family:'DM Sans',sans-serif; outline:none;"
                        onfocus="this.style.borderColor='#3d2372';" onblur="this.style.borderColor='rgba(61,35,114,0.2)';"
                        placeholder="Nombre del cliente">
                    @error('nombre')<p style="color:#cd3529;font-size:.72rem;margin-top:.25rem;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:.72rem; font-weight:700; color:#9490b0; letter-spacing:.08em; text-transform:uppercase; margin-bottom:.35rem;">Días de crédito</label>
                    <input wire:model="diasCredito" type="number" min="0"
                        style="width:100%; border:1px solid rgba(61,35,114,0.2); border-radius:6px; padding:.55rem .85rem; font-size:.875rem; font-family:monospace; outline:none;"
                        onfocus="this.style.borderColor='#3d2372';" onblur="this.style.borderColor='rgba(61,35,114,0.2)';">
                    @error('diasCredito')<p style="color:#cd3529;font-size:.72rem;margin-top:.25rem;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="padding:.85rem 1.5rem; border-top:1px solid rgba(61,35,114,0.1); background:#f8f7fc; display:flex; justify-content:flex-end; gap:.75rem;">
                <button wire:click="$set('mostrarModal', false)" style="padding:.5rem 1.1rem; background:white; border:1px solid rgba(61,35,114,0.2); border-radius:6px; font-size:.875rem; color:#5a4e80; cursor:pointer; font-family:'DM Sans',sans-serif;">Cancelar</button>
                <button wire:click="guardar" style="padding:.5rem 1.4rem; background:#3d2372; color:white; border:none; border-radius:6px; font-size:.875rem; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif;" onmouseover="this.style.background='#5035a0';" onmouseout="this.style.background='#3d2372';">{{ $editando ? 'Guardar' : 'Crear' }}</button>
            </div>
        </div>
    </div>
    @endif

</div>
