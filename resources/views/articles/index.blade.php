@extends('layouts.app')

@section('title', 'Paramètres — BellevieShop')

@section('content')
<div class="wrap-sm">
  <div class="page-top">
    <h1>Paramètres du profil</h1>
    @if(auth()->check())
      <a href="{{ route('profile', auth()->user()->username) }}" class="btn btn-ghost btn-sm">
        <i class="fas fa-arrow-left"></i> Retour
      </a>
    @endif
  </div>
  
  <div class="form-card">
    <div style="text-align:center;margin-bottom:2rem">
      <img id="avatarPreview" src="{{ auth()->user()?->avatar_url ?? 'https://via.placeholder.com/88' }}" alt=""
        style="width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid var(--border);margin:0 auto .6rem">
      <p class="form-hint">Cliquez sur "Choisir" pour changer la photo</p>
    </div>
    
    @if(auth()->check())
      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT')

        <div class="form-group" style="margin-bottom: 1.5rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Nom d'utilisateur</label>
          <input type="text" name="username" value="{{ auth()->user()->username }}" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 4px;">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">
          Sauvegarder les modifications
        </button>
      </form>
    @else
      <p style="text-align: center; color: red; font-weight: bold; padding: 1rem; border: 1px dashed red; border-radius: 4px;">
        Veuillez vous connecter pour modifier votre profil.
      </p>
    @endif
  </div>
</div>
@endsection

