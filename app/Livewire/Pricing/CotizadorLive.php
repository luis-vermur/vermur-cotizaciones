<?php

namespace App\Livewire\Pricing;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Solicitud;
use App\Models\Cotizacion;
use App\Models\CotizacionLclDetalle;
use App\Models\LineaCotizacion;
use App\Models\HistorialEstado;
use App\Models\Adjunto;
use App\Models\Proveedor;
use App\Models\Comentario;
use App\Services\CotizacionCalculator;
use App\Notifications\CotizacionEntregadaNotification;

class CotizadorLive extends Component
{
    use WithFileUploads;

    public int $solicitudId;

    // Tarifas — comparativa de agentes
    public array $agentes    = [];  // ['ASIA SHIP CO', 'DHL', ...]
    public array $agentesIds = [];  // [proveedor_id|null, ...]  — índice paralelo a $agentes
    public array $tarifas    = [];  // [concepto][agente_index] = valor
    public string $nuevoAgente = '';
    public ?int   $nuevoAgenteProveedorId = null;
    public bool   $mostrarModalAgente = false;

    // Cabecera
    public string $tipo_plantilla = 'MXN';
    public string $moneda         = 'MXN'; // MXN | USD
    public int    $version        = 1;
    public float  $tc             = 0;
    public float  $margen_deseado = 0;
    public float  $costo_ope      = 1500;
    public string $validez        = '';
    public string $notas          = '';
    public ?int $proveedor_global_id = null;

    // Líneas
    public array $lineas = [];

    // Para plantilla terrestre
    public array $unidades = []; // unidad por línea: Torton, Rabon, Full, etc.

    // Totales
    public float $costo_total          = 0;
    public float $profit_total         = 0;
    public float $venta_total          = 0;
    public float $margen_real          = 0;
    public float $comision_monto       = 0;
    public float $financiamiento_monto = 0;
    public float $profit_real_monto    = 0;
    public float $ganancia_real        = 0;
    public float $profit_a_sumar       = 0;

    public array $etiquetas = [];

    // Imágenes de referencia
    public array $imagenesSubir = [];

    // LCL campos adicionales (solo aplica cuando tipo_plantilla = 'LCL')
    public string $lcl_pol              = '';
    public string $lcl_pod              = '';
    public string $lcl_incoterm         = '';
    public int    $lcl_piezas           = 0;
    public float  $lcl_peso_tons        = 0;
    public float  $lcl_medidas_cbm      = 0;
    public float  $lcl_pickup           = 0;
    public float  $lcl_despacho_mxn     = 0;
    public float  $lcl_maniobras_mxn    = 0;
    public float  $lcl_desconsolidacion = 0;
    public float  $lcl_transfer_fee     = 0;
    public float  $lcl_revalidacion     = 0;
    public float  $lcl_transmision      = 0;
    public float  $lcl_admon_fee        = 0;
    public float  $lcl_recargo_imo      = 0;
    public float  $lcl_iva_pct          = 16;
    public float  $lcl_total_local      = 0;
    public float  $lcl_iva              = 0;
    public float  $lcl_total_iva        = 0;

    // Crear proveedor inline
    public bool   $mostrarCrearProveedor = false;
    public string $nuevoProveedorNombre  = '';

    // Chat pricing
    public string $msgPricing = '';

    // Modal — cargar tarifas de solicitud anterior
    public bool   $mostrarModalTarifasAnt = false;
    public string $busquedaTarifasAnt     = '';
    public ?int   $solicitudAntId         = null;

    // Entregar a Ventas (PDF)
    public $pdfEntrega    = null;
    public bool $mostrarEntrega = false;

    const CONCEPTOS_DEFAULT = [
        'gastos_origen'        => 'Gastos de origen',
        'gastos_destino'       => 'Gastos de destino',
        'flete_internacional'  => 'Flete internacional',
        'seguro'               => 'Seguro',
        'despacho'             => 'Despacho',
        'maniobra'             => 'Maniobra',
        'entrega'              => 'Entrega',
        'dias_transito'        => 'Días de tránsito',
        'validez'              => 'Validez',
    ];

