@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div style="max-width: 600px; margin: 2rem auto; padding: 0 1rem;">
    <h1 style="margin-bottom: 1.5rem;"><i class="fas fa-user-cog"></i> Paramètres du profil</h1>

    <div style="background: #ffffff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--blue); margin-bottom: 1rem;">
                
                <div style="position: relative; max-width: 300px; margin: 0 auto;">
                    <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror">
                    <small style="color: var(--muted); display: block; margin-top: .4rem;">Formats acceptés : JPG, PNG, WEBP (Max : 2 Mo)</small>
                    @error('avatar')
                        <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="name" style="display:block; margin-bottom:.5rem; font-weight:600;">Nom complet</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="username" style="display:block; margin-bottom:.5rem; font-weight:600;">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="bio" style="display:block; margin-bottom:.5rem; font-weight:600;">Biographie</label>
                <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="4" placeholder="Parlez-nous de vous...">{{ old('bio', $user->bio) }}</textarea>
                @error('bio') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('profile', $user->username) }}" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-blue">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

