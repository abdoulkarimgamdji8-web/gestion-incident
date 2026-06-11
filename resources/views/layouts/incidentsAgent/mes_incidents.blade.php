@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                
                </span>
               
            </h3>
            <nav aria-label="breadcrumb">
                
                    
                        <span></span>
                        Tableau de mes incidents
                    </li>
                </ul>
            </nav>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('incidents.create') }}" class="btn btn-icon-text-icon-text"style="background: #1e8449; border-color: #1e8449; color:white;">
                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                    Déclarer un incident
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Titre</th>
                                        <th>Domaine</th>
                                        <th>Station</th>
                                        <th>Équipement</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incidents as $incident)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $incident->titre }}</td>
                                        <td>{{ optional($incident->domaine)->nom_domaine ?? 'N/A' }}</td>
                                        <td>{{ optional($incident->station)->nom ?? 'N/A' }}</td>
                                        <td>{{ optional($incident->equipement)->nom ?? 'N/A' }}</td>
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
                                        <td>
                                            <a href="{{ route('incidents.show', $incident->id) }}"
                                                class="btn btn-sm btn-outline-info mb-1" title="Voir détails et contacter le directeur maintenance en cas de problème">
                                                <i class="mdi mdi-eye"></i>
                                            </a>

                                            @if($incident->statut === 'declare')
                                            <a href="{{ route('incidents.edit', $incident->id) }}"
                                                class="btn btn-sm btn-outline-warning mb-1" title="Modifier">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            @endif

                                            @if($incident->statut === 'resolu')
                                            <form action="{{ route('incidents.cloturer', $incident->id) }}"
                                                method="POST" style="display:inline-block;"
                                                onsubmit="return confirm('Confirmer la clôture ? Cela signifie que vous avez vérifié la réparation sur le terrain.')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success mb-1" title="Clôturer">
                                                    <i class="mdi mdi-checkbox-marked-circle"></i> Clôturer
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun incident déclaré pour le moment.</td>
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
</div>
@endsection