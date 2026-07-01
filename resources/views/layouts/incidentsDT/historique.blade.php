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
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-history"></i>
            </span>
            Historique — INC-{{ str_pad($incident->id, 6, '0', STR_PAD_LEFT) }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('incidents.index') }}">Tous les incidents</a>
                </li>
                <li class="breadcrumb-item active">Historique</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        {{-- Infos incident --}}
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-alert-circle-outline text-primary"></i>
                        Incident
                    </h4>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Titre</td>
                            <td><strong>{{ $incident->titre }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Station</td>
                            <td>{{ optional($incident->station)->nom ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Équipement</td>
                            <td>{{ optional($incident->equipement)->nom ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Statut actuel</td>
                            <td>
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
                                <span class="badge badge-gradient-info">En attente</span>
                                @break
                                @case('resolu')
                                <span class="badge badge-gradient-success">Résolu</span>
                                @break
                                @case('non_resolu')
                                <span class="badge badge-gradient-danger">Non résolu</span>
                                @break
                                @case('cloture')
                                <span class="badge badge-gradient-dark">Clôturé</span>
                                @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Déclaré par</td>
                            <td>{{ optional($incident->declarant)->prenom }} {{ optional($incident->declarant)->nom }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Intervenant</td>
                            <td>{{ optional($incident->technicien)->prenom ?? 'Non assigné' }} {{ optional($incident->technicien)->nom ?? '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Timeline historique --}}
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-timeline text-primary"></i>
                        Historique des actions
                    </h4>

                    @if($incident->historiques->isEmpty())
                    <p class="text-muted">Aucun historique disponible.</p>
                    @else
                    <div class="mt-3">
                        @foreach($incident->historiques as $historique)
                        <div class="d-flex mb-4">
                            {{-- Icône selon action --}}
                            <div class="me-3">
                                @switch($historique->action)
                                @case('Déclaration')
                                <span class="badge badge-gradient-secondary p-2">
                                    <i class="mdi mdi-alert-circle-outline"></i>
                                </span>
                                @break
                                @case('Assignation')
                                <span class="badge badge-gradient-primary p-2">
                                    <i class="mdi mdi-account-arrow-right"></i>
                                </span>
                                @break
                                @case('Intervention')
                                <span class="badge badge-gradient-warning p-2">
                                    <i class="mdi mdi-progress-wrench"></i>
                                </span>
                                @break
                                @case('Rapport soumis')
                                <span class="badge badge-gradient-success p-2">
                                    <i class="mdi mdi-file-document-outline"></i>
                                </span>
                                @break
                                @case('Mémo')
                                <span class="badge badge-gradient-info p-2">
                                    <i class="mdi mdi-message-text-outline"></i>
                                </span>
                                @break
                                @case('Réassignation')
                                <span class="badge badge-gradient-danger p-2">
                                    <i class="mdi mdi-refresh"></i>
                                </span>
                                @break
                                @case('Clôture')
                                <span class="badge badge-gradient-dark p-2">
                                    <i class="mdi mdi-check-circle"></i>
                                </span>
                                @break
                                @default
                                <span class="badge badge-gradient-secondary p-2">
                                    <i class="mdi mdi-information"></i>
                                </span>
                                @endswitch
                            </div>

                            {{-- Contenu --}}
                            <div class="flex-grow-1 border-bottom pb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $historique->action }}</strong>
                                    <small class="text-muted">
                                        {{ $historique->date_action->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <p class="mb-0 text-muted small mt-1">{{ $historique->description }}</p>
                                <small class="text-muted">
                                    <i class="mdi mdi-account-outline"></i>
                                    {{ optional($historique->user)->prenom }} {{ optional($historique->user)->nom }}
                                    — {{ optional($historique->user)->role->nom_role ?? 'N/A' }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-3">
                        @if($role === 'Directeur maintenance' || $role === 'Responsable maintenance')
                        <a href="{{ route('incidents.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour
                        </a>
                        @else($role === 'Administrateur système')
                        <a href="{{ route('historique.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection