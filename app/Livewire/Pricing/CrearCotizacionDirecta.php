<?php

namespace App\Livewire\Pricing;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Cliente;
use App\Models\User;
use App\Models\HistorialEstado;

class CrearCotizacionDirecta extends Component
{
    // Cliente
    public $cliente_id     = '';
    public $cliente_nombre = '';
    public $dias_credito   = 0;

    // Crear nuevo cliente inline
    public bool   $mostrarCrearCliente = false;
    public string $nuevoClienteNombre  = '';
    public int    $nuevoClienteDias    = 30;

    // Info general
    public $tipo_operacion  = '';
    public $tipo_transporte = '';
    public $tipo_mercancia  = '';
    public $incoterm        = '';
    public $pol_aol         = '';
    public $pod_asd         = '';
    public $asignado_a      = '';

    // Servicios
    public $recoleccion     = false;
    public $dir_recoleccion = '';
    public $entrega         = false;
    public $dir_entrega     = '';

    // Terrestre
    public $ter_tipo        = 'FTL';
    public $ter_unidad      = '';
    public $ter_mercancia   = '';
    public $ter_num_pallets = '';
    public $ter_peso        = '';
    public $ter_peso_unidad = 'kg';
    public $ter_medidas     = '';
    public $ter_volumen     = '';
    public $ter_estibable   = false;

    // FCL
    public $tipo_embarque      = 'ninguno';
    public $fcl_contenedor     = '';
    public $fcl_peso           = '';
    public $fcl_peso_unidad    = 'kg';
    public $fcl_reqs           = '';
    public $fcl_food_grade     = false;
    public $fcl_reforzado      = false;
    public $fcl_sobredimension = false;
    public $fcl_enlonado       = false;
    public $fcl_atmos_controlada = false;

    // LCL
    public $lcl_num_pallets    = '';
    public $lcl_estibable      = false;
    public $lcl_cubicaje_total = '';

    public $nota_interna = '';

    protected function rules(): array
    {
        return [
            'tipo_operacion'  => 'required',
            'tipo_transporte' => 'required',
            'tipo_mercancia'  => 'required',
            'cliente_nombre'  => 'required',
        ];
    }

    protected function messages(): array
    {
        return [
            'tipo_operacion.required'  => 'Selecciona el tipo de operación.',
            'tipo_transporte.required' => 'Selecciona el tipo de transporte.',
            'tipo_mercancia.required'  => 'Indica el tipo de mercancía.',
            'cliente_nombre.required'  => 'El nombre del cliente es obligatorio.',
        ];
    }

    public function updatedClienteId($value)
    {
        $cliente = Cliente::find($value);
        if ($cliente) {
            $this->cliente_nombre = $cliente->nombre;
            $this->dias_credito   = $cliente->dias_credito;
        }
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

        $this->cliente_id          = $cliente->id;
        $this->cliente_nombre      = $cliente->nombre;
        $this->dias_credito        = $cliente->dias_credito;
        $this->mostrarCrearCliente = false;
        $this->nuevoClienteNombre  = '';
        $this->nuevoClienteDias    = 30;
    }

    public function crear()
    {
        $this->validate();

        // Generar folio
        $año   = date('Y');
        $ultimo = Solicitud::orderBy('id', 'desc')->first();
        $num   = $ultimo ? ($ultimo->id + 1) : 1;
        $folio = sprintf("VRM-%04d%s", $num, $año);

        $solicitud = Solicitud::create([
            'folio'           => $folio,
            'cliente_id'      => $this->cliente_id ?: null,
            'cliente_nombre'  => $this->cliente_nombre,
            'dias_credito'    => $this->dias_credito,
            'creado_por'      => auth()->id(),
            'asignado_a'      => $this->asignado_a ?: auth()->id(),
            'tipo_operacion'  => $this->tipo_operacion,
            'tipo_transporte' => $this->tipo_transporte,
            'tipo_mercancia'  => $this->tipo_mercancia,
            'incoterm'        => $this->incoterm ?: null,
            'pol_aol'         => $this->pol_aol ?: null,
            'pod_asd'         => $this->pod_asd ?: null,
            'recoleccion'     => $this->recoleccion,
            'dir_recoleccion' => $this->dir_recoleccion ?: null,
            'entrega'         => $this->entrega,
            'dir_entrega'     => $this->dir_entrega ?: null,
            'tipo_embarque'   => $this->tipo_transporte === 'terrestre' ? 'ninguno' : $this->tipo_embarque,
            'fcl_contenedor'  => $this->fcl_contenedor ?: null,
            'fcl_peso'        => $this->fcl_peso ?: null,
            'fcl_peso_unidad' => $this->fcl_peso_unidad ?: null,
            'fcl_reqs'        => $this->fcl_reqs ?: null,
            'fcl_food_grade'  => $this->fcl_food_grade,
            'fcl_reforzado'   => $this->fcl_reforzado,
            'fcl_sobredimension' => $this->fcl_sobredimension,
            'fcl_enlonado'    => $this->fcl_enlonado,
            'fcl_atmos_controlada' => $this->fcl_atmos_controlada,
            'lcl_num_pallets' => $this->lcl_num_pallets ?: null,
            'lcl_estibable'   => $this->lcl_estibable,
            'lcl_cubicaje_total' => $this->lcl_cubicaje_total ?: null,
            'ter_tipo'        => $this->tipo_transporte === 'terrestre' ? $this->ter_tipo : null,
            'ter_unidad'      => $this->ter_unidad ?: null,
            'ter_mercancia'   => $this->ter_mercancia ?: null,
            'ter_num_pallets' => $this->ter_num_pallets ?: null,
            'ter_peso'        => $this->ter_peso ?: null,
            'ter_peso_unidad' => $this->ter_peso_unidad ?: null,
            'ter_medidas'     => $this->ter_medidas ?: null,
            'ter_volumen'     => $this->ter_volumen ?: null,
            'ter_estibable'   => $this->ter_estibable,
            'nota_interna'    => $this->nota_interna ?: null,
            'estado'          => 'en_revision',
        ]);

        HistorialEstado::create([
            'solicitud_id'    => $solicitud->id,
            'estado_anterior' => null,
            'estado_nuevo'    => 'en_revision',
            'user_id'         => auth()->id(),
            'motivo'          => 'Cotización directa creada por pricing',
        ]);

        return redirect()->route('pricing.cotizador', $solicitud->id);
    }

    public function render()
    {
        return view('livewire.pricing.crear-cotizacion-directa', [
            'clientes'       => Cliente::orderBy('nombre')->get(),
            'equipoPricing'  => User::where('rol', 'pricing')->where('activo', true)->get(),
        ])->layout('layouts.ventas');
    }
}
