<?php $this->displayToastMessagesBootstrap(); ?>
<?php //$this->displayToastMessages(); ?>
<div class="card shadow-sm border-0">
  <div class="card-header bg-<?=isset($testimonial) ? 'success':'primary';?> text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-<?=isset($project) ? 'pencil':'folder-plus';?>"></i> <?=isset($project)? 'Modifier le Projet : <b>'.$project->slug."</b>":'Creer un Nouveau Projet';?></h5>
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
          <input type="text" name="title" class="form-control" placeholder="Ex : Application de gestion budgétaire" value="<?=isset($project) ? $project->title:'';?>" >
        </div>


       

        <!-- Date -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Date du projet</label>
          <input type="date" name="project_date" class="form-control" value="<?=isset($project) ? $project->project_date:'';?>">
        </div>

          <!-- Catégorie -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Catégorie</label>
          <input type="text" name="category" class="form-control" placeholder="Ex : Application Mobile" value="<?=isset($project) ? $project->category:'';?>">
        </div>

        <!-- Image -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Image principale</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        

        
        <!-- Description -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Description</label>
          <textarea name="description" rows="3" class="form-control"><?=isset($project) ? ($project->description):'';?></textarea>
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