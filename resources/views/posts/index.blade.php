@extends('layouts.app')

@section('title', 'Accueil - Bellevieshop')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 1rem;">

    {{-- Boucle sur les publications --}}
    @forelse($posts as $post)
        <div class="post-card" style="background: #ffffff; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 1.5rem; border: 1px solid #f0f0f0; overflow: hidden;">
            
            {{-- En-tête de la publication (Auteur + Temps) --}}
            <div style="display: flex; align-items: center; padding: 1rem;">
                <img src="{{ $post->user->avatar_url }}" alt="Avatar" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; margin-right: 0.75rem; border: 2px solid #f0f2f5;">
                <div>
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #2d3748;">
                        <a href="{{ route('profile', $post->user->username) }}" style="color: inherit; text-decoration: none;">
                            {{ $post->user->name }}
                        </a>
                    </h3>
                    <span style="font-size: 0.78rem; color: #a0aec0;">
                        il y a {{ $post->created_at->diffForHumans(null, true) }} · 1 min
                    </span>
                </div>
            </div>

            {{-- Corps du texte / Titre --}}
            <div style="padding: 0 1rem 1rem 1rem;">
                <h2 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 800; color: #1a202c;">
                    <a href="{{ route('posts.show', $post) }}" style="color: inherit; text-decoration: none;">
                        {{ $post->title }}
                    </a>
                </h2>
                <p style="margin: 0; color: #4a5568; font-size: 0.95rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $post->content }}
                </p>
            </div>

            {{-- Image d'illustration (si elle existe) --}}
            @if($post->image_url)
                <div style="width: 100%; max-height: 350px; overflow: hidden; border-top: 1px solid #f7fafc; border-bottom: 1px solid #f7fafc;">
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width: 100%; height: auto; object-fit: cover;">
                </div>
            @endif

            {{-- Barre d'actions du bas (Likes, Commentaires, Vues) --}}
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1rem; border-top: 1px solid #edf2f7; background: #fafafa; font-size: 0.88rem; color: #718096;">
                
                {{-- Likes --}}
                <div style="display: flex; align-items: center; gap: 0.35rem; cursor: pointer;">
                    <i class="far fa-heart" style="font-size: 1.05rem;"></i>
                    <span>0</span>
                </div>

                {{-- Bouton Commenter qui amène sur l'article --}}
                <a href="{{ route('posts.show', $post) }}#comments" style="display: flex; align-items: center; gap: 0.35rem; color: inherit; text-decoration: none;">
                    <i class="far fa-comment" style="font-size: 1.05rem;"></i>
                    <span>Commenter</span>
                </a>

                {{-- Compteur de Vues --}}
                <div style="display: flex; align-items: center; gap: 0.35rem;">
                    <i class="far fa-eye" style="font-size: 1.05rem;"></i>
                    <span>Lire · {{ $post->views ?? 0 }} vue{{ ($post->views ?? 0) > 1 ? 's' : '' }}</span>
                </div>

            </div>
        </div>
    @empty
        {{-- Si aucun article n'existe encore --}}
        <div style="text-align: center; padding: 3rem 1rem; background: #ffffff; border-radius: 16px; border: 1px solid #edf2f7;">
            <i class="fas fa-feather-alt" style="font-size: 2.5rem; color: #cbd5e0; margin-bottom: 1rem;"></i>
            <p style="color: #718096; margin: 0;">Aucune publication pour le moment. Soyez le premier à publier !</p>
        </div>
    @endforelse

</div>
@endsection

