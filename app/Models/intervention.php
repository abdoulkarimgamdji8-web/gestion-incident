<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $table = 'interventions';

    protected $fillable = [
        'date_debut',
        'date_fin',
        'resultat',
        'observation',
        'incident_id',
        'technicien_id',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    public function rapport()
    {
        return $this->hasOne(Rapport::class);
    }
}