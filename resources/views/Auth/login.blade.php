@extends('layouts.head')

<div class="container-fluid page-body-wrapper full-page-wrapper login-background">
    <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left p-5">
                    <div class="brand-logo text-center mb-4">
                        <img src="{{ asset('dist/assets/images/logo.jpg') }}" alt="Logo" style="width: 180px; height: 80px; object-fit: contain;">
                    </div>
                    <h4 class="text-center mb-2">Bienvenue</h4>
                    <h6 class="font-weight-light text-center mb-4">Connectez-vous pour continuer.</h6>
                    <form class="pt-3" action="{{ route('login') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="email" class="sr-only">Adresse e-mail</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" placeholder="Adresse e-mail" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Mot de passe</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Mot de passe" required>
                        </div>
                        <div class="my-2 text-center">
                            <a href="#" class="auth-link text-success">Mot de passe oublié ?</a>
                        </div>
                        <div class="mt-3 d-grid gap-2">
                            <button type="submit" class="btn btn-icon-text"style="background: #1e8449; border-color: #1e8449; color:white;">Se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
</div>