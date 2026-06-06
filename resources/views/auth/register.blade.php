@extends('layouts.app')
@section('title', 'Inscription — BellevieShop')
@section('content')
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-icon"><i class="fas fa-bolt"></i></div>
    <h1 class="auth-title">Créer un compte</h1>
    <p class="auth-sub">Rejoignez la communauté BellevieShop</p>

    <form action="{{ route('register') }}" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label">Nom complet</label>
        <input type="text" name="name"
          class="form-control @error('name') is-invalid @enderror"
          value="{{ old('name') }}" placeholder="Jean Dupont" autofocus>
        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Nom d'utilisateur</label>
        <input type="text" name="username"
          class="form-control @error('username') is-invalid @enderror"
          value="{{ old('username') }}" placeholder="jean_dupont">
        <span class="form-hint">Utilisé pour les @mentions</span>
        @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Adresse email</label>
        <input type="email" name="email"
          class="form-control @error('email') is-invalid @enderror"
          value="{{ old('email') }}" placeholder="vous@exemple.com">
        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password"
          class="form-control @error('password') is-invalid @enderror"
          placeholder="Minimum 6 caractères">
        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation"
          class="form-control" placeholder="Répéter le mot de passe">
      </div>
      <button type="submit" class="btn btn-blue btn-lg" style="width:100%;justify-content:center">
        <i class="fas fa-user-plus"></i> Créer mon compte
      </button>
    </form>

    <div class="auth-footer">
      Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
    </div>
  </div>
</div>
@endsection

