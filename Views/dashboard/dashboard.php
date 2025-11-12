<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Administration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?=ASSETS."css/dashboard.css";?>">
</head>
<body>
  <div class="d-flex" id="wrapper">
    
    <!-- Sidebar -->
    <?php include INCLUDES.'sidebar.php' ;?> 

    <!-- Contenu principal -->
    <div id="page-content-wrapper" class="flex-grow-1">
      
      <!-- Navbar -->
      <?php include INCLUDES.'nav.php' ;?> 

      <!-- Contenu dynamique -->
      <div id="main-content" class="container-fluid p-4">
        <h4 class="text-secondary">Bienvenue, <?= htmlspecialchars($user['name'] ?? 'Admin') ?> 👋</h4>
        <p>Sélectionnez un module dans le menu pour commencer.</p>
      </div>

      <!-- Footer -->
      <?php include INCLUDES.'foot.php' ;?> 
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const toggleSidebar = document.getElementById('toggleSidebar');
  const mainContent = document.getElementById('main-content');

  if (!sidebar || !toggleSidebar || !mainContent) return;

  // ✅ Toggle sidebar
  toggleSidebar.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
  });

  // ✅ Gère le chargement AJAX des modules
  function bindMenuLinks() {
    document.querySelectorAll('.menu-link').forEach(link => {
      link.addEventListener('click', async e => {
        e.preventDefault();
        const url = e.currentTarget.getAttribute('data-url');
        if (!url) return;

        showProgress();

        const res = await fetch(url);
        const html = await res.text();
        mainContent.innerHTML = html;

        // Réattache les actions CRUD à chaque chargement
        attachProjectEvents();
      });
    });
  }
  

  // ✅ Gère les boutons et formulaires du module "projects"
  function attachProjectEvents() {
    const addBtn = document.getElementById('addBtn');
    if (addBtn) {
      addBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        const url =e.target.dataset.url;
        showProgress();
        const res = await fetch(url);
        const html = await res.text();
        mainContent.innerHTML = html;
        attachProjectEvents(); // Rebind si nouveau contenu
      });
    }

    
    document.querySelectorAll('.edit').forEach(btn => {
      btn.addEventListener('click', async e => {
        const id = e.target.dataset.id;
        const url =e.target.dataset.url;
        showProgress();
        const res = await fetch(url);
        const html = await res.text();
        mainContent.innerHTML = html;
        attachProjectEvents();
      });
    });

     document.querySelectorAll('.delete').forEach(btn => {
      btn.addEventListener('click', async e => {
        const url =e.target.dataset.url;
        showProgress();
        const res = await fetch(url);
        const html = await res.text();
        mainContent.innerHTML = html;
        attachProjectEvents();
      });
    });

     document.querySelectorAll('.detail').forEach(btn => {
      btn.addEventListener('click', async e => {
        const url =e.target.dataset.url;
        showProgress();
        const res = await fetch(url);
        const html = await res.text();
        mainContent.innerHTML = html;
        attachProjectEvents();
      });
    });


    const form = document.querySelector('#main-content form');

    if (form) {
      form.addEventListener('submit', async e => {
        e.preventDefault();
        const formData = new FormData(form);
        const res = await fetch(form.action, { method: 'POST', body: formData ,headers: {'X-Requested-With': 'XMLHttpRequest'}});
        const data = await res.json();
        if (data.success) {
          showToast('success',data.message,15000);
          showProgress();
          const resList = await fetch(data.route);
          const html = await resList.text();
          mainContent.innerHTML = html;
          attachProjectEvents();
        }
        else{
          errors=data.errorForms;
          for (const key in errors) {
            if (!Object.hasOwn(errors, key)) continue;
            
            const element = errors[key];
            showToast('danger',element,10000)
          }
        }
      });
    }

    const backToListBtn = document.getElementById('backToList');
    if (backToListBtn) {
      backToListBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        const url =e.target.dataset.url;
        showProgress();
        const res = await fetch(url);
        const html = await res.text();
        mainContent.innerHTML = html;
        attachProjectEvents(); // Rebind si nouveau contenu
      });
    }

    
  }
 
  

  // Liaisons initiales
  bindMenuLinks();
  attachProjectEvents();

   function showToast(type, message, time = 10000) {
            // Créer ou récupérer le conteneur de toast
            const toastContainer = document.getElementById('toastContainer') || createToastContainer();
            
            // Créer un nouveau toast à chaque fois plutôt que de réutiliser le même
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = 'toast fade show';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            // Déterminer l'icône et le titre en fonction du type
            const typeConfig = {
                success: { icon: 'check-circle', title: 'Succès' },
                danger: { icon: 'exclamation-triangle', title: 'Erreur' },
                error: { icon: 'exclamation-triangle', title: 'Erreur' },
                warning: { icon: 'exclamation-circle', title: 'Avertissement' },
                info: { icon: 'info-circle', title: 'Information' }
            };
            
            const config = typeConfig[type] || typeConfig.info;
            
            // Construire le contenu du toast
            toast.innerHTML = `
                <div class="toast-header">
                    <strong class="me-auto text-${type}"><i class="bi bi-${config.icon} mx-1"></i>${config.title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body bg-${type} text-white rounded p-2 mb-2 shadow">
                    ${message}
                </div>
            `;
            
            // Ajouter le toast au conteneur
            toastContainer.appendChild(toast);
            
            // Gérer la fermeture automatique
            const closeToast = () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            };
            
            // Fermeture automatique après le délai
            const timeoutId = setTimeout(closeToast, time);
            
            // Gérer la fermeture manuelle
            const closeButton = toast.querySelector('.btn-close');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    clearTimeout(timeoutId);
                    closeToast();
                });
            }
            
            // Activer le toast avec Bootstrap si disponible
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                new bootstrap.Toast(toast).show();
            }
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1100';
        document.body.appendChild(container);
        return container;
    }

  
    function showProgress(){
    mainContent.innerHTML = `
          <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3">Chargement...</p>
          </div>`;
  }
});

</script>


</body>
</html>
