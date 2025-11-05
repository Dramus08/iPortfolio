<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>

<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4cc9f0;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --success-color: #4bb543;
        --warning-color: #ffc107;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 20px 0;
    }
    .auth-container {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 650px;
        width: 100%;
        margin: 0 auto;
    }
    
    .auth-header {
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 30px 20px;
        text-align: center;
    }
    
    .auth-header h2 {
        margin: 0;
        font-weight: 600;
    }
    
    .auth-body {
        padding: 20px;
    }
    
    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e1e5ee;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }
    
    .divider {
        display: flex;
        align-items: center;
        margin: 25px 0;
    }
    
    .divider::before, .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background-color: #e1e5ee;
    }
    
    .divider span {
        padding: 0 15px;
        color: #6c757d;
        font-size: 14px;
    }
    
    .social-login {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .social-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e1e5ee;
        background-color: white;
        transition: all 0.3s;
    }
    
    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .auth-footer {
        text-align: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #e1e5ee;
    }
    
    .auth-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .auth-footer a:hover {
        text-decoration: underline;
    }
    
    .validation-container {
        text-align: center;
        padding: 40px 30px;
    }
    
    .validation-icon {
        font-size: 80px;
        color: var(--primary-color);
        margin-bottom: 20px;
    }
    
    .progress {
        height: 8px;
        margin: 30px 0;
    }
    
    .floating-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        min-width: 300px;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
    }
    
    .floating-alert.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
    }
    
    .password-container {
        position: relative;
    }
    
    .password-strength {
        height: 5px;
        margin-top: 5px;
        border-radius: 5px;
        transition: all 0.3s;
    }
    
    .strength-0 {
        width: 20%;
        background-color: #dc3545;
    }
    
    .strength-1 {
        width: 40%;
        background-color: #fd7e14;
    }
    
    .strength-2 {
        width: 60%;
        background-color: #ffc107;
    }
    
    .strength-3 {
        width: 80%;
        background-color: #20c997;
    }
    
    .strength-4 {
        width: 100%;
        background-color: #198754;
    }
    
    .countdown {
        font-size: 18px;
        font-weight: bold;
        color: var(--primary-color);
    }
</style>

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
    #toast-container {
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

    .email-form {
  max-height: 0;
  overflow: hidden;
  opacity: 0;
  transform: translateY(-5px);
  transition: all 0.4s ease;
    }

    .email-form.active {
    max-height: 500px; /* Ajuste selon la taille du contenu */
    opacity: 1;
    transform: translateY(0);
    }
</style>

  <!-- Conteneur principal -->
  <div class="container">

      <!-- Page d'attente de validation email -->
      <div id="validation-page" class="auth-container">
        <div class="validation-container">
            <div class="validation-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h3>Vérifiez votre email</h3>
            <p class="text-muted">Nous avons envoyé un lien de confirmation à <strong id="user-email"><?=$user->email ;?></strong></p>
            <p class="text-muted">Cliquez sur le lien dans l'email pour activer votre compte.</p>
            <p><a href="<?=$Router::route('index');?>">Retour à l’accueil</a></p>
            <div class="progress mt-4 mb-4">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 65%"></div>
            </div>
            
            <p class="text-muted">Vous n'avez pas reçu l'email?</p>
            <form method="POST" action="<?= $Router::route('resend_token', ['id' => $user->id]); ?>" style="display:inline-block;"> 
            <input type="hidden" name="csrf" value="<?= $csrf;?>">

              <button type="submit" class="btn btn-outline-primary me-2" id="resend-email">Renvoyer le lien de confirmation</button>
            </form>

            <!-- Bouton de modification -->
          <button class="btn btn-outline-secondary toggle-email-btn">
            Modifier mon adresse e-mail
          </button>

          <!-- Formulaire caché -->
          <div id="change-email" class="email-form mt-3">
            <form action="<?= $Router::route('change_email', ['id' => $user->id]) ?>" method="post">
              <input type="hidden" name="csrf" value="<?= $csrf;?>">
              <div class="mb-3">
                <p>Ancienne Addresse Email: <b class="text-danger"><?=$user->email;?></b></p>
                <input type="email" name="new_email" class="form-control" placeholder="Nouvelle adresse e-mail" required>
              </div>
              <button type="submit" class="btn btn-warning">Mettre à jour et renvoyer le lien</button>
            </form>
          </div>


   <?php
