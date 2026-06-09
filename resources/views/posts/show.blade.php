@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div style="max-width: 760px; margin: 2.5rem auto; padding: 0 1rem;">
    <a href="{{ route('home') }}" style="text-decoration: none; color: #4f6d58; font-weight: 600; display: inline-block; margin-bottom: 1.5rem;">
        <i class="fas fa-arrow-left"></i> Retour aux articles
    </a>

    <article style="background: #ffffff; border-radius: 28px; box-shadow: 0 24px 70px rgba(31, 83, 58, 0.08); overflow: hidden; border: 1px solid #e7f3ea; margin-bottom: 2rem;">
        <div style="padding: 1.6rem 1.6rem 1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; background: #f5fbf6; border-bottom: 1px solid #e8f4e9;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="{{ $post->user->avatar_url ?? 'https://ui-avatars.com/api/?name=U&background=74c69d&color=fff' }}" style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid #c8edcd;">
                <div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1f4033;">{{ $post->user->name ?? 'Auteur' }}</div>
                    <div style="font-size: 0.88rem; color: #6b7d70;">{{ $post->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <div style="display:flex; gap:0.65rem; align-items:center; flex-wrap:wrap;">
                @auth
                    @if(!auth()->user()->is_admin)
                        <button onclick="openOrderModal('{{ $post->slug }}')" style="background: #2f7d4f; color: white; border: none; padding: 0.85rem 1.25rem; border-radius: 999px; font-weight: 700; cursor: pointer;">
                            🛒 Commander
                        </button>
                    @endif
                @else
                    <button onclick="openOrderModal('{{ $post->slug }}')" style="background: #2f7d4f; color: white; border: none; padding: 0.85rem 1.25rem; border-radius: 999px; font-weight: 700; cursor: pointer;">
                        🛒 Commander
                    </button>
                @endauth
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('posts.edit', $post) }}" style="background: #f3f4f6; color: #1f2937; border: none; padding: 0.85rem 1rem; border-radius: 999px; font-weight: 700; text-decoration: none;">✏️ Modifier</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer cette publication ?')" style="background: #fee2e2; color: #991b1b; border: none; padding: 0.85rem 1rem; border-radius: 999px; font-weight: 700; cursor: pointer;">🗑️ Supprimer</button>
                        </form>
                    @endif
                @endauth
            </div>

            <div id="orderModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                <div style="background:white; padding:2rem; border-radius:16px; max-width:500px; width:90%; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
                    <h3 style="margin-top:0; color:#1f2937;">Passer une commande</h3>
                    <form id="orderForm" method="POST" action="">
                        @csrf
                        <div style="margin-bottom:1.5rem;">
                            <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#374151;">Message ou détails (optionnel)</label>
                            <textarea name="message" rows="4" placeholder="Ex: Taille M, couleur noire..." style="width:100%; padding:0.75rem; border:1px solid #d1d5db; border-radius:8px; font-family:inherit;"></textarea>
                        </div>
                        <div style="display:flex; gap:0.75rem;">
                            <button type="submit" style="flex:1; background:#2f7d4f; color:white; padding:0.85rem; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Envoyer la commande</button>
                            <button type="button" onclick="closeOrderModal()" style="flex:1; background:#e5e7eb; color:#1f2937; padding:0.85rem; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function openOrderModal(postId) {
                    @auth
                        document.getElementById('orderForm').action = '/posts/' + postId + '/order';
                        document.getElementById('orderModal').style.display = 'flex';
                    @else
                        window.location.href = '{{ route("login") }}';
                    @endauth
                }
                function closeOrderModal() {
                    document.getElementById('orderModal').style.display = 'none';
                }
            </script>
        </div>

        @if($post->image_url)
            <div style="overflow: hidden;">
                <img src="{{ $post->image_url }}" style="width: 100%; max-height: 420px; object-fit: cover;">
            </div>
        @endif

        <div style="padding: 1.8rem 1.6rem 2rem;">
            <h1 style="font-size: 2.4rem; margin: 0 0 1rem; color: #1f3f2c;">{{ $post->title }}</h1>
            <p style="color: #505f54; font-size: 1.05rem; line-height: 1.8; margin-bottom: 1.75rem;">{{ $post->content }}</p>
            <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                <span style="background: #fbe8ef; color: #a12d59; padding: 0.65rem 1rem; border-radius: 999px; font-size: 0.9rem;">#mode</span>
                <span style="background: #fbe8ef; color: #a12d59; padding: 0.65rem 1rem; border-radius: 999px; font-size: 0.9rem;">#vetements</span>
                <span style="background: #fbe8ef; color: #a12d59; padding: 0.65rem 1rem; border-radius: 999px; font-size: 0.9rem;">#style</span>
            </div>
            @if($post->images->isNotEmpty())
                <div style="margin-top:1.25rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                    @foreach($post->images as $img)
                        <div style="width:calc(33% - 0.5rem); background:#fafafa; border-radius:12px; overflow:hidden; border:1px solid #eef6ee;">
                            <img src="{{ $img->url }}" style="width:100%; height:140px; object-fit:cover; display:block;">
                            @if($img->caption)
                                <div style="padding:0.45rem 0.6rem; font-size:0.85rem; color:#495a4f;">{{ $img->caption }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </article>

    <section style="background: #ffffff; border-radius: 28px; padding: 2rem; border: 1px solid #e7f3ea; box-shadow: 0 18px 45px rgba(44, 103, 64, 0.06);">
        <h3 style="margin-top: 0; color: #1f4334; margin-bottom: 1.5rem; font-size: 1.4rem;">Discussions</h3>

        @auth
            <form method="POST" action="{{ route('comments.store', $post) }}" style="margin-bottom: 2rem;">
                @csrf
                <div style="display: flex; gap: 0.85rem; flex-wrap: wrap; position: relative;">
                    <div style="flex: 1; min-width: 220px; position: relative;">
                        <textarea name="content" id="comment-area" rows="3"
                            placeholder="Écrivez un commentaire... Tapez @ pour mentionner quelqu'un"
                            style="width: 100%; padding: 1rem; border-radius: 18px; border: 1px solid #d9e9da; font-family: inherit; font-size: 1rem; box-sizing: border-box;"
                            required></textarea>
                        <!-- Liste d'autocomplétion -->
                        <div id="mention-list" style="display:none; position:absolute; bottom: calc(100% + 4px); left:0; background:#fff; border:1px solid #d1d5db; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,0.12); z-index:999; min-width:220px; overflow:hidden;"></div>
                    </div>
                    <button type="submit" style="background: #2f7d4f; color: white; border: none; padding: 0.95rem 1.35rem; border-radius: 18px; font-weight: 700; cursor: pointer; align-self: flex-start;">Envoyer</button>
                </div>
            </form>
        @else
            <div style="margin-bottom: 2rem; padding: 1.4rem 1.2rem; background: #f0fbef; border-radius: 20px; border: 1px solid #d9eed8; color: #3b5947;">
                <p style="margin: 0 0 0.75rem; font-weight: 700;">Connectez-vous pour commenter et participer.</p>
                <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #2f7d4f; color: white; padding: 0.85rem 1rem; border-radius: 16px; text-decoration: none; font-weight: 700;">Connexion</a>
            </div>
        @endauth

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($post->comments as $comment)
                <div style="background: #f4fbf6; padding: 1.2rem 1.3rem; border-radius: 18px; border: 1px solid #e3f2e7;">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; margin-bottom: 0.65rem; align-items: flex-start;">
                        <div>
                            <div style="font-weight: 700; color: #1f4334;">{{ $comment->user->name ?? 'Client' }}</div>
                            <div style="font-size: 0.84rem; color: #6c7a6d;">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'À l’instant' }}</div>
                        </div>
                        @if($comment->user_id == 1 || ($comment->user?->is_admin ?? false))
                            <span style="background: #2f7d4f; color: #fff; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.78rem;">Vendeur</span>
                        @endif
                    </div>
                    <p style="margin: 0 0 0.85rem; color: #425146; line-height: 1.7;">{!! $comment->formatted_body !!}</p>
                    <div style="text-align: right;">
                        <a href="#" onclick="event.preventDefault(); document.getElementById('comment-area').value = '@{{ $comment->user->name }} '; document.getElementById('comment-area').focus();" style="color: #2f7d4f; font-size: 0.9rem; font-weight: 700; text-decoration: none;">Répondre</a>
                    </div>
                </div>
            @empty
                <p style="color: #6b7b6f; text-align: center; margin: 0;">Aucun commentaire pour l'instant. Soyez le premier à donner votre avis.</p>
            @endforelse
        </div>
    </section>
