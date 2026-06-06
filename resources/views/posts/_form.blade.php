<div class="form-group">
  <label class="form-label">Titre *</label>
  <input type="text" name="title"
    class="form-control @error('title') is-invalid @enderror"
    value="{{ old('title', $post->title ?? '') }}"
    placeholder="Un titre accrocheur..." autofocus>
  @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
  <label class="form-label">Contenu *</label>
  <textarea name="content"
    class="form-control @error('content') is-invalid @enderror"
    rows="14" placeholder="Écrivez votre article..."
    style="min-height:340px;font-size:1rem;line-height:1.75"
  >{{ old('content', $post->content ?? '') }}</textarea>
  @error('content')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
  <label class="form-label">Image de couverture</label>
  @if(isset($post) && $post->image_url)
    <img id="imgPreview" src="{{ $post->image_url }}" alt=""
      style="max-height:180px;border-radius:var(--r);object-fit:cover;margin-bottom:.65rem">
  @else
    <img id="imgPreview" src="" alt=""
      style="display:none;max-height:180px;border-radius:var(--r);margin-bottom:.65rem;object-fit:cover">
  @endif
  <input type="file" name="image"
    class="form-control @error('image') is-invalid @enderror"
    accept="image/*" onchange="previewImage(this,'imgPreview')">
  <span class="form-hint">JPG, PNG, WebP — max 3 Mo (optionnel)</span>
  @error('image')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
  <label class="form-label">Statut</label>
  <select name="status" class="form-control">
    <option value="published" {{ old('status', $post->status ?? 'published')==='published' ? 'selected' : '' }}>
      ✅ Publié — visible par tous
    </option>
    <option value="draft" {{ old('status', $post->status ?? '')==='draft' ? 'selected' : '' }}>
      📝 Brouillon — non visible
    </option>
  </select>
</div>

<hr style="border:none;border-top:1px solid var(--border);margin:1.5rem 0">