    const CONCEPTOS_FCL = [
        'flete_internacional'  => 'Flete internacional',
        't_pantaco'            => 'T. Pantaco',
        'rail_pantaco'         => 'Rail Pantaco',
        'ams'                  => 'AMS',
        'doc_fee'              => 'Doc Fee',
        'isps'                 => 'ISPS',
        'bl_fee'               => 'BL Fee',
        'validez'              => 'Validez',
    ];

    const CONCEPTOS_TERRESTRE = [
        'flete'                => 'Flete',
        'seguro'               => 'Seguro',
        'maniobra_origen'      => 'Maniobra origen',
        'maniobra_destino'     => 'Maniobra destino',
        'despacho'             => 'Despacho',
        'validez'              => 'Validez',
    ];


    public function mount(int $solicitud)
    {
        if (!in_array(auth()->user()->rol, ['pricing', 'admin'])) abort(403);
        $this->solicitudId = $solicitud;

        $ultima = Cotizacion::where('solicitud_id', $solicitud)
            ->orderBy('version', 'desc')
            ->first();

        // Autocargar TC del sistema (Banxico, actualizado cada 24h)
        $this->tc = \App\Models\TipoCambio::vigente();

        if ($ultima) {
            $this->tipo_plantilla = $ultima->tipo_plantilla;
            $this->moneda         = $ultima->moneda ?? $this->monedaDerivada($ultima->tipo_plantilla);
            $this->version        = $ultima->version;
            $this->tc             = $ultima->tc ?? $this->tc;
            $this->margen_deseado = $ultima->margen_deseado ?? 0;
            $this->costo_ope      = $ultima->costo_ope;
            $this->validez        = $ultima->validez ?? '15 días';
            $this->notas          = $ultima->notas ?? '';

            $this->lineas = $ultima->lineas()
                ->orderBy('orden')
                ->get()
                ->map(fn($l) => [
                    'proveedor_id'     => $l->proveedor_id,
                    'proveedor_nombre' => $l->proveedor_nombre,
                    'concepto'         => $l->concepto,
                    'costo'            => $l->costo,
                    'profit'           => $l->profit,
                    'venta'            => $l->venta,
                    'margen'           => round(($l->margen ?? 0) * 100, 2),
                    'target'           => $l->target,
                    'orden'            => $l->orden,
                ])
                ->toArray();
        }

        $this->recalcularTodo();

        // Cargar campos LCL si la última cotización es de tipo LCL
        if ($ultima && $this->tipo_plantilla === 'LCL' && $ultima->lclDetalle) {
            $d = $ultima->lclDetalle;
            $this->lcl_pol              = $d->pol              ?? '';
            $this->lcl_pod              = $d->pod              ?? '';
            $this->lcl_incoterm         = $d->incoterm         ?? '';
            $this->lcl_piezas           = $d->piezas           ?? 0;
            $this->lcl_peso_tons        = $d->peso_tons        ?? 0;
            $this->lcl_medidas_cbm      = $d->medidas_cbm      ?? 0;
            $this->lcl_pickup           = $d->pickup           ?? 0;
            $this->lcl_despacho_mxn     = $d->despacho_mxn     ?? 0;
            $this->lcl_maniobras_mxn    = $d->maniobras_mxn    ?? 0;
            $this->lcl_desconsolidacion = $d->desconsolidacion ?? 0;
            $this->lcl_transfer_fee     = $d->transfer_fee     ?? 0;
            $this->lcl_revalidacion     = $d->revalidacion     ?? 0;
            $this->lcl_transmision      = $d->transmision      ?? 0;
            $this->lcl_admon_fee        = $d->admon_fee        ?? 0;
            $this->lcl_recargo_imo      = $d->recargo_imo      ?? 0;
            $this->lcl_iva_pct          = $d->iva_pct ?? 16;
            $this->recalcularLcl();
        }

        // Cargar tarifas guardadas
        $tarifaGuardada = \App\Models\TarifaSolicitud::where('solicitud_id', $solicitud)->first();
        if ($tarifaGuardada && $tarifaGuardada->datos_json) {
            $datos = is_array($tarifaGuardada->datos_json)
                ? $tarifaGuardada->datos_json
                : json_decode($tarifaGuardada->datos_json, true);
            $this->agentes    = $datos['agentes']     ?? [];
            $this->agentesIds = $datos['agentes_ids'] ?? array_fill(0, count($this->agentes), null);
            $this->tarifas    = $datos['tarifas']     ?? [];
            $this->etiquetas  = $datos['etiquetas']   ?? [];
        }
    }

