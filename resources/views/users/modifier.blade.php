@extends('layouts.app')

@section('content')

  <div class="content-wrapper d-flex align-items-center justify-content-center auth" style="min-height:100vh;">
    <div class="col-lg-8 col-md-10 col-sm-12" style="max-width:760px; width:100%;">

      <div class="auth-form-light text-left p-4 p-lg-5">
        <div class="brand-logo text-center mb-4">
          <img src="{{ asset('dist/assets/images/') }}" alt="">
        </div>
        <h3 class="text-center mb-2">Modifier un utilisateur</h3>
        <p class="text-center text-muted mb-4">Mettez à jour les informations de l'utilisateur.</p>

        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form class="pt-3" method="POST" action="{{ route('users.update', $user->id) }}">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="last_name" class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control form-control-sm" id="last_name" placeholder="Nom" value="{{ old('nom', $user->nom) }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="first_name" class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control form-control-sm" id="first_name" placeholder="Prénom" value="{{ old('prenom', $user->prenom) }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-select form-select-sm" name="role_id" id="role" required>
                  <option value="">Sélectionnez un rôle</option>
                  @foreach($roles as $role)
                  <option value="{{ $role->id }}" data-name="{{ strtolower($role->nom_role) }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->nom_role }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email" class="form-label">Mail</label>
                <input type="email" name="email" class="form-control form-control-sm" id="email" placeholder="email@exemple.com" value="{{ old('email', $user->email) }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control form-control-sm" id="password" placeholder="Laisser vide pour conserver le mot de passe actuel" maxlength="8">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="phone" class="form-label">Numéro de téléphone</label>
                <input type="tel" name="numero" class="form-control form-control-sm" id="phone" placeholder="06 12 34 56 78" value="{{ old('numero', $user->numero) }}" required title="Veuillez entrer un numéro de téléphone valide à 9 chiffres" maxlength="9">
              </div>
            </div>
            <div class="col-md-6" id="domaineField" style="display:none;">
              <div class="form-group">
                <label for="domaine_id" class="form-label">Domaine</label>
                <select class="form-select form-select-sm" name="domaine_id" id="domaine_id">
                  <option value="">Sélectionnez un domaine</option>
                  @foreach($domaines as $domaine)
                  <option value="{{ $domaine->id }}" {{ old('domaine_id', $user->domaine_id) == $domaine->id ? 'selected' : '' }}>{{ $domaine->nom_domaine }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6" id="disponibiliteField" style="display:none;">
              <div class="form-group">
                <label for="disponibilite" class="form-label">Disponibilité</label>
                <select class="form-select form-select-sm" name="disponibilite" id="disponibilite">
                  <option value="">Non défini</option>
                  <option value="1" {{ old('disponibilite', $user->disponibilite) == 1 ? 'selected' : '' }}>Disponible</option>
                  <option value="0" {{ old('disponibilite', $user->disponibilite) === 0 ? 'selected' : '' }}>Occupé</option>
                </select>
              </div>
            </div>
          </div>

          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-gradient-warning btn-lg font-weight-medium auth-form-btn flex-grow-1">Enregistrer</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-lg font-weight-medium auth-form-btn flex-grow-1">Retour</a>
          </div>
        </form>
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