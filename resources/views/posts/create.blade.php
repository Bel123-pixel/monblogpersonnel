@extends('layouts.app')

@section('title', 'Publier un article - BellevieShop')

@section('content')
<div style="max-width: 640px; margin: 3rem auto; padding: 2.5rem; background: #ffffff; border-radius: 28px; box-shadow: 0 24px 60px rgba(40, 108, 71, 0.08); border: 1px solid #e7f3ea;">
    <h2 style="font-size: 2rem; font-weight: 800; color: #1f3f2c; margin-top: 0; margin-bottom: 0.5rem;">Publier un nouvel article BellevieShop</h2>
    <p style="color: #5f7164; margin-bottom: 2rem; font-size: 1rem;">Partagez vos looks, les nouvelles pièces et les conseils de style que HOUNTY propose à ses clients.</p>

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.65rem; font-weight: 700; font-size: 0.95rem; color: #2f533f;">Titre de l'article</label>
            <input type="text" name="title" placeholder="Ex: Nouvelle robe d'été éthique" style="width: 100%; padding: 1rem; border-radius: 16px; border: 1px solid #d7e9dd; font-size: 1rem; box-sizing: border-box;" required>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.65rem; font-weight: 700; font-size: 0.95rem; color: #2f533f;">Contenu</label>
            <textarea name="content" rows="6" placeholder="Décrivez le look, les tissus, les coupes et pourquoi cette pièce est unique..." style="width: 100%; padding: 1rem; border-radius: 16px; border: 1px solid #d7e9dd; font-size: 1rem; box-sizing: border-box; font-family: inherit;" required></textarea>
        </div>

        <div style="margin-bottom: 1.75rem;">
            <label style="display: block; margin-bottom: 0.65rem; font-weight: 700; font-size: 0.95rem; color: #2f533f;">Image (optionnel)</label>
            <input type="file" name="image" style="width: 100%; font-size: 0.95rem; color: #5f7164;">
        </div>

        <button type="submit" style="width: 100%; background: linear-gradient(135deg, #2f7d4f, #5dca95); color: white; padding: 1rem; border: none; border-radius: 18px; font-weight: 800; font-size: 1rem; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            <i class="fas fa-paper-plane"></i> Publier l'article
        </button>
    </form>
</div>
@endsection