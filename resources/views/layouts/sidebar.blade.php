@php
use Illuminate\Support\Facades\Auth;
$user = Auth::user();
$role = $user->role->nom_role;
$isAdmin = $role === 'Administrateur système';
$isDT = in_array($role, ['Directeur maintenance', 'Responsable maintenance']);
$isTech = in_array($role, ['Technicien', 'Prestataire Externe']);
$isSous = $role === 'Sous-gérant de station';
$current = request()->route()?->getName();
@endphp

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- Profil utilisateur --}}
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('dist/assets/images/image.jpeg') }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ $user->prenom }} {{ $user->nom }}</span>
                    <span class="text-secondary text-small">{{ $role }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        {{-- Dashboard (tous les rôles) --}}
        <li class="nav-item {{ $current === 'dashboard.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Tableau de bord</span>
            </a>
        </li>

        {{-- ===== ADMINISTRATEUR SYSTÈME ===== --}}
        @if($isAdmin)
        <li class="nav-item {{ str_starts_with($current ?? '', 'users') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="mdi mdi-account-multiple menu-icon"></i>
                <span class="menu-title">Utilisateurs</span>
            </a>
        </li>

        <li class="nav-item {{ $current === 'historique.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('historique.index') }}">
                <i class="mdi mdi-history menu-icon"></i>
                <span class="menu-title">Historique système</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#nav-params"
                aria-expanded="{{ in_array($current, ['roles.index','domaines.index','stations.index','equipements.index']) ? 'true' : 'false' }}">
                <i class="mdi mdi-cog menu-icon"></i>
                <span class="menu-title">Paramètres</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ in_array($current, ['roles.index','domaines.index','stations.index','equipements.index']) ? 'show' : '' }}"
                id="nav-params">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ $current === 'roles.index' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('roles.index') }}">
                            <i class="mdi mdi-shield-account menu-icon"></i> Rôles
                        </a>
                    </li>
                    <li class="nav-item {{ $current === 'domaines.index' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('domaines.index') }}">
                            <i class="mdi mdi-tag menu-icon"></i> Domaines
                        </a>
                    </li>
                    <li class="nav-item {{ $current === 'stations.index' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('stations.index') }}">
                            <i class="mdi mdi-map-marker menu-icon"></i> Stations
                        </a>
                    </li>
                    <li class="nav-item {{ $current === 'equipements.index' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('equipements.index') }}">
                            <i class="mdi mdi-laptop menu-icon"></i> Équipements
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        {{-- ===== DIRECTION MAINTENANCE ===== --}}
        @if($isDT)
        <li class="nav-item {{ str_starts_with($current ?? '', 'incidents') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('incidents.index') }}">
                <i class="mdi mdi-alert-circle menu-icon"></i>
                <span class="menu-title">Gestion des incidents</span>
            </a>
        </li>
        @endif

        {{-- ===== TECHNICIEN / PRESTATAIRE ===== --}}
        @if($isTech)
        <li class="nav-item {{ $current === 'interventions.mes_interventions' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('interventions.mes_interventions') }}">
                <i class="mdi mdi-wrench menu-icon"></i>
                <span class="menu-title">Mes interventions</span>
            </a>
        </li>
        @endif

        {{-- ===== SOUS-GÉRANT DE STATION ===== --}}
        @if($isSous)
        <li class="nav-item {{ $current === 'incidents.create' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('incidents.create') }}">
                <i class="mdi mdi-plus-circle menu-icon"></i>
                <span class="menu-title">Déclarer un incident</span>
            </a>
        </li>
        <li class="nav-item {{ $current === 'incidents.mes_incidents' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('incidents.mes_incidents') }}">
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                <span class="menu-title">Mes incidents</span>
            </a>
        </li>
        @endif

        {{-- Déconnexion (tous les rôles) --}}
        <li class="nav-item" style="margin-top: 24px !important;">
            <a class="nav-link" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <i class="mdi mdi-logout menu-icon"></i>
                <span class="menu-title">Déconnexion</span>
            </a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</nav>