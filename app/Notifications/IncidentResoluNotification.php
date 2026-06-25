<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IncidentResoluNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Incident $incident,
        public string   $statut
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ref   = 'INC-' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT);
        $label = $this->statut === 'resolu' ? 'résolu' : 'non résolu';

        return (new MailMessage)
            ->subject("Incident {$label} — {$ref}")
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line("L'incident suivant a été marqué comme **{$label}**.")
            ->line('**Référence :** ' . $ref)
            ->line('**Titre :** ' . $this->incident->titre)
            ->line('**Station :** ' . optional($this->incident->station)->nom)
            ->action("Voir l'incident", url('/incidents/' . $this->incident->id));
    }
}
