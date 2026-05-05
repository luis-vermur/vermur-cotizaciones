<?php

namespace App\Notifications;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevaSolicitudNotification extends Notification
{
    use Queueable;

    public function __construct(public Solicitud $solicitud) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nueva solicitud: {$this->solicitud->folio}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Se ha recibido una nueva solicitud de cotización.")
            ->line("**Cliente:** {$this->solicitud->cliente_nombre}")
            ->line("**Folio:** {$this->solicitud->folio}")
            ->line("**Transporte:** " . ucfirst($this->solicitud->tipo_transporte))
            ->line("**Operación:** " . ucfirst($this->solicitud->tipo_operacion))
            ->action('Ver solicitud', url("/pricing/solicitud/{$this->solicitud->id}"))
            ->salutation('Vermur Cotizaciones');
    }
}