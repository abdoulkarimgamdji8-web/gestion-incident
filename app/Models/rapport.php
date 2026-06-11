<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    protected $table = 'rapport';

    protected $fillable = [
        'contenu',
        'date_rapport',
        'intervention_id',
    ];

    protected $casts = [
        'date_rapport' => 'date',
    ];

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
}