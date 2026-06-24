@extends('layouts.app')

@section('content')

	<div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
		<div class="col-lg-6 col-md-8 col-sm-12" style="max-width:600px; width:100%;">

			<div class="auth-form-light text-left p-4 p-lg-5">
				<h3 class="text-center mb-2">Modifier un équipement</h3>
				<p class="text-center text-muted mb-4">Mettez à jour les informations de l'équipement.</p>

				@if ($errors->any())
				<div class="alert alert-danger">
					<ul class="mb-0">
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif

				<form class="pt-3" method="POST" action="{{ route('equipements.update', $equipement->id) }}">
					@csrf
					@method('PUT')

					<div class="form-group mb-3">
						<label for="nom" class="form-label">Nom</label>
						<input type="text" name="nom" class="form-control form-control-lg" id="nom" placeholder="Nom de l'équipement" value="{{ old('nom', $equipement->nom) }}" required>
					</div>

					<div class="form-group mb-3">
						<label for="type" class="form-label">Type</label>
						<input type="text" name="type" class="form-control form-control-lg" id="type" placeholder="Type d'équipement" value="{{ old('type', $equipement->type) }}" required>
					</div>

					<div class="form-group mb-3">
						<label for="etat" class="form-label">État</label>
						<select name="etat" id="etat" class="form-select form-control-lg" required>
							<option value="fonctionnel" {{ old('etat', $equipement->etat) === 'fonctionnel' ? 'selected' : '' }}>Fonctionnel</option>
							<option value="en_panne" {{ old('etat', $equipement->etat) === 'en_panne' ? 'selected' : '' }}>En panne</option>
							<option value="critique" {{ old('etat', $equipement->etat) === 'critique' ? 'selected' : '' }}>Critique</option>
						</select>
					</div>

					<div class="form-group mb-4">
						<label for="station_id" class="form-label">Station</label>
						<select name="station_id" id="station_id" class="form-select form-control-lg" required>
							<option value="">Sélectionnez une station</option>
							@foreach($stations as $station)
							<option value="{{ $station->id }}" {{ old('station_id', $equipement->station_id) == $station->id ? 'selected' : '' }}>{{ $station->nom }} ({{ $station->ville }})</option>
							@endforeach
						</select>
					</div>

					<div class="mt-4 d-flex gap-2">
						<button type="submit" class="btn btn-gradient-warning btn-lg font-weight-medium auth-form-btn flex-grow-1">Enregistrer</button>
						<a href="{{ route('equipements.index') }}" class="btn btn-secondary btn-lg font-weight-medium auth-form-btn flex-grow-1">Retour</a>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection