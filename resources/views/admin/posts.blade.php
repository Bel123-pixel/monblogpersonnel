@extends('layouts.app')
@section('title', 'Publications — Admin')
@section('content')
<div class="admin-shell">
  @include('admin._sidebar')
  <div class="admin-main">
    <h1 class="admin-h1">Publications</h1>
    <form method="GET" style="display:flex;gap:.65rem;margin-bottom:1.5rem">
      <input type="text" name="search" class="form-control" style="max-width:320px"
        placeholder="Rechercher par titre..." value="{{ $search }}">
      <button type="submit" class="btn btn-blue btn-sm"><i class="fas fa-search"></i></button>
    </form>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Titre</th><th>Auteur</th><th>Commentaires</th><th>Vues</th><th>Statut</th><th>Date</th><th></th></tr>
        </thead>
        <tbody>
          @forelse($posts as $post)
          <tr>
            <td>
              <a href="{{ route('posts.show', $post->slug) }}"
                style="color:var(--blue);font-weight:500;font-size:.85rem">
                {{ Str::limit($post->title, 40) }}
              </a>
            </td>
            <td style="font-size:.84rem">{{ $post->user->name }}</td>
            <td>{{ $post->comments->count() }}</td>
            <td>{{ $post->views }}</td>
            <td>
              <span class="badge {{ $post->status==='published' ? 'badge-green' : 'badge-yellow' }}">
                {{ $post->status==='published' ? 'Publié' : 'Brouillon' }}
              </span>
            </td>
            <td style="font-size:.8rem">{{ $post->created_at->format('d/m/Y') }}</td>
            <td>
              <div style="display:flex;gap:.3rem">
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-ghost btn-sm btn-icon-only">
                  <i class="fas fa-pen" style="font-size:.76rem"></i>
                </a>
                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm btn-icon-only"
                    data-confirm="Supprimer cette publication ?">
                    <i class="fas fa-trash" style="font-size:.76rem"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--muted)">Aucune publication.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div>{{ $posts->withQueryString()->links() }}</div>
  </div>
</div>
@endsection

