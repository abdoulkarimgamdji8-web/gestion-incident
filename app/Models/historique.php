<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    protected $table = 'historique';
    protected $fillable = [
        'action',
        'description',
        'date_action',
        'user_id',
        'incident_id',
    ];

    protected $casts = [
        'date_action' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}