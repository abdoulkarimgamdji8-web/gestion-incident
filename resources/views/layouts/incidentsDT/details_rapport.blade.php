@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-file-document-outline"></i>
            </span>
            Rapport — INC-{{ str_pad($incident->id, 6, '0', STR_PAD_LEFT) }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('incidents.index') }}">Tous les incidents</a>
                </li>
                <li class="breadcrumb-item active">Détail rapport</li>
            </ul>
        </nav>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @php $role = Auth::user()->role->nom_role; @endphp

    <div class="row">
        {{-- Colonne gauche : infos incident --}}
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-alert-circle-outline text-primary"></i>
                        Incident
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted">Titre</td>
                                <td><strong>{{ $incident->titre }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Station</td>
                                <td>{{ optional($incident->station)->nom}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Équipement</td>
                                <td>{{ optional($incident->equipement)->nom}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Domaine</td>
                                <td>{{ optional($incident->domaine)->nom_domaine}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Priorité</td>
                                <td>
                                    @if ($incident->priorite === 'faible')
                                    <span class="badge badge-gradient-info">Faible</span>
                                    @elseif($incident->priorite === 'eleve')
                                    <span class="badge badge-gradient-warning">Élevé</span>
                                    @else
                                    <span class="badge badge-gradient-danger">Critique</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Statut</td>
                                <td>
                                    @switch($incident->statut)
                                    @case('declare')
                                    <span class="badge badge-gradient-secondary">Déclaré</span>
                                    @break

                                    @case('assigne')
                                    <span class="badge badge-gradient-primary">Assigné</span>
                                    @break

                                    @case('en_cours')
                                    <span class="badge badge-gradient-warning">En cours</span>
                                    @break

                                    @case('en_attente')
                                    <span class="badge badge-gradient-warning">En attente</span>
                                    @break

                                    @case('resolu')
                                    <span class="badge badge-gradient-success">Résolu</span>
                                    @break

                                    @case('non_resolu')
                                    <span class="badge badge-gradient-success">Non résolu</span>
                                    @break

                                    @case('cloture')
                                    <span class="badge badge-gradient-dark">Clôturé</span>
                                    @break
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Déclaré par</td>
                                <td>{{ optional($incident->declarant)->prenom }}
                                    {{ optional($incident->declarant)->nom }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Intervenant</td>
                                <td>{{ optional($incident->technicien)->prenom }}
                                    {{ optional($incident->technicien)->nom }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date</td>
                                <td>{{ $incident->date_signalement?->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <p class="text-muted small mb-1">Description</p>
                    <p>{{ $incident->description }}</p>
                </div>
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="col-md-8 mb-4">

            {{-- Rapport --}}
            @php
            $intervention = $incident->interventions->last();
            $rapport = $intervention?->rapport;
            @endphp

            <div class="card mb-4">
                @if($incident->statut === 'en_attente')
                <div class="card border-warning mt-3">
                    <div class="card-body">

                        <h5 class="text-warning">
                            <i class="mdi mdi-clock-outline"></i>
                            Mise en attente
                        </h5>

                        <hr>

                        <p>
                            <strong>Motif :</strong><br>
                            {{ $incident->motif_attente }}
                        </p>

                        <p>
                            <strong>Description :</strong><br>
                            {{ $incident->description_attente }}
                        </p>

                        <p>
                            <strong>Date prévisionnelle de reprise :</strong><br>
                            {{ \Carbon\Carbon::parse($incident->date_reprise_prevue)->format('d/m/Y') }}
                        </p>

                    </div>
                </div>
                @else

                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-file-document-edit-outline text-primary"></i>
                        Rapport d'intervention
                    </h4>

                    @if ($rapport)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Date du rapport</p>
                            <p>{{ $rapport->date_rapport->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Résultat</p>
                            <p>{{ $intervention->resultat ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Contenu</p>
                        <p style="white-space: pre-line;">{{ $rapport->contenu }}</p>
                    </div>
                    @if ($intervention->observation)
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Observations</p>
                        <p>{{ $intervention->observation }}</p>
                    </div>
                    @endif
                    @if($incident->statut === 'en_attente' && $intervention->observation)
                    <div class="alert alert-info mt-3">
                        <strong><i class="mdi mdi-information-outline"></i> Motif de mise en attente :</strong>
                        <p class="mb-0">{{ $intervention->observation }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-muted small mb-1">Durée de l'intervention</p>
                        <p>
                            Du {{ $intervention->date_debut->format('d/m/Y') }}
                            au {{ $intervention->date_fin?->format('d/m/Y') ?? 'N/A' }}
                        </p>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert-outline me-2"></i>
                        Aucun rapport soumis pour le moment.
                    </div>
                    @endif
                </div>
                @endif
            </div>

            @if($incident->statut !== 'en_attente')
            {{-- Pièces jointes du rapport --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-paperclip text-primary"></i>
                        Pièces jointes du rapport
                    </h4>

                    @php $pjRapport = $incident->piecesJointes->where('source', 'rapport'); @endphp

                    @if ($pjRapport->isEmpty())
                    <p class="text-muted">Aucune pièce jointe.</p>
                    @else
                    <div class="row g-3">
                        @foreach ($pjRapport as $pj)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-2 border rounded gap-2">
                                @if (str_contains($pj->type_fichier, 'image'))
                                <i class="mdi mdi-image text-success" style="font-size:22px;"></i>
                                @elseif(str_contains($pj->type_fichier, 'pdf'))
                                <i class="mdi mdi-file-pdf text-danger" style="font-size:22px;"></i>
                                @else
                                <i class="mdi mdi-file-word text-primary" style="font-size:22px;"></i>
                                @endif
                                <span class="flex-grow-1 text-truncate small">{{ $pj->nom_fichier }}</span>
                                <a href="{{ asset('storage/' . $pj->chemin_fichier) }}" target="_blank"
                                    class="btn btn-sm btn-gradient-primary">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @php
            $memoSousGerant = 0;
            if ($intervention) {
            $memoSousGerant = $incident->commentaires->filter(function($c) use ($intervention) {
            return in_array($c->user->role->nom_role, ['Sous-gérant de station'])
            && $c->created_at >= $intervention->created_at;
            })->count();
            }

            $memoSousGerantExiste = $memoSousGerant > 0;
            @endphp

            @if($incident->statut === 'resolu' && in_array($role, ['Directeur maintenance', 'Responsable maintenance']) && $memoSousGerant === 0)
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-bell-outline text-warning"></i>
                        Notifier le sous-gérant
                    </h4>
                    <p class="text-muted small">
                        Informez le sous-gérant que l'intervention est terminée
                        et qu'il peut aller vérifier sur le terrain.
                    </p>
                    <form method="POST"
                        action="{{ route('incidents.commentaire.store', $incident->id) }}">
                        @csrf
                        <input type="hidden" name="contenu"
                            value="L'intervention sur votre incident est terminée. Merci de vérifier sur le terrain et dire au directeur maintenance si tout est en ordre.">
                        <button type="submit" class="btn btn-gradient-warning btn-lg"
                            onclick="return confirm('Notifier le sous-gérant ?')">
                            <i class="mdi mdi-bell-ring"></i> Notifier le sous-gérant
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Mémos / Commentaires --}}
            @if($incident->statut !== 'non_resolu' && $incident->statut !== 'en_attente')
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-message-text-outline text-primary"></i>
                        Mémos / Commentaires
                    </h4>

                    @if ($incident->commentaires->isEmpty())
                    <p class="text-muted">Aucun commentaire pour le moment.</p>
                    @else
                    @foreach($incident->commentaires as $commentaire)
                    <div class="p-2 border rounded mb-2 {{ $commentaire->created_at >= $intervention->created_at ? '' : 'bg-light text-muted' }}">
                        <p class="mb-1 fw-bold small">
                            {{ $commentaire->user->prenom }} {{ $commentaire->user->nom }}
                            <span class="badge badge-gradient-secondary ms-2">{{ $commentaire->user->role->nom_role }}</span>
                            <span class="text-muted ms-2">{{ $commentaire->created_at->format('d/m/Y H:i') }}</span>
                            @if($commentaire->created_at < $intervention->created_at)
                                <span class="badge bg-secondary ms-2">Ancien échange</span>
                                @endif
                        </p>
                        <p class="mb-0">{{ $commentaire->contenu }}</p>
                    </div>
                    @endforeach
                    @endif

                    @if (in_array($role, ['Responsable maintenance', 'Directeur maintenance']))
                    <form method="POST" action="{{ route('incidents.commentaire.store', $incident->id) }}"
                        class="mt-3">
                        @csrf
                        <div class="form-group mb-2">
                            <textarea name="contenu" rows="3" class="form-control" placeholder="Laisser un commentaire..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary btn-sm">
                            <i class="mdi mdi-send"></i> Envoyer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

            {{-- Formulaire de rapport : DT si statut assigné ou en cours --}}

            {{-- Réassigner : DT si statut non_resolu --}}
            @if ($incident->statut === 'non_resolu' && ($role === 'Directeur maintenance' || $role === 'Responsable maintenance'))
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title text-danger">
                        <i class="mdi mdi-account-arrow-right text-danger"></i>
                        Intervention non résolue — Réassigner
                    </h4>
                    <p class="text-muted small">
                        Le technicien a signalé que le problème n'est pas résolu.
                        Réassignez à un intervenant.
                    </p>

                    @if ($intervenants->isEmpty())
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert-outline"></i>
                        Aucun intervenant disponible pour le domaine
                        <strong>{{ optional($incident->domaine)->nom_domaine }}</strong>.
                    </div>
                    @else
                    <form method="POST" action="{{ route('incidents.reassigner', $incident->id) }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Choisir l'intervenant</label>
                            <select name="technicien_assigne_id" class="form-select form-control-lg" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach ($intervenants as $intervenant)
                                <option value="{{ $intervenant->id }}">
                                    {{ $intervenant->nom }} {{ $intervenant->prenom }}
                                    — {{ $intervenant->role->nom_role }}
                                    — {{ optional($intervenant->domaine)->nom_domaine ?? 'N/A' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-gradient-danger btn-lg"
                            onclick="return confirm('Réassigner cet incident ?')">
                            <i class="mdi mdi-refresh"></i> Réassigner
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

            {{-- Boutons --}}
            <div class="d-flex gap-2">
                <a href="{{ route('incidents.index') }}"
                    class="btn btn-secondary btn-lg font-weight-medium auth-form-btn">
                    <i class="mdi mdi-arrow-left"></i> Retour
                </a>

                @if(
                $incident->statut === 'resolu'
                &&
                $memoSousGerant > 0
                &&
                ($role === 'Directeur maintenance' || $role === 'Responsable maintenance'))
                )

                <form method="POST"
                    action="{{ route('incidents.cloturer', $incident->id) }}">
                    @csrf

                    <button type="submit"
                        class="btn btn-gradient-success btn-lg"
                        onclick="return confirm('Clôturer cet incident ?')">

                        <i class="mdi mdi-check-circle"></i>
                        Clôturer l'incident

                    </button>
                </form>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection