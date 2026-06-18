@extends('layouts.app')

@section('content')

  <div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
    <div class="col-lg-6 col-md-8 col-sm-12" style="max-width:600px; width:100%;">

      <div class="auth-form-light text-left p-4 p-lg-5">
        <div class="brand-logo text-center mb-4">
          <img src="{{ asset('dist/assets/images/') }}" alt="">
        </div>
        <h3 class="text-center mb-2">Modifier un rôle</h3>
        <p class="text-center text-muted mb-4">Mettez à jour les informations du rôle.</p>

        @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form class="pt-3" method="POST" action="{{ route('roles.update', $role->id) }}">
          @csrf
          @method('PUT')

          <div class="form-group mb-3">
            <label for="nom_role" class="form-label">Nom du rôle</label>
            <input type="text" name="nom_role" class="form-control form-control-lg" id="nom_role" placeholder="Ex: Administrateur, Technicien, etc." value="{{ old('nom_role', $role->nom_role) }}" required>
          </div>

          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-gradient-primary btn-lg font-weight-medium auth-form-btn flex-grow-1">Enregistrer</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-lg font-weight-medium auth-form-btn flex-grow-1">Retour</a>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection