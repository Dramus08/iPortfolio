<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-4">
  <div class="container-fluid">
    <button class="btn btn-outline-dark me-3" id="toggleSidebar">
      <i class="bi bi-list"></i>
    </button>

    <form class="d-none d-md-flex me-auto">
      <input class="form-control me-2" type="search" placeholder="Rechercher...">
    </form>

    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
        <img src="/public/img/user.png" alt="user" width="32" height="32" class="rounded-circle me-2">
        <strong><?= htmlspecialchars($user['name'] ?? 'Admin') ?></strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow">
        <li><a class="dropdown-item" href="/profile"><i class="bi bi-person"></i> Mon profil</a></li>
        <li><a class="dropdown-item" href="/settings"><i class="bi bi-gear"></i> Paramètres</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?=$Router::route('logout');?>"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>
