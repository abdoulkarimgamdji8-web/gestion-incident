<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IncidentDeclareNotification extends Notification
{
    use Queueable;

    public function __construct(public Incident $incident)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvel incident déclaré — INC-' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT))
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Un nouvel incident a été déclaré et nécessite votre attention.')
            ->line('**Titre :** ' . $this->incident->titre)
            ->line('**Station :** ' . optional($this->incident->station)->nom)
            ->line('**Priorité :** ' . ucfirst($this->incident->priorite))
            ->line('**Description :** ' . $this->incident->description)
            ->action('Voir l\'incident', url('/incidents/' . $this->incident->id))
            ->line('Merci de traiter cet incident dans les meilleurs délais.');
    }
}