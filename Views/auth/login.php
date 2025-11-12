<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            padding: 30px;
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



    <!-- Alertes flottantes -->
    <?php //$this->displayToastMessages(); ?>
    <?php $this->displayToastMessagesBootstrap(); ?>


    <!-- Conteneur principal -->
    <div class="container">
        <?php // echo $_SESSION['csrf_token'] ; ?>
        <!-- Page de connexion -->
        <div id="login-page" class="auth-container">
            <div class="auth-header">
                <h2><i class="fas fa-sign-in-alt me-2"></i>Connexion</h2>
                <p class="mb-0">Accédez à votre compte</p>
            </div>
            <div class="auth-body">
                <form id="login-form" method="POST" action="<?=$Router::route('login');?>">
                <input type="hidden" name="csrf_token" value="<?= $csrf; ?>" >    
                <div class="mb-3">
                        <label for="login-email" class="form-label">Adresse email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="login-email" placeholder="votre@email.com" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="login-password" class="form-label">Mot de passe</label>
                        <div class="password-container">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" id="login-password" placeholder="Votre mot de passe" required>
                            </div>
                            <button type="button" class="password-toggle" id="login-password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember-me">
                        <label class="form-check-label" for="remember-me">Se souvenir de moi</label>
                        <a href="<?=$Router::route('forgot_password');?>" class="float-end">Mot de passe oublié?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Se connecter</button>
                </form>
                
                <div class="divider">
                    <span>Ou connectez-vous avec</span>
                </div>
                
                <div class="social-login">
                    <button class="social-btn text-primary">
                        <i class="fab fa-google"></i>
                    </button>
                    <button class="social-btn text-primary">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button class="social-btn text-primary">
                        <i class="fab fa-twitter"></i>
                    </button>
                </div>
            </div>
            <div class="auth-footer">
                <p>Vous n'avez pas de compte? <a href="<?=$Router::route('register');?>" id="show-register">S'inscrire</a></p>
            </div>
        </div>

    </div>
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
        
        // Fonction pour masquer l'alerte
        function hideAlert() {
            alertElement.classList.remove('show');
        }
        
       
            // Gestion de la connexion
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            
            // Validation côté client uniquement
            if (!email || !password) {
                showToast( 'danger','Veuillez remplir tous les champs.');
                return;
            }
            
            // Validation basique du format email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showToast('danger','Veuillez entrer une adresse email valide.');
                return;
            }
            
            // Afficher un indicateur de charnement
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion en cours...';
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
                    showToast('danger','Le lien de confirmation a expiré. Veuillez en demander un nouveau.', 'warning');
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
        

      </script>
</body>
</html>



