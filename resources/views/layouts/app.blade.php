<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BellevieShop')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: 'Segoe UI', Roboto, sans-serif; background-color: #f4fbf6; margin: 0; padding: 0; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.96); padding: 1rem 2rem; box-shadow: 0 10px 35px rgba(72, 187, 120, 0.08); position: sticky; top: 0; z-index: 1000; backdrop-filter: blur(12px); }
        .logo { font-size: 1.35rem; font-weight: 800; color: #247f4d; text-decoration: none; display: flex; align-items: center; gap: 0.55rem; }
        .logo i { color: #4fbb87; }
        .nav-links { display: flex; align-items: center; list-style: none; gap: 1.2rem; margin: 0; padding: 0; }
        .nav-link { text-decoration: none; color: #3b4c42; font-weight: 600; font-size: 0.95rem; transition: all 0.2s; }
        .nav-link:hover { color: #1f5c38; }
        .btn-publier { background: linear-gradient(135deg, #35a56d, #5bcc92); color: white !important; padding: 0.55rem 1.2rem; border-radius: 999px; box-shadow: 0 10px 25px rgba(53,165,109,0.16); }
        .icon-btn { position: relative; display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 14px; border: 1px solid rgba(94, 171, 122, 0.22); background: white; color: #3b4c42; cursor: pointer; }
        .notif-count { position: absolute; top: -6px; right: -6px; min-width: 18px; height: 18px; border-radius: 999px; background: #ef4444; color: #fff; font-size: 0.68rem; font-weight: 700; display: flex; align-items: center; justify-content: center; padding: 0 4px; }
        .notif-panel { position: absolute; right: 0; top: calc(100% + 0.75rem); width: 320px; background: #fff; border: 1px solid #e4f1e6; border-radius: 22px; box-shadow: 0 20px 50px rgba(47, 113, 74, 0.08); display: none; z-index: 30; overflow: hidden; }
        .notif-panel.open { display: block; }
        .notif-panel-head { padding: 1rem 1rem 0.9rem; font-weight: 700; color: #1f5c38; display: flex; justify-content: space-between; align-items: center; }
        .notif-panel-body { max-height: 260px; overflow-y: auto; }
        .notif-panel-foot { display: block; padding: 0.9rem 1rem 1rem; font-size: 0.84rem; color: #2f7d4f; text-align: right; }
        .np-item { display: flex; gap: 0.8rem; padding: 0.85rem 1rem; align-items: flex-start; text-decoration: none; color: inherit; border-bottom: 1px solid #f0f5ef; }
        .np-item:hover { background: #f5fdf5; }
        .np-item.unread { background: #ecf8ed; }
        .np-item img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .np-item-msg { font-size: 0.86rem; line-height: 1.4; }
        .np-item-time { font-size: 0.72rem; color: #6f7f72; margin-top: 0.35rem; }
    </style>
</head>
<body>

    <nav id="navbar" class="navbar">
        <a href="{{ route('home') }}" class="logo">
            <i class="fas fa-shopping-bag"></i>
            BellevieShop
        </a>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="nav-link"><i class="fas fa-home"></i> Accueil</a></li>
            @auth
                @if(auth()->user()->is_admin)
                    <li><a href="{{ route('posts.create') }}" class="nav-link btn-publier"><i class="fas fa-plus-circle"></i> Publier</a></li>
                @endif

                <li id="notifWrap" class="notif-wrap">
                    <button type="button" class="icon-btn" onclick="toggleNotif()" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        <span id="notifCount" class="notif-count" style="display: none">0</span>
                    </button>
                    <div id="notifPanel" class="notif-panel">
                        <div class="notif-panel-head">
                            Notifications
                            <button type="button" class="nav-link" style="font-size:0.82rem; padding:0; background:none; border:none; cursor:pointer;" onclick="closeNotif()">Fermer</button>
                        </div>
                        <div id="notifBody" class="notif-panel-body">
                            <div class="notif-spinner">Chargement...</div>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="notif-panel-foot">Voir toutes les notifications</a>
                    </div>
                </li>

                <li><span class="nav-link" style="color: #2f7d4f;"><i class="fas fa-user-circle"></i> {{ auth()->user()->name }}</span></li>
                <li><a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i></a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            @else
                <li><a href="{{ route('login') }}" class="nav-link">Connexion</a></li>
                <li><a href="{{ route('login') }}" class="nav-link btn-publier">S'inscrire</a></li>
            @endauth
        </ul>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script defer>
        window.addEventListener('load', () => {
            @auth
                if (typeof loadNotifCount === 'function') {
                    loadNotifCount();
                }
            @endauth
        });
    </script>
</body>
</html>