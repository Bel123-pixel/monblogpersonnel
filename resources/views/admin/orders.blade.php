@extends('layouts.app')
@section('title', 'Commandes — Admin')
@section('content')
<div class="admin-shell">
  @include('admin._sidebar')
  <div class="admin-main">
    <h1 class="admin-h1">📦 Commandes reçues</h1>

    @if(session('success'))
        <div style="background:#d1fae5; color:#065f46; padding:1rem 1.25rem; border-radius:12px; margin-bottom:1.5rem; border:1px solid #6ee7b7; font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @forelse($orders as $order)
        <div style="background:white; border:1px solid #e5e7eb; border-radius:16px; padding:1.5rem; margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
            <div style="display:flex; justify-content:space-between; align-items:start; gap:1rem; flex-wrap:wrap; margin-bottom:1rem;">
                <div>
                    <h3 style="margin:0 0 0.5rem; color:#1f3f2c;">
                        {{ $order->post?->title ?? '(Publication supprimée)' }}
                    </h3>
                    <p style="margin:0; color:#6b7280; font-size:0.9rem;">
                        <strong>Client:</strong>
                        {{ $order->user?->name ?? '(Utilisateur supprimé)' }}
                        @if($order->user) ({{ $order->user->email }}) @endif
                    </p>
                    <p style="margin:0.25rem 0 0; color:#6b7280; font-size:0.9rem;">
                        <strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <span style="
                    background: {{ $order->status === 'pending' ? '#fef3c7' : ($order->status === 'confirmed' ? '#dbeafe' : ($order->status === 'shipped' ? '#ddd6fe' : '#d1fae5')) }};
                    color: {{ $order->status === 'pending' ? '#92400e' : ($order->status === 'confirmed' ? '#0c4a6e' : ($order->status === 'shipped' ? '#4c1d95' : '#065f46')) }};
                    padding:0.5rem 0.75rem; border-radius:999px; font-weight:600; font-size:0.85rem; white-space:nowrap;">
                    @if($order->status === 'pending') ⏳ En attente
                    @elseif($order->status === 'confirmed') ✅ Confirmée
                    @elseif($order->status === 'shipped') 📦 Expédiée
                    @else ✔️ Livrée @endif
                </span>
            </div>

            @if($order->message)
                <div style="background:#f9fafb; padding:1rem; border-radius:12px; margin-bottom:1rem; border-left:3px solid #2f7d4f;">
                    <p style="margin:0; color:#374151; font-size:0.95rem;"><strong>Message:</strong> {{ $order->message }}</p>
                </div>
            @endif

            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                @if($order->status !== 'delivered')
                <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" style="background:#2f7d4f; color:white; padding:0.65rem 1rem; border:none; border-radius:8px; font-weight:600; cursor:pointer; font-size:0.9rem;">
                        @if($order->status === 'pending') ✅ Confirmer
                        @elseif($order->status === 'confirmed') 📦 Marquer expédiée
                        @else ✔️ Marquer livrée @endif
                    </button>
                </form>
                @endif
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Supprimer cette commande ?')"
                        style="background:#fecaca; color:#991b1b; padding:0.65rem 1rem; border:none; border-radius:8px; font-weight:600; cursor:pointer; font-size:0.9rem;">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div style="text-align:center; padding:3rem; background:#f9fafb; border-radius:16px; border:1px solid #e5e7eb;">
            <p style="color:#6b7280; font-size:1.05rem; margin:0;">Aucune commande pour le moment.</p>
        </div>
    @endforelse

    <div>{{ $orders->links() }}</div>
  </div>
</div>
@endsection
