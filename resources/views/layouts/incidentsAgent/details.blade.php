<?php

use Illuminate\Support\Facades\Auth;

$user = Auth::user();
$role = $user->role->nom_role;
?>

@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
           
            Détail de l'incident
        </h3>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $incident->titre }}</h4>
                    <p class="card-description">{{ $incident->description }}</p>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Domaine :</strong>
                            <p>{{ optional($incident->domaine)->nom_domaine}}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Station :</strong>
                            <p>{{ optional($incident->station)->nom}}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Équipement :</strong>
                            <p>{{ optional($incident->equipement)->nom}}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Priorité :</strong>
                            <p>
                                @if ($incident->priorite === 'faible')
                                <span class="badge badge-gradient-info">Faible</span>
                                @elseif($incident->priorite === 'eleve')
                                <span class="badge badge-gradient-warning">Élevé</span>
                                @else
                                <span class="badge badge-gradient-danger">Critique</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <strong>Statut :</strong>
                            <p>
                                @switch($incident->statut)
                                @case('declare')
                                <span class="badge badge-gradient-secondary">Déclaré</span>
                                @break

                                @case('assigne')
                                <span class="badge badge-gradient-primary">Assigné</span>
                                @break

                                @case('en_cours')
                                <span class="badge badge-gradient-warning">En cours</span>
                                @break

                                @case('en_attente')
                                <span class="badge badge-gradient-warning">En attente</span>
                                @break

                                @case('resolu')
                                <span class="badge badge-gradient-success">Résolu</span>
                                @break

                                @case('non_resolu')
                                <span class="badge badge-gradient-success">Non résolu</span>
                                @break

                                @case('cloture')
                                <span class="badge badge-gradient-dark">Clôturé</span>
                                @break
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-4">
                            <strong>Intervenant :</strong>
                            <p>{{ optional($incident->technicien)->prenom ?? 'Non assigné' }}
                                {{ optional($incident->technicien)->nom ?? '' }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <strong>Date signalement :</strong>
                            <p>{{ $incident->date_signalement?->format('d/m/Y H:i')}}</p>
                        </div>
                    </div>

                    {{-- Pièces jointes de la déclaration --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <strong>Pièces jointes :</strong>
                            @php $pjDeclaration = $incident->piecesJointes->where('source', 'declaration'); @endphp
                            @if ($pjDeclaration->isEmpty())
                            <p class="text-muted mt-2">Aucune pièce jointe.</p>
                            @else
                            <div class="row g-3 mt-1">
                                @foreach ($pjDeclaration as $pj)
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center p-2 border rounded gap-2">
                                        @if (str_contains($pj->type_fichier, 'image'))
                                        <i class="mdi mdi-image text-success"
                                            style="font-size:22px;"></i>
                                        @elseif(str_contains($pj->type_fichier, 'pdf'))
                                        <i class="mdi mdi-file-pdf text-danger"
                                            style="font-size:22px;"></i>
                                        @else
                                        <i class="mdi mdi-file-word text-primary"
                                            style="font-size:22px;"></i>
                                        @endif
                                        <span
                                            class="flex-grow-1 text-truncate small">{{ $pj->nom_fichier }}</span>
                                        <a href="{{ asset('storage/' . $pj->chemin_fichier) }}"
                                            target="_blank" class="btn btn-sm btn-success">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Mémos : visible si statut résolu --}}
                    @if ($incident->statut === 'resolu' && $role === 'Sous-gérant de station')
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="mdi mdi-message-text-outline text-primary"></i>
                                        Mémos
                                    </h5>

                                    @if ($incident->commentaires->isEmpty())
                                    <p class="text-muted">Aucun commentaire pour le moment.</p>
                                    @else
                                    @foreach ($incident->commentaires as $commentaire)
                                    <div class="p-2 border rounded mb-2">
                                        <p class="mb-1 fw-bold small">
                                            {{ $commentaire->user->prenom }}
                                            {{ $commentaire->user->nom }}
                                            <span
                                                class="badge badge-gradient-secondary ms-2">{{ $commentaire->user->role->nom_role }}</span>
                                            <span
                                                class="text-muted ms-2">{{ $commentaire->created_at->format('d/m/Y H:i') }}</span>
                                        </p>
                                        <p class="mb-0">{{ $commentaire->contenu }}</p>
                                    </div>
                                    @endforeach
                                    @endif

                                    {{-- Formulaire mémo sous-gérant --}}
                                    @if (in_array($role, ['Sous-gérant de station']))
                                    <form method="POST"
                                        action="{{ route('incidents.commentaire.store', $incident->id) }}"
                                        class="mt-3">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <textarea name="contenu" rows="3" class="form-control form-control-lg"
                                                placeholder="Signaler un problème au Directeur maintenance..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-warning btn-sm">
                                            <i class="mdi mdi-send"></i> Signaler un problème ou ajouter une remarque
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Boutons --}}
                    <div class="mt-4 d-flex gap-2">

                        {{-- Retour conditionnel --}}
                        @if (in_array($role, ['Sous-gérant de station']))
                        <a href="{{ route('incidents.mes_incidents') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour
                        </a>
                        @elseif(in_array($role, ['Responsable maintenance', 'Directeur maintenance']))
                        <a href="{{ route('incidents.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour
                        </a>
                        @elseif(in_array($role, ['Technicien', 'Prestataire externe']))
                        <a href="{{ route('interventions.mes_interventions') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour
                        </a>
                        @else
                        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour
                        </a>
                        @endif

                        {{-- Modifier : agent seulement si statut declare --}}
                        @if (in_array($role, ['Agent Station', 'Sous-gérant de station']) && $incident->statut === 'declare')
                        <a href="{{ route('incidents.edit', $incident->id) }}"
                            class="btn btn-gradient-primary">
                            <i class="mdi mdi-pencil"></i> Modifier
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection