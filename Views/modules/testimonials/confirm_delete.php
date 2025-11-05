<div class="card shadow-sm border-0 col-md-6 p-4">

  <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-trash3"></i>Supprimer un enregistrement</h5>
    <button class="btn btn-light btn-sm" id="backToList" data-url="<?= \Core\Router::route('dashboard_testimonial_list');?>">
      <i class="bi bi-arrow-left-circle"></i> Retour à la liste
    </button>
  </div>

  <div class="card-body">
<form id="TestimonialForm" action="<?=\Core\Router::route('dashboard_testimonial_confirm_delete',['id'=>$testimonial->id]);?>" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrf; ?>">
        <h3>
            Voulez vous supprimer cette enregistrement <br>
            Attention Cette action est irreversible
        </h3>
        <!-- Bouton -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-danger px-4">
            <i class="bi bi-trash"></i> Confirmer Suppression
          </button>
        </div>
</form>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const tagInput = document.getElementById('tagInput');
  const tagsContainer = document.getElementById('tags-container');
  const tagsHidden = document.getElementById('tagsHidden');
  const tags = [];
});
document.getElementById('backToList').addEventListener('click', async e => {
        const url =e.target.dataset.url;
        window.location.href = url;
      
    });

</script>