</div>

<style>
.mention-link {
    color: #2f7d4f;
    font-weight: 700;
    text-decoration: none;
    background: #edf9ef;
    padding: 0 4px;
    border-radius: 4px;
}
.mention-link:hover { text-decoration: underline; }
.mention-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.6rem 1rem;
    cursor: pointer;
    font-size: 0.9rem;
    color: #1f3f2c;
    border-bottom: 1px solid #f0f5ef;
}
.mention-item:hover { background: #f0fbf1; }
.mention-item img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
</style>

<script>
(function () {
    const textarea   = document.getElementById('comment-area');
    const list       = document.getElementById('mention-list');
    if (!textarea || !list) return;

    let mentionStart = -1;

    textarea.addEventListener('input', function () {
        const val    = textarea.value;
        const cursor = textarea.selectionStart;

        // Chercher le dernier @ avant le curseur
        const before = val.substring(0, cursor);
        const atIdx  = before.lastIndexOf('@');

        if (atIdx === -1) { hideMentions(); return; }

        const query = before.substring(atIdx + 1);

        // Pas d'espace dans la query
        if (/\s/.test(query)) { hideMentions(); return; }

        mentionStart = atIdx;

        if (query.length === 0) { hideMentions(); return; }

        fetch(`{{ route('mention.search') }}?q=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(users => {
                if (!users.length) { hideMentions(); return; }
                list.innerHTML = users.map(u =>
                    `<div class="mention-item" data-username="${u.username}">
                        <img src="${u.avatar_url}" alt="">
                        <div>
                            <strong>${u.name}</strong>
                            <span style="color:#6b7c6f; font-size:0.8rem;"> @${u.username}</span>
                        </div>
                    </div>`
                ).join('');
                list.style.display = 'block';

                list.querySelectorAll('.mention-item').forEach(item => {
                    item.addEventListener('mousedown', function (e) {
                        e.preventDefault();
                        const username = this.dataset.username;
                        const val      = textarea.value;
                        const before   = val.substring(0, mentionStart);
                        const after    = val.substring(textarea.selectionStart);
                        textarea.value = before + '@' + username + ' ' + after;
                        textarea.focus();
                        hideMentions();
                    });
                });
            });
    });

    textarea.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') hideMentions();
    });

    document.addEventListener('click', function (e) {
        if (!list.contains(e.target) && e.target !== textarea) hideMentions();
    });

    function hideMentions() {
        list.style.display = 'none';
        list.innerHTML = '';
        mentionStart = -1;
    }
})();
</script>
@endsection