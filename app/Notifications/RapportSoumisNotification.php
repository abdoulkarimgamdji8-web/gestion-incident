<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RapportSoumisNotification extends Notification
{
    use Queueable;

    public function __construct(public Incident $incident, public string $resultat) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resultatLabel = [
            'resolu'     => 'Résolu',
            'non_resolu' => 'Non résolu — réassignation nécessaire',
        ];

        return (new MailMessage)
            ->subject('Rapport soumis — INC-' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT))
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Un rapport d\'intervention a été soumis pour l\'incident suivant.')
            ->line('**Titre :** ' . $this->incident->titre)
            ->line('**Station :** ' . optional($this->incident->station)->nom)
            ->line('**Intervenant :** ' . optional($this->incident->technicien)->prenom . ' ' . optional($this->incident->technicien)->nom)
            ->line('**Résultat :** ' . ($resultatLabel[$this->resultat] ?? $this->resultat))
            ->action('Consulter le rapport', url('/incidents/' . $this->incident->id . '/details-rapport'))
            ->line('Merci de consulter le rapport et de prendre les mesures nécessaires.');
    }
}