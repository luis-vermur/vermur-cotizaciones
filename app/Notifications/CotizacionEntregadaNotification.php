<?php

namespace App\Notifications;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CotizacionEntregadaNotification extends Notification
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
            ->subject("Cotización lista: {$this->solicitud->folio}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Tu cotización está lista para revisión.")
            ->line("**Cliente:** {$this->solicitud->cliente_nombre}")
            ->line("**Folio:** {$this->solicitud->folio}")
            ->action('Ver cotización', url("/ventas/solicitud/{$this->solicitud->id}"))
            ->salutation('Vermur Cotizaciones');
    }
}