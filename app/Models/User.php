<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom', 
        'prenom', 
        'email', 
        'password', 
        'numero', 
        'date', 
        'statut', 
        'role_id', 
        'domaine', 
        'disponibilite'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // === Relations ===
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function assignedIncidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'technicien_assigne_id');
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class, 'technicien_id'); // Correction nom (majuscule)
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function historiques(): HasMany
    {
        return $this->hasMany(Historique::class);
    }

    // Accesseur nom complet
    public function getNameAttribute(): string
    {
        return trim(($this->nom ?? '') . ' ' . ($this->prenom ?? ''));
    }
}