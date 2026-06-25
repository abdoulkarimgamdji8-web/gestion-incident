@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
           
           Listes des mes incidents
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>
                  
                </li>
            </ul>
        </nav>
        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('incidents.create') }}" class="btn btn--icon-text"style="background: #1e8449; border-color: #1e8449; color:white btn-lg font-weight-medium auth-form-btn flex-grow-1">
                <i class="mdi mdi-plus btn-icon-prepend"></i>
                Déclarer un incident
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    {{-- Filtres --}}
                    <form method="GET" action="{{ route('incidents.mes_incidents') }}" class="row g-2 mb-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Rechercher (titre, description)..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="statut" class="form-select form-select-sm">
                                <option value="">Tous les statuts</option>
                                @foreach(['declare'=>'Déclaré','assigne'=>'Assigné','en_cours'=>'En cours','en_attente'=>'En attente','resolu'=>'Résolu','non_resolu'=>'Non résolu','cloture'=>'Clôturé'] as $val => $label)
                                    <option value="{{ $val }}" {{ request('statut') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="priorite" class="form-select form-select-sm">
                                <option value="">Toutes priorités</option>
                                <option value="faible"   {{ request('priorite') === 'faible'   ? 'selected' : '' }}>Faible</option>
                                <option value="eleve"    {{ request('priorite') === 'eleve'    ? 'selected' : '' }}>Élevé</option>
                                <option value="critique" {{ request('priorite') === 'critique' ? 'selected' : '' }}>Critique</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-primary flex-grow-1" type="submit">
                                    <i class="mdi mdi-magnify"></i> Filtrer
                                </button>
                                @if(request()->hasAny(['search','statut','priorite']))
                                <a href="{{ route('incidents.mes_incidents') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-close"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>

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
                                        @default
                                        <span class="badge badge-gradient-secondary">Inconnu</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('incidents.show', $incident->id) }}"
                                            class="btn btn-sm btn-outline-info mb-1" title="Voir détails et contacter le directeur maintenance">
                                            <i class="mdi mdi-eye"></i>
                                        </a>

                                        @if($incident->statut === 'declare')
                                        <a href="{{ route('incidents.edit', $incident->id) }}"
                                            class="btn btn-sm btn-outline-warning mb-1" title="Modifier">
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
@endsection