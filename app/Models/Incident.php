<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incident';

    protected $fillable = [
        'titre',
        'description',
        'statut',
        'motif_attente',
        'description_attente',
        'date_reprise_prevue',
        'priorite',
        'date_signalement',
        'domaine_id',
        'technicien_assigne_id',
        'declarant_id',
        'station_id',
        'equipement_id',
    ];

    protected $casts = [
        'date_signalement' => 'datetime',
    ];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_assigne_id');
    }

    public function declarant()
    {
        return $this->belongsTo(User::class, 'declarant_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'equipement_id');
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class)->orderBy('date_debut', 'asc');
    }

    public function rapports()
    {
        return $this->hasManyThrough(Rapport::class, Intervention::class);
    }

    public function piecesJointes()
    {
        return $this->hasMany(Pieces::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class)->orderBy('created_at', 'asc');
    }
}