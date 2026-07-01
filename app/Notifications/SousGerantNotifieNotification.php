<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SousGerantNotifieNotification extends Notification
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
            ->subject('Vérification terrain requise — INC-' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT))
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('L\'intervention sur votre incident est terminée. Merci de vérifier sur le terrain.')
            ->line('**Titre :** ' . $this->incident->titre)
            ->line('**Station :** ' . optional($this->incident->station)->nom)
            ->line('**Équipement :** ' . optional($this->incident->equipement)->nom)
            ->line('**Intervenant :** ' . optional($this->incident->technicien)->prenom . ' ' . optional($this->incident->technicien)->nom)
            ->action('Voir l\'incident', url('/incidents/' . $this->incident->id))
            ->line('Si tout est en ordre, vous pouvez signaler au Directeur Maintenance pour clôturer l\'incident.')
            ->line('Dans le cas contraire, laissez un mémo au Directeur Maintenance.');
    }
}