// s'assurer que $user existe et contient token_expires_at
$expiresIso = '';
if (!empty($user)) {
    // si $user est un objet
    if (is_object($user) && isset($user->token_expires_at)) {
        $expiresIso = date('c', strtotime($user->token_expires_at));
    }
    // si $user est un tableau
    if (is_array($user) && isset($user['token_expires_at'])) {
        $expiresIso = date('c', strtotime($user['token_expires_at']));
    }
}
?>         
            
            
            <div class="mt-4">
                <p class="text-muted">Temps restant avant expiration du lien: <span class="countdown"></span></p>
                <p class="text-muted text-dark bg-warning" id="endCountDown"></p>
            </div>
        </div>
      </div>
    
  </div>

  <?php $this->displayToastMessages(); ?>
  <!-- Bootstrap JS avec Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    const tokenExpiresAt = "<?= $expiresIso ? htmlspecialchars($expiresIso, ENT_QUOTES) : ''?>";
    document.addEventListener("DOMContentLoaded", () => {
    // Récupère le bouton et le formulaire
      startCountdown();
    const toggleBtn = document.querySelector('.toggle-email-btn');
  const formContainer = document.getElementById('change-email');

  if (toggleBtn && formContainer) {
    toggleBtn.addEventListener('click', () => {
      const isVisible = formContainer.classList.toggle('active');

      // Change dynamiquement le texte du bouton
      toggleBtn.textContent = isVisible
        ? "Annuler la modification"
        : "Modifier mon adresse e-mail";
    });
  }

      // Éléments DOM
      const loginPage = document.getElementById('login-page');
      const registerPage = document.getElementById('register-page');
      const validationPage = document.getElementById('validation-page');
      //const showRegisterLink = document.getElementById('show-register');
      const endCountDown = document.getElementById('endCountDown');
      const loginForm = document.getElementById('login-form');
      const registerForm = document.getElementById('register-form');
      const resendEmailBtn = document.getElementById('resend-email');
      const changeEmailBtn = document.getElementById('change-email');
      const userEmailSpan = document.getElementById('user-email');
      const alertElement = document.querySelector('.floating-alert');
      const  countdownElement= document.querySelector('.countdown');
      
      // Fonction pour afficher une alerte
      
      // Fonction pour masquer l'alerte
      function hideAlert() {
          alertElement.classList.remove('show');
      }
      
      
      // Fonction pour le compte à rebours
        if (!countdownElement || !tokenExpiresAt) return;

  function startCountdown() {
    const expirationTime = new Date(tokenExpiresAt).getTime();
    const countdownInterval = setInterval(() => {
      const now = new Date().getTime();
      const distance = expirationTime - now;

      if (distance <= 0) {
        clearInterval(countdownInterval);
        countdownElement.textContent = "00:00:00";
        showToast('warning',"⏰ Le lien de confirmation a expiré. Veuillez en demander un nouveau.");
        endCountDown.textContent= "⏰ Le lien de confirmation a expiré. Veuillez en demander un nouveau.";
        return;
      }

      const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
      const minutes = Math.floor((distance / (1000 * 60)) % 60);
      const seconds = Math.floor((distance / 1000) % 60);

      countdownElement.textContent = 
        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);
  }

      function showToast(type, message, time = 5000) {
            // Créer ou récupérer le conteneur de toast
            const toastContainer = document.getElementById('toastContainer') || createToastContainer();
            
            // Créer un nouveau toast à chaque fois plutôt que de réutiliser le même
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = 'toast fade show';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            // Déterminer l'icône et le titre en fonction du type
            const typeConfig = {
                success: { icon: 'check-circle', title: 'Succès' },
                danger: { icon: 'exclamation-triangle', title: 'Erreur' },
                error: { icon: 'exclamation-triangle', title: 'Erreur' },
                warning: { icon: 'exclamation-circle', title: 'Avertissement' },
                info: { icon: 'info-circle', title: 'Information' }
            };
            
            const config = typeConfig[type] || typeConfig.info;
            
            // Construire le contenu du toast
            toast.innerHTML = `
                <div class="toast-header">
                    <strong class="me-auto text-${type}"><i class="bi bi-${config.icon} mx-1"></i>${config.title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body bg-${type} text-white rounded p-2 mb-2 shadow">
                    ${message}
                </div>
            `;
            
            // Ajouter le toast au conteneur
            toastContainer.appendChild(toast);
            
            // Gérer la fermeture automatique
            const closeToast = () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            };
            
            // Fermeture automatique après le délai
            const timeoutId = setTimeout(closeToast, time);
            
            // Gérer la fermeture manuelle
            const closeButton = toast.querySelector('.btn-close');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    clearTimeout(timeoutId);
                    closeToast();
                });
            }
            
            // Activer le toast avec Bootstrap si disponible
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                new bootstrap.Toast(toast).show();
            }
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1100';
            document.body.appendChild(container);
            return container;
        }
    });  
  </script>
</body>
</html>