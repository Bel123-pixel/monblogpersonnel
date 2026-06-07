<aside class="admin-side">
  <div class="admin-side-label">Administration</div>
  <a href="{{ route('admin.dashboard') }}"
    class="admin-nav {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="fas fa-chart-pie"></i> Tableau de bord
  </a>
  <a href="{{ route('admin.users') }}"
    class="admin-nav {{ request()->routeIs('admin.users') ? 'active' : '' }}">
    <i class="fas fa-users"></i> Utilisateurs
  </a>
  <a href="{{ route('admin.posts') }}"
    class="admin-nav {{ request()->routeIs('admin.posts') ? 'active' : '' }}">
    <i class="fas fa-file-lines"></i> Publications
  </a>
  <a href="{{ route('admin.comments') }}"
    class="admin-nav {{ request()->routeIs('admin.comments') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Commentaires
  </a>
  <a href="{{ route('admin.orders.index') }}"
    class="admin-nav {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
    <i class="fas fa-shopping-cart"></i> Commandes
  </a>
  <div class="admin-side-label" style="margin-top:1.5rem">Site</div>
  <a href="{{ route('home') }}" class="admin-nav">
    <i class="fas fa-arrow-up-right-from-square"></i> Voir le site
  </a>
</aside>