    public function agregarLinea()
    {
        $nombreProveedor = '';
        if ($this->proveedor_global_id) {
            $proveedor       = Proveedor::find($this->proveedor_global_id);
            $nombreProveedor = $proveedor?->nombre ?? '';
        }

        $this->lineas[] = [
            'proveedor_id'     => $this->proveedor_global_id,
            'proveedor_nombre' => $nombreProveedor,
            'concepto'         => '',
            'unidad'           => '',  
            'ruta'             => '',  
            'costo'            => 0,
            'profit'           => 0,
            'venta'            => 0,
            'margen'           => 0,
            'target'           => null,
            'orden'            => count($this->lineas),
        ];
    }

    public function eliminarLinea(int $index)
    {
        array_splice($this->lineas, $index, 1);
        $this->recalcularTodo();
    }

    public function updatedLineas($value, $key)
    {
        if (str_ends_with($key, '.proveedor_id') && $value) {
            $index     = (int) explode('.', $key)[0];
            $proveedor = Proveedor::find($value);
            if ($proveedor) {
                $this->lineas[$index]['proveedor_nombre'] = $proveedor->nombre;
            }
        }

        $this->recalcularTodo();
    }

    public function updatedTipoPlantilla()
    {
        // Sincronizar moneda automáticamente según la plantilla seleccionada
        $this->moneda = $this->monedaDerivada($this->tipo_plantilla);
        $this->recalcularTodo();
    }

    public function updatedCostoOpe()
    {
        $this->recalcularTodo();
    }

    public function updatedMargenDeseado()
    {
        $this->recalcularTodo();
    }

    public function recalcularTodo()
    {
        foreach ($this->lineas as $i => $linea) {
            $result = CotizacionCalculator::calcLinea(
                floatval($linea['costo'] ?? 0),
                floatval($linea['profit'] ?? 0)
            );
            $this->lineas[$i]['venta']  = $result['venta'];
            $this->lineas[$i]['margen'] = round($result['margen'] * 100, 2);
        }

        $solicitud = Solicitud::findOrFail($this->solicitudId);

        $totales = CotizacionCalculator::calcTotales(
            $this->lineas,
            $solicitud->dias_credito,
            $this->costo_ope
        );

        $this->costo_total          = $totales['costo_total'];
        $this->profit_total         = $totales['profit_total'];
        $this->venta_total          = $totales['venta_total'];
        $this->margen_real          = round($totales['margen_real'] * 100, 2);
        $this->comision_monto       = $totales['comision_monto'];
        $this->financiamiento_monto = $totales['financiamiento_monto'];
        $this->profit_real_monto    = $totales['profit_real_monto'];
        $this->ganancia_real        = $totales['ganancia_real'];

        if ($this->margen_deseado > 0) {
            $this->profit_a_sumar = CotizacionCalculator::calcProfitFaltante(
                $this->costo_total,
                $this->venta_total,
                $this->margen_deseado / 100
            );
        }
    }

    public function crearProveedor()
    {
        $this->validateOnly('nuevoProveedorNombre', [
            'nuevoProveedorNombre' => 'required|string|min:2|unique:proveedores,nombre',
        ]);

        $p = Proveedor::create([
            'nombre' => $this->nuevoProveedorNombre,
            'activo' => true,
        ]);

        $this->nuevoAgenteProveedorId = $p->id;
        $this->nuevoAgente            = $p->nombre;
        $this->nuevoProveedorNombre   = '';
        $this->mostrarCrearProveedor  = false;
    }

    public function enviarMensajePricing(): void
    {
        $texto = trim($this->msgPricing);
        if (!$texto) return;

        Comentario::create([
            'solicitud_id' => $this->solicitudId,
            'user_id'      => auth()->id(),
            'texto'        => $texto,
            'rol'          => 'pricing',
        ]);

        $this->msgPricing = '';
    }

