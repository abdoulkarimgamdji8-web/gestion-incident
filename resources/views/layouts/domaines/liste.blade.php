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
                        Liste des domaines
                        
                    </li>
                </ul>
            </nav>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('domaines.create') }}" class="btn btn--icon-text"style="background: #1e8449; border-color: #1e8449; color:white btn-icon-text">
                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                    Ajouter un domaine
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
                                        <th>Nom du domaine</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(isset($domaines) ? $domaines : [] as $domaine)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $domaine->nom_domaine }}</td>
                                        <td>
                                            <a href="{{ route('domaines.edit', $domaine->id) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="mdi mdi-pencil"></i> Modifier
                                            </a>
                                            <form action="{{ route('domaines.destroy', $domaine->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce domaine ? Cette action est irréversible.');">
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
                                        <td colspan="3" class="text-center">Aucun domaine trouvé.</td>
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