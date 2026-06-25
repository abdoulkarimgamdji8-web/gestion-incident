<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IncidentAssigneNotification extends Notification
{
    use Queueable;

    public function __construct(public Incident $incident) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ref = 'INC-' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT);

        return (new MailMessage)
            ->subject("Incident assigné — {$ref}")
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Un incident vous a été assigné et nécessite votre intervention.')
            ->line('**Référence :** ' . $ref)
            ->line('**Titre :** ' . $this->incident->titre)
            ->line('**Station :** ' . optional($this->incident->station)->nom)
            ->line('**Priorité :** ' . ucfirst($this->incident->priorite))
            ->action("Voir l'incident", url('/incidents/' . $this->incident->id))
            ->line('Merci de démarrer votre intervention dans les meilleurs délais.');
    }
}
