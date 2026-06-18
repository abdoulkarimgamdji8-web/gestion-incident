@extends('layouts.app')

@section('content')

    <div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
        <div class="col-lg-6 col-md-8 col-sm-12" style="max-width:650px; width:100%;">
            <div class="auth-form-light text-left p-4 p-lg-5">
                <h3 class="text-center mb-2">Rapport d'intervention</h3>
                <p class="text-center text-muted mb-4">
                    INC-{{ str_pad($incident->id, 6, '0', STR_PAD_LEFT) }} — {{ $incident->titre }}
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

                <form method="POST" action="{{ route('incidents.rapport.store', $incident->id) }}"
                    enctype="multipart/form-data" class="pt-3">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">Contenu du rapport</label>
                        <textarea name="contenu" rows="6" class="form-control form-control-lg"
                            placeholder="Décrivez l'intervention réalisée..."
                            required>{{ old('contenu') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Résultat</label>
                        <input type="text" name="resultat" class="form-control form-control-lg"
                            placeholder="Ex: Pompe remplacée, système rétabli..."
                            value="{{ old('resultat') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            Observations <span class="text-muted small">(optionnel)</span>
                        </label>
                        <textarea name="observation" rows="3" class="form-control form-control-lg"
                            placeholder="Remarques supplémentaires...">{{ old('observation') }}</textarea>
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
                        <button type="submit"
                            class="btn btn-gradient-success btn-lg font-weight-medium auth-form-btn flex-grow-1"
                            onclick="return confirm('Soumettre le rapport et marquer l\'incident comme résolu ?')">
                            <i class="mdi mdi-check-circle"></i> Terminer et soumettre
                        </button>
                        <a href="{{ route('interventions.mes_interventions') }}"
                            class="btn btn-secondary btn-lg font-weight-medium auth-form-btn flex-grow-1">
                            Retour
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection