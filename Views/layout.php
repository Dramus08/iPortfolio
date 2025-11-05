<!DOCTYPE html>
<html lang="en">
<head>
  <?php include INCLUDES.DIRECTORY_SEPARATOR."head.php"; ?>
</head>
<body>
<?php use Router\Router; ?>

<style>
    /* --- Flash Messages classiques --- */
    .flash-message {
      padding: 12px 16px;
      border-radius: 6px;
      margin: 10px 0;
      color: #fff;
      font-weight: 500;
      animation: fadeIn 0.4s ease;
    }
    .flash-success { background-color: #28a745; }
    .flash-error { background-color: #dc3545; }
    .flash-warning { background-color: #ffc107; color: #222; }
    .flash-info { background-color: #17a2b8; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* --- Toasts --- */
    #toast-container,#toast-container-flask,#toast-container-errors,#toast-container-errorsForms {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
    }

    .toast {
      background-color: #333;
      color: #fff;
      padding: 12px 18px;
      border-radius: 8px;
      margin-top: 10px;
      opacity: 0;
      transform: translateY(-20px);
      transition: opacity 0.5s ease, transform 0.5s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .toast-success { background: #28a745; }
    .toast-error { background: #dc3545; }
    .toast-warning { background: #ffc107; color: #222; }
    .toast-info { background: #17a2b8; }

    .toast.show {
      opacity: 1;
      transform: translateY(0);
    }
</style>

<!-- NAVRBAR -->
 <?php // include INCLUDES.DIRECTORY_SEPARATOR."navbar.php"; ?>

  <!-- Message flash (type alertes statiques) -->
    <div class="flash-container">
      <?php //$this->displayFlashMessages(); ?>
    </div>
 <?= $content; ?>
    <?php //include INCLUDES.DIRECTORY_SEPARATOR."footer.php"; ?>
    <!-- Toasts (notifications temporaires) -->
    <?php $this->displayToastMessages(); ?>
    <?php //$this->displayToastMessagesBootstrap(); ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
 

<?php //include INCLUDES.DIRECTORY_SEPARATOR."vendor-js-file.php"; ?>
<!-- Zone de conteneur des notifications -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000;"></div>

<!-- Bootstrap Toast Template -->
<template id="toast-template">
  <div class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-semibold"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
  </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('toast-container');
  const template = document.getElementById('toast-template');
  if (!container || !template) return;

  /**
   * Affiche un toast Bootstrap stylé
   */
  function showToast(message, type = 'info', delay = 5000) {
    const clone = template.content.cloneNode(true);
    const toastEl = clone.querySelector('.toast');
    const toastBody = clone.querySelector('.toast-body');

    // Couleur selon type
    const colors = {
      success: 'bg-success',
      error: 'bg-danger',
      warning: 'bg-warning text-dark',
      info: 'bg-info text-dark'
    };

    toastEl.classList.add(colors[type] || 'bg-secondary');
    toastBody.textContent = message;

    container.appendChild(clone);
    const toast = new bootstrap.Toast(container.lastElementChild, { delay });
    toast.show();

    // Suppression automatique après fermeture
    container.lastElementChild.addEventListener('hidden.bs.toast', (e) => e.target.remove());
  }

  /**
   * Affiche les messages flash PHP (si disponibles)
   * Injectés par ton BaseController::displayFlashMessages()
   */
  <?php if (!empty($_SESSION['flash'])): ?>
      <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
          showToast("<?= addslashes($msg) ?>", "<?= $type ?>");
      <?php endforeach; unset($_SESSION['flash']); ?>
  <?php endif; ?>
   <?php if (!empty($_SESSION['errorForms'])): ?>
      <?php foreach ($_SESSION['errorForms'] as $type => $msg): ?>
          showToast("<?= addslashes($msg) ?>", "errors");
      <?php endforeach; unset($_SESSION['errorForms']); ?>
  <?php endif; ?>

  /**
   * Si tu veux afficher directement les messages du backend dynamiquement
   * (par exemple depuis $this->response envoyé au rendu HTML)
   */
  <?php if (!empty($this->response)): ?>
      <?php
      $resp = is_array($this->response) ? (object)$this->response : $this->response;
      if (isset($resp->error) && $resp->error): ?>
          showToast("<?= addslashes($resp->message) ?>", "error");
          <?php if (!empty($resp->errors)): ?>
              <?php foreach ($resp->errors as $err): ?>
                  showToast("<?= addslashes($err) ?>", "warning");
              <?php endforeach; ?>
          <?php endif; ?>
      <?php elseif (isset($resp->success) && $resp->success): ?>
          showToast("<?= addslashes($resp->message ?? 'Opération réussie !') ?>", "success");
      <?php endif; ?>
  <?php endif; ?>
});
</script>

</body>
</html>