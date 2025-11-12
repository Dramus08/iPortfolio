
<!-- views/modules/projects/list.php -->

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-dark fw-bold mb-0">
      <i class="bi bi-folder2-open me-2"></i>Liste des Projets
    </h4>
    <button  class="btn btn-primary btn-sm" id="addBtn" data-url="<?=$Router::route("dashboard_project_create");?>">
      <i class="bi bi-plus-circle me-1"></i> Nouveau Projet
    </button>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <input type="text" class="form-control" id="searchProject" placeholder="Rechercher un projet...">
    </div>
  </div>

  <div class="row g-4" id="projectContainer">
    <?php if (!empty($projects)): ?>
      <?php foreach ($projects as $project): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card shadow-sm border-0 h-100">
            <img src="public/<?= !empty($project->image) ? htmlspecialchars($project->image) : 'https://via.placeholder.com/400x250' ?>" 
                 class="card-img-top" alt="<?= htmlspecialchars($project->title) ?>">
              <video src="public/<?= !empty($project->image) ? htmlspecialchars($project->image) : 'https://via.placeholder.com/400x250' ?>" controls></video>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($project->title) ?></h5>
              <p class="card-text text-muted small flex-grow-1">
                <?= nl2br(substr($project->description, 0, 100)) ?>...
              </p>

              <div class="d-flex justify-content-between align-items-center">
                <button data-url="<?=$Router::route("dashboard_project_show",['id'=>$project->id]);?>" class="btn btn-outline-primary btn-sm detail">
                  <i class="bi bi-eye"></i> Voir
                </button>
                <div>
                  
                  <?php foreach ($project->tags as $tag): ?>
                      <span style="background: <?= $tag->color ?>; color:#fff; padding:5px; border-radius:4px;border-radius:8px;margin:4px;text-center;">
                          <?= htmlspecialchars($tag->name) ?>
                      </span>
                  <?php endforeach; ?>
                </div>
                <div>
                  <button data-url="<?=$Router::route("dashboard_project_edit",['id'=>$project->id]);?>" class="btn btn-outline-success btn-sm edit">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button data-url="<?=$Router::route("dashboard_project_delete",['id'=>$project->id]);?>" class="btn btn-outline-danger btn-sm delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="card-footer bg-light text-muted small d-flex justify-content-between">
              <span><i class="bi bi-tags me-1"></i><?= htmlspecialchars($project->category) ?></span>
              <span><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($project->project_date)) ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center text-muted">
        <p>Aucun projet disponible pour le moment.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
  // 🔍 Recherche dynamique
  document.getElementById("searchProject").addEventListener("keyup", function() {
    const value = this.value.toLowerCase();
    document.querySelectorAll("#projectContainer .card").forEach(card => {
      const text = card.textContent.toLowerCase();
      card.parentElement.style.display = text.includes(value) ? "block" : "none";
    });
  });
</script>
<script>
  document.querySelectorAll('.edit').forEach(btn => {
      btn.addEventListener('click', async e => {
        const url =e.target.dataset.url;
        window.location.href = url;
      });
    });

    document.querySelectorAll('.delete').forEach(btn => {
      btn.addEventListener('click', async e => {
        const url =e.target.dataset.url;
        window.location.href = url;
      });
    });

    document.querySelectorAll('.detail').forEach(btn => {
      btn.addEventListener('click', async e => {
        const url =e.target.dataset.url;
        window.location.href = url;
      });
    });

    document.getElementById('addBtn').addEventListener('click', async e => {
        const url =e.target.dataset.url;
        window.location.href = url;
      
    });

    
</script>



