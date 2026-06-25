<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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

    public function domaine()
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    public function incidentsAssignes()
    {
        return $this->hasMany(Incident::class, 'technicien_assigne_id');
    }

    public function incidentsDeclares()
    {
        return $this->hasMany(Incident::class, 'declarant_id');
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
    public function commentaires()
{
    return $this->hasMany(Commentaire::class);
}
}