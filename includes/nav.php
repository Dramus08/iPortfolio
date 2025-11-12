<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- Votre navbar ici -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-4">
  <div class="container-fluid">
    <button class="btn btn-outline-dark me-3" id="toggleSidebar">
      <i class="bi bi-list"></i>
    </button>
    <form class="d-none d-md-flex me-auto">
      <input class="form-control me-2" type="search" placeholder="Rechercher...">
    </form>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="<?= isset($_SESSION['user']) ? ASSETS.'images/logo/logo.png' : ASSETS.'images/logo/logo.png' ;?>" alt="user" width="32" height="32" class="rounded-circle me-2">
        <strong><?= isset($_SESSION['user']) ?  htmlspecialchars($_SESSION['user']['username']) : 'Admin'; ?></strong>
      </a>
      
      <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
       <?php if (isset($_SESSION['user_id'])): ?>
         <li><a class="dropdown-item" href="<?=$Router::route('auth_profile',['slug'=>$_SESSION['slug']]) ;?>"><i class="bi bi-person me-2"></i> Mon profil</a></li>
        <li><a class="dropdown-item" href="<?=$Router::route('change_password',['slug'=>$_SESSION['slug']]) ;?>"><i class="bi bi-shield-lock me-2"></i> Changer Mot de Passe</a></li>
        <li><a class="dropdown-item" href="<?=$Router::route('home') ;?>"><i class="bi bi-gear me-2"></i> Paramètres</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?=$Router::route('logout');?>"><i class="bi bi-box-arrow-right me-2"></i> Déconnexion</a></li>
        <?php else: ?>
          <li><a class="dropdown-item text-primary" href="<?=$Router::route('login');?>"><i class="bi bi-box-arrow-in-right me-2"></i> Se Connecter</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Bootstrap JS AVANT la fermeture de body -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>