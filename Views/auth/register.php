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
    <link rel="stylesheet" href="<?=ASSETS."css/toast.css";?>">

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


 <!-- Page d'inscription -->
<div id="register-page" class="auth-container" style="">
    <div class="auth-header">
        <h2><i class="fas fa-user-plus me-2"></i>Inscription</h2>
        <p class="mb-0">Créez votre compte</p>
    </div>
    <div class="auth-body">
        <form id="register-form" method="POST" action="<?=$Router::route('register');?>">
            <input type="hidden" name="csrf_token" value="<?= $csrf; ?>" >    
            <div class="row">
                <!--
                <div class="col-md-6 mb-3">
                    <label for="register-firstname" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="register-firstname" placeholder="Votre prénom" name="first_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="register-lastname" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="register-lastname" placeholder="Votre nom" name="last_name" required>
                </div>
                -->
               
            </div>
            <div class="mb-3">
                    <label for="register-username" class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="register-username" name="username"  placeholder="nom d'utilisateur" required>
                    </div>
            </div>
            <div class="mb-3">
                <label for="register-email" class="form-label">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="register-email" name="email"  placeholder="votre@email.com" required>
                </div>
            </div>
            <div class="mb-3">
                    <label for="register-email" class="form-label">Numero Telephone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="tel" class="form-control" id="register-name" name="phone"  placeholder="00237xxxxxxxxx" required>
                    </div>
            </div>
            <div class="mb-3">
                <label for="register-password" class="form-label">Mot de passe</label>
                <div class="password-container">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" id="register-password" placeholder="Créez un mot de passe" required>
                    </div>
                    <button type="button" class="password-toggle" id="register-password-toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength strength-0 mt-2" id="password-strength"></div>
                <div class="form-text" id="password-feedback">Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.</div>
            </div>
            <div class="mb-3">
                <label for="register-confirm-password" class="form-label">Confirmer le mot de passe</label>
                <div class="password-container">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="confirm_password" id="register-confirm-password" placeholder="Confirmez votre mot de passe" required>
                    </div>
                    <button type="button" class="password-toggle" id="register-confirm-password-toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="confirm-password-feedback">Les mots de passe ne correspondent pas.</div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="accept-terms" required>
                <label class="form-check-label" for="accept-terms">J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a></label>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">S'inscrire</button>
        
        </form>
    </div>
    
    <div class="auth-footer">
        <p>Vous avez déjà un compte? <a href="<?=$Router::route('login');?>" id="show-login">Se connecter</a></p>
    </div>
</div>
    <?php //$this->displayToastMessages(); ?>
    <?php $this->displayToastMessagesBootstrap(); ?>



<!-- Bootstrap JS avec Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?=ASSETS."js/toast.js";?>"></script>


<script>
    // Éléments DOM
    const loginPage = document.getElementById('login-page');
    const registerPage = document.getElementById('register-page');
    const validationPage = document.getElementById('validation-page');
    //const showRegisterLink = document.getElementById('show-register');
    //const showLoginLink = document.getElementById('show-login');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const resendEmailBtn = document.getElementById('resend-email');
    const changeEmailBtn = document.getElementById('change-email');
    const userEmailSpan = document.getElementById('user-email');
    const alertElement = document.querySelector('.floating-alert');
    
    // Fonction pour afficher une alerte
    function showAlert(message, type = 'success') {
        const alertMessage = alertElement.querySelector('.alert-message');
        alertMessage.textContent = message;
        
        // Changer la classe en fonction du type
        alertElement.className = `floating-alert alert alert-${type} alert-dismissible fade show`;
        
        // Masquer automatiquement après 5 secondes
        setTimeout(() => {
            hideAlert();
        }, 5000);
    }
    
    // Fonction pour masquer l'alerte
    function hideAlert() {
        alertElement.classList.remove('show');
    }
    
    // Gestion de l'inscription


    // Gestion de l'inscription
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        //const firstName = document.getElementById('register-firstname').value;
        //const lastName = document.getElementById('register-lastname').value;
        const email = document.getElementById('register-email').value;
        const username = document.getElementById('register-username').value;
        const password = document.getElementById('register-password').value;
        const confirmPassword = document.getElementById('register-confirm-password').value;
        const acceptTerms = document.getElementById('accept-terms').checked;
        
        // Validation côté client uniquement
        if ( !username || !email || !password || !confirmPassword) {
            showToast('danger','Veuillez remplir tous les champs.');
            return;
        }
        
        // Validation du format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showToast('danger','Veuillez entrer une adresse email valide.');
            return;
        }
        
        if (password !== confirmPassword) {
            showToast('danger','Les mots de passe ne correspondent pas.');
            return;
        }
        
        if (password.length < 8) {
            showToast( 'danger','Le mot de passe doit contenir au moins 8 caractères.');
            return;
        }
        
        if (!acceptTerms) {
            showToast( 'danger','Veuillez accepter les conditions d\'utilisation.');
            return;
        }
        
        // Afficher un indicateur de charnement
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Inscription en cours...';
        submitBtn.disabled = true;
        
        // Soumettre le formulaire après un court délai pour l'UI
        setTimeout(() => {
            this.submit();
        }, 500);
    });
        
    
    // Fonction pour le compte à rebours
    function startCountdown() {
        let timeLeft = 15 * 60; // 15 minutes en secondes
        const countdownElement = document.querySelector('.countdown');
        
        const countdownInterval = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                countdownElement.textContent = '00:00';
                showToast('warning','Le lien de confirmation a expiré. Veuillez en demander un nouveau.');
            } else {
                timeLeft--;
            }
        }, 1000);
    }
    
    // Fonctionnalité de basculement de visibilité du mot de passe
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
    });
    
    // Validation de la force du mot de passe
    const passwordInput = document.getElementById('register-password');
    const passwordStrength = document.getElementById('password-strength');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Longueur minimale
        if (password.length >= 8) strength++;
        
        // Contient des lettres minuscules et majuscules
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        
        // Contient des chiffres
        if (/[0-9]/.test(password)) strength++;
        
        // Contient des caractères spéciaux
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Mise à jour de l'affichage de la force
        passwordStrength.className = `password-strength strength-${strength} mt-2`;
    });
    
    // Validation de la confirmation du mot de passe
    const confirmPasswordInput = document.getElementById('register-confirm-password');
    
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
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
</script>
</body>
</html>
