<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model

{
    protected $table = 'solicitudes';

    protected $fillable = [
        'folio', 'cliente_id', 'cliente_nombre', 'dias_credito',
        'creado_por', 'asignado_a',
        'tipo_operacion', 'tipo_transporte', 'tipo_mercancia',
        'incoterm', 'pol_aol', 'pod_asd',
        'recoleccion', 'dir_recoleccion', 'entrega', 'dir_entrega',
        'seguro_mercancia', 'financiamiento', 'dias_financiamiento',
        'requiere_despacho', 'target', 'embalaje',
        'volumen_operacion', 'valor_factura', 'margen_profit',
        'tipo_embarque',
        'fcl_contenedor', 'fcl_peso', 'fcl_peso_unidad', 'fcl_reqs',
        'fcl_food_grade', 'fcl_reforzado', 'fcl_sobredimension',
        'fcl_enlonado', 'fcl_atmos_controlada',
        'lcl_num_pallets', 'lcl_estibable', 'lcl_cubicaje_total',
        'nota_interna', 'estado',
    ];

    protected $casts = [
        'recoleccion'          => 'boolean',
        'entrega'              => 'boolean',
        'seguro_mercancia'     => 'boolean',
        'financiamiento'       => 'boolean',
        'requiere_despacho'    => 'boolean',
        'target'               => 'boolean',
        'embalaje'             => 'boolean',
        'fcl_food_grade'       => 'boolean',
        'fcl_reforzado'        => 'boolean',
        'fcl_sobredimension'   => 'boolean',
        'fcl_enlonado'         => 'boolean',
        'fcl_atmos_controlada' => 'boolean',
        'lcl_estibable'        => 'boolean',
    ];

    // Estados válidos
    const ESTADOS = [
        'nueva'       => 'Nueva',
        'en_revision' => 'En revisión',
        'cotizada'    => 'Cotizada',
        'enviada'     => 'Enviada',
        'rechazada'   => 'Rechazada',
    ];

    // Transiciones permitidas
    const TRANSICIONES = [
        'nueva'       => ['en_revision', 'rechazada'],
        'en_revision' => ['cotizada', 'rechazada'],
        'cotizada'    => ['enviada', 'rechazada', 'en_revision'],
        'enviada'     => ['cotizada'],
        'rechazada'   => [],
    ];

    public function puedeTransicionarA(string $nuevoEstado): bool
    {
        return in_array($nuevoEstado, self::TRANSICIONES[$this->estado] ?? []);
    }

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function asignadoA()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    public function pallets()
    {
        return $this->hasMany(Pallet::class);
    }

    public function adjuntos()
    {
        return $this->hasMany(Adjunto::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function historial()
    {
        return $this->hasMany(HistorialEstado::class);
    }

    public function tarifas()
    {
        return $this->hasOne(TarifaSolicitud::class);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }

    // Scopes
    public function scopeParaUsuario($query, User $user)
    {
        if ($user->rol === 'admin') {
            return $query;
        }
        return $query->where('asignado_a', $user->id)
                     ->orWhereNull('asignado_a');
    }
}