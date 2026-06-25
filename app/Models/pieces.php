<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pieces extends Model
{
    protected $table = 'pieces_jointes';

    protected $fillable = [
        'nom_fichier',
        'type_fichier',
        'chemin_fichier',
        'incident_id',
        'source',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}