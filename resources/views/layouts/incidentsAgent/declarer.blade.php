@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
        <div class="col-lg-6 col-md-8 col-sm-12" style="max-width:600px; width:100%;">
            <div class="auth-form-light text-left p-4 p-lg-5">
                <h3 class="text-center mb-2">Déclarer un incident</h3>
                <p class="text-center text-muted mb-4">Remplissez le formulaire pour signaler un nouveau problème.</p>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form class="pt-3" method="POST" action="{{ route('incidents.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control form-control-lg" id="titre" placeholder="Titre de l'incident" value="{{ old('titre') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control form-control-lg" placeholder="Description détaillée" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="domaine_id" class="form-label">Domaine</label>
                        <select name="domaine_id" id="domaine_id" class="form-select form-control-lg" required>
                            <option value="">Sélectionnez un domaine</option>
                            @foreach($domaines as $domaine)
                            <option value="{{ $domaine->id }}" {{ old('domaine_id') == $domaine->id ? 'selected' : '' }}>{{ $domaine->nom_domaine }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="station_id" class="form-label">Station</label>
                        <select name="station_id" id="station_id" class="form-select form-control-lg" required>
                            <option value="">Sélectionnez une station</option>
                            @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>{{ $station->nom }} ({{ $station->ville }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="equipement_id" class="form-label">Équipement</label>
                        <div id="no-equipment-alert" class="alert alert-warning d-none" role="alert">
                            <i class="mdi mdi-alert-outline me-2"></i>
                            Aucun équipement n'est enregistré pour cette station.
                        </div>
                        <select name="equipement_id" id="equipement_id" class="form-select form-control-lg" required @if(!old('station_id')) disabled @endif>
                            <option value="">Sélectionnez un équipement</option>
                            @foreach($equipements as $equipement)
                            <option value="{{ $equipement->id }}" data-station="{{ $equipement->station_id }}" {{ old('equipement_id') == $equipement->id ? 'selected' : '' }}>{{ $equipement->nom }} ({{ $equipement->type }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="priorite" class="form-label">Priorité</label>
                        <select name="priorite" id="priorite" class="form-select form-control-lg" required>
                            <option value="faible" {{ old('priorite') === 'faible' ? 'selected' : '' }}>Faible</option>
                            <option value="eleve" {{ old('priorite') === 'eleve' ? 'selected' : '' }}>Élevé</option>
                            <option value="critique" {{ old('priorite') === 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Pièces jointes <span class="text-muted small">(optionnel — images, PDF, Word)</span>
                        </label>
                        <input type="file" name="pieces_jointes[]"
                            class="form-control form-control-lg"
                            multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <small class="text-muted">Taille max : 5 Mo par fichier.</small>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn--icon-text"style="background: #1e8449; border-color: #1e8449; color:white btn-lg font-weight-medium auth-form-btn flex-grow-1">Déclarer</button>
                        <a href="{{ route('incidents.mes_incidents') }}" class="btn btn-secondary btn-lg font-weight-medium auth-form-btn flex-grow-1">Retour</a>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const stationSelect = document.getElementById('station_id');
                        const equipSelect = document.getElementById('equipement_id');

                        // Collect all equipment options (except placeholder)
                        const allOptions = Array.from(equipSelect.querySelectorAll('option[data-station]')).map(opt => ({
                            value: opt.value,
                            text: opt.textContent,
                            station: opt.getAttribute('data-station')
                        }));

                        function populateEquipements(stationId) {
                            const noEquipAlert = document.getElementById('no-equipment-alert');
                            // Remove existing options except the placeholder (first option)
                            while (equipSelect.options.length > 1) {
                                equipSelect.remove(1);
                            }

                            if (!stationId) {
                                equipSelect.disabled = true;
                                noEquipAlert.classList.add('d-none');
                                return;
                            }

                            const filtered = allOptions.filter(o => String(o.station) === String(stationId));

                            if (filtered.length === 0) {
                                // Show alert and disable select if no equipment found
                                noEquipAlert.classList.remove('d-none');
                                equipSelect.disabled = true;
                                equipSelect.value = '';
                                return;
                            }

                            // Hide alert and enable select if equipment found
                            noEquipAlert.classList.add('d-none');

                            filtered.forEach(o => {
                                const opt = document.createElement('option');
                                opt.value = o.value;
                                opt.textContent = o.text;
                                equipSelect.appendChild(opt);
                            });

                            equipSelect.disabled = false;

                            // If there was an old selected value, try to reselect it
                            const oldEquip = '{{ old("equipement_id") }}';
                            if (oldEquip) {
                                equipSelect.value = oldEquip;
                            }
                        }

                        // On initial load, if a station is selected, populate equipments
                        if (stationSelect && equipSelect) {
                            if (stationSelect.value) {
                                populateEquipements(stationSelect.value);
                            } else {
                                equipSelect.disabled = true;
                            }

                            stationSelect.addEventListener('change', function() {
                                populateEquipements(this.value);
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection