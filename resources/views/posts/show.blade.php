@extends('layouts.app')
@section('title', $post->title)
@section('content')
<div class="post-single">

  <div class="post-single-kicker">
    <i class="fas fa-file-lines"></i> Article
  </div>
  <h1 class="post-single-title">{{ $post->title }}</h1>

  <div class="post-single-meta">
    <img src="{{ $post->user->avatar_url }}" alt="">
    <div class="meta-info">
      <strong><a href="{{ route('profile', $post->user->username) }}" style="color:inherit">{{ $post->user->name }}</a></strong>
      <span>{{ $post->created_at->format('d M Y') }} · {{ $post->views }} vues · {{ $post->comments->count() }} commentaire{{ $post->comments->count()>1?'s':'' }}</span>
    </div>
    @auth @if(auth()->id()===$post->user_id||auth()->user()->is_admin)
    <div class="post-single-actions">
      <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline btn-sm">
        <i class="fas fa-pen"></i> Modifier
      </a>
      <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline">
        @csrf 
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Supprimer définitivement ?">
          <i class="fas fa-trash"></i>
        </button>
      </form>
    </div>
    @endif @endauth
  </div>

  @if($post->image_url)
  <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="post-hero-img">
  @endif

  <div class="post-body">{!! nl2br(e($post->content)) !!}</div>

  {{-- SECTION COMMENTAIRES --}}
  <div class="comments-wrap">
    <h2 class="comments-title">
      <i class="fas fa-comments" style="color:var(--blue);margin-right:.4rem"></i>
      {{ $post->comments->count() }} commentaire{{ $post->comments->count()>1?'s':'' }}
    </h2>

    {{-- Formulaire d'ajout de commentaire principal --}}
    @auth
    <div class="comment-form-card">
      <form action="{{ route('comments.store', $post) }}" method="POST">
        @csrf
        <div style="display:flex;gap:.75rem">
          <img src="{{ auth()->user()->avatar_url }}" alt="" style="width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;margin-top:.2rem">
          <div style="flex:1">
            <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="3" placeholder="Écrivez un commentaire... @username pour mentionner" data-mentions data-max="1000" style="margin-bottom:.65rem">{{ old('body') }}</textarea>
            @error('body')<span class="invalid-feedback">{{ $message }}</span>@enderror
            <button type="submit" class="btn btn-blue btn-sm">
              <i class="fas fa-paper-plane"></i> Publier
            </button>
          </div>
        </div>
      </form>
    </div>
    @else
    <div style="text-align:center;padding:1.75rem;background:var(--blue-light);border-radius:var(--r);margin-bottom:1.5rem">
      <p style="color:var(--muted);margin-bottom:.85rem">Connectez-vous pour commenter</p>
      <a href="{{ route('login') }}" class="btn btn-blue btn-sm">Se connecter</a>
    </div>
    @endauth

    {{-- Liste des commentaires --}}
    <div>
      @forelse($post->comments as $comment)
      <div class="comment-item" id="c{{ $comment->id }}">
        <div class="comment-head">
          <img src="{{ $comment->user->avatar_url }}" alt="">
          <div>
            <div class="comment-author">
              <a href="{{ route('profile', $comment->user->username) }}" style="color:inherit">
                {{ $comment->user->name }}
              </a>
            </div>
            <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
          </div>
          @auth @if(auth()->id()===$comment->user_id||auth()->user()->is_admin||auth()->id()===$post->user_id)
          <div style="margin-left:auto;display:flex;gap:.3rem">
            @if(auth()->id()===$comment->user_id)
            <button class="btn btn-ghost btn-sm btn-icon-only edit-toggle" data-id="{{ $comment->id }}" data-type="comment" title="Modifier">
              <i class="fas fa-pen" style="font-size:.78rem"></i>
            </button>
            @endif
            <form action="{{ route('comments.destroy', $comment) }}" method="POST">
              @csrf 
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm btn-icon-only" data-confirm="Supprimer ce commentaire ?" title="Supprimer">
                <i class="fas fa-trash" style="font-size:.78rem"></i>
              </button>
            </form>
          </div>
          @endif @endauth
        </div>

        {{-- Contenu du commentaire --}}
        <div id="comment-disp-{{ $comment->id }}" class="comment-body">
          {!! $comment->formatted_body !!}
        </div>

        {{-- Formulaire d'ÉDITION de commentaire (CORRIGÉ) --}}
        @auth @if(auth()->id()===$comment->user_id)
        <div id="comment-edit-{{ $comment->id }}" class="comment-edit-form-block" style="display:none;margin-top:.65rem">
          <form action="{{ route('comments.update', $comment) }}" method="POST">
            @csrf 
            @method('PUT')
            <textarea name="body" class="form-control" rows="2" data-mentions style="margin-bottom:.5rem">{{ $comment->body }}</textarea>
            <div style="display:flex;gap:.4rem">
              <button type="submit" class="btn btn-blue btn-sm">Enregistrer</button>
              <button type="button" class="btn btn-ghost btn-sm edit-toggle" data-id="{{ $comment->id }}" data-type="comment">Annuler</button>
            </div>
          </form>
        </div>
        @endif @endauth

        <div class="comment-actions">
          @auth
          <button class="reply-btn" data-cid="{{ $comment->id }}">
            <i class="fas fa-reply"></i> Répondre
          </button>
          @endauth
          @if($comment->replies->count())
          <span style="font-size:.76rem;color:var(--muted2)">
            {{ $comment->replies->count() }} réponse{{ $comment->replies->count()>1?'s':'' }}
          </span>
          @endif
        </div>

        {{-- RÉPONSES --}}
        <div class="replies-block">
          @foreach($comment->replies as $reply)
          <div class="reply-item" id="r{{ $reply->id }}">
            <div class="comment-head" style="margin-bottom:.5rem">
              <img src="{{ $reply->user->avatar_url }}" alt="" style="width:28px;height:28px;border-radius:50%;object-fit:cover">
              <div>
                <div class="comment-author" style="font-size:.84rem">
                  <a href="{{ route('profile', $reply->user->username) }}" style="color:inherit">
                    {{ $reply->user->name }}
                  </a>
                </div>
                <div class="comment-time">{{ $reply->created_at->diffForHumans() }}</div>
              </div>
              @auth @if(auth()->id()===$reply->user_id||auth()->user()->is_admin||auth()->id()===$comment->user_id)
              <div style="margin-left:auto;display:flex;gap:.3rem">
                @if(auth()->id()===$reply->user_id)
                <button class="btn btn-ghost btn-sm btn-icon-only edit-toggle" data-id="{{ $reply->id }}" data-type="reply">
                  <i class="fas fa-pen" style="font-size:.76rem"></i>
                </button>
                @endif
                <form action="{{ route('replies.destroy', $reply) }}" method="POST">
                  @csrf 
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm btn-icon-only" data-confirm="Supprimer ?">
                    <i class="fas fa-trash" style="font-size:.76rem"></i>
                  </button>
                </form>
              </div>
              @endif @endauth
            </div>

            <div id="reply-disp-{{ $reply->id }}" class="comment-body" style="font-size:.9rem">
              {!! $reply->formatted_body !!}
            </div>

            {{-- Formulaire d'ÉDITION de réponse (CORRIGÉ) --}}
            @auth @if(auth()->id()===$reply->user_id)
            <div id="reply-edit-{{ $reply->id }}" class="reply-edit-form-block" style="display:none;margin-top:.5rem">
              <form action="{{ route('replies.update', $reply) }}" method="POST">
                @csrf 
                @method('PUT')
                <textarea name="body" class="form-control" rows="2" data-mentions style="margin-bottom:.4rem">{{ $reply->body }}</textarea>
                <div style="display:flex;gap:.4rem">
                  <button type="submit" class="btn btn-blue btn-sm">Enregistrer</button>
                  <button type="button" class="btn btn-ghost btn-sm edit-toggle" data-id="{{ $reply->id }}" data-type="reply">Annuler</button>
                </div>
              </form>
            </div>
            @endif @endauth
          </div>
          @endforeach

          {{-- Formulaire pour AJOUTER une réponse --}}
          @auth
          <div id="rf-{{ $comment->id }}" class="reply-form" style="display:none;">
            <form action="{{ route('replies.store', $comment) }}" method="POST">
              @csrf
              <div style="display:flex;gap:.6rem">
                <img src="{{ auth()->user()->avatar_url }}" alt="" style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;margin-top:.2rem">
                <div style="flex:1">
                  <textarea name="body" class="form-control" rows="2" placeholder="Répondre... @username pour mentionner" data-mentions style="margin-bottom:.45rem"></textarea>
                  <div style="display:flex;gap:.4rem">
                    <button type="submit" class="btn btn-blue btn-sm">
                      <i class="fas fa-paper-plane"></i> Répondre
                    </button>
                    <button type="button" class="btn btn-ghost btn-sm reply-btn" data-cid="{{ $comment->id }}">Annuler</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          @endauth
        </div>
      </div>
      @empty
      <div class="empty" style="padding:2rem">
        <i class="empty-icon fas fa-comment-slash"></i>
        <p>Aucun commentaire. Soyez le premier !</p>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection

