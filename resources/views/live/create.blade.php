@extends('layouts.app')
@section('title', 'Démarrer un live — BellevieShop')
@section('content')
<div class="wrap-sm">
  <div class="page-top">
    <h1>🎥 Démarrer un live</h1>
    <a href="{{ route('live.index') }}" class="btn btn-ghost btn-sm">
      <i class="fas fa-arrow-left"></i> Retour
    </a>
  </div>
  <div class="form-card">
    <div style="background:var(--blue-light);border-radius:var(--r);padding:.9rem 1rem;margin-bottom:1.5rem;font-size:.86rem;color:var(--blue)">
      <i class="fas fa-circle-info"></i>
      <strong> Comment ça marche :</strong> Copiez l'URL de votre YouTube Live ou Twitch et collez-la ci-dessous.
    </div>
    <form action="{{ route('live.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label class="form-label">Titre du live *</label>
        <input type="text" name="title"
          class="form-control @error('title') is-invalid @enderror"
          value="{{ old('title') }}" placeholder="Ex : Q&A Laravel 12" autofocus>
        @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description"
          class="form-control @error('description') is-invalid @enderror"
          rows="3" placeholder="Décrivez votre live..." data-max="500"
        >{{ old('description') }}</textarea>
        @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">URL du live *</label>
        <input type="url" name="video_url"
          class="form-control @error('video_url') is-invalid @enderror"
          value="{{ old('video_url') }}"
          placeholder="https://www.youtube.com/watch?v=...">
        <span class="form-hint">YouTube Live, Twitch ou toute URL de stream valide</span>
        @error('video_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Miniature (optionnel)</label>
        <img id="thumbPrev" src="" alt=""
          style="display:none;max-height:150px;border-radius:var(--r);margin-bottom:.5rem;object-fit:cover">
        <input type="file" name="thumbnail"
          class="form-control @error('thumbnail') is-invalid @enderror"
          accept="image/*" onchange="previewImage(this,'thumbPrev')">
        @error('thumbnail')<span class="invalid-feedback">{{ $message }}</span>@enderror
      </div>
      <button type="submit" class="btn btn-red btn-lg" style="width:100%;justify-content:center">
        <i class="fas fa-broadcast-tower"></i> Démarrer le live maintenant
      </button>
    </form>
  </div>
</div>
@endsection