    public function marcarResuelto(int $id)
    {
        $comentario = Comentario::where('solicitud_id', $this->solicitudId)->findOrFail($id);
        if ($comentario->user_id !== auth()->id() && auth()->user()->rol !== 'admin') {
            abort(403);
        }
        $comentario->update([
            'resuelto'    => true,
            'resuelto_en' => now(),
        ]);
    }

    public function updatedNuevoAgenteProveedorId($value)
    {
        if ($value) {
            $p = Proveedor::find($value);
            if ($p) $this->nuevoAgente = $p->nombre;
        }
    }

    public function agregarAgente()
    {
        // Si no hay nombre manual, usar el nombre del proveedor seleccionado
        if (!trim($this->nuevoAgente) && $this->nuevoAgenteProveedorId) {
            $p = Proveedor::find($this->nuevoAgenteProveedorId);
            if ($p) $this->nuevoAgente = $p->nombre;
        }

        $nombre = strtoupper(trim($this->nuevoAgente));
        if (!$nombre) return;

        $this->agentes[]    = $nombre;
        $this->agentesIds[] = $this->nuevoAgenteProveedorId;
        $this->nuevoAgente  = '';
        $this->nuevoAgenteProveedorId = null;
        $this->mostrarModalAgente = false;

        // Si es el primer agente, inicializar conceptos default
        if (count($this->agentes) === 1) {
            $conceptos = match ($this->tipo_plantilla) {
                'FCL'        => self::CONCEPTOS_FCL,
                'terrestre'  => self::CONCEPTOS_TERRESTRE,
                default      => self::CONCEPTOS_DEFAULT,
            };
            foreach ($conceptos as $key => $label) {
                $this->tarifas[$key]   = [0 => null];
                $this->etiquetas[$key] = $label;
            }
        } else {
            // Agregar columna vacía a conceptos existentes
            $newIndex = count($this->agentes) - 1;
            foreach ($this->tarifas as $key => $fila) {
                $this->tarifas[$key][$newIndex] = null;
            }
        }
    }

    public function eliminarAgente(int $index)
    {
        array_splice($this->agentes,    $index, 1);
        array_splice($this->agentesIds, $index, 1);

        // Reindexar columnas en tarifas
        foreach ($this->tarifas as $concepto => $vals) {
            $nuevaFila = [];
            foreach ($vals as $i => $val) {
                if ($i === $index) continue;
                $nuevaFila[] = $val;
            }
            $this->tarifas[$concepto] = $nuevaFila;
        }
    }

    public function agregarConceptoTarifa()
    {
        $key = 'concepto_' . uniqid();
        $this->tarifas[$key]   = array_fill(0, count($this->agentes), null);
        $this->etiquetas[$key] = 'Nuevo concepto';
    }

    public function eliminarConceptoTarifa(string $key)
    {
        unset($this->tarifas[$key]);
        unset($this->etiquetas[$key]);
    }

    public function abrirModalTarifasAnt(): void
    {
        $this->mostrarModalTarifasAnt = true;
        $this->busquedaTarifasAnt     = '';
        $this->solicitudAntId         = null;
    }

    public function cargarTarifasDeAnterior(): void
    {
        if (!$this->solicitudAntId) return;

        $tarifa = \App\Models\TarifaSolicitud::where('solicitud_id', $this->solicitudAntId)->first();
        if (!$tarifa) return;

        $datos = is_array($tarifa->datos_json)
            ? $tarifa->datos_json
            : json_decode($tarifa->datos_json, true);

        $this->agentes    = $datos['agentes']     ?? [];
        $this->agentesIds = $datos['agentes_ids'] ?? array_fill(0, count($this->agentes), null);
        $this->tarifas    = $datos['tarifas']     ?? [];
        $this->etiquetas  = $datos['etiquetas']   ?? [];

        $this->mostrarModalTarifasAnt = false;
        session()->flash('success_tarifas', 'Tarifas cargadas desde solicitud anterior.');
    }

    public function guardarTarifas()
    {
        \App\Models\TarifaSolicitud::updateOrCreate(
            ['solicitud_id' => $this->solicitudId],
            [
                'datos_json' => json_encode([
                    'agentes'     => $this->agentes,
                    'agentes_ids' => $this->agentesIds,
                    'tarifas'     => $this->tarifas,
                    'etiquetas'   => $this->etiquetas,
                ]),
                'actualizado_por' => auth()->id(),
            ]
        );

        session()->flash('success_tarifas', 'Tarifas guardadas.');
    }

