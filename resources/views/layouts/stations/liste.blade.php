@extends('layouts.app')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                

            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>
                        Liste des stations
                        <i class="mdi mdi-map-marker-circle icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('stations.create') }}" class="btn btn-gradient-primary btn-icon-text">
                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                    Ajouter une station
                </a>
            </div>
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nom</th>
                                        <th>Ville</th>
                                        <th>Zone</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(isset($stations) ? $stations : [] as $station)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $station->nom }}</td>
                                        <td>{{ $station->ville }}</td>
                                        <td>{{ $station->zone }}</td>
                                        <td>
                                            @if ($station->statut)
                                            <span class="badge badge-gradient-primary">Actif</span>
                                            @else
                                            <span class="badge badge-gradient-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('stations.edit', $station->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-pencil"></i> Modifier
                                            </a>
                                            <form action="{{ route('stations.destroy', $station->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette station ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="mdi mdi-delete"></i> Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune station trouvée.</td>
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
    <!-- content-wrapper ends -->
</div>

@endsection