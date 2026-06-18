@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon -icon-text"style="background: #1e8449; border-color: #1e8449; color:white; text-white me-2">
                    <i class="mdi mdi-account-arrow-right"></i>
                </span>
                Assigner l'incident
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('incidents.index') }}">Tous les incidents</a>
                    </li>
                    <li class="breadcrumb-item active">Assignation</li>
                </ul>
            </nav>
        </div>

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            {{-- Récapitulatif de l'incident --}}
            <div class="col-md-5 mb-4">
                <div class="card h-100">
                    <div class="card-header -icon-text"style="background: #1e8449; border-color: #1e8449; color:white text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-alert-circle-outline"></i>
                            INC-{{ str_pad($incident->id, 6, '0', STR_PAD_LEFT) }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Titre :</strong> {{ $incident->titre }}</p>
                        <p><strong>Station :</strong> {{ optional($incident->station)->nom ?? 'N/A' }}</p>
                        <p><strong>Équipement :</strong> {{ optional($incident->equipement)->nom ?? 'N/A' }}</p>
                        <p><strong>Domaine :</strong> {{ optional($incident->domaine)->nom_domaine ?? 'N/A' }}</p>
                        <p>
                            <strong>Priorité :</strong>
                            @if($incident->priorite === 'faible')
                            <span class="badge badge-gradient-info">Faible</span>
                            @elseif($incident->priorite === 'eleve')
                            <span class="badge badge-gradient-warning">Élevé</span>
                            @else
                            <span class="badge badge-gradient-danger">Critique</span>
                            @endif
                        </p>
                        <p>
                            <strong>Déclaré par :</strong>
                            {{ optional($incident->declarant)->prenom }}
                            {{ optional($incident->declarant)->nom }}
                        </p>
                        <p><strong>Date :</strong> {{ $incident->date_signalement?->format('d/m/Y H:i') }}</p>
                        <hr>
                        <p class="mb-1"><strong>Description :</strong></p>
                        <p class="text-muted">{{ $incident->description }}</p>
                    </div>
                </div>
            </div>

            {{-- Formulaire d'assignation --}}
            <div class="col-md-7 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0" style="color: #667eea;">
                            <i class="mdi mdi-account-tie-outline"></i>
                            Choisir un intervenant
                        </h5>
                    </div>
                    <div class="card-body">

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if($intervenants->isEmpty())
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-outline"></i>
                            Aucun intervenant disponible pour le domaine
                            <strong>{{ optional($incident->domaine)->nom_domaine }}</strong>.
                            <br>
                            <small>Vérifiez la disponibilité des techniciens et prestataires externes de ce domaine.</small>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('incidents.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Retour
                            </a>
                        </div>
                        @else
                        <form method="POST" action="{{ route('incidents.assignation.store', $incident->id) }}">
                            @csrf

                            <div class="form-group mb-4">
                                <label class="form-label fw-bold">Intervenant disponible</label>
                                <select name="technicien_assigne_id" class="form-select form-control-lg" required>
                                    <option value="" selected>-- Sélectionner --</option>
                                    @foreach($intervenants as $intervenant)
                                    <option value="{{ $intervenant->id }}">
                                        {{ $intervenant->nom }} {{ $intervenant->prenom }}
                                        —
                                        @if($intervenant->role->nom_role === 'Technicien')
                                        <span>Technicien</span>
                                        @else
                                        <span>Prestataire Externe</span>
                                        @endif
                                        — {{ optional($intervenant->domaine)->nom_domaine ?? 'N/A' }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    Seuls les techniciens et prestataires externes disponibles
                                    du domaine "{{ optional($incident->domaine)->nom_domaine }}" sont affichés.
                                </small>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn--icon-text"style="background: #1e8449; border-color: #1e8449; color:white btn-lg flex-grow-1">
                                    <i class="mdi mdi-check-circle"></i> Confirmer l'assignation
                                </button>
                                <a href="{{ route('incidents.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="mdi mdi-arrow-left"></i> Retour
                                </a>
                            </div>
                        </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection