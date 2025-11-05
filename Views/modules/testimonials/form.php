
<div class="card shadow-sm border-0">
  <div class="card-header bg-<?=isset($testimonial) ? 'success':'primary';?> text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-<?=isset($testimonial) ? 'pencil':'folder-plus';?>"></i> <?=isset($testimonial)? 'Modifier le Temoignage : <b>'.$testimonial->name."</b>":'Creer un Nouveau Temoignage';?></h5>
    <button class="btn btn-light btn-sm" id="backToList" data-url="<?= \Core\Router::route('dashboard_testimonial_list');?>">
      <i class="bi bi-arrow-left-circle"></i> Retour à la liste
    </button>
  </div>

  <div class="card-body">
    <form id="TestimonialForm" action="<?= isset($testimonial) ? \Core\Router::route('dashboard_testimonial_update',['id'=>$testimonial->id]) : \Core\Router::route('dashboard_testimonial_store'); ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf; ?>">

      <div class="row g-3">

        <!-- Nom -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nom du service</label>
          <input type="text" name="name" class="form-control" placeholder="Ex : Application de gestion budgétaire" value="<?=isset($testimonial) ? $testimonial->name:'';?>" required>
        </div>

        <!-- Icon -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Icon</label>
          <input type="text" name="icon" class="form-control" placeholder="Ex : Application Mobile" value="<?=isset($testimonial) ? $testimonial->icon:'';?>" required>
        </div>




        <!-- Description -->
        <div class="col-12">
          <label class="form-label fw-semibold">Description</label>
          <textarea name="description" rows="3" class="form-control" required><?=isset($testimonial) ? $testimonial->description:'';?></textarea>
        </div>

      
    <div class="mb-3">
        <label for="tags" class="form-label">Tags</label>
        <input type="text" class="form-control" name="tags" id="tags" placeholder="React, Mobile, FinTech">
    </div>

        <!-- Tags -->
        <div class="col-12">
          <label class="form-label fw-semibold">Tags du projet</label>
          <div id="tags-container" class="d-flex flex-wrap gap-2">
            <input type="text" id="tagInput" class="form-control w-auto" placeholder="Ajouter un tag...">
          </div>
          <input type="hidden" name="tags" id="tagsHidden">
          <div class="form-text">Appuyez sur Entrée pour ajouter un tag.</div>
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





