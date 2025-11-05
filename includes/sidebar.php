<style>
  #sidebar .menu-link.active {
    font-weight: bold;
    background-color: #f8f9fa !important;
    color: #000 !important;
    border-radius: 5px;
  }
</style>
<div class="bg-dark text-white p-3 vh-100" id="sidebar">
  <div class="d-flex align-items-center mb-4">
    <i class="bi bi-grid fs-3 me-2"></i>
    <h5 class="m-0">IAF Admin</h5>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item mb-2">
      <a href="#" class="nav-link text-white menu-link" data-url="<?=$Router::route('dashboard_project_list');?>">
        <i class="bi bi-kanban"></i> Projets
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="#" class="nav-link text-white menu-link" data-url="<?=$Router::route('dashboard_service_list');?>">
        <i class="bi bi-gear"></i> Services
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="#" class="nav-link text-white menu-link" data-url="<?=$Router::route('dashboard_testimonial_list');?>">
        <i class="bi bi-chat-quote"></i> Témoignages
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="#" class="nav-link text-white menu-link" data-url="<?=$Router::route('dashboard_portfolio_list');?>">
        <i class="bi bi-images"></i> Portfolio
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="#" class="nav-link text-white menu-link" data-url="/about">
        <i class="bi bi-info-circle"></i> À propos
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="#" class="nav-link text-white menu-link" data-url="/staff">
        <i class="bi bi-people"></i> Personnel / Formations
      </a>
    </li>
  </ul>
</div>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const menuLinks = document.querySelectorAll('.menu-link');

    menuLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();

        // 🔹 Supprime la classe 'active' de tous les liens
        menuLinks.forEach(l => l.classList.remove('active', 'bg-light', 'text-dark'));

        // 🔹 Ajoute la classe 'active' au lien cliqué
        link.classList.add('active', 'bg-light', 'text-dark');

        // 🔹 Redirige vers le lien associé
        //const targetUrl = link.dataset.url;
        //if (targetUrl) {indow.location.href = targetUrl;}
      });
    });
  });
</script>

