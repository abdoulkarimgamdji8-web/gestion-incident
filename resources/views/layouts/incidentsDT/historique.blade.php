@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-history"></i>
                </span>
                Historique de l'incident
            </h3>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $incident->titre }}</h4>
                        <p class="card-description">{{ $incident->description }}</p>

                        <div class="mb-3">
                            <strong>Statut actuel :</strong>
                            <span class="badge badge-gradient-primary">{{ ucfirst(str_replace('_', ' ', $incident->statut)) }}</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Utilisateur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($historiques as $historique)
                                    <tr>
                                        <td>{{ $historique->date_action?->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>{{ $historique->action }}</td>
                                        <td>{{ optional($historique->user)->prenom ?? 'Système' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Aucun historique disponible.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Retour aux incidents</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection