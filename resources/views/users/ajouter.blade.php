@extends('layouts.app')

@section('content')
<div class="main-panel">
  <div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
    <div class="col-lg-8 col-md-10 col-sm-12" style="max-width:760px; width:100%;">

      <div class="auth-form-light text-left p-4 p-lg-5">
        <div class="brand-logo text-center mb-4">
          <img src="{{ asset('dist/assets/images/') }}" alt="">
        </div>
        <h3 class="text-center mb-2">Ajouter un utilisateur</h3>
        <p class="text-center text-muted mb-4">Complétez les informations ci-dessous.</p>
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        <form class="pt-3" method="POST" action="{{ route('users.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="last_name" class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control form-control-sm" id="last_name" placeholder="Nom" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="first_name" class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control form-control-sm" id="first_name" placeholder="Prénom" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-select form-select-sm" name="role_id" id="role" required>
                  <option value="">Sélectionnez un rôle</option>
                  @foreach($roles as $role)
                  <option value="{{ $role->id }}" data-name="{{ strtolower($role->nom_role) }}">{{ $role->nom_role }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email" class="form-label">Mail</label>
                <input type="email" name="email" class="form-control form-control-sm" id="email" placeholder="email@exemple.com" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control form-control-sm" id="password" placeholder="Mot de passe" required maxlength="8">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="phone" class="form-label">Numéro de téléphone</label>
                <input type="tel" name="numero" class="form-control form-control-sm" id="phone" placeholder="06 12 34 56 78" required title="Veuillez entrer un numéro de téléphone valide à 9 chiffres" maxlength="9">
              </div>
            </div>
            <div class="col-md-6" id="domaineField" style="display:none;">
              <div class="form-group">
                <label for="domaine_id" class="form-label">Domaine</label>
                <select class="form-select form-select-sm" name="domaine_id" id="domaine_id">
                  <option value="">Sélectionnez un domaine</option>
                  @foreach($domaines as $domaine)
                  <option value="{{ $domaine->id }}">{{ $domaine->nom_domaine }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6" id="disponibiliteField" style="display:none;">
              <div class="form-group">
                <label for="disponibilite" class="form-label">Disponibilité</label>
                <select class="form-select form-select-sm" name="disponibilite" id="disponibilite">
                  <option value="">Non défini</option>
                  <option value="1">Disponible</option>
                  <option value="0">Occupé</option>
                </select>
              </div>
            </div>
          </div>
          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn--icon-text"style="background: #1e8449; border-color: #1e8449; color:white   btn-lg font-weight-medium auth-form-btn flex-grow-1">Ajouter</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-lg font-weight-medium auth-form-btn flex-grow-1">Retour</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const roleField = document.getElementById('role');
    const domainGroup = document.getElementById('domaineField');
    const availabilityGroup = document.getElementById('disponibiliteField');

    function updateFields() {
      const selectedOption = roleField.selectedOptions[0];
      const roleName = selectedOption?.dataset?.name || '';
      const showExtra = roleName.includes('technicien') || roleName.includes('prestataire');
      domainGroup.style.display = showExtra ? 'block' : 'none';
      availabilityGroup.style.display = showExtra ? 'block' : 'none';
    }

    if (roleField) {
      roleField.addEventListener('input', updateFields);
      roleField.addEventListener('change', updateFields);
      updateFields();
    }
  });
</script>
@endsection