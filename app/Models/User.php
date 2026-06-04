<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'numero',
        'date',
        'statut',
        'role_id',
        'domaine_id',
        'disponibilite',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date' => 'date',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'technicien_assigne_id');
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'technicien_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }
}