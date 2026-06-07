<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Bellevieshop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8fafc; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .login-card { animation: fadeInUp 0.6s ease; max-width: 420px; width: 100%; padding: 2.5rem; background: #ffffff; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; box-sizing: border-box; }
        .input-field { width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.95rem; box-sizing: border-box; margin-top: 0.5rem; }
        .btn-submit { width: 100%; background: linear-gradient(135deg, #ff4757, #ff6b81); color: white; padding: 0.75rem; border: none; border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: transform 0.2s; margin-top: 1rem; }
        .btn-submit:hover { transform: scale(1.02); }
    </style>
</head>
<body>

<div class="login-card">
    <h2 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 0 0 0.5rem 0;">🛍️ Bellevieshop</h2>
    <p style="color: #64748b; margin-bottom: 2rem; font-size: 0.95rem;">Connectez-vous à votre compte de démonstration</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div style="margin-bottom: 1.25rem;">
            <label style="font-weight: 600; font-size: 0.9rem; color: #334155;">Adresse email</label>
            <input type="email" name="email" class="input-field" placeholder="vous@exemple.com" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-weight: 600; font-size: 0.9rem; color: #334155;">Mot de passe</label>
            <input type="password" name="password" class="input-field" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>
    </form>
</div>

</body>
</html>