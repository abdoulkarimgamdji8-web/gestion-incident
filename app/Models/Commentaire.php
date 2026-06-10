<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $fillable = ['contenu', 'incident_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}