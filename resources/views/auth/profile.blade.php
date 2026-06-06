@extends('layouts.app')
@section('title', $user->name . ' — BellevieShop')
@section('content')

<div class="profile-hero">
  <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar">
  <h1 class="profile-name">{{ $user->name }}</h1>
  <p class="profile-username">@{{ $user->username }}</p>
  @if($user->bio)<p class="profile-bio mt-1">{{ $user->bio }}</p>@endif

  <div style="display:flex;gap:.6rem;justify-content:center;flex-wrap:wrap;margin-top:1.1rem;position:relative;z-index:1">
    <span style="background:rgba(255,255,255,.15);padding:.28rem .85rem;border-radius:999px;font-size:.82rem">
      <i class="fas fa-file-alt"></i> {{ $posts->total() }} publication{{ $posts->total()>1?'s':'' }}
    </span>
    <span style="background:rgba(255,255,255,.15);padding:.28rem .85rem;border-radius:999px;font-size:.82rem">
      <i class="fas fa-calendar"></i> Membre depuis {{ $user->created_at->format('M Y') }}
    </span>
    @if($user->is_admin)
    <span style="background:rgba(255,215,0,.25);padding:.28rem .85rem;border-radius:999px;font-size:.82rem">
      <i class="fas fa-shield-halved"></i> Admin
    </span>
    @endif
  </div>

  @auth @if(auth()->id()===$user->id)
  <div style="margin-top:1.25rem;position:relative;z-index:1">
    <a href="{{ route('profile.edit') }}" class="btn btn-ghost"
      style="background:rgba(255,255,255,.15);color:#fff;border-color:rgba(255,255,255,.3)">
      <i class="fas fa-sliders"></i> Modifier le profil
    </a>
  </div>
  @endif @endauth
</div>

<div class="wrap">
  <div class="page-top">
    <h1>Publications</h1>
    @auth @if(auth()->id()===$user->id)
    <a href="{{ route('posts.create') }}" class="btn btn-blue btn-sm">
      <i class="fas fa-plus"></i> Nouvel article
    </a>
    @endif @endauth
  </div>

  @if($posts->isEmpty())
  <div class="empty">
    <i class="empty-icon fas fa-pen-to-square"></i>
    <h3>Aucune publication</h3>
    <p>{{ auth()->id()===$user->id ? 'Commencez à écrire !' : $user->name . " n'a pas encore publié." }}</p>
  </div>
  @else
  <div class="posts-grid">
    @foreach($posts as $post)
    <div class="post-card">
      @if($post->image_url)
        <div class="post-card-cover"><img src="{{ $post->image_url }}" alt="{{ $post->title }}"></div>
      @else
        <div class="post-card-cover-placeholder"><i class="fas fa-feather-pointed"></i></div>
      @endif
      <div class="post-card-body">
        <h3 class="post-card-title">
          <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
        </h3>
        <p class="post-card-excerpt">{{ $post->excerpt }}</p>
        <div class="post-card-footer">
          <span>{{ $post->created_at->diffForHumans() }}</span>
          <div class="post-card-stats">
            <span><i class="fas fa-eye"></i> {{ $post->views }}</span>
            <span><i class="fas fa-comment"></i> {{ $post->comments->count() }}</span>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div>{{ $posts->links() }}</div>
  @endif
</div>
@endsection

