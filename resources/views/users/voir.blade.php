@extends('layouts.app')

@section('content')

    <div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
        <div class="col-lg-8 col-md-10 col-sm-12" style="max-width:760px; width:100%;">
            <div class="auth-form-light text-left p-4 p-lg-5">
                <h3 class="text-center mb-2">Détails de l'utilisateur {{ $user->nom }} {{ $user->prenom }}</h3>
                <p class="text-center text-muted mb-4">Informations complètes du compte utilisateur.</p>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Nom</h6>
                                        <p>{{ $user->nom }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Prénom</h6>
                                        <p>{{ $user->prenom }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Email</h6>
                                        <p>{{ $user->email }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Numéro</h6>
                                        <p>{{ $user->numero }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Statut</h6>
                                        <p>
                                            @if ($user->statut)
                                            <span class="badge badge-gradient-success">Actif</span>
                                            @else
                                            <span class="badge badge-gradient-danger">Inactif</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Rôle</h6>
                                        <p>{{ $user->role->nom_role }}</p>
                                    </div>
                                </div>

                                @php
                                $roleNameLower = strtolower($user->role->nom_role ?? '');
                                $isTechnicianOrProvider = str_contains($roleNameLower, 'technicien') || str_contains($roleNameLower, 'prestataire');
                                @endphp

                                @if ($isTechnicianOrProvider)
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Disponibilité</h6>
                                        <p>
                                            @if ($user->disponibilite === false)
                                            <span class="badge badge-gradient-danger">Occupé</span>
                                            @else
                                            <span class="badge badge-gradient-success">Disponible</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Domaine</h6>
                                        <p>{{ optional($user->domaine)->nom_domaine}}</p>
                                    </div>
                                </div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Créé le</h6>
                                        <p>{{ optional($user->created_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6 class="text-muted">Mis à jour le</h6>
                                        <p>{{ optional($user->updated_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-12">
                                        <h6 class="text-muted">Mot de passe</h6>
                                        <p class="text-muted small">Le mot de passe est masqué pour des raisons de sécurité.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-lg font-weight-medium auth-form-btn flex-grow-1">Retour</a>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-lg font-weight-medium auth-form-btn flex-grow-1">Modifier</a>
                </div>
            </div>
        </div>
    </div>

@endsection