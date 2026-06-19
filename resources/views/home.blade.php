@extends('layouts.app')

@section('title', 'BellevieShop')

@section('content')
<div style="max-width: 1120px; margin: 0 auto; padding: 2.5rem 1rem;">

    <section style="position: relative; overflow: hidden; border-radius: 36px; background: linear-gradient(135deg, rgba(249, 238, 243, 0.95) 0%, rgba(255, 255, 255, 0.98) 100%), url('https://images.unsplash.com/photo-1595777707802-61b6c3b60abd?w=1200&h=600&fit=crop') center/cover; padding: 4rem 2rem; margin-bottom: 2.5rem; box-shadow: 0 28px 80px rgba(38, 121, 70, 0.08); min-height: 400px; display: flex; align-items: center;">
        <div style="position: absolute; width: 220px; height: 220px; top: -40px; left: -40px; clip-path: polygon(0 0, 100% 0, 0 100%); background: rgba(57, 181, 121, 0.08);" ></div>
        <div style="position: absolute; width: 160px; height: 160px; bottom: -30px; right: 20px; clip-path: polygon(100% 0, 100% 100%, 0 100%); background: rgba(121, 209, 150, 0.08);"></div>
        <div style="position: absolute; width: 120px; height: 120px; top: 40%; right: -40px; clip-path: polygon(0 0, 100% 0, 100% 100%); background: rgba(0, 172, 117, 0.05);"></div>

        <div style="display: flex; gap: 2rem; align-items: center; position: relative; z-index: 1; max-width: 1080px;">
            <div style="flex: 1; max-width: 720px;">
                <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f9eef3; color: #a12d59; padding: 0.55rem 1rem; border-radius: 999px; font-weight: 700; margin-bottom: 1rem; font-size: 0.95rem;">
                    <i class="fas fa-tshirt"></i> BellevieShop
                </span>
                <h1 style="font-size: 3rem; line-height: 1.05; color: #1f2332; margin: 0 0 1rem; font-weight: 800;">
                    Bienvenue dans mon univers mode
                </h1>
                <p style="max-width: 660px; color: #545762; font-size: 1.05rem; line-height: 1.8; margin-bottom: 1.75rem;">
                    Explorez mes articles, découvrez les dernières tendances et échangez avec la communauté. Likez, commentez et commandez vos articles préférés en quelques clics.
                </p>
                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                    <span style="background: #fce8f1; color: #a12d59; padding: 0.65rem 1rem; border-radius: 999px; font-weight: 600;">#mode</span>
                    <span style="background: #fce8f1; color: #a12d59; padding: 0.65rem 1rem; border-radius: 999px; font-weight: 600;">#vetements</span>
                    <span style="background: #fce8f1; color: #a12d59; padding: 0.65rem 1rem; border-radius: 999px; font-weight: 600;">#style</span>
                    <span style="background: #fce8f1; color: #a12d59; padding: 0.65rem 1rem; border-radius: 999px; font-weight: 600;">#looks</span>
                </div>
            </div>
        </div>
    </section>

    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 2rem; margin: 0 0 0.35rem; color: #1f3f2c;">Articles récents</h2>
            <p style="margin: 0; color: #5b6c60;">Les clients peuvent liker ou commenter chaque publication.</p>
        </div>
        
        @auth
            <span style="background: #eef8f1; color: #2f7d4f; padding: 0.75rem 1rem; border-radius: 999px; font-weight: 700;">
                <i class="fas fa-check-circle"></i> Connecté en tant que {{ auth()->user()->name }}
            </span>
        @else
            <a href="{{ route('login') }}" style="background: #2f7d4f; color: white; padding: 0.85rem 1.3rem; border-radius: 999px; font-weight: 700; text-decoration: none;">
                <i class="fas fa-sign-in-alt"></i> Connexion pour interagir
            </a>
        @endauth
    </div>

    @forelse($posts as $post)
        <article style="position: relative; background: #ffffff; border-radius: 26px; box-shadow: 0 18px 40px rgba(34, 97, 62, 0.08); margin-bottom: 2rem; overflow: hidden; border: 1px solid #e6f1e8;">
            <div style="position:absolute; top: 1rem; right: 1rem; width: 0; height: 0; border-left: 32px solid transparent; border-bottom: 32px solid #84cc16; opacity: 0.85;"></div>
            <div style="padding: 1.6rem 1.6rem 0; display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid #f3faf4; background: #f7fcf7;">
                <img src="{{ ($post->user->avatar_url ?? 'https://ui-avatars.com/api/?name=U&background=74c69d&color=fff') }}?v={{ $post->user->updated_at->timestamp }}" style="width: 46px; height: 46px; border-radius: 50%; object-fit: cover; border: 2px solid #bdeacb;">
                <div>
                    <div style="font-size: 0.95rem; font-weight: 700; color: #1f4734;">{{ $post->user->name ?? 'Auteur' }}</div>
                    <div style="font-size: 0.82rem; color: #6b7c6f;">{{ $post->created_at->diffForHumans() }}</div>
                </div>
            </div>
            
            @php $postImages = $post->images ?? collect(); @endphp
            @if($post->image_url || $postImages->isNotEmpty())
                <div style="display: grid; grid-template-columns: repeat({{ min(($post->image_url ? 1 : 0) + $postImages->count(), 3) }}, 1fr); gap: 3px; overflow: hidden;">
                    @if($post->image_url)
                        <img src="{{ $post->image_url }}" style="width:100%; max-height:480px; object-fit:contain; background:#f7fbf8;">
                    @endif
                    @foreach($postImages->take(3 - ($post->image_url ? 1 : 0)) as $img)
                        <img src="{{ $img->url }}" style="width:100%; max-height:480px; object-fit:contain; background:#f7fbf8;">
                    @endforeach
                </div>
            @endif
            
            <div style="padding: 1.6rem;">
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                    <span style="background: #fbe8ef; color: #a12d59; padding: 0.45rem 0.85rem; border-radius: 999px; font-size: 0.82rem;">#mode</span>
                    <span style="background: #fbe8ef; color: #a12d59; padding: 0.45rem 0.85rem; border-radius: 999px; font-size: 0.82rem;">#vetements</span>
                    <span style="background: #fbe8ef; color: #a12d59; padding: 0.45rem 0.85rem; border-radius: 999px; font-size: 0.82rem;">#style</span>
                </div>
                <h3 style="margin: 0 0 1rem; font-size: 1.55rem; color: #1f3f2c;">{{ $post->title }}</h3>
                <p style="margin: 0 0 1.5rem; color: #516156; font-size: 1rem; line-height: 1.7;">{{ \Illuminate\Support\Str::limit($post->content, 220) }}</p>
                
                <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 1rem; color: #4f6f57; font-weight: 600;">
                        <span><i class="fas fa-heart"></i> <span class="like-count-{{ $post->id }}">{{ $post->likes ? $post->likes->count() : 0 }}</span></span>
                        <span><i class="fas fa-comments"></i> {{ $post->comments->count() }}</span>
                    </div>
                    <div style="display:flex; gap:0.75rem; flex-wrap: wrap;">
                        @auth
                            <button onclick="submitLike({{ $post->id }}, this)" style="border:none;background:#edf9ef;color:#1f5c38;padding:0.85rem 1rem;border-radius:14px;cursor:pointer;font-weight:700;">
                                <i class="fas fa-heart"></i> J'aime
                            </button>
                        @else
                            <a href="{{ route('login') }}" style="border:none;background:#edf9ef;color:#1f5c38;padding:0.85rem 1rem;border-radius:14px;font-weight:700;text-decoration:none;">
                                <i class="fas fa-heart"></i> Connexion pour liker
                            </a>
                        @endauth
                        <a href="{{ route('posts.show', $post) }}" style="background:#1f5c38;color:#fff;padding:0.85rem 1rem;border-radius:14px;font-weight:700;text-decoration:none;">
                            <i class="fas fa-comment"></i> Commenter
                        </a>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div style="text-align: center; padding: 4rem 2rem; background: #ffffff; border-radius: 24px; box-shadow: 0 18px 40px rgba(38, 121, 70, 0.08);">
            <p style="color: #5a6a5f; margin: 0; font-size: 1.05rem;">Aucun article disponible pour le moment.</p>
        </div>
    @endforelse
