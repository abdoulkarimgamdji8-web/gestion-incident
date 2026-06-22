@extends('layouts.app')

@section('content')
<div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="auth-form-light text-left p-4 p-lg-5">

            <h3 class="text-center mb-2">
                Rapport d'intervention
            </h3>

            <p class="text-center text-muted mb-4">
                INC-{{ str_pad($incident->id, 6, '0', STR_PAD_LEFT) }}
                — {{ $incident->titre }}
            </p>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST"
                action="{{ route('incidents.rapport.store', $incident->id) }}"
                enctype="multipart/form-data">

                @csrf

                {{-- RESULTAT INTERVENTION --}}
                <div class="form-group mb-4">
                    <label class="form-label">
                        Résultat de l'intervention
                    </label>

                    <select
                        name="resultat_intervention"
                        id="resultat_intervention"
                        class="form-select form-control-lg"
                        required>

                        <option value="">
                            -- Sélectionnez --
                        </option>

                        <option value="resolu">
                            Résolu
                        </option>
                        @if(
                        empty($incident->motif_attente)
                        )
                        <option value="en_attente">
                            En attente
                        </option>
                        @endif

                        <option value="non_resolu">
                            Non résolu
                        </option>

                    </select>
                </div>

                {{-- BLOC EN ATTENTE --}}
                <div id="attente-fields" class="d-none">

                    <div class="alert alert-warning">
                        Cette intervention sera mise en attente.
                        Aucun rapport final ne sera généré.
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Motif d'attente
                        </label>

                        <select
                            name="motif_attente"
                            id="motif_attente"
                            class="form-select form-control-lg">

                            <option value="">
                                -- Sélectionnez --
                            </option>

                            <option value="Pièce indisponible">
                                Pièce indisponible
                            </option>

                            <option value="Attente fournisseur">
                                Attente fournisseur
                            </option>

                            <option value="Attente validation">
                                Attente validation
                            </option>

                            <option value="Accès au site impossible">
                                Accès au site impossible
                            </option>

                            <option value="Autre">
                                Autre
                            </option>

                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Description de l'attente
                        </label>

                        <textarea
                            name="description_attente"
                            id="description_attente"
                            rows="4"
                            class="form-control form-control-lg"
                            placeholder="Décrivez ce qui bloque actuellement l'intervention..."></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Date prévisionnelle de reprise
                        </label>

                        <input
                            type="date"
                            name="date_reprise_prevue"
                            id="date_reprise_prevue"
                            class="form-control form-control-lg"
                            min="{{ date('Y-m-d') }}">
                    </div>

                </div>

                {{-- BLOC RAPPORT --}}
                <div id="rapport-fields">

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Contenu du rapport
                        </label>

                        <textarea
                            name="contenu"
                            rows="6"
                            class="form-control form-control-lg"
                            placeholder="Décrivez l'intervention réalisée...">{{ old('contenu') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Résultat obtenu
                        </label>

                        <input
                            type="text"
                            name="resultat"
                            class="form-control form-control-lg"
                            placeholder="Ex : Pompe remplacée"
                            value="{{ old('resultat') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Observations
                        </label>

                        <textarea
                            name="observation"
                            rows="3"
                            class="form-control form-control-lg"
                            placeholder="Observations complémentaires...">{{ old('observation') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Pièces jointes
                        </label>

                        <input
                            type="file"
                            name="pieces_jointes[]"
                            class="form-control form-control-lg"
                            multiple
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">

                        <small class="text-muted">
                            Taille maximale : 5 Mo par fichier.
                        </small>
                    </div>

                </div>

                {{-- BOUTONS --}}
                <div class="mt-4 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-gradient-success btn-lg flex-grow-1">

                        <i class="mdi mdi-check-circle"></i>
                        Soumettre
                    </button>

                    <a href="{{ route('interventions.mes_interventions') }}"
                        class="btn btn-secondary btn-lg flex-grow-1">

                        Retour

                    </a>

                </div>

            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const resultat =
            document.getElementById('resultat_intervention');

        const attenteFields =
            document.getElementById('attente-fields');

        const rapportFields =
            document.getElementById('rapport-fields');

        const motif =
            document.getElementById('motif_attente');

        const description =
            document.getElementById('description_attente');

        const dateReprise =
            document.getElementById('date_reprise_prevue');

        function toggleFields() {

            if (resultat.value === 'en_attente') {

                attenteFields.classList.remove('d-none');
                rapportFields.classList.add('d-none');

                motif.required = true;
                description.required = true;
                dateReprise.required = true;

            } else {

                attenteFields.classList.add('d-none');
                rapportFields.classList.remove('d-none');

                motif.required = false;
                description.required = false;
                dateReprise.required = false;
            }
        }

        resultat.addEventListener(
            'change',
            toggleFields
        );

        toggleFields();

    });
</script>

@endsection