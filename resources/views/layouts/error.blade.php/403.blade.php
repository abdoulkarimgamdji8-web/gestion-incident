@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
            <div class="text-center">
                <div class="mb-4">
                    <i class="mdi mdi-lock-alert text-danger" style="font-size: 100px;"></i>
                </div>
                <h1 class="display-3 fw-bold text-danger">403</h1>
                <h4 class="mb-3">Accès refusé</h4>
                <p class="text-muted mb-4">
                    Vous n'avez pas les permissions nécessaires pour accéder à cette page.<br>
                    Si vous pensez qu'il s'agit d'une erreur, contactez l'administrateur système.
                </p>
                <a href="{{ route('dashboard.index') }}" class="btn btn-gradient-primary btn-lg">
                    <i class="mdi mdi-home me-2"></i> Retour au tableau de bord
                </a>
            </div>
        </div>
    </div>
</div>
@endsection