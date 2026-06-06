@extends('layouts.app')
@section('title', $liveStream->title . ' — Live')
@section('content')
<div class="wrap-md">
  <div style="display:flex;align-items:center;gap:.85rem;margin-bottom:1.5rem;flex-wrap:wrap">
    <a href="{{ route('live.index') }}" class="btn btn-ghost btn-sm">
      <i class="fas fa-arrow-left"></i> Retour
    </a>
    @if($liveStream->isLive())
      <span class="badge badge-red" style="display:flex;align-items:center;gap:.35rem">
        <span class="live-dot" style="width:6px;height:6px"></span> EN DIRECT
      </span>
    @else
      <span class="badge badge-gray">TERMINÉ</span>
    @endif
  </div>

  <div style="background:#000;border-radius:var(--r-lg);overflow:hidden;aspect-ratio:16/9;margin-bottom:1.5rem;box-shadow:var(--shadow-lg)">
    @if($liveStream->isLive())
      <iframe src="{{ $liveStream->youtube_embed_url }}"
        width="100%" height="100%" frameborder="0"
        allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
        allowfullscreen style="display:block"></iframe>
    @else
      <div style="height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;color:rgba(255,255,255,.35)">
        <i class="fas fa-video-slash" style="font-size:2.5rem;margin-bottom:.75rem"></i>
        <p>Ce live est terminé.</p>
      </div>
    @endif
  </div>

  <div class="form-card">
    <h1 style="font-family:'Syne',sans-serif;font-size:1.45rem;font-weight:800;margin-bottom:.75rem">
      {{ $liveStream->title }}
    </h1>
    @if($liveStream->description)
    <p style="color:var(--muted);margin-bottom:1rem">{{ $liveStream->description }}</p>
    @endif
    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;font-size:.86rem;color:var(--muted)">
      <span style="display:flex;align-items:center;gap:.5rem">
        <img src="{{ $liveStream->user->avatar_url }}" alt=""
          style="width:26px;height:26px;border-radius:50%;object-fit:cover">
        <a href="{{ route('profile', $liveStream->user->username) }}"
          style="font-weight:600;color:var(--ink2)">{{ $liveStream->user->name }}</a>
      </span>
      @if($liveStream->started_at)
      <span><i class="fas fa-clock"></i> Démarré {{ $liveStream->started_at->diffForHumans() }}</span>
      @endif
    </div>
    @auth @if(auth()->id()===$liveStream->user_id||auth()->user()->is_admin)
    <div style="margin-top:1.15rem;padding-top:1rem;border-top:1px solid var(--border);display:flex;gap:.65rem">
      @if($liveStream->isLive())
      <form action="{{ route('live.end', $liveStream) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Terminer le live ?">
          <i class="fas fa-stop-circle"></i> Terminer
        </button>
      </form>
      @endif
      <form action="{{ route('live.destroy', $liveStream) }}" method="POST">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Supprimer définitivement ?">
          <i class="fas fa-trash"></i> Supprimer
        </button>
      </form>
    </div>
    @endif @endauth
  </div>
</div>
@endsection

