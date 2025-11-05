<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="fw-bold">Liste des Services</h3>
  <button id="addBtn" class="btn btn-primary" data-url="<?=$Router::route("dashboard_service_create");?>">
    <i class="bi bi-plus-circle"></i> Ajouter un Service
  </button>
</div>
<div id="ServiceTable">
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
      <?php foreach($services as $s): ?>
      <tr>
        <td><?= htmlspecialchars($s->name) ?></td>
        <td><?= htmlspecialchars($s->icon) ?></td>
        <td><?= htmlspecialchars($s->description) ?></td>
        
        <td>
          <button class="btn btn-sm btn-warning editService edit" data-id="<?= $s->id ?>" data-url="<?=$Router::route("dashboard_service_edit",['id'=>$s->id]);?>">Modifier</button>
          <button class="btn btn-sm btn-danger deleteService delete" data-id="<?= $s->id ?>" data-url="<?=$Router::route("dashboard_service_delete",['id'=> $s->id]);?>">Supprimer</button>
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