    public function cargarTarifasEnLineas()
    {
        if (empty($this->agentes) || empty($this->tarifas)) return;

        // Encontrar agente con menor costo total
        $mejorAgente = null;
        $menorTotal  = PHP_FLOAT_MAX;

        foreach ($this->agentes as $i => $agente) {
            $total = collect($this->tarifas)
                ->sum(fn($fila) => floatval($fila[$i] ?? 0));
            if ($total < $menorTotal && $total > 0) {
                $menorTotal  = $total;
                $mejorAgente = $i;
            }
        }

        if ($mejorAgente === null) return;

        // Limpiar líneas existentes antes de cargar
        $this->lineas = [];

        $proveedorId     = $this->agentesIds[$mejorAgente] ?? null;
        $proveedorNombre = $this->agentes[$mejorAgente];

        // Si tiene proveedor_id pero el nombre no está guardado, obtenerlo del modelo
        if ($proveedorId && !$proveedorNombre) {
            $p = Proveedor::find($proveedorId);
            $proveedorNombre = $p?->nombre ?? '';
        }

        foreach ($this->tarifas as $key => $fila) {
            $costo = floatval($fila[$mejorAgente] ?? 0);
            if ($costo <= 0) continue;

            $this->lineas[] = [
                'proveedor_id'     => $proveedorId,
                'proveedor_nombre' => $proveedorNombre,
                'concepto'         => $this->etiquetas[$key] ?? $key,
                'costo'            => $costo,
                'profit'           => 0,
                'venta'            => $costo,
                'margen'           => 0,
                'target'           => null,
                'orden'            => count($this->lineas),
            ];
        }

        $this->recalcularTodo();
        session()->flash('success', 'Líneas actualizadas desde la mejor tarifa.');
    }

    public function subirImagenes()
    {
        $this->validate([
            'imagenesSubir.*' => 'file|mimes:jpg,jpeg,png,webp,heic,pdf|max:10240',
        ]);

        foreach ($this->imagenesSubir as $img) {
            $ruta = $img->store('referencias/' . $this->solicitudId, 'public');
            Adjunto::create([
                'solicitud_id'   => $this->solicitudId,
                'nombre_archivo' => $img->getClientOriginalName(),
                'ruta'           => $ruta,
                'tipo'           => $img->getClientOriginalExtension(),
            ]);
        }

        $this->imagenesSubir = [];
        session()->flash('success_img', 'Imágenes subidas correctamente.');
    }

    public function eliminarAdjunto(int $adjuntoId)
    {
        $adjunto = Adjunto::where('solicitud_id', $this->solicitudId)->findOrFail($adjuntoId);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($adjunto->ruta);
        $adjunto->delete();
    }

    public function recalcularLcl()
    {
        $this->lcl_total_local =
            $this->lcl_pickup +
            $this->lcl_despacho_mxn +
            $this->lcl_maniobras_mxn +
            $this->lcl_desconsolidacion +
            $this->lcl_transfer_fee +
            $this->lcl_revalidacion +
            $this->lcl_transmision +
            $this->lcl_admon_fee +
            $this->lcl_recargo_imo;

        $this->lcl_iva       = round($this->lcl_total_local * ($this->lcl_iva_pct / 100), 2);
        $this->lcl_total_iva = round($this->lcl_total_local + $this->lcl_iva, 2);
    }

    public function updatedLclIvaPct()
    {
        $this->recalcularLcl();
    }

