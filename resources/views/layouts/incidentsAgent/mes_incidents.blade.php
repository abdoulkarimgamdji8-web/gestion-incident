@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-account-circle"></i>
                </span>
                Mes incidents déclarés
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>
                        Tableau de mes incidents
                    </li>
                </ul>
            </nav>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('incidents.create') }}" class="btn btn-gradient-primary btn-icon-text">
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
                                            <a href="{{ route('incidents.show', $incident->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($incident->statut === 'declare')
                                            <a href="{{ route('incidents.edit', $incident->id) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
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