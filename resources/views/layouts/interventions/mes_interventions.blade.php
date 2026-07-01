@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                   
                </span>
                Mes interventions
            </h3>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Titre</th>
                                        <th>Station</th>
                                        <th>Équipement</th>
                                        <th>Priorité</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($interventions as $incident)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $incident->titre }}</td>
                                        <td>{{ optional($incident->station)->nom ?? 'N/A' }}</td>
                                        <td>{{ optional($incident->equipement)->nom ?? 'N/A' }}</td>
                                        <td>
                                            @if($incident->priorite === 'faible')
                                            <span class="badge badge-gradient-info">Faible</span>
                                            @elseif($incident->priorite === 'eleve')
                                            <span class="badge badge-gradient-warning">Élevé</span>
                                            @else
                                            <span class="badge badge-gradient-danger">Critique</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($incident->statut)
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
                                            @endswitch
                                        </td>
                                        <td>
                                            {{-- Intervenir : statut assigne --}}
                                            @if($incident->statut === 'assigne')
                                            <form action="{{ route('incidents.intervenir', $incident->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Démarrer l\'intervention ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary mb-1" title="Intervenir">
                                                    <i class="mdi mdi-play-circle-outline"></i> Intervenir
                                                </button>
                                            </form>
                                            @endif

                                            {{-- Soumettre rapport : statut en_cours --}}
                                            @if($incident->statut === 'en_cours')
                                            <a href="{{ route('incidents.rapport', $incident->id) }}" class="btn btn-sm btn-outline-success mb-1" title="Soumettre rapport">
                                                <i class="mdi mdi-file-document-edit-outline"></i> Rapport
                                            </a>
                                            @endif

                                            {{-- Voir détails --}}
                                            <a href="{{ route('incidents.show', $incident->id) }}" class="btn btn-sm btn-outline-info mb-1" title="Détails">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucune intervention pour le moment.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                           <div class="mt-3">
                            {{ $interventions->links() }}
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection