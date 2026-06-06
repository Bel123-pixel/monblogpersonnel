@extends('layouts.app')

@section('title', 'Boutique - Bellevieshop')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes BellevieShopBadge {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .shop-card {
        animation: fadeInUp 0.6s ease backwards;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .shop-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
        border-color: #ff4757 !important;
    }
    .like-btn {
        transition: all 0.2s ease;
    }
    .like-btn:hover {
        color: #ff4757 !important;
        transform: scale(1.2);
    }
    .product-badge {
        animation: BellevieShopBadge 2s infinite ease-in-out;
    }
</style>

<div style="max-width: 650px; margin: 0 auto; padding: 2rem 1rem;">
    
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 2.2rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem; font-family: 'Poppins', sans-serif;">
            🛍️ <span style="background: linear-gradient(45deg, #ff4757, #ff6b81); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Bellevieshop</span>
        </h1>
        <p style="color: #64748b; font-size: 1.05rem; margin: 0;">Découvrez nos articles exclusifs en ligne au meilleur prix</p>
    </div>

    @forelse($posts as $key => $post)
        <div class="shop-card" style="background: #ffffff; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); margin-bottom: 2rem; border: 1px solid #e2e8f0; overflow: hidden; animation-delay: {{ $key * 0.1 }}s;">
            
            <div style="display: flex; align-items: center; padding: 1.25rem; background: #fafafa; border-bottom: 1px solid #f1f5f9;">
                <img src="{{ $post->user->avatar_url ?? 'https://via.placeholder.com/42' }}" alt="Avatar" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #ff6b81; margin-right: 0.85rem;">
                <div style="flex-grow: 1;">
                    <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: #1e293b;">
                        {{ $post->user->name }} <span style="color: #2ed573; font-size: 0.8rem; margin-left: 0.3rem;">● Certifié</span>
                    </h3>
                    <span style="font-size: 0.75rem; color: #94a3b8;">Posté {{ $post->created_at->diffForHumans() }}</span>
                </div>
                <span class="product-badge" style="background: #ff4757; color: white; padding: 0.4rem 0.8rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                    En Stock
                </span>
            </div>

            <div style="position: relative; width: 100%; background: #f8fafc; text-align: center; overflow: hidden;">
                @if($post->image_url)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width: 100%; max-height: 380px; object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                @else
                    <div style="padding: 4rem 2rem; color: #cbd5e1;">
                        <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #e2e8f0; margin-bottom: 1rem;"></i>
                    </div>
                @endif
            </div>

            <div style="padding: 1.5rem;">
                <h2 style="margin: 0 0 0.75rem 0; font-size: 1.4rem; font-weight: 800; color: #0f172a;">
                    {{ $post->title }}
                </h2>
                <p style="margin: 0; color: #475569; font-size: 0.98rem; line-height: 1.6;">
                    {{ $post->content }}
                </p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; background: #ffffff;">
                
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <button class="like-btn" style="background: none; border: none; cursor: pointer; color: #64748b; padding: 0;" onclick="toggleLike(this)">
                        <i class="far fa-heart" style="font-size: 1.4rem;"></i>
                    </button>
                    <span style="font-weight: 600; color: #1e293b; font-size: 0.95rem;"><span class="like-count">0</span> J'aime</span>
                </div>

                <a href="{{ route('posts.show', $post) }}" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 0.6rem 1.2rem; border-radius: 12px; text-decoration: none; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; transition: background 0.2s;">
                    <i class="fas fa-shopping-cart" style="font-size: 0.85rem;"></i> Voir les détails
                </a>

            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 4rem 2rem; background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0;">
            <p style="color: #64748b; margin: 0; font-size: 1.1rem;">Aucun article en vente pour le moment.</p>
        </div>
    @endforelse

</div>

<script>
function toggleLike(btn) {
    const icon = btn.querySelector('i');
    const countSpan = btn.nextElementSibling.querySelector('.like-count');
    let currentLikes = parseInt(countSpan.innerText);

    if (icon.classList.contains('far')) {
        // Liké !
        icon.classList.remove('far');
        icon.classList.add('fas');
        btn.style.color = '#ff4757';
        countSpan.innerText = currentLikes + 1;
    } else {
        // Unliké
        icon.classList.remove('fas');
        icon.classList.add('far');
        btn.style.color = '#64748b';
        countSpan.innerText = currentLikes - 1;
    }
}
</script>
@auth
    @if(auth()->user()->is_admin)
        <a href="{{ route('posts.create') }}" class="btn btn-blue">
            <i class="fas fa-plus"></i> Ajouter un nouvel article en vente
        </a>
    @endif
@endauth
@endsection

