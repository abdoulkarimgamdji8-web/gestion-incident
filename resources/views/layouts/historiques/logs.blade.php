@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-history"></i>
            </span>
            Historique global du système
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">Historique</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Utilisateur</th>
                                    <th>Rôle</th>
                                    <th>Incident</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historiques as $historique)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <small>{{ $historique->date_action->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @switch($historique->action)
                                        @case('Connexion')
                                        <span class="badge badge-gradient-success">{{ $historique->action }}</span>
                                        @break
                                        @case('Déconnexion')
                                        <span class="badge badge-gradient-secondary">{{ $historique->action }}</span>
                                        @break
                                        @case('Déclaration')
                                        <span class="badge badge-gradient-warning">{{ $historique->action }}</span>
                                        @break
                                        @case('Assignation')
                                        <span class="badge badge-gradient-primary">{{ $historique->action }}</span>
                                        @break
                                        @case('Intervention')
                                        <span class="badge badge-gradient-warning">{{ $historique->action }}</span>
                                        @break
                                        @case('Rapport soumis')
                                        <span class="badge badge-gradient-info">{{ $historique->action }}</span>
                                        @break
                                        @case('Mémo')
                                        <span class="badge badge-gradient-info">{{ $historique->action }}</span>
                                        @break
                                        @case('Réassignation')
                                        <span class="badge badge-gradient-danger">{{ $historique->action }}</span>
                                        @break
                                        @case('Clôture')
                                        <span class="badge badge-gradient-dark">{{ $historique->action }}</span>
                                        @break
                                        @default
                                        <span class="badge badge-gradient-secondary">{{ $historique->action }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <small>{{ $historique->description }}</small>
                                    </td>
                                    <td>
                                        {{ optional($historique->user)->prenom }}
                                        {{ optional($historique->user)->nom }}
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ optional($historique->user)->role->nom_role ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($historique->incident_id)
                                        <a href="{{ route('incidents.historiques', $historique->incident_id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            INC-{{ str_pad($historique->incident_id, 6, '0', STR_PAD_LEFT) }}
                                        </a>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Aucune action enregistrée.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $historiques->links() }}
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection