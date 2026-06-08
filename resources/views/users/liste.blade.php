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
                        Liste des utilisateurs
                        <i class="mdi mdi-account-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('users.create') }}" class="btn btn-gradient-primary btn-icon-text">
                    <i class="mdi mdi-account-plus btn-icon-prepend"></i>
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
                                        <th>Nom</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(isset($users) ? $users : [] as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->nom }}</td>
                                        <td>{{ $user->role->nom_role ?? 'N/A' }}</td>
                                        <td>
                                            <p>
                                                @if ($user->statut)
                                                <span class="badge badge-gradient-success">Actif</span>
                                                @else
                                                <span class="badge badge-gradient-danger">Inactif</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-dark">Voir</a>
                                            {{-- Use a form for toggle to match typical POST/PATCH route and ensure action works --}}
                                            <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $user->statut ? 'btn-outline-danger' : 'btn-outline-success' }}">{{ $user->statut ? 'Désactiver' : 'Activer' }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Aucun utilisateur trouvé.</td>
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