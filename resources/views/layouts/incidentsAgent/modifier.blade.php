@extends('layouts.app')

@section('content')

    <div class="content-wrapper d-flex align-items-center justify-content-center" style="min-height:100vh;">
        <div class="col-lg-6 col-md-8 col-sm-12" style="max-width:600px; width:100%;">
            <div class="auth-form-light text-left p-4 p-lg-5">
                <h3 class="text-center mb-2">Modifier l'incident</h3>
                <p class="text-center text-muted mb-4">Vous pouvez modifier cet incident tant qu'il n'est pas encore assigné.</p>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form class="pt-3" method="POST" action="{{ route('incidents.update', $incident->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control form-control-lg"
                            value="{{ old('titre', $incident->titre) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control form-control-lg" required>{{ old('description', $incident->description) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Domaine</label>
                        <select name="domaine_id" class="form-select form-control-lg" required>
                            <option value="">Sélectionnez un domaine</option>
                            @foreach($domaines as $domaine)
                            <option value="{{ $domaine->id }}" {{ old('domaine_id', $incident->domaine_id) == $domaine->id ? 'selected' : '' }}>
                                {{ $domaine->nom_domaine }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Station</label>
                        <select name="station_id" id="station_id" class="form-select form-control-lg" required>
                            <option value="">Sélectionnez une station</option>
                            @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ old('station_id', $incident->station_id) == $station->id ? 'selected' : '' }}>
                                {{ $station->nom }} ({{ $station->ville }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Équipement</label>
                        <div id="no-equipment-alert" class="alert alert-warning d-none">
                            Aucun équipement enregistré pour cette station.
                        </div>
                        <select name="equipement_id" id="equipement_id" class="form-select form-control-lg" required>
                            <option value="">Sélectionnez un équipement</option>
                            @foreach($equipements as $equipement)
                            <option value="{{ $equipement->id }}"
                                data-station="{{ $equipement->station_id }}"
                                {{ old('equipement_id', $incident->equipement_id) == $equipement->id ? 'selected' : '' }}>
                                {{ $equipement->nom }} ({{ $equipement->type }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Priorité</label>
                        <select name="priorite" class="form-select form-control-lg" required>
                            <option value="faible" {{ old('priorite', $incident->priorite) === 'faible' ? 'selected' : '' }}>Faible</option>
                            <option value="eleve" {{ old('priorite', $incident->priorite) === 'eleve' ? 'selected' : '' }}>Élevé</option>
                            <option value="critique" {{ old('priorite', $incident->priorite) === 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-gradient-primary btn-lg flex-grow-1">Mettre à jour</button>
                        <a href="{{ route('incidents.mes_incidents') }}" class="btn btn-secondary btn-lg flex-grow-1">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stationSelect = document.getElementById('station_id');
        const equipSelect = document.getElementById('equipement_id');
        const noEquipAlert = document.getElementById('no-equipment-alert');

        const allOptions = Array.from(equipSelect.querySelectorAll('option[data-station]')).map(opt => ({
            value: opt.value,
            text: opt.textContent,
            station: opt.getAttribute('data-station')
        }));

        function populateEquipements(stationId) {
            while (equipSelect.options.length > 1) equipSelect.remove(1);

            if (!stationId) {
                equipSelect.disabled = true;
                noEquipAlert.classList.add('d-none');
                return;
            }

            const filtered = allOptions.filter(o => String(o.station) === String(stationId));

            if (filtered.length === 0) {
                noEquipAlert.classList.remove('d-none');
                equipSelect.disabled = true;
                return;
            }

            noEquipAlert.classList.add('d-none');
            filtered.forEach(o => {
                const opt = document.createElement('option');
                opt.value = o.value;
                opt.textContent = o.text;
                equipSelect.appendChild(opt);
            });
            equipSelect.disabled = false;

            const oldEquip = '{{ old("equipement_id", $incident->equipement_id) }}';
            if (oldEquip) equipSelect.value = oldEquip;
        }

        if (stationSelect.value) {
            populateEquipements(stationSelect.value);
        }

        stationSelect.addEventListener('change', function() {
            populateEquipements(this.value);
        });
    });
</script>
@endsection