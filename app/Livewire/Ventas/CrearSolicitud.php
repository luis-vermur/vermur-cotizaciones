<?php

namespace App\Livewire\Ventas;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Adjunto;
use App\Models\Pallet;
use App\Models\HistorialEstado;
use App\Services\CotizacionCalculator;
use Livewire\WithFileUploads;
use App\Notifications\NuevaSolicitudNotification;
use Illuminate\Support\Facades\Notification;



class CrearSolicitud extends Component
{
    use WithFileUploads;

    public array $archivos_fcl = [];
    public array $archivos_lcl = [];
    public array $pallets = [];

    // Crear nuevo cliente inline
    public bool   $mostrarCrearCliente  = false;
    public string $nuevoClienteNombre   = '';
    public int    $nuevoClienteDias     = 30;

    // Búsqueda de cliente
    public string $clienteBusqueda      = '';
    public bool   $mostrarDropdownCliente = false;


    // Info general
    public $cliente_id       = '';
    public $cliente_nombre   = '';
    public $dias_credito     = 0;
    public $asignado_a       = '';
    public $tipo_operacion   = '';
    public $tipo_transporte  = '';
    public $tipo_mercancia   = '';
    public $incoterm         = '';
    public $pol_aol          = '';
    public $pod_asd          = '';

    // Servicios
    public $recoleccion        = false;
    public $dir_recoleccion    = '';
    public $entrega            = false;
    public $dir_entrega        = '';
    public $seguro_mercancia   = false;
    public $requiere_despacho  = false;
    public $embalaje           = false;
    public $target             = false;
    public $financiamiento     = false;
    public $dias_financiamiento = '';

    // Comercial
    public $volumen_operacion = 1;
    public $valor_factura     = '';
    public $margen_profit     = '';

    // Embarque
    public $tipo_embarque = 'ninguno';

    // FCL
    public $fcl_contenedor      = '';
    public $fcl_peso            = '';
    public $fcl_peso_unidad     = '';
    public $fcl_reqs            = '';
    public $fcl_food_grade      = false;
    public $fcl_reforzado       = false;
    public $fcl_sobredimension  = false;
    public $fcl_enlonado        = false;
    public $fcl_atmos_controlada = false;

    // LCL
    public $lcl_num_pallets    = '';
    public $lcl_estibable      = false;
    public $lcl_cubicaje_total = '';
    public int $palletsVersion = 0;

    // Terrestre
    public $ter_tipo        = 'FTL';  // FTL | LTL
    public $ter_unidad      = '';
    public $ter_mercancia   = '';
    public $ter_num_pallets = '';
    public $ter_peso        = '';
    public $ter_peso_unidad = 'kg';
    public $ter_medidas     = '';
    public $ter_volumen     = '';
    public $ter_estibable   = false;

    public function updatedClienteBusqueda()
    {
        $this->mostrarDropdownCliente = strlen($this->clienteBusqueda) >= 1;
    }

    public function seleccionarCliente($id, $nombre, $dias)
    {
        $this->cliente_id             = $id;
        $this->cliente_nombre         = $nombre;
        $this->dias_credito           = $dias;
        $this->clienteBusqueda        = $nombre;
        $this->mostrarDropdownCliente = false;
    }

    public function updatedTipoTransporte()
    {
        // Resetear tipo de embarque al cambiar el transporte
        $this->tipo_embarque = 'ninguno';
    }

    public function updatedClienteId($value)
    {
        $cliente = Cliente::find($value);
        if ($cliente) {
            $this->cliente_nombre = $cliente->nombre;
            $this->dias_credito   = $cliente->dias_credito;
        }
    }

    protected function rules(): array
    {
        return [
            'cliente_nombre'  => 'required|string',
            'tipo_operacion'  => 'required|string',
            'tipo_transporte' => 'required|string',
            'tipo_mercancia'  => 'required|string',
            'nuevoClienteNombre'  => 'nullable|string|min:2',
            'nuevoClienteDias'    => 'nullable|integer|min:0',
            'archivos_fcl.*'  => 'file|mimes:pdf,jpg,jpeg,png,webp,xlsx,docx|max:10240',
            'archivos_lcl.*'  => 'file|mimes:pdf,jpg,jpeg,png,webp,xlsx,docx|max:10240',
        ];
    }

    public function crearCliente()
    {
        $this->validateOnly('nuevoClienteNombre', [
            'nuevoClienteNombre' => 'required|string|min:2',
        ], [], ['nuevoClienteNombre' => 'nombre del cliente']);

        $cliente = Cliente::create([
            'nombre'       => $this->nuevoClienteNombre,
            'dias_credito' => $this->nuevoClienteDias,
        ]);

        $this->cliente_id           = $cliente->id;
        $this->cliente_nombre       = $cliente->nombre;
        $this->dias_credito         = $cliente->dias_credito;
        $this->mostrarCrearCliente  = false;
        $this->nuevoClienteNombre   = '';
        $this->nuevoClienteDias     = 30;
    }

