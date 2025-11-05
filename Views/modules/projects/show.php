

<!-- views/projects/show.php -->
<div class="container py-4">
  <?php if (!empty($project)): ?>
    <?php //var_dump($tags); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold text-dark">
        <i class="bi bi-folder-check me-2"></i><?= htmlspecialchars($project->title) ?>
      </h4>
      <div>
        <button data-url="<?=$Router::route("dashboard_project_list");?>"class="btn btn-outline-secondary btn-sm list" id="backToList">
          <i class="bi bi-arrow-left"></i> Retour à la liste
        </button>
        <button data-url="<?=$Router::route("dashboard_project_edit",['id'=>$project->id]);?>" class="btn btn-primary btn-sm edit">
          <i class="bi bi-pencil-square"></i> Modifier
        </button>
      </div>
    </div>
    <div class="card shadow-sm border-0">
      <div class="row g-0">
        <div class="col-md-5">
          <img src="public/<?= !empty($project->image) ? htmlspecialchars($project->image) : 'https://via.placeholder.com/500x350' ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($project->title) ?>">
          <img src="<?= !empty($project->image) ? htmlspecialchars($project->image) : 'https://via.placeholder.com/400x250' ?>" >
        </div>

        <div class="col-md-7">
          <div class="card-body">
            <h5 class="card-title text-primary fw-bold"><?= htmlspecialchars($project->title) ?></h5>
            <p class="card-text text-muted mb-2"><strong>Catégorie :</strong> <?= htmlspecialchars($project->category) ?></p>
            <p class="card-text text-muted mb-2"><strong>Client :</strong> <?= htmlspecialchars($project->customer ?? 'N/A') ?></p>
            <p class="card-text text-muted mb-2"><strong>Date du projet :</strong> <?= date('d M Y', strtotime($project->project_date)) ?></p>
            <p class="card-text text-muted mb-3"><strong>Statut :</strong> 
              <?php
                $status = $project->status;
                $badge = [
                  'open' => 'warning',
                  'close' => 'success',
                  'pending' => 'secondary'
                ][$status] ?? 'light';
              ?>
              <span class="badge bg-<?= $badge ?>"><?= ucfirst($status) ?></span>
            </p>

            <p class="card-text"><?= nl2br(htmlspecialchars($project->description)) ?></p>

            <?php if (!empty($project->features)): ?>
              <hr>
              <h6 class="fw-bold text-dark">Fonctionnalités :</h6>
              <p class="text-muted"><?= nl2br(htmlspecialchars($project->features)) ?></p>
            <?php endif; ?>

            <?php if (!empty($project->technologies)): ?>
              <h6 class="fw-bold text-dark">Technologies utilisées :</h6>
              <p class="text-muted"><?= nl2br(htmlspecialchars($project->technologies)) ?></p>
            <?php endif; ?>

            <?php if (!empty($project->video_url) && isset($project->video_url)): ?>
  <?php
    $videoUrl = htmlspecialchars($project->video_url);

    // Vérifie si c’est un lien YouTube ou Vimeo
    $isYouTube = preg_match('/(youtube\.com|youtu\.be)/i', $videoUrl);
    $isVimeo   = preg_match('/(vimeo\.com)/i', $videoUrl);
  ?>

  <div class="ratio ratio-16x9 my-3">
    <?php if ($isYouTube || $isVimeo): ?>
      <!-- 🎥 Lien vidéo externe (YouTube ou Vimeo) -->
      <iframe
        src="<?= $videoUrl ?>"
        title="Vidéo du projet"
        allowfullscreen
        frameborder="0"
      ></iframe>
    <?php else: ?>
      <!-- 🎬 Fichier vidéo local -->
      <video controls class="w-100 rounded">
        <source src="public/<?= $videoUrl ?>" type="video/mp4">
        Votre navigateur ne supporte pas la lecture vidéo.
      </video>
    <?php endif; ?>
  </div>
<?php endif; ?>
<?php foreach ($tags as $tag): ?>
  <span style="background: <?= $tag->color ?>; color:#fff; padding:4px; border-radius:4px;margin:5px;">
      <?= htmlspecialchars($tag->name) ?>
  </span>
  <?php endforeach; ?>

            <?php if (!empty($project->link_demo)): ?>
              <a href="<?= htmlspecialchars($project->link_demo) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-link-45deg"></i> Voir la démo
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="card-footer bg-light text-muted small d-flex justify-content-between">
        <span><i class="bi bi-calendar3 me-1"></i> Créé le <?= date('d M Y', strtotime($project->created_at)) ?></span>
        <span><i class="bi bi-clock-history me-1"></i> Dernière mise à jour le <?= date('d M Y', strtotime($project->updated_at)) ?></span>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center">
      <i class="bi bi-exclamation-triangle me-2"></i> Projet introuvable.
    </div>
  <?php endif; ?>
</div>
<?php echo "Project <b>".$project->id."</b></br>";  echo "<pre>";print_r($project); echo "</pre></br>";?>
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

    document.querySelectorAll('.show').forEach(btn => {
      btn.addEventListener('click', async e => {
        const url =e.target.dataset.url;
        window.location.href = url;
      });
    });
    document.querySelectorAll('.list').forEach(btn => {
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