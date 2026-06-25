<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

    {{-- Brand avec logo --}}
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center"
         style="background:#ffffff; width:255px; height:60px; flex-shrink:0; border-right:1px solid #f1f5f9;">
        <a href="{{ route('dashboard.index') }}" class="d-flex align-items-center justify-content-center text-decoration-none" style="width:100%; height:100%; padding:8px 16px;">
            <img src="{{ asset('dist/assets/images/logo.jpg') }}"
                 alt="GULFCAM"
                 style="max-height:44px; max-width:180px; width:auto; object-fit:contain;">
        </a>
    </div>

    {{-- Menu wrapper --}}
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end flex-grow-1 px-3">

        {{-- Bouton toggle mobile --}}
        <button class="navbar-toggler navbar-toggler align-self-center me-2" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu" style="color:#374151; font-size:1.4rem;"></span>
        </button>

        {{-- Titre page (optionnel) --}}
        <div class="flex-grow-1"></div>

        <ul class="navbar-nav navbar-nav-right align-items-center gap-1">

            {{-- Notification cloche --}}
            <li class="nav-item">
                <a class="nav-link position-relative px-2" href="#">
                    <i class="mdi mdi-bell-outline" style="font-size:1.3rem; color:#6b7280;"></i>
                </a>
            </li>

            {{-- Profil dropdown --}}
            <li class="nav-item dropdown ms-1">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-2"
                   id="profileDropdown" href="#"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <div style="width:34px; height:34px; border-radius:50%; background:#dcfce7;
                                display:flex; align-items:center; justify-content:center; overflow:hidden;">
                        <img src="{{ asset('dist/assets/images/image.jpeg') }}"
                             alt="profil" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div class="d-none d-md-block" style="line-height:1.2;">
                        <div style="font-weight:600; font-size:0.85rem; color:#1e293b;">
                            {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                        </div>
                        <div style="font-size:0.72rem; color:#16a34a;">
                            {{ Auth::user()->role->nom_role }}
                        </div>
                    </div>
                    <i class="mdi mdi-chevron-down d-none d-md-block" style="color:#9ca3af; font-size:1rem;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end mt-1 py-1"
                     aria-labelledby="profileDropdown"
                     style="min-width:200px; border:none; border-radius:10px;
                            box-shadow:0 8px 24px rgba(0,0,0,0.08);">
                    <div class="px-3 py-2 border-bottom">
                        <div style="font-weight:600; font-size:0.85rem; color:#1e293b;">
                            {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                        </div>
                        <div style="font-size:0.75rem; color:#6b7280;">{{ Auth::user()->email }}</div>
                    </div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                        <i class="mdi mdi-logout" style="color:#ef4444; font-size:1rem;"></i>
                        <span style="font-size:0.875rem;">Déconnexion</span>
                    </a>
                    <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </li>

        </ul>

        {{-- Toggle mobile --}}
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center ms-2" type="button"
                data-toggle="offcanvas">
            <span class="mdi mdi-menu" style="color:#374151; font-size:1.4rem;"></span>
        </button>

    </div>
</nav>
