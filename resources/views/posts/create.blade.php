@extends('layouts.app')
@section('title', 'Nouvelle publication — BellevieShop')
@section('content')
<div class="wrap-md">
  <div class="page-top">
    <h1>Nouvelle publication</h1>
    <a href="{{ route('home') }}" class="btn btn-ghost btn-sm">
      <i class="fas fa-arrow-left"></i> Retour
    </a>
  </div>
  <div class="form-card">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @include('posts._form')
      <button type="submit" class="btn btn-blue btn-lg">
        <i class="fas fa-paper-plane"></i> Publier l'article
      </button>
    </form>
  </div>
</div>
@endsection