    public function guardar()
    {
        try {
            $this->recalcularTodo();

            $solicitud = Solicitud::findOrFail($this->solicitudId);

            $totales = CotizacionCalculator::calcTotales(
                $this->lineas,
                $solicitud->dias_credito,
                $this->costo_ope
            );

            $folioCoti = CotizacionCalculator::generarFolioCoti(
                $this->solicitudId,
                $this->version
            );

            // Guardar o actualizar cotización
            $cotizacion = Cotizacion::updateOrCreate(
                [
                    'solicitud_id' => $this->solicitudId,
                    'version'      => $this->version,
                ],
                [
                    'folio_coti'           => $folioCoti,
                    'tipo_plantilla'       => $this->tipo_plantilla,
                    'moneda'               => $this->moneda,
                    'tc'                   => $this->tc ?: null,
                    'margen_deseado'       => $this->margen_deseado ?: null,
                    'costo_ope'            => $this->costo_ope,
                    'validez'              => $this->validez,
                    'notas'                => $this->notas,
                    'creado_por'           => auth()->id(),
                    'costo_total'          => $totales['costo_total'],
                    'profit_total'         => $totales['profit_total'],
                    'venta_total'          => $totales['venta_total'],
                    'margen_real'          => $totales['margen_real'],
                    'comision_pct'         => $totales['comision_pct'],
                    'comision_monto'       => $totales['comision_monto'],
                    'financiamiento_pct'   => $totales['financiamiento_pct'],
                    'financiamiento_monto' => $totales['financiamiento_monto'],
                    'profit_real_pct'      => $totales['profit_real_pct'],
                    'profit_real_monto'    => $totales['profit_real_monto'],
                    'ganancia_real'        => $totales['ganancia_real'],
                ]
            );

            // Guardar líneas — eliminar y recrear
            $cotizacion->lineas()->delete();

            foreach ($this->lineas as $i => $linea) {
                $calc = CotizacionCalculator::calcLinea(
                    floatval($linea['costo'] ?? 0),
                    floatval($linea['profit'] ?? 0)
                );
                LineaCotizacion::create([
                    'cotizacion_id'    => $cotizacion->id,
                    'proveedor_id'     => $linea['proveedor_id'] ?: null,
                    'proveedor_nombre' => $linea['proveedor_nombre'] ?? '',
                    'concepto'         => $linea['concepto'] ?? '',
                    'costo'            => floatval($linea['costo'] ?? 0),
                    'profit'           => floatval($linea['profit'] ?? 0),
                    'venta'            => $calc['venta'],
                    'margen'           => $calc['margen'],
                    'target'           => $linea['target'] ?: null,
                    'orden'            => $i,
                ]);
            }

            // Guardar detalle LCL si aplica
            if ($this->tipo_plantilla === 'LCL') {
                $this->recalcularLcl();
                CotizacionLclDetalle::updateOrCreate(
                    ['cotizacion_id' => $cotizacion->id],
                    [
                        'pol'              => $this->lcl_pol,
                        'pod'              => $this->lcl_pod,
                        'incoterm'         => $this->lcl_incoterm,
                        'piezas'           => $this->lcl_piezas,
                        'peso_tons'        => $this->lcl_peso_tons,
                        'medidas_cbm'      => $this->lcl_medidas_cbm,
                        'pickup'           => $this->lcl_pickup,
                        'despacho_mxn'     => $this->lcl_despacho_mxn,
                        'maniobras_mxn'    => $this->lcl_maniobras_mxn,
                        'desconsolidacion' => $this->lcl_desconsolidacion,
                        'transfer_fee'     => $this->lcl_transfer_fee,
                        'revalidacion'     => $this->lcl_revalidacion,
                        'transmision'      => $this->lcl_transmision,
                        'admon_fee'        => $this->lcl_admon_fee,
                        'recargo_imo'      => $this->lcl_recargo_imo,
                        'total_local'      => $this->lcl_total_local,
                        'iva_pct'          => $this->lcl_iva_pct,
                        'iva'              => $this->lcl_iva,
                        'total_iva'        => $this->lcl_total_iva,
                    ]
                );
            }

            // Cambiar estado de solicitud
            if ($solicitud->puedeTransicionarA('cotizada')) {
                $estadoAnterior = $solicitud->estado;
                $solicitud->update(['estado' => 'cotizada']);

                HistorialEstado::create([
                    'solicitud_id'    => $solicitud->id,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo'    => 'cotizada',
                    'user_id'         => auth()->id(),
                    'motivo'          => "Cotización guardada: {$folioCoti}",
                ]);
            }

            session()->flash('success', "Cotización {$folioCoti} guardada correctamente.");
            return redirect()->route('pricing.dashboard');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('CotizadorLive::guardar', [
                'error'        => $e->getMessage(),
                'solicitud_id' => $this->solicitudId,
            ]);
            session()->flash('error', 'Error al guardar la cotización. Intenta de nuevo.');
        }
    }

