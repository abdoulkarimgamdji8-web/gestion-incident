@extends('layouts.app')

@section('content')

  <div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
    <div class="col-lg-6 col-md-8 col-sm-12" style="max-width:600px; width:100%;">

      <div class="auth-form-light text-left p-4 p-lg-5">
        <div class="brand-logo text-center mb-4">
          <img src="{{ asset('dist/assets/images/') }}" alt="">
        </div>
        <h3 class="text-center mb-2">Modifier une station</h3>
        <p class="text-center text-muted mb-4">Mettez à jour les informations de la station.</p>

        @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form class="pt-3" method="POST" action="{{ route('stations.update', $station->id) }}">
          @csrf
          @method('PUT')

          <div class="form-group mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control form-control-lg" id="nom" placeholder="Nom de la station" value="{{ old('nom', $station->nom) }}" required>
          </div>

          <div class="form-group mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" name="ville" class="form-control form-control-lg" id="ville" placeholder="Ville" value="{{ old('ville', $station->ville) }}" required>
          </div>

          <div class="form-group mb-3">
            <label for="zone" class="form-label">Zone</label>
            <input type="text" name="zone" class="form-control form-control-lg" id="zone" placeholder="Zone" value="{{ old('zone', $station->zone) }}" required>
          </div>

          <div class="form-group mb-4">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="statut" name="statut" value="1" {{ old('statut', $station->statut) ? 'checked' : '' }}>
              <label class="form-check-label" for="statut">Station active</label>
            </div>
          </div>

          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-gradient-warning btn-lg font-weight-medium auth-form-btn flex-grow-1">Enregistrer</button>
            <a href="{{ route('stations.index') }}" class="btn btn-secondary btn-lg font-weight-medium auth-form-btn flex-grow-1">Retour</a>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection