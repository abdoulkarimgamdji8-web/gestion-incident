<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class station extends Model
{
    protected $fillable = [
        'nom',
        'ville',
        'zone',
        'statut',
    ];

    public function equipements(): HasMany
    {
        return $this->hasMany(Equipement::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }
}
