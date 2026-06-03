<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incident extends Model
{
    public function station(): BelongsTo
    {
        return $this->belongsTo(station::class);
    }

    public function equipement(): BelongsTo
    {
        return $this->belongsTo(Equipement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technicien_assigne_id');
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(intervention::class);
    }

    public function pieceJointes(): HasMany
    {
        return $this->hasMany(piece_jointes::class);
    }
}
