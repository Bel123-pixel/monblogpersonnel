@extends('layouts.app')
@section('title', 'Commentaires — Admin')
@section('content')
<div class="admin-shell">
  @include('admin._sidebar')
  <div class="admin-main">
    <h1 class="admin-h1">Commentaires</h1>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Auteur</th><th>Commentaire</th><th>Publication</th><th>Réponses</th><th>Date</th><th></th></tr>
        </thead>
        <tbody>
          @forelse($comments as $comment)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:.5rem">
                <img src="{{ $comment->user->avatar_url }}" alt=""
                  style="width:26px;height:26px;border-radius:50%;object-fit:cover">
                <span style="font-size:.84rem;font-weight:600">{{ $comment->user->name }}</span>
              </div>
            </td>
            <td style="font-size:.83rem;max-width:220px;color:var(--muted)">
              {{ Str::limit($comment->body, 55) }}
            </td>
            <td>
              <a href="{{ route('posts.show', $comment->post->slug) }}"
                style="color:var(--blue);font-size:.83rem">
                {{ Str::limit($comment->post->title, 32) }}
              </a>
            </td>
            <td>{{ $comment->replies->count() }}</td>
            <td style="font-size:.8rem">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm btn-icon-only"
                  data-confirm="Supprimer ce commentaire ?">
                  <i class="fas fa-trash" style="font-size:.76rem"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--muted)">Aucun commentaire.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div>{{ $comments->links() }}</div>
  </div>
</div>
@endsection