    public function guardar()
    {
        $this->validate();

        $solicitud = Solicitud::create([
            'folio'             => 'TMP-' . uniqid(),
            'cliente_id'        => $this->cliente_id ?: null,
            'cliente_nombre'    => $this->cliente_nombre,
            'dias_credito'      => $this->dias_credito,
            'creado_por'        => auth()->id(),
            'asignado_a'        => $this->asignado_a ?: null,
            'tipo_operacion'    => $this->tipo_operacion,
            'tipo_transporte'   => $this->tipo_transporte,
            'tipo_mercancia'    => $this->tipo_mercancia,
            'incoterm'          => $this->incoterm ?: null,
            'pol_aol'           => $this->pol_aol ?: null,
            'pod_asd'           => $this->pod_asd ?: null,
            'recoleccion'       => $this->recoleccion,
            'dir_recoleccion'   => $this->dir_recoleccion ?: null,
            'entrega'           => $this->entrega,
            'dir_entrega'       => $this->dir_entrega ?: null,
            'seguro_mercancia'  => $this->seguro_mercancia,
            'requiere_despacho' => $this->requiere_despacho,
            'embalaje'          => $this->embalaje,
            'target'            => $this->target,
            'financiamiento'    => $this->financiamiento,
            'dias_financiamiento' => $this->dias_financiamiento ?: null,
            'volumen_operacion' => $this->volumen_operacion,
            'valor_factura'     => $this->valor_factura ?: null,
            'margen_profit'     => $this->margen_profit ?: null,
            'tipo_embarque'     => $this->tipo_embarque,
            'fcl_contenedor'    => $this->fcl_contenedor ?: null,
            'fcl_peso'          => $this->fcl_peso ?: null,
            'fcl_peso_unidad'   => $this->fcl_peso_unidad ?: null,
            'fcl_reqs'          => $this->fcl_reqs ?: null,
            'fcl_food_grade'    => $this->fcl_food_grade,
            'fcl_reforzado'     => $this->fcl_reforzado,
            'fcl_sobredimension' => $this->fcl_sobredimension,
            'fcl_enlonado'      => $this->fcl_enlonado,
            'fcl_atmos_controlada' => $this->fcl_atmos_controlada,
            'lcl_num_pallets'   => $this->lcl_num_pallets ?: null,
            'lcl_estibable'     => $this->lcl_estibable,
            'lcl_cubicaje_total' => $this->lcl_cubicaje_total ?: null,
            'ter_tipo'          => $this->tipo_transporte === 'terrestre' ? $this->ter_tipo : null,
            'ter_unidad'        => $this->ter_unidad ?: null,
            'ter_mercancia'     => $this->ter_mercancia ?: null,
            'ter_num_pallets'   => $this->ter_num_pallets ?: null,
            'ter_peso'          => $this->ter_peso ?: null,
            'ter_peso_unidad'   => $this->ter_peso_unidad ?: null,
            'ter_medidas'       => $this->ter_medidas ?: null,
            'ter_volumen'       => $this->ter_volumen ?: null,
            'ter_estibable'     => $this->ter_estibable,
            'estado'            => 'nueva',
        ]);


        $folio = CotizacionCalculator::generarFolio($solicitud->id);
        $solicitud->update(['folio' => $folio]);

        HistorialEstado::create([
            'solicitud_id'    => $solicitud->id,
            'estado_anterior' => null,
            'estado_nuevo'    => 'nueva',
            'user_id'         => auth()->id(),
            'motivo'          => 'Solicitud creada por Ventas',
        ]);



        session()->flash('success', "Solicitud {$folio} creada correctamente.");

        // Guardar adjuntos FCL
        foreach ($this->archivos_fcl as $archivo) {
            $ruta = $archivo->store('adjuntos', 'public');
            Adjunto::create([
                'solicitud_id'   => $solicitud->id,
                'nombre_archivo' => $archivo->getClientOriginalName(),
                'ruta'           => $ruta,
                'tipo'           => $archivo->getClientOriginalExtension(),
            ]);
        }

        // Guardar adjuntos LCL
        foreach ($this->archivos_lcl as $archivo) {
            $ruta = $archivo->store('adjuntos', 'public');
            Adjunto::create([
                'solicitud_id'   => $solicitud->id,
                'nombre_archivo' => $archivo->getClientOriginalName(),
                'ruta'           => $ruta,
                'tipo'           => $archivo->getClientOriginalExtension(),
            ]);
        }

        // Guardar pallets
        foreach ($this->pallets as $pallet) {
            Pallet::create([
                'solicitud_id' => $solicitud->id,
                'numero'       => $pallet['numero'],
                'largo_cm'     => $pallet['largo_cm'] ?: null,
                'ancho_cm'     => $pallet['ancho_cm'] ?: null,
                'alto_cm'      => $pallet['alto_cm'] ?: null,
                'peso'         => $pallet['peso'] ?: null,
                'peso_unidad'  => $pallet['peso_unidad'] ?? 'kg',
                'cubicaje_m3'  => $pallet['cubicaje_m3'] ?: null,
            ]);
        }

        try {
            $equipoPricing = User::where('rol', 'pricing')->get();
            Notification::send($equipoPricing, new NuevaSolicitudNotification($solicitud));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error enviando notificación: ' . $e->getMessage());
        }

        return redirect()->route('ventas.dashboard');
    }

