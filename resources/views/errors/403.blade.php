@extends('layouts.app')
@section('title', '403 — Accès refusé')
@section('content')
<div style="min-height:65vh;display:flex;align-items:center;justify-content:center;padding:2rem">
  <div style="text-align:center;animation:fadeUp .5s var(--ease) both">
    <div style="font-family:'Syne',sans-serif;font-size:6rem;font-weight:900;background:linear-gradient(135deg,var(--blue),var(--sky));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1;margin-bottom:.5rem">403</div>
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:.6rem">Accès refusé</h1>
    <p style="color:var(--muted);margin-bottom:1.75rem;max-width:340px;margin-left:auto;margin-right:auto">
      Vous n'avez pas la permission d'accéder à cette page.
    </p>
    <a href="{{ route('home') }}" class="btn btn-blue btn-lg">
      <i class="fas fa-house"></i> Retour à l'accueil
    </a>
  </div>
</div>
@endsection

