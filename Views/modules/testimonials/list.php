<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="fw-bold">Liste des temoignages</h3>
  <button id="addBtn" class="btn btn-primary" data-url="<?=\Core\Router::route("dashboard_testimonial_create");?>">
    <i class="bi bi-plus-circle"></i> Ajouter un Temoignage
  </button>
</div>
<div id="TestimonialTable">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>name</th>
        <th>icon</th>
        <th>description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($testimonials as $t): ?>
      <tr>
        <td><?= htmlspecialchars($t->name) ?></td>
        <td><?= htmlspecialchars($t->icon) ?></td>
        <td><?= htmlspecialchars($t->description) ?></td>
        
        <td>
          <button class="btn btn-sm btn-warning editTestimonial edit" data-id="<?= $t->id ?>" data-url="<?=\Core\Router::route("dashboard_testimonial_edit",['id'=>$t->id]);?>">Modifier</button>
          <button class="btn btn-sm btn-danger deleteTestimonial delete" data-id="<?= $t->id ?>" data-url="<?=\Core\Router::route("dashboard_testimonial_delete",['id'=> $t->id]);?>">Supprimer</button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>


<script>
  document.querySelectorAll('.edit').forEach(btn => {
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


