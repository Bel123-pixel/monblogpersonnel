@extends('layouts.app')
@section('title', 'Notifications — BellevieShop')
@section('content')
<div style="max-width: 900px; margin: 2.5rem auto; padding: 0 1rem;">
  <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem; align-items: center; margin-bottom: 2rem;">
    <div>
      <h1 style="margin: 0; font-size: 2rem; color: #1f4334;">Notifications</h1>
      <p style="margin: 0.5rem 0 0; color: #577063;">Recevez un signal quand un client commente ou qu’un plan vous mentionne.</p>
    </div>
    @if(auth()->user()->unreadNotifications->count())
      <form action="{{ route('notifications.markAllRead') }}" method="POST">
        @csrf
        <button type="submit" style="background: #2f7d4f; color: white; border: none; padding: 0.85rem 1rem; border-radius: 999px; font-weight: 700; cursor: pointer;">Tout marquer comme lu</button>
      </form>
    @endif
  </div>

  @if($notifications->isEmpty())
    <div style="background: #f5fbf6; border-radius: 24px; padding: 3rem; text-align: center; border: 1px solid #e3f2e7;">
      <i class="fas fa-bell-slash" style="font-size: 2.5rem; color: #6b7c70;"></i>
      <h3 style="margin: 1rem 0 0.5rem; color: #1f4334;">Aucune notification</h3>
      <p style="color: #5c6f63;">Tout est à jour. Les nouveaux commentaires arriveront ici.</p>
    </div>
  @else
    <div style="display: grid; gap: 1rem;">
      @foreach($notifications as $notif)
      @php
        $d = $notif->data;
        $type = $d['type'] ?? 'comment';
        $icon = $type === 'mention' ? 'fas fa-at' : ($type === 'reply' ? 'fas fa-reply' : 'fas fa-comment');
      @endphp
      <div style="display:flex; gap: 1rem; align-items: flex-start; background: {{ $notif->read_at ? '#ffffff' : '#edf8ef' }}; border: 1px solid #e3f2e7; border-radius: 22px; padding: 1rem 1rem 1.1rem;">
        <div style="width: 46px; height: 46px; border-radius: 50%; background: #dff3e6; display: grid; place-items: center; color: #2f7d4f; font-size: 1.1rem;"><i class="{{ $icon }}"></i></div>
        <div style="flex: 1;">
          <p style="margin: 0; font-weight: 700; color: #1f4334;">{{ $d['message'] ?? '' }}</p>
          @if(!empty($d['content']))
            <p style="margin: 0.55rem 0 0; color: #53645a;">"{{ $d['content'] }}"</p>
          @endif
          <p style="margin: 0.7rem 0 0; color: #6d7f74; font-size: 0.9rem;">{{ $notif->created_at->diffForHumans() }}</p>
        </div>
        <div style="display: grid; gap: 0.45rem; justify-items: end;">
          @if(!$notif->read_at)
          <a href="{{ route('notifications.read', $notif->id) }}" style="background: #edf8ef; color: #2f7d4f; padding: 0.55rem 0.8rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; text-decoration: none;">Marquer lu</a>
          @endif
          @if(!empty($d['url']))
          <a href="{{ $d['url'] }}" style="background: #f7fdf7; color: #23674a; padding: 0.55rem 0.8rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; text-decoration: none;">Voir</a>
          @endif
          <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #fee2e2; color: #b91c1c; border: none; padding: 0.55rem 0.8rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; cursor: pointer;">Supprimer</button>
          </form>
        </div>
      </div>
      @endforeach
      <div style="margin-top: 1rem;">{{ $notifications->links() }}</div>
    </div>
  @endif
</div>
@endsection

