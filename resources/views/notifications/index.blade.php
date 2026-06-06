@extends('layouts.app')
@section('title', 'Notifications — BellevieShop')
@section('content')
<div class="wrap-md">
  <div class="page-top">
    <h1>
      Notifications
      @if(auth()->user()->unreadNotifications->count())
        <span class="badge badge-red" style="vertical-align:middle;font-size:.65rem">
          {{ auth()->user()->unreadNotifications->count() }} nouvelles
        </span>
      @endif
    </h1>
    @if($notifications->isNotEmpty())
    <form action="{{ route('notifications.markAllRead') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-outline btn-sm">
        <i class="fas fa-check-double"></i> Tout marquer comme lu
      </button>
    </form>
    @endif
  </div>

  @if($notifications->isEmpty())
  <div class="empty">
    <i class="empty-icon fas fa-bell-slash"></i>
    <h3>Aucune notification</h3>
    <p>Vous êtes à jour ! Les nouvelles apparaîtront ici.</p>
  </div>
  @else
  @foreach($notifications as $notif)
  @php
    $d = $notif->data;
    $type = $d['type'] ?? 'comment';
    $chipClass = ['mention'=>'chip-mention','comment'=>'chip-comment','reply'=>'chip-reply'][$type] ?? 'chip-comment';
    $chipIcon  = ['mention'=>'fas fa-at','comment'=>'fas fa-comment','reply'=>'fas fa-reply'][$type] ?? 'fas fa-bell';
  @endphp
  <div class="notif-item-page {{ $notif->read_at ? '' : 'unread' }}" data-nid="{{ $notif->id }}"
    style="animation-delay:{{ $loop->index * 40 }}ms">
    <div class="notif-type-chip {{ $chipClass }}">
      <i class="{{ $chipIcon }}"></i>
    </div>
    <img src="{{ $d['from_avatar'] ?? 'https://ui-avatars.com/api/?name=U&background=2563eb&color=fff' }}"
      alt="" onerror="this.src='https://ui-avatars.com/api/?name=U&background=2563eb&color=fff'">
    <div style="flex:1">
      <div class="notif-msg">{{ $d['message'] ?? '' }}</div>
      @if(!empty($d['content']))
      <div class="notif-excerpt">"{{ $d['content'] }}"</div>
      @endif
      <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
    </div>
    <div style="display:flex;flex-direction:column;gap:.35rem;align-items:flex-end;flex-shrink:0">
      @if(!$notif->read_at)
      <a href="{{ route('notifications.read', $notif->id) }}" class="btn btn-outline btn-sm btn-icon-only" title="Marquer lu">
        <i class="fas fa-check" style="font-size:.76rem"></i>
      </a>
      @endif
      @if(!empty($d['url']))
      <a href="{{ $d['url'] }}" class="btn btn-ghost btn-sm btn-icon-only" title="Voir">
        <i class="fas fa-arrow-up-right-from-square" style="font-size:.76rem"></i>
      </a>
      @endif
      <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm btn-icon-only"
          data-confirm="Supprimer ?" title="Supprimer">
          <i class="fas fa-xmark" style="font-size:.8rem"></i>
        </button>
      </form>
    </div>
  </div>
  @endforeach
  <div style="margin-top:1.5rem">{{ $notifications->links() }}</div>
  @endif
</div>
@endsection