    public function agregarPallet()
    {
        $this->pallets[] = [
            'numero'     => count($this->pallets) + 1,
            'largo_cm'   => null,
            'ancho_cm'   => null,
            'alto_cm'    => null,
            'peso'       => null,
            'peso_unidad' => 'kg',
            'cubicaje_m3' => null,
        ];
    }

    public function eliminarPallet(int $index)
    {
        array_splice($this->pallets, $index, 1);
        // Renumerar
        foreach ($this->pallets as $i => $p) {
            $this->pallets[$i]['numero'] = $i + 1;
        }
    }

    public function updatedPallets($value, $key)
    {
        if (preg_match('/^(\d+)\.(largo_cm|ancho_cm|alto_cm)$/', $key, $m)) {
            $i     = (int) $m[1];
            $largo = floatval($this->pallets[$i]['largo_cm'] ?? 0);
            $ancho = floatval($this->pallets[$i]['ancho_cm'] ?? 0);
            $alto  = floatval($this->pallets[$i]['alto_cm'] ?? 0);

            if ($largo > 0 && $ancho > 0 && $alto > 0) {
                // Fórmula en metros: L × A × H directamente
                $this->pallets[$i]['cubicaje_m3'] = round($largo * $ancho * $alto, 4);
            }
        }

        $this->lcl_cubicaje_total = round(
            collect($this->pallets)->sum(fn($p) => floatval($p['cubicaje_m3'] ?? 0)),
            4
        );
    }

    public function updatedLclNumPallets($value)
    {
        $value = intval($value);
        if ($value < 1 || $value > 100) return;

        $actuales = count($this->pallets);

        if ($value > $actuales) {
            // Agregar pallets faltantes
            for ($i = $actuales; $i < $value; $i++) {
                $this->pallets[] = [
                    'numero'      => $i + 1,
                    'largo_cm'    => null,
                    'ancho_cm'    => null,
                    'alto_cm'     => null,
                    'peso'        => null,
                    'peso_unidad' => 'kg',
                    'cubicaje_m3' => null,
                ];
            }
        } elseif ($value < $actuales) {
            // Recortar
            $this->pallets = array_slice($this->pallets, 0, $value);
        }
    }


    public function copiarPrimerPallet()
    {
        if (count($this->pallets) < 2) return;

        $primero = $this->pallets[0];
        $total   = count($this->pallets);

        // Reconstruir el array completo desde cero
        $nuevos = [];
        for ($i = 0; $i < $total; $i++) {
            $nuevos[] = [
                'numero'      => $i + 1,
                'largo_cm'    => $primero['largo_cm'],
                'ancho_cm'    => $primero['ancho_cm'],
                'alto_cm'     => $primero['alto_cm'],
                'peso'        => $primero['peso'],
                'peso_unidad' => $primero['peso_unidad'],
                'cubicaje_m3' => $primero['cubicaje_m3'],
            ];
        }

        // Reemplazar el array completo — fuerza re-render de Livewire
        $this->pallets = [];
        $this->pallets = $nuevos;

        $this->lcl_cubicaje_total = round(
            collect($this->pallets)->sum(fn($p) => floatval($p['cubicaje_m3'] ?? 0)),
            4
        );

        $this->palletsVersion++;
    }

    public function render()
    {
        $clientesFiltrados = $this->clienteBusqueda
            ? Cliente::where('nombre', 'like', '%' . $this->clienteBusqueda . '%')->orderBy('nombre')->limit(10)->get()
            : collect();

        return view('livewire.ventas.crear-solicitud', [
            'clientes'          => Cliente::orderBy('nombre')->get(),
            'clientesFiltrados' => $clientesFiltrados,
            'equipoPricing'     => User::where('rol', 'pricing')->where('activo', true)->get(),
        ])->layout('layouts.ventas');
    }
}
