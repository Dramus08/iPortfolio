
<div class="card shadow-sm border-0">
  <div class="card-header bg-<?=isset($testimonial) ? 'success':'primary';?> text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-<?=isset($project) ? 'pencil':'folder-plus';?>"></i> <?=isset($project)? 'Modifier le Projet : <b>'.$project->slug."</b>":'Creer un Nouveau Projet';?></h5>
     <?= $Router::route('dashboard_project_list');?>
    <button class="btn btn-light btn-sm" id="backToList" data-url="<?= $Router::route('dashboard_project_list');?>">
      <i class="bi bi-arrow-left-circle"></i> Retour à la liste
    </button>
  </div>

  <div class="card-body">
    <form id="projectForm" action="<?= isset($project) ? $Router::route('dashboard_project_update',['id'=>$project->id]) : $Router::route('dashboard_project_store'); ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf; ?>">

      <div class="row g-3">

        <!-- Titre -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Titre du projet</label>
          <input type="text" name="title" class="form-control" placeholder="Ex : Application de gestion budgétaire" value="<?=isset($project) ? $project->title:'';?>" required>
        </div>

        <!-- Catégorie -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Catégorie</label>
          <input type="text" name="category" class="form-control" placeholder="Ex : Application Mobile" value="<?=isset($project) ? $project->category:'';?>" required>
        </div>

        <!-- Client -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Client</label>
          <input type="text" name="customer" value="<?=isset($project) ? $project->customer:'';?>"  class="form-control">
        </div>

        <!-- Date -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Date du projet</label>
          <input type="date" name="project_date" class="form-control" value="<?=isset($project) ? $project->project_date:'';?>">
        </div>

        <!-- Statut -->
        

        <div class="col-md-6">
            <label class="form-label fw-semibold" for="status">Statut</label>
            <select class="form-select" name="status">
                <option value="open" <?= (isset($project) && $project->status == 'open') ? 'selected' : '' ?>>En cours</option>
                <option value="close" <?= (isset($project) && $project->status == 'close') ? 'selected' : '' ?>>Terminé</option>
                <option value="pending" <?= (isset($project) && $project->status == 'pending') ? 'selected' : '' ?>>En attente</option>
            </select>
        </div>


        <!-- Lien de démo -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Lien du projet / Démo</label>
          <input type="text" name="link_demo" class="form-control" placeholder="https://monprojet.com" value="<?=isset($project) ? $project->link_demo:'';?>">
        </div>

        <!-- Image -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Image principale</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <!-- Vidéo -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Vidéo d’aperçu (Lien ou fichier)</label>
          <input type="text" name="video_url" class="form-control mb-2" placeholder="https://youtu.be/..." value="<?=isset($project) ? htmlspecialchars($project->video_url):'';?>">
          <input type="file" name="video_file" class="form-control" accept="video/*" value="<?=isset($project) ? htmlspecialchars($project->video_file):'';?>">
          <div class="form-text">Tu peux ajouter soit un lien YouTube/Vimeo, soit un fichier MP4.</div>
        </div>

        
        <!-- Description -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Description</label>
          <textarea name="description" rows="3" class="form-control" required><?=isset($project) ? nl2br(htmlspecialchars($project->description)):'';?></textarea>
        </div>

        <!-- Fonctionnalités -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Fonctionnalités principales</label>
          <textarea name="features" rows="3" class="form-control" placeholder="Séparez par des virgules"><?=isset($project) ? nl2br(htmlspecialchars($project->features)):'';?></textarea>
        </div>
       
        <div class="col-md-6">
          <label class="form-label fw-bold">Tags du projet</label>

          <div class="border rounded p-3" style="max-height: 180px; overflow-y: auto;">
            <?php if (!empty($tags)): ?>
              <?php  $selectedTagIds = [];

                  if (!empty($selectedTags)) {
                      // Extraire tous les tag_id des objets
                      $selectedTagIds = array_map(function ($t) {
                          return $t->tag_id ?? $t->id ?? null; // sécurise selon ton champ
                      }, $selectedTags);
                  }
                  ?>
              <?php foreach ($tags as $tag): ?>
                <?php 
                  // Vérifie si le tag est déjà sélectionné (en mode édition)
                  //$isChecked = isset($selectedTags) && in_array($tag->id, $selectedTags);
                  $isChecked = in_array($tag->id, $selectedTagIds);
                ?>
                <div class="form-check">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="tags[]"
                    id="tag_<?= $tag->id ?>"
                    value="<?= $tag->id ?>"
                    <?= $isChecked ? 'checked' : '' ?>
                  >
                  <label class="form-check-label" for="tag_<?= $tag->id ?>">
                    <?= htmlspecialchars($tag->name) ?>
                  </label>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted small">Aucun tag disponible. <a href="create_tag.php">Ajouter des tags</a></p>
            <?php endif; ?>
          </div>
        </div>

    <!-- Technologies -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Technologies</label>
          <input type="text" name="technologies" class="form-control" placeholder="Ex : React Native, Node.js, Firebase" value="<?=isset($project) ? nl2br(htmlspecialchars($project->technologies)):'';?>">
        </div>


        

        <!-- Bouton -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success px-4">
            <i class="bi bi-save2"></i> Enregistrer
          </button>
        </div>

      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const tagInput = document.getElementById('tagInput');
  const tagsContainer = document.getElementById('tags-container');
  const tagsHidden = document.getElementById('tagsHidden');
  const tags = [];

  tagInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && tagInput.value.trim() !== '') {
      e.preventDefault();
      const tag = tagInput.value.trim();
      if (!tags.includes(tag)) {
        tags.push(tag);
        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary';
        badge.textContent = tag;
        badge.style.cursor = 'pointer';
        badge.title = 'Cliquer pour supprimer';
        badge.addEventListener('click', () => {
          tags.splice(tags.indexOf(tag), 1);
          badge.remove();
          tagsHidden.value = JSON.stringify(tags);
        });
        tagsContainer.insertBefore(badge, tagInput);
        tagsHidden.value = JSON.stringify(tags);
      }
      tagInput.value = '';
    }
  });
});
document.getElementById('backToList').addEventListener('click', async e => {
        const url =e.target.dataset.url;
        window.location.href = url;
      
    });

</script>







<!-- Tags 
        <div class="col-12">
          <label class="form-label fw-semibold">Tags du projet</label>
          <div id="tags-container" class="d-flex flex-wrap gap-2">
            <input type="text" id="tagInput" class="form-control w-auto" placeholder="Ajouter un tag...">
          </div>
          <input type="hidden" name="tags" id="tagsHidden">
          <div class="form-text">Appuyez sur Entrée pour ajouter un tag.</div>
        </div>
-->