@extends('layouts.app')
@section('title', 'Dashboard — Admin')
@section('content')
<div class="admin-shell">
  @include('admin._sidebar')
  <div class="admin-main">
    <h1 class="admin-h1">Tableau de bord</h1>
    <div class="stat-grid">
      @foreach([
        ['chip-blue',  'fas fa-users',          $stats['users'],    'Utilisateurs'],
        ['chip-sky',   'fas fa-file-lines',      $stats['posts'],    'Publications'],
        ['chip-green', 'fas fa-comments',        $stats['comments'], 'Commentaires'],
        ['chip-orange','fas fa-shopping-cart',   $stats['orders'],   'Commandes total'],
        ['chip-yellow','fas fa-clock',           $stats['pending'],  'Commandes en attente'],
      ] as [$chip, $icon, $val, $label])
      <div class="stat-card">
        <div class="stat-chip {{ $chip }}"><i class="{{ $icon }}"></i></div>
        <div>
          <div class="stat-val">{{ $val }}</div>
          <div class="stat-lbl">{{ $label }}</div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="table-wrap">
      <div class="table-head-bar">
        <span><i class="fas fa-users" style="color:var(--blue)"></i> Nouveaux membres</span>
        <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">Voir tous</a>
      </div>
      <table>
        <thead><tr><th>Utilisateur</th><th>Email</th><th>Rôle</th><th>Inscrit le</th></tr></thead>
        <tbody>
          @foreach($recentUsers as $u)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:.55rem">
                <img src="{{ $u->avatar_url }}" alt=""
                  style="width:30px;height:30px;border-radius:50%;object-fit:cover">
                <div>
                  <div style="font-weight:600;font-size:.86rem">{{ $u->name }}</div>
                  <div style="font-size:.74rem;color:var(--muted)">@{{ $u->username }}</div>
                </div>
              </div>
            </td>
            <td style="font-size:.84rem">{{ $u->email }}</td>
            <td>
              <span class="badge {{ $u->is_admin ? 'badge-yellow' : 'badge-blue' }}">
                {{ $u->is_admin ? 'Admin' : 'Membre' }}
              </span>
            </td>
            <td style="font-size:.8rem">{{ $u->created_at->format('d/m/Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="table-wrap">
      <div class="table-head-bar">
        <span><i class="fas fa-file-lines" style="color:var(--blue)"></i> Publications récentes</span>
        <a href="{{ route('admin.posts') }}" class="btn btn-ghost btn-sm">Voir toutes</a>
      </div>
      <table>
        <thead><tr><th>Titre</th><th>Auteur</th><th>Statut</th><th>Date</th><th></th></tr></thead>
        <tbody>
          @foreach($recentPosts as $post)
          <tr>
            <td>
              <a href="{{ route('posts.show', $post->slug) }}" style="color:var(--blue);font-weight:500;font-size:.86rem">
                {{ Str::limit($post->title, 42) }}
              </a>
            </td>
            <td style="font-size:.84rem">{{ $post->user->name }}</td>
            <td>
              <span class="badge {{ $post->status==='published' ? 'badge-green' : 'badge-yellow' }}">
                {{ $post->status==='published' ? 'Publié' : 'Brouillon' }}
              </span>
            </td>
            <td style="font-size:.8rem">{{ $post->created_at->format('d/m/Y') }}</td>
            <td>
              <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm btn-icon-only"
                  data-confirm="Supprimer ?">
                  <i class="fas fa-trash" style="font-size:.76rem"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

