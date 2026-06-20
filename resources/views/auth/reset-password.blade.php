@extends('layouts.app')
@section('title', 'Réinitialiser le mot de passe - BellevieShop')
@section('content')
<div style="max-width: 420px; margin: 4rem auto; padding: 2.5rem; background: #ffffff; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
    <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-top:0;">🔑 Nouveau mot de passe</h2>
    <p style="color: #64748b; margin-bottom: 2rem; font-size: 0.9rem;">Choisissez un nouveau mot de passe pour votre compte.</p>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
            @foreach ($errors->all() as $error)
                <p style="margin: 0.25rem 0;">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div style="margin-bottom: 1.25rem;">
            <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Adresse Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com"
                style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Nouveau mot de passe</label>
            <input type="password" name="password" placeholder="Minimum 6 caractères"
                style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" placeholder="Répétez le mot de passe"
                style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
        </div>

        <button type="submit" style="width: 100%; background: #2f7d4f; color: white; padding: 0.75rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
            Réinitialiser le mot de passe
        </button>
    </form>
</div>
@endsection
