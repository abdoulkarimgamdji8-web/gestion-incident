<?php

use Illuminate\Support\Facades\Auth;

$user = Auth::user();
$role = $user->role->nom_role;

?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('dist/assets/images/image.jpeg') }}" alt="profile" />
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::user()->nom }}</span>
                    <span class="text-secondary text-small">{{ Auth::user()->role->nom_role }}</span>
                </div>
                <i
                    class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        @if ($role == 'Administrateur système')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <span class="menu-title">Gestion des utilisateurs</span>
                <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
        </li>
         @endif
        

        @if ($role == 'Directeur maintenance' || $role == 'Responsable maintenance')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('incidents.index') }}">
                <span class="menu-title">Gestion des incidents</span>
                <i class="mdi mdi-alert-circle menu-icon"></i>
            </a>
        </li>
        @endif

        @if ($role == 'Sous-gérant de station')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('incidents.mes_incidents') }}">
                <span class="menu-title">Mes incidents</span>
                <i class="mdi mdi-alert-circle menu-icon"></i>
            </a>
        </li>
        @endif

        @if ($role == 'Technicien' || $role == 'Prestataire externe')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('interventions.mes_interventions') }}">
                <span class="menu-title">Mes interventions</span>
                <i class="mdi mdi-alert-circle menu-icon"></i>
            </a>
        </li>
        @endif
        <!--
        <li class="nav-item">
            <a
                class="nav-link"
                data-bs-toggle="collapse"
                href="#incident-info"
                aria-expanded="false"
                aria-controls="incident-info">
                <span class="menu-title">À propos des incidents</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-information menu-icon"></i>
            </a>
            <div class="collapse" id="incident-info">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <div class="text-muted small p-2">
                            <strong>Incident Technique :</strong><br>
                            Un problème signalé sur un équipement nécessitant intervention.<br><br>
                            <strong>Paramètres :</strong><br>
                            • Domaines : catégories métier<br>
                            • Stations : lieux d'installation<br>
                            • Équipements : ressources affectées<br>
                            • Rôles : permissions utilisateur
                        </div>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a
                class="nav-link"
                data-bs-toggle="collapse"
                href="#ui-basic"
                aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-title">Basic UI Elements</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/ui-features/buttons.html">
                            Buttons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/ui-features/dropdowns.html">
                            Dropdowns
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="pages/ui-features/typography.html">
                            Typography
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                data-bs-toggle="collapse"
                href="#icons"
                aria-expanded="false"
                aria-controls="icons">
                <span class="menu-title">Icons</span>
                <i class="mdi mdi-contacts menu-icon"></i>
                    <i class="mdi mdi-settings menu-icon"></i>
            <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/icons/font-awesome.html">
                            Font Awesome
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                data-bs-toggle="collapse"
                href="#forms"
                aria-expanded="false"
                aria-controls="forms">
                <span class="menu-title">Forms</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
            <div class="collapse" id="forms">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/forms/basic_elements.html">
                            Form Elements
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                data-bs-toggle="collapse"
                href="#charts"
                aria-expanded="false"
                aria-controls="charts">
                <span class="menu-title">Charts</span>
                <i class="mdi mdi-chart-bar menu-icon"></i>
            </a>
            <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/charts/chartjs.html">
                            ChartJs
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                data-bs-toggle="collapse"
                href="#tables"
                aria-expanded="false"
                aria-controls="tables">
                <span class="menu-title">Tables</span>
                <i class="mdi mdi-table-large menu-icon"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/tables/basic-table.html">
                            Basic table
                        </a>
                    </li>
                </ul>
            </div>
        </li>
-->
        @if ($role == 'Administrateur système')
        <li class="nav-item">
            <a
                class="nav-link"
                data-bs-toggle="collapse"
                href="#auth"
                aria-expanded="false"
                aria-controls="auth">
                <span class="menu-title">Paramètres</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-settings menu-icon"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('roles.index') }}">
                            Gestion des rôles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('domaines.index') }}">
                            Gestion des domaines
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('stations.index') }}">
                            Gestion des stations
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('equipements.index') }}">
                            Gestion des équipements
                        </a>
                    </li>

                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('incidents.index') }}">
                            Gestion des incidents
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/error-500.html">
                            500
                        </a>
                    </li>
-->
                </ul>
            </div>
        </li>
        @endif
        <!--
        <li class="nav-item">
            <a
                class="nav-link"
                href="docs/documentation.html"
                target="_blank">
                <span class="menu-title">Documentation</span>
                <i class="mdi mdi-file-document-box menu-icon"></i>
            </a>
        </li>
-->
    </ul>
</nav>