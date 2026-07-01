<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IncidentClotureNotification extends Notification
{
    use Queueable;

    public function __construct(public Incident $incident) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Incident clôturé — INC-' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT))
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('L\'incident que vous avez déclaré a été clôturé.')
            ->line('**Titre :** ' . $this->incident->titre)
            ->line('**Station :** ' . optional($this->incident->station)->nom)
            ->line('**Équipement :** ' . optional($this->incident->equipement)->nom)
            ->line('L\'équipement est de nouveau opérationnel.')
            ->action('Voir l\'incident', url('/incidents/' . $this->incident->id))
            ->line('Merci pour votre signalement.');
    }
}