@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span>
            Tableau de bord
        </h3>
    </div>

    {{-- Cartes stats --}}
    <div class="row">
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('dist/assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="">
                    <h4 class="font-weight-normal mb-3">Total incidents <i class="mdi mdi-alert mdi-24px float-end"></i></h4>
                    <h2 class="mb-2">{{ $stats['total'] }}</h2>
                    <h6 class="card-text">{{ $stats['declare'] }} non assigné(s)</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-warning card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('dist/assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="">
                    <h4 class="font-weight-normal mb-3">En cours <i class="mdi mdi-progress-wrench mdi-24px float-end"></i></h4>
                    <h2 class="mb-2">{{ $stats['en_cours'] }}</h2>
                    <h6 class="card-text">assignés + en cours + en attente</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('dist/assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="">
                    <h4 class="font-weight-normal mb-3">Résolus <i class="mdi mdi-check-circle mdi-24px float-end"></i></h4>
                    <h2 class="mb-2">{{ $stats['resolu'] }}</h2>
                    <h6 class="card-text">{{ $stats['cloture'] }} clôturé(s)</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('dist/assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="">
                    <h4 class="font-weight-normal mb-3">Équipements <i class="mdi mdi-wrench mdi-24px float-end"></i></h4>
                    <h2 class="mb-2">{{ $equipementsEnPanne }}</h2>
                    <h6 class="card-text">en panne · {{ $techniciensDispo }} technicien(s) dispo</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Priorités --}}
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Incidents actifs par priorité</h4>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Critique</span>
                            <strong class="text-danger">{{ $priorites['critique'] }}</strong>
                        </div>
                        <div class="progress mb-3" style="height:8px">
                            @php $total = max(1, $priorites['critique'] + $priorites['eleve'] + $priorites['faible']); @endphp
                            <div class="progress-bar bg-danger" style="width:{{ ($priorites['critique']/$total)*100 }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Élevé</span>
                            <strong class="text-warning">{{ $priorites['eleve'] }}</strong>
                        </div>
                        <div class="progress mb-3" style="height:8px">
                            <div class="progress-bar bg-warning" style="width:{{ ($priorites['eleve']/$total)*100 }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Faible</span>
                            <strong class="text-info">{{ $priorites['faible'] }}</strong>
                        </div>
                        <div class="progress mb-3" style="height:8px">
                            <div class="progress-bar bg-info" style="width:{{ ($priorites['faible']/$total)*100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statuts --}}
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Répartition par statut</h4>
                    <div class="row text-center mt-3">
                        <div class="col">
                            <h3 class="text-secondary fw-bold">{{ $stats['declare'] }}</h3>
                            <span class="badge badge-gradient-secondary">Déclaré</span>
                        </div>
                        <div class="col">
                            <h3 class="text-primary fw-bold">{{ $stats['en_cours'] }}</h3>
                            <span class="badge badge-gradient-warning">En cours</span>
                        </div>
                        <div class="col">
                            <h3 class="text-success fw-bold">{{ $stats['resolu'] }}</h3>
                            <span class="badge badge-gradient-success">Résolu</span>
                        </div>
                        <div class="col">
                            <h3 class="text-danger fw-bold">{{ $stats['non_resolu'] }}</h3>
                            <span class="badge badge-gradient-danger">Non résolu</span>
                        </div>
                        <div class="col">
                            <h3 class="fw-bold">{{ $stats['cloture'] }}</h3>
                            <span class="badge bg-dark text-white">Clôturé</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Incidents récents --}}
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Incidents récents</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Réf.</th>
                                    <th>Titre</th>
                                    <th>Station</th>
                                    <th>Priorité</th>
                                    <th>Statut</th>
                                    <th>Technicien</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recents as $inc)
                                <tr>
                                    <td class="text-muted small">INC-{{ str_pad($inc->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $inc->titre }}</td>
                                    <td>{{ optional($inc->station)->nom ?? '—' }}</td>
                                    <td>
                                        @if($inc->priorite === 'critique')
                                            <span class="badge badge-gradient-danger">Critique</span>
                                        @elseif($inc->priorite === 'eleve')
                                            <span class="badge badge-gradient-warning">Élevé</span>
                                        @else
                                            <span class="badge badge-gradient-info">Faible</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($inc->statut)
                                            @case('declare')   <span class="badge badge-gradient-secondary">Déclaré</span> @break
                                            @case('assigne')   <span class="badge badge-gradient-primary">Assigné</span> @break
                                            @case('en_cours')  <span class="badge badge-gradient-warning">En cours</span> @break
                                            @case('en_attente')<span class="badge badge-gradient-info">En attente</span> @break
                                            @case('resolu')    <span class="badge badge-gradient-success">Résolu</span> @break
                                            @case('non_resolu')<span class="badge badge-gradient-danger">Non résolu</span> @break
                                            @case('cloture')   <span class="badge bg-dark text-white">Clôturé</span> @break
                                        @endswitch
                                    </td>
                                    <td>{{ optional($inc->technicien)->prenom ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('incidents.show', $inc->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted">Aucun incident.</td></tr>
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
