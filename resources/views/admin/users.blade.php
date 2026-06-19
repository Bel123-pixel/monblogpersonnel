@extends('layouts.app')
@section('title', 'Utilisateurs — Admin')
@section('content')
<div class="admin-shell">
  @include('admin._sidebar')
  <div class="admin-main">
    <h1 class="admin-h1">Utilisateurs</h1>
    <form method="GET" style="display:flex;gap:.65rem;margin-bottom:1.5rem">
      <input type="text" name="search" class="form-control" style="max-width:320px"
        placeholder="Rechercher..." value="{{ $search }}">
      <button type="submit" class="btn btn-blue btn-sm"><i class="fas fa-search"></i></button>
    </form>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Utilisateur</th><th>Email</th><th>Posts</th><th>Rôle</th><th>Inscrit</th><th>Actions</th></tr>
        </thead>
        <tbody>
          @forelse($users as $u)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:.55rem">
                <img src="{{ $u->avatar_url }}" alt=""
                  style="width:32px;height:32px;border-radius:50%;object-fit:cover">
                <div>
                  <a href="{{ route('profile', $u->username) }}" style="font-weight:600;font-size:.86rem;color:var(--ink2)">
                    {{ $u->name }}
                  </a>
                  <div style="font-size:.74rem;color:var(--muted)">{{ $u->username }}</div>
                </div>
              </div>
            </td>
            <td style="font-size:.83rem">{{ $u->email }}</td>
            <td>{{ $u->posts->count() }}</td>
            <td>
              <span class="badge {{ $u->is_admin ? 'badge-yellow' : 'badge-blue' }}">
                {{ $u->is_admin ? 'Admin' : 'Membre' }}
              </span>
            </td>
            <td style="font-size:.8rem">{{ $u->created_at->format('d/m/Y') }}</td>
            <td>
              <div style="display:flex;gap:.3rem">
                @if($u->id !== auth()->id())
                <form action="{{ route('admin.users.toggleAdmin', $u) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-ghost btn-sm btn-icon-only"
                    title="{{ $u->is_admin ? 'Retirer admin' : 'Passer admin' }}">
                    <i class="fas fa-shield-halved" style="font-size:.78rem;{{ $u->is_admin ? 'color:var(--warn)' : '' }}"></i>
                  </button>
                </form>
                <form action="{{ route('admin.users.destroy', $u) }}" method="POST">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm btn-icon-only"
                    data-confirm="Supprimer {{ $u->name }} et toutes ses données ?">
                    <i class="fas fa-trash" style="font-size:.76rem"></i>
                  </button>
                </form>
                @else
                <span style="font-size:.76rem;color:var(--muted)">Vous</span>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--muted)">Aucun utilisateur.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div>{{ $users->withQueryString()->links() }}</div>
  </div>
</div>
@endsection