    public function nuevaVersion()
    {
        $count = Cotizacion::where('solicitud_id', $this->solicitudId)->count();
        if ($count >= 10) {
            session()->flash('error', 'Límite máximo de 10 versiones alcanzado.');
            return;
        }
        $this->version++;
        $this->lineas = [];
        $this->recalcularTodo();
        session()->flash('info', "Nueva versión V{$this->version} lista — agrega líneas y guarda.");
    }

    public function entregarAVentas()
    {
        $solicitud = Solicitud::findOrFail($this->solicitudId);

        if (!in_array($solicitud->estado, ['cotizada', 'en_revision'])) {
            session()->flash('error', 'Guarda la cotización antes de entregarla.');
            return;
        }

        if ($this->pdfEntrega) {
            $this->validate(['pdfEntrega' => 'file|mimes:pdf|max:20480']);
            $ruta = $this->pdfEntrega->store('cotizaciones/' . $this->solicitudId, 'public');
            Adjunto::create([
                'solicitud_id'   => $this->solicitudId,
                'nombre_archivo' => $this->pdfEntrega->getClientOriginalName(),
                'ruta'           => $ruta,
                'tipo'           => 'pdf',
            ]);
        }

        $estadoAnterior = $solicitud->estado;
        $solicitud->update(['estado' => 'enviada']);
        HistorialEstado::create([
            'solicitud_id'    => $solicitud->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => 'enviada',
            'user_id'         => auth()->id(),
            'motivo'          => 'Cotización entregada a Ventas desde cotizador',
        ]);

        // Notificar al usuario de ventas que creó la solicitud
        try {
            $solicitud->creadoPor?->notify(new CotizacionEntregadaNotification($solicitud));
        } catch (\Throwable) {}

        $this->pdfEntrega    = null;
        $this->mostrarEntrega = false;
        session()->flash('success', 'Cotización entregada a Ventas exitosamente.');
        return redirect()->route('pricing.solicitud', $this->solicitudId);
    }

    public function render()
    {
        $solicitud   = Solicitud::with(['comentarios.user'])->findOrFail($this->solicitudId);
        $proveedores = Proveedor::activos()->orderBy('nombre')->get();

        $solicitudesConTarifas = collect();
        if ($this->mostrarModalTarifasAnt) {
            $registros = \App\Models\TarifaSolicitud::with('solicitud')
                ->where('solicitud_id', '!=', $this->solicitudId)
                ->orderBy('updated_at', 'desc')
                ->limit(50)
                ->get()
                ->filter(fn($t) => $t->solicitud !== null);

            if ($this->busquedaTarifasAnt) {
                $busq = strtolower($this->busquedaTarifasAnt);
                $registros = $registros->filter(
                    fn($t) =>
                    str_contains(strtolower($t->solicitud->folio ?? ''), $busq) ||
                        str_contains(strtolower($t->solicitud->cliente_nombre ?? ''), $busq)
                );
            }

            $solicitudesConTarifas = $registros;
        }

        $adjuntosRef = \App\Models\Adjunto::where('solicitud_id', $this->solicitudId)
            ->where('ruta', 'like', 'referencias/%')
            ->get();

        return view('livewire.pricing.cotizador-live', compact('solicitud', 'proveedores', 'solicitudesConTarifas', 'adjuntosRef'))
            ->layout('layouts.ventas');
    }

    public function updatedProveedorGlobalId($value)
    {
        if (!$value) return;

        $proveedor = Proveedor::find($value);
        if (!$proveedor) return;

        // Aplicar a todas las líneas existentes
        foreach ($this->lineas as $i => $linea) {
            $this->lineas[$i]['proveedor_id']     = $value;
            $this->lineas[$i]['proveedor_nombre'] = $proveedor->nombre;
        }
    }

    /**
     * Deriva la moneda según el tipo de plantilla si no hay registro previo.
     */
    protected function monedaDerivada(string $tipo_plantilla): string
    {
        return match ($tipo_plantilla) {
            'USD', 'LCL' => 'USD',
            default      => 'MXN',
        };
    }
}
