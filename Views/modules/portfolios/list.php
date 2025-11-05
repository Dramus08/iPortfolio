<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="fw-bold">Liste des PortFolios</h3>
  <button id="addBtn" class="btn btn-primary" data-url="<?=$Router::route("dashboard_portfolio_create");?>">
    <i class="bi bi-plus-circle"></i> Ajouter un PortFolio
  </button>
</div>
<div id="PortfolioTable">
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
      <?php foreach($portfolios as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p->name) ?></td>
        <td><?= htmlspecialchars($p->icon) ?></td>
        <td><?= htmlspecialchars($p->description) ?></td>
        
        <td>
          <button class="btn btn-sm btn-warning editPortfolio edit" data-id="<?= $p->id ?>" data-url="<?=$Router::route("dashboard_portfolio_edit",['id'=>$p->id]);?>">Modifier</button>
          <button class="btn btn-sm btn-danger deletePortfolio delete" data-id="<?= $p->id ?>" data-url="<?=$Router::route("dashboard_portfolio_delete",['id'=> $p->id]);?>">Supprimer</button>
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


