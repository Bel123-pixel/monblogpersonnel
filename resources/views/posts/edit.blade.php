@extends('layouts.app')
@section('title', 'Modifier — ' . $post->title)
@section('content')
<div class="wrap-md">
  <div class="page-top">
    <h1>Modifier l'article</h1>
    <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-ghost btn-sm">
      <i class="fas fa-arrow-left"></i> Retour
    </a>
  </div>
  <div class="form-card">
    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')
      @include('posts._form', ['post' => $post])
      <button type="submit" class="btn btn-blue btn-lg">
        <i class="fas fa-check"></i> Enregistrer les modifications
      </button>
    </form>
  </div>
</div>
@endsection

