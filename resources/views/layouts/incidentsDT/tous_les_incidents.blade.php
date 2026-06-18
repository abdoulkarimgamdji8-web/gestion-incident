@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                
               
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>
                       Liste des incidents
                    </li>
                </ul>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Équipement</th>
                                        <th>Priorité</th>
                                        <th>Statut</th>
                                        <th>Technicien</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incidents as $incident)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($incident->equipement)->nom }}</td>
                                        <td>
                                            @if ($incident->priorite === 'faible')
                                            <span class="badge badge-gradient-info">Faible</span>
                                            @elseif ($incident->priorite === 'eleve')
                                            <span class="badge badge-gradient-warning">Élevé</span>
                                            @else
                                            <span class="badge badge-gradient-danger">Critique</span>
                                            @endif
                                        </td>
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
                                            @case('resolu')
                                            <span class="badge badge-gradient-success">Résolu</span>
                                            @break
                                            @case('cloture')
                                            <span class="badge badge-gradient-dark">Clôturé</span>
                                            @break
                                            @default
                                            <span class="badge badge-gradient-secondary">Inconnu</span>
                                            @endswitch
                                        </td>
                                        <td>{{ optional($incident->technicien)->prenom ?? 'Non assigné' }}</td>
                                        <td>
                                            <a href="{{ route('incidents.show', $incident->id) }}"
                                                class="btn btn-sm btn-outline-info mb-1" title="Voir détails">
                                                <i class="mdi mdi-eye"></i>
                                            </a>

                                            @if($incident->statut === 'declare')
                                            <a href="{{ route('incidents.assignation', $incident->id) }}"
                                                class="btn btn-sm btn--icon-text"style="background: #1e8449; border-color: #1e8449; color:white mb-1" title="Assigner">
                                                <i class="mdi mdi-account-arrow-right"></i>
                                            </a>
                                            @endif

                                            @if($incident->statut === 'resolu')
                                            <a href="{{ route('incidents.details_rapport', $incident->id) }}"
                                                class="btn btn-sm btn-outline-success mb-1" title="Voir rapport ">
                                                <i class="mdi mdi-checkbox-marked-circle"></i>
                                            </a>
                                            @endif

                                            <a href="{{ route('incidents.historique', $incident->id) }}"
                                                class="btn btn-sm btn-outline-secondary mb-1" title="Historique">
                                                <i class="mdi mdi-history"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun incident trouvé.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection