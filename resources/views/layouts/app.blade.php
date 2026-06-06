<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BellevieShop — Blog')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>

{{-- NAVBAR --}}
<nav class="navbar" id="navbar">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">
            <span class="logo-icon"><i class="fas fa-bolt"></i></span>
            <span class="logo-text">BellevieShop</span>
        </a>

        <ul class="nav-menu" id="navMenu">
            <li>
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'nav-link active' : 'nav-link' }}">
                    Accueil
                </a>
            </li>
            <li>
                <a href="{{ route('live.index') }}" class="{{ request()->routeIs('live.*') ? 'nav-link active' : 'nav-link' }}">
                    <span class="live-dot"></span> Lives
                </a>
            </li>
            @auth
            <li>
                <a href="{{ route('posts.create') }}" class="nav-link nav-write">
                    <i class="fas fa-pen"></i> Écrire
                </a>
            </li>
            @endauth
        </ul>

        <div class="nav-actions">
            @auth
            {{-- Notifications --}}
            <div class="notif-wrap" id="notifWrap">
                <button class="icon-btn" id="notifToggle" onclick="toggleNotif()">
                    <i class="fas fa-bell"></i>
                    <span class="notif-count" id="notifCount" style="display:none">0</span>
                </button>
                <div class="notif-panel" id="notifPanel">
                    <div class="notif-panel-head">
                        <span>Notifications</span>
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-text-sm">Tout lire</button>
                        </form>
                    </div>
                    <div class="notif-panel-body" id="notifBody">
                        <div class="notif-spinner"><i class="fas fa-circle-notch fa-spin"></i></div>
                    </div>
                    <a href="{{ route('notifications.index') }}" class="notif-panel-foot">
                        Voir toutes les notifications
                    </a>
                </div>
            </div>

            {{-- User menu --}}
            <div class="user-menu-wrap" id="userMenuWrap">
                <button class="user-trigger" onclick="toggleUserMenu()">
                    <img src="{{ auth()->user()->avatar_url }}" alt="" class="user-avatar-sm">
                    <i class="fas fa-chevron-down" style="font-size:.65rem; color:var(--muted)"></i>
                </button>
                <div class="user-menu" id="userMenu">
                    <div class="user-menu-header">
                        <img src="{{ auth()->user()->avatar_url }}" alt="">
                        <div>
                            <div class="user-menu-name">{{ auth()->user()->name }}</div>
                            <div class="user-menu-username">@{{ auth()->user()->username }}</div>
                        </div>
                    </div>
                    <div class="user-menu-divider"></div>
                    <a href="{{ route('profile', auth()->user()->username) }}" class="user-menu-item">
                        <i class="fas fa-user"></i> Mon profil
                    </a>
                    <a href="{{ route('profile.edit') }}" class="user-menu-item">
                        <i class="fas fa-sliders"></i> Paramètres
                    </a>
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="user-menu-item">
                        <i class="fas fa-shield-halved"></i> Administration
                    </a>
                    @endif
                    <div class="user-menu-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="user-menu-item user-menu-logout">
                            <i class="fas fa-arrow-right-from-bracket"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn-ghost-nav">Connexion</a>
            <a href="{{ route('register') }}" class="btn-blue-nav">S'inscrire</a>
            @endauth

            <button class="hamburger" id="hamburger" onclick="toggleMobile()">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

{{-- FLASH --}}
@if(session('success') || session('error'))
<div class="toasts" id="toasts">
    @if(session('success'))
    <div class="toast toast-success" data-auto-dismiss>
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button onclick="this.closest('.toast').remove()">&times;</button>
    </div>
    @endif
    @if(session('error'))
    <div class="toast toast-error" data-auto-dismiss>
        <i class="fas fa-triangle-exclamation"></i>
        <span>{{ session('error') }}</span>
        <button onclick="this.closest('.toast').remove()">&times;</button>
    </div>
    @endif
</div>
@endif

<main>@yield('content')</main>

<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <span class="logo-icon" style="width:30px;height:30px;font-size:.75rem"><i class="fas fa-bolt"></i></span>
            <span style="font-weight:700;font-size:1.1rem">BellevieShop</span>
        </div>
        <p class="footer-copy">© {{ date('Y') }} BellevieShop — Fait avec passion et Laravel 12</p>
        <div class="footer-links">
            <a href="{{ route('home') }}">Accueil</a>
            <a href="{{ route('live.index') }}">Lives</a>
        </div>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@auth
<script>
    // Version ultra-simplifiée pour éviter les erreurs de compilation Blade
    @php
        $usersList = \App\Models\User::select('name', 'username', 'avatar')->get()->map(function($u) {
            return [
                'name' => $u->name,
                'username' => $u->username,
                'avatar' => $u->avatar_url
            ];
        })->toArray();
    @endphp

    window.blogUsers = @json($usersList);

    if (typeof loadNotifCount === 'function') {
        loadNotifCount();
        setInterval(loadNotifCount, 30000);
    }
</script>
@endauth
@stack('scripts')
</body>
</html>

