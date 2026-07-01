@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
           
            Tous les incidents
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>
                  
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

                    {{-- Filtres --}}
                    <form method="GET" action="{{ route('incidents.index') }}" class="row g-2 mb-3">
                        <div class="col-md-4">
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
                            <button class="btn btn-sm btn-primary w-100" type="submit">
                                <i class="mdi mdi-magnify"></i> Filtrer
                            </button>
                        </div>
                        @if(request()->hasAny(['search','statut','priorite']))
                        <div class="col-md-1">
                            <a href="{{ route('incidents.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                                <i class="mdi mdi-close"></i>
                            </a>
                        </div>
                        @endif
                    </form>

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
                                            class="btn btn-sm btn-outline-primary mb-1" title="Assigner">
                                            <i class="mdi mdi-account-arrow-right"></i>
                                        </a>
                                        @endif

                                        @if($incident->statut === 'resolu' || $incident->statut === 'non_resolu' || $incident->statut === 'en_attente')
                                        <a href="{{ route('incidents.details_rapport', $incident->id) }}"
                                            class="btn btn-sm btn-outline-success mb-1" title="Voir rapport">
                                            <i class="mdi mdi-checkbox-marked-circle"></i>
                                        </a>
                                        @endif

                                        <a href="{{ route('incidents.historiques', $incident->id) }}"
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
                        <div class="mt-3">
                            {{ $incidents->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection