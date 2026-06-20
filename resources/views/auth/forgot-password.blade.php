@extends('layouts.app')
@section('title', 'Mot de passe oublié - BellevieShop')
@section('content')
<div style="max-width: 420px; margin: 4rem auto; padding: 2.5rem; background: #ffffff; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
    <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-top:0;">🔒 Mot de passe oublié ?</h2>
    <p style="color: #64748b; margin-bottom: 2rem; font-size: 0.9rem;">Entrez votre email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

    @if (session('status'))
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600;">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
            @foreach ($errors->all() as $error)
                <p style="margin: 0.25rem 0;">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div style="margin-bottom: 1.5rem;">
            <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Adresse Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com"
                style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required autofocus>
        </div>

        <button type="submit" style="width: 100%; background: #2f7d4f; color: white; padding: 0.75rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <div style="text-align: center; margin-top: 1.5rem;">
        <a href="{{ route('login') }}" style="color: #64748b; font-size: 0.85rem; text-decoration: none;">← Retour à la connexion</a>
    </div>
</div>
@endsection
