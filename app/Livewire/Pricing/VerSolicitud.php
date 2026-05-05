<?php

namespace App\Livewire\Pricing;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Solicitud;
use App\Models\Comentario;
use App\Models\HistorialEstado;
use App\Models\Adjunto;
use App\Notifications\CotizacionEntregadaNotification;

class VerSolicitud extends Component
{
    use WithFileUploads;

    public int    $solicitudId;
    public string $nuevoComentario = '';
    public string $motivoRechazo   = '';
    public bool   $mostrarRechazo  = false;
    public $pdfEntrega             = null;
    public bool   $mostrarEntrega  = false;
    public string $notaInterna     = '';
    public bool   $editandoNota    = false;

    public function mount(int $solicitud)
    {
        if (!in_array(auth()->user()->rol, ['pricing', 'admin'])) abort(403);
        $this->solicitudId = $solicitud;
        $sol = $this->getSolicitud();
        $this->notaInterna = $sol->nota_interna ?? '';
        $this->cambiarEstadoRevision();
    }

    public function guardarNotaInterna()
    {
        $solicitud = $this->getSolicitud();
        $solicitud->update(['nota_interna' => $this->notaInterna]);
        $this->editandoNota = false;
        session()->flash('success', 'Nota interna guardada.');
    }

    protected function getSolicitud(): Solicitud
    {
        return Solicitud::with(['pallets', 'adjuntos', 'comentarios.user', 'historial.user', 'cotizaciones'])
            ->findOrFail($this->solicitudId);
    }

    protected function cambiarEstadoRevision(): void
    {
        $solicitud = $this->getSolicitud();

        if ($solicitud->estado === 'nueva' && $solicitud->puedeTransicionarA('en_revision')) {
            $solicitud->update(['estado' => 'en_revision']);

            HistorialEstado::create([
                'solicitud_id'    => $solicitud->id,
                'estado_anterior' => 'nueva',
                'estado_nuevo'    => 'en_revision',
                'user_id'         => auth()->id(),
                'motivo'          => 'Solicitud abierta por Pricing',
            ]);
        }
    }

    public function agregarComentario()
    {
        $this->validate(['nuevoComentario' => 'required|string|min:3']);

        Comentario::create([
            'solicitud_id' => $this->solicitudId,
            'user_id'      => auth()->id(),
            'texto'        => $this->nuevoComentario,
            'rol'          => auth()->user()->rol,
        ]);

        $this->nuevoComentario = '';
    }

    public function rechazar()
    {
        $this->validate(['motivoRechazo' => 'required|string|min:5']);

        $solicitud = $this->getSolicitud();

        if (!$solicitud->puedeTransicionarA('rechazada')) {
            session()->flash('error', 'Esta solicitud no puede rechazarse desde su estado actual.');
            $this->mostrarRechazo = false;
            return;
        }

        $estadoAnterior = $solicitud->estado;
        $solicitud->update(['estado' => 'rechazada']);

        HistorialEstado::create([
            'solicitud_id'    => $solicitud->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => 'rechazada',
            'user_id'         => auth()->id(),
            'motivo'          => $this->motivoRechazo,
        ]);

        session()->flash('success', 'Solicitud rechazada.');
        return redirect()->route('pricing.dashboard');
    }

    public function entregarAVentas()
    {
        $solicitud = $this->getSolicitud();

        if (!$solicitud->puedeTransicionarA('enviada')) {
            session()->flash('error', 'La solicitud debe estar cotizada para entregar.');
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

        // Notificar al vendedor que creó la solicitud
        $solicitud->creadoPor?->notify(new CotizacionEntregadaNotification($solicitud));

        HistorialEstado::create([
            'solicitud_id'    => $solicitud->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => 'enviada',
            'user_id'         => auth()->id(),
            'motivo'          => 'Cotización entregada a Ventas',
        ]);

        $this->pdfEntrega    = null;
        $this->mostrarEntrega = false;
        session()->flash('success', 'Cotización entregada a Ventas exitosamente.');
    }


    public function render()
    {
        $solicitud    = $this->getSolicitud();
        $comentarios  = $solicitud->comentarios->sortBy('created_at');
        $historial    = $solicitud->historial->sortBy('created_at');
        $cotizaciones = $solicitud->cotizaciones->sortBy('version');

        return view('livewire.pricing.ver-solicitud', compact(
            'solicitud',
            'comentarios',
            'historial',
            'cotizaciones'
        ))->layout('layouts.ventas');
    }
}
