@extends('layouts.app')

@section('title', 'Espace Client - Bellevieshop')

@section('content')
<div style="display: flex; gap: 2rem; max-width: 900px; margin: 3rem auto; padding: 0 1rem; flex-wrap: wrap;">

    <div class="login-card" style="flex: 1; min-width: 350px; background: #ffffff; padding: 2.5rem; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-top:0;">🔐 Connexion Client</h2>
        <p style="color: #64748b; margin-bottom: 2rem; font-size: 0.9rem;">Accédez à votre compte pour liker et commander.</p>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0.25rem 0;">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Adresse Email</label>
                <input type="email" name="email" placeholder="client@example.com" value="{{ old('email') }}" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
            </div>
            <button type="submit" style="width: 100%; background: #1e293b; color: white; padding: 0.75rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Se connecter</button>
        </form>
        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('password.request') }}" style="color: #64748b; font-size: 0.85rem; text-decoration: none;">Mot de passe oublié ?</a>
        </div>

    <div class="register-card" style="flex: 1; min-width: 350px; background: #ffffff; padding: 2.5rem; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #ff4757; margin-top:0;">🛍️ Créer un compte Client</h2>
        <p style="color: #64748b; margin-bottom: 2rem; font-size: 0.9rem;">Inscrivez-vous en 10 secondes pour interagir sur le blog.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Nom complet</label>
                <input type="text" name="name" placeholder="Ex: Elnis Sossou" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Nom d'utilisateur</label>
                <input type="text" name="username" placeholder="ex: elnis_sossou" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Adresse Email</label>
                <input type="email" name="email" placeholder="nom@gmail.com" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Mot de passe</label>
                <input type="password" name="password" placeholder="Minimum 4 caractères" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight: 600; font-size: 0.85rem; color: #334155;">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" placeholder="Répétez le mot de passe" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid #cbd5e1; box-sizing: border-box;" required>
            </div>
            <button type="submit" style="width: 100%; background: linear-gradient(135deg, #ff4757, #ff6b81); color: white; padding: 0.75rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Créer mon compte Client</button>
        </form>
    </div>

</div>
@endsection