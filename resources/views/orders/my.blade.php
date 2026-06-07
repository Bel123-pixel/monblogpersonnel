@extends('layouts.app')

@section('title', 'Mes commandes')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 2.5rem 1rem;">
    <h1 style="font-size: 2rem; color: #1f3f2c; margin-bottom: 2rem;">📦 Commandes reçues</h1>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #6ee7b7;">
            {{ session('success') }}
        </div>
    @endif

    @forelse($orders as $order)
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
                <div>
                    <h3 style="margin: 0 0 0.5rem; color: #1f3f2c;">{{ optional($order->post)->title ?? 'Produit supprimé' }}</h3>
                    <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">
                        <strong>Client:</strong> {{ optional($order->user)->name ?? 'Client supprimé' }} ({{ optional($order->user)->email ?? '—' }})
                    </p>
                    <p style="margin: 0.25rem 0 0; color: #6b7280; font-size: 0.9rem;">
                        <strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <span style="background: 
                    @if($order->status === 'pending') #fef3c7
                    @elseif($order->status === 'confirmed') #dbeafe
                    @elseif($order->status === 'shipped') #ddd6fe
                    @else #d1fae5 @endif;
                    color:
                    @if($order->status === 'pending') #92400e
                    @elseif($order->status === 'confirmed') #0c4a6e
                    @elseif($order->status === 'shipped') #4c1d95
                    @else #065f46 @endif;
                    padding: 0.5rem 0.75rem; border-radius: 999px; font-weight: 600; font-size: 0.85rem;">
                    @if($order->status === 'pending') ⏳ En attente
                    @elseif($order->status === 'confirmed') ✅ Confirmée
                    @elseif($order->status === 'shipped') 📦 Expédiée
                    @else ✔️ Livrée @endif
                </span>
            </div>

            @if($order->message)
                <div style="background: #f9fafb; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; border-left: 3px solid #2f7d4f;">
                    <p style="margin: 0; color: #374151; font-size: 0.95rem;"><strong>Message:</strong> {{ $order->message }}</p>
                </div>
            @endif

            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                @if($order->post)
                    <a href="{{ route('posts.show', $order->post) }}" style="background: #1f5c38; color: white; padding: 0.75rem 1rem; border-radius: 10px; text-decoration: none; font-weight: 700;">Voir le produit</a>
                @else
                    <span style="background: #f3f4f6; color: #4b5563; padding: 0.75rem 1rem; border-radius: 10px; font-weight: 700;">Produit supprimé</span>
                @endif
                <span style="background: #eef8ef; color: #065f46; padding: 0.75rem 1rem; border-radius: 10px; font-weight: 700;">Statut : {{ ucfirst($order->status) }}</span>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 3rem; background: #f9fafb; border-radius: 16px;">
            <p style="color: #6b7280; font-size: 1.05rem;">Aucune commande reçue pour vos produits.</p>
        </div>
    @endforelse
</div>
@endsection
