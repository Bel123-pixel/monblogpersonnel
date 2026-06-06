@extends('layouts.app')
@section('title', 'Connexion — BellevieShop')
@section('content')
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-icon"><i class="fas fa-bolt"></i></div>
    <h1 class="auth-title">Bon retour !</h1>
    <p class="auth-sub">Connectez-vous à votre compte BellevieShop</p>

    <form action="{{ route('login') }}" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label">Adresse email</label>
        <input type="email" name="email"
          class="form-control @error('email') is-invalid @enderror"
          value="{{ old('email') }}" placeholder="vous@exemple.com" autofocus>
        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password"
          class="form-control @error('password') is-invalid @enderror"
          placeholder="••••••••">
        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem">
        <input type="checkbox" id="remember" name="remember"
          style="width:15px;height:15px;accent-color:var(--blue)">
        <label for="remember" style="font-size:.84rem;color:var(--muted);cursor:pointer">
          Se souvenir de moi
        </label>
      </div>
      <button type="submit" class="btn btn-blue btn-lg" style="width:100%;justify-content:center">
        <i class="fas fa-arrow-right-to-bracket"></i> Se connecter
      </button>
    </form>

    <div class="auth-footer">
      Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire gratuitement</a>
    </div>
  </div>
</div>
@endsection

