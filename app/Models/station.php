<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'stations';

    
    protected $fillable = [
        'nom',
        'ville',
        'zone',
        'statut',
    ];

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'station_id');
    }

    public function equipements()
    {
        return $this->hasMany(Equipement::class, 'station_id');
    }
}