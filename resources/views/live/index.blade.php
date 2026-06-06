@extends('layouts.app')
@section('title', 'Lives — BellevieShop')
@section('content')
<div class="wrap">
  <div class="page-top">
    <div>
      <h1>🔴 Lives</h1>
      <p style="color:var(--muted);font-size:.88rem;margin-top:.2rem">
        Diffusions en direct de la communauté
      </p>
    </div>
    @auth
    <a href="{{ route('live.create') }}" class="btn btn-red">
      <i class="fas fa-video"></i> Démarrer un live
    </a>
    @endauth
  </div>

  @if($liveStreams->isNotEmpty())
  <div style="margin-bottom:2.5rem">
    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1.25rem">
      <span class="live-dot"></span>
      <span style="font-weight:700;color:var(--danger)">
        {{ $liveStreams->total() }} en direct
      </span>
    </div>
    <div class="lives-grid">
      @foreach($liveStreams as $live)
      <div class="live-card" style="animation-delay:{{ $loop->index * 60 }}ms">
        <div class="live-thumb">
          @if($live->thumbnail)
            <img src="{{ asset('storage/lives/'.$live->thumbnail) }}" alt="{{ $live->title }}">
          @else
            <i class="live-thumb-icon fas fa-play-circle"></i>
          @endif
          <span class="live-badge-live"><span class="live-dot" style="width:5px;height:5px"></span> LIVE</span>
        </div>
        <div class="live-body">
          <div class="live-title">{{ $live->title }}</div>
          @if($live->description)
          <p style="font-size:.8rem;color:var(--muted);margin-bottom:.55rem">
            {{ Str::limit($live->description, 70) }}
          </p>
          @endif
          <div class="live-meta">
            <span>
              <img src="{{ $live->user->avatar_url }}" alt=""
                style="width:18px;height:18px;border-radius:50%;vertical-align:middle;margin-right:.3rem">
              {{ $live->user->name }}
            </span>
            <span><i class="fas fa-clock"></i> {{ $live->started_at?->diffForHumans() ?? 'À l\'instant' }}</span>
          </div>
          <div style="margin-top:.85rem;display:flex;gap:.5rem">
            <a href="{{ route('live.show', $live) }}" class="btn btn-red btn-sm" style="flex:1;justify-content:center">
              <i class="fas fa-play"></i> Rejoindre
            </a>
            @auth @if(auth()->id()===$live->user_id||auth()->user()->is_admin)
            <form action="{{ route('live.end', $live) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-ghost btn-sm" data-confirm="Terminer ce live ?">
                <i class="fas fa-stop"></i>
              </button>
            </form>
            @endif @endauth
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div style="margin-top:1.5rem">{{ $liveStreams->links() }}</div>
  </div>
  @else
  <div class="empty" style="margin-bottom:2.5rem">
    <i class="empty-icon fas fa-video-slash"></i>
    <h3>Aucun live en cours</h3>
    @auth
    <a href="{{ route('live.create') }}" class="btn btn-blue mt-2">
      <i class="fas fa-video"></i> Soyez le premier !
    </a>
    @endauth
  </div>
  @endif

  @if($endedStreams->isNotEmpty())
  <div>
    <h2 style="font-size:1.05rem;font-weight:700;color:var(--muted);margin-bottom:1.15rem">
      <i class="fas fa-history"></i> Lives récents
    </h2>
    <div class="lives-grid">
      @foreach($endedStreams as $live)
      <div class="live-card" style="opacity:.65">
        <div class="live-thumb">
          @if($live->thumbnail)
            <img src="{{ asset('storage/lives/'.$live->thumbnail) }}" alt="">
          @else
            <i class="live-thumb-icon fas fa-video" style="font-size:2rem"></i>
          @endif
          <span class="live-badge-ended">TERMINÉ</span>
        </div>
        <div class="live-body">
          <div class="live-title">{{ $live->title }}</div>
          <div class="live-meta">
            <span>{{ $live->user->name }}</span>
            <span>{{ $live->ended_at?->diffForHumans() }}</span>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif
</div>
@endsection

