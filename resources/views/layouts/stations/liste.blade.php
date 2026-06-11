@extends('layouts.app')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
               
                   
                </span>
               
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>
                        Liste des stations
                       
                    </li>
                </ul>
            </nav>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('stations.create') }}" class="btn btn--icon-text"style="background: #1e8449; border-color: #1e8449; color:white; btn-icon-text">
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
                                            <p>
                                                @if ($station->statut)
                                                <span class="badge badge--icon-text"style="background: #1e8449; border-color: #1e8449; color:white">Actif</span>
                                                @else
                                                <span class="badge badge-gradient-danger">Inactif</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <a href="{{ route('stations.edit', $station->id) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="mdi mdi-pencil"></i> Modifier
                                            </a>
                                            {{-- Use a form for toggle to match typical POST/PATCH route and ensure action works --}}
                                            <form action="{{ route('stations.toggleStatus', $station->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $station->statut ? 'btn-outline-danger' : 'btn-outline-success' }}">{{ $station->statut ? 'Désactiver' : 'Activer' }}</button>
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