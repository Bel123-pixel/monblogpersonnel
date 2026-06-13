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
            <label style="display: block; margin-bottom: 0.75rem; font-weight: 700; font-size: 0.95rem; color: #2f533f;">
                Photos <span style="color:#6b7c6f; font-weight:400;">(3 photos côte à côte, optionnel)</span>
            </label>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.65rem;">
                @for($i = 0; $i < 3; $i++)
                <label style="display:block; border: 2px dashed #c8e6cf; border-radius: 14px; cursor:pointer; overflow:hidden; aspect-ratio:1; background:#f7fbf8; position:relative; transition: border-color .2s;" onmouseover="this.style.borderColor='#2f7d4f'" onmouseout="this.style.borderColor='#c8e6cf'">
                    <input type="file" name="extra_images[]" accept="image/*" style="display:none;" onchange="previewExtra(this, 'prev{{$i}}')">
                    <img id="prev{{$i}}" src="" alt="" style="display:none; width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0;">
                    <div id="icon{{$i}}" style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; color:#6b9e78; gap:0.35rem; padding:0.75rem;">
                        <i class="fas fa-camera" style="font-size:1.5rem;"></i>
                        <span style="font-size:0.75rem; text-align:center; font-weight:600;">Photo {{ $i+1 }}</span>
                    </div>
                </label>
                @endfor
            </div>
        </div>

        <button type="submit" style="width: 100%; background: linear-gradient(135deg, #2f7d4f, #5dca95); color: white; padding: 1rem; border: none; border-radius: 18px; font-weight: 800; font-size: 1rem; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            <i class="fas fa-paper-plane"></i> Publier l'article
        </button>
    </form>
</div>
<script>
function previewExtra(input, previewId) {
    const img  = document.getElementById(previewId);
    const icon = document.getElementById(previewId.replace('prev', 'icon'));
    if (!input.files?.[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        img.src = e.target.result;
        img.style.display = 'block';
        if (icon) icon.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endsection