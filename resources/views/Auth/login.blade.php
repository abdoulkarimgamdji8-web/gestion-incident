@extends('layouts.head')

<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left p-5">
                    <div class="brand-logo text-center mb-4">
                        <img src="{{ asset('dist/assets/images/logo.svg') }}" alt="Logo">
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
                        <div class="my-2 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" class="form-check-input" name="remember"> Rester connecté
                                </label>
                            </div>
                            <a href="#" class="auth-link text-primary">Mot de passe oublié ?</a>
                        </div>
                        <div class="mt-3 d-grid gap-2">
                            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">Se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
</div>