</div>

<script>
function submitLike(postId, el) {
    const button = el;
    const countSpan = document.querySelector('.like-count-' + postId);
    const currentCount = parseInt(countSpan.innerText || '0');
    
    button.style.background = '#d1fae5';
    button.style.color = '#065f46';
    button.disabled = true;
    
    fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (countSpan) countSpan.innerText = data.likes_count;
        button.style.background = '#1f5c38';
        button.style.color = '#fff';
        button.disabled = false;
    })
    .catch(error => {
        console.error('Erreur:', error);
        countSpan.innerText = currentCount + 1;
        button.style.background = '#1f5c38';
        button.style.color = '#fff';
        button.disabled = false;
    });
}
</script>

<footer style="background: linear-gradient(135deg, #1a3a2a 0%, #0f2318 100%); color: #b6d9c3; margin-top: 3rem; padding: 3rem 2rem 1.5rem;">
    <div style="max-width: 1120px; margin: 0 auto;">
        <div style="display: flex; flex-wrap: wrap; gap: 2.5rem; justify-content: space-between; margin-bottom: 2.5rem;">
            <div style="flex: 1; min-width: 220px; max-width: 340px;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.6rem; text-decoration: none; margin-bottom: 1rem;">
                    <i class="fas fa-shopping-bag" style="color: #4fbb87; font-size: 1.4rem;"></i>
                    <span style="font-size: 1.4rem; font-weight: 800; color: #ffffff;">BellevieShop</span>
                </a>
                <p style="margin: 0; font-size: 0.93rem; line-height: 1.7; color: #8ab59a;">
                    La boutique mode de HOUNTY — découvrez les dernières collections, looks et conseils style au quotidien.
                </p>
            </div>
            <div style="min-width: 150px;">
                <h4 style="margin: 0 0 1rem; color: #ffffff; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.06em;">Navigation</h4>
                <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.6rem;">
                    <li><a href="{{ route('home') }}" style="color: #8ab59a; text-decoration: none; font-size: 0.9rem;" onmouseover="this.style.color='#4fbb87'" onmouseout="this.style.color='#8ab59a'"><i class="fas fa-home" style="width:16px;"></i> Accueil</a></li>
                    @auth
                        <li><a href="{{ route('notifications.index') }}" style="color: #8ab59a; text-decoration: none; font-size: 0.9rem;" onmouseover="this.style.color='#4fbb87'" onmouseout="this.style.color='#8ab59a'"><i class="fas fa-bell" style="width:16px;"></i> Notifications</a></li>
                        <li><a href="{{ route('profile', auth()->user()->username) }}" style="color: #8ab59a; text-decoration: none; font-size: 0.9rem;" onmouseover="this.style.color='#4fbb87'" onmouseout="this.style.color='#8ab59a'"><i class="fas fa-user" style="width:16px;"></i> Mon profil</a></li>
                    @else
                        <li><a href="{{ route('login') }}" style="color: #8ab59a; text-decoration: none; font-size: 0.9rem;" onmouseover="this.style.color='#4fbb87'" onmouseout="this.style.color='#8ab59a'"><i class="fas fa-sign-in-alt" style="width:16px;"></i> Connexion</a></li>
                    @endauth
                </ul>
            </div>
            <div style="min-width: 150px;">
                <h4 style="margin: 0 0 1rem; color: #ffffff; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.06em;">Tendances</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="background: rgba(79,187,135,0.15); color: #4fbb87; padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600;">#mode</span>
                    <span style="background: rgba(79,187,135,0.15); color: #4fbb87; padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600;">#vetements</span>
                    <span style="background: rgba(79,187,135,0.15); color: #4fbb87; padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600;">#style</span>
                    <span style="background: rgba(79,187,135,0.15); color: #4fbb87; padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600;">#looks</span>
                    <span style="background: rgba(79,187,135,0.15); color: #4fbb87; padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600;">#tendances</span>
                </div>
            </div>
        </div>
        <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
            <p style="margin: 0; font-size: 0.84rem; color: #5a7a67;">&copy; {{ date('Y') }} BellevieShop — Tous droits réservés.</p>
            <p style="margin: 0; font-size: 0.84rem; color: #5a7a67;">Fait avec <i class="fas fa-heart" style="color: #ef4444;"></i> par HOUNTY Bignon</p>
        </div>
    </div>
</footer>
@endsection