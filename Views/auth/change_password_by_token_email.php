<?php // Activer l'affichage des erreurs pour le débogage  error_reporting(E_ALL);ini_set('display_errors', 1);?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le mot de passe - iPortfolio</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?=ASSETS."css/toast.css";?>">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4bb543;
            --danger-color: #dc3545;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .password-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }
        
        .password-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px 10px;
            text-align: center;
        }
        
        .password-body {
            padding: 20px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 8px 10px;
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
            padding: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
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
            z-index: 10;
        }
        
        .password-input-group {
            position: relative;
        }
        
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .strength-0 { width: 20%; background-color: var(--danger-color); }
        .strength-1 { width: 40%; background-color: #fd7e14; }
        .strength-2 { width: 60%; background-color: #ffc107; }
        .strength-3 { width: 80%; background-color: #20c997; }
        .strength-4 { width: 100%; background-color: var(--success-color); }
        
        .requirement-list {
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .requirement i {
            margin-right: 8px;
            font-size: 0.75rem;
        }
        
        .requirement.met {
            color: var(--success-color);
        }
        
        .requirement.unmet {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php //$this->displayToastMessages(); ?>
    <?php $this->displayToastMessagesBootstrap(); ?>
    
    <div class="container">

        <div class="password-container">
            <div class="password-header">
                <h2><i class="fas fa-key me-2"></i>Modifier Votre mot de passe </h2>
                <p class="mb-0">Choisissez un nouveau mot de passe sécurisé</p>
            </div>
            
            <div class="password-body">
                <form id="changePasswordForm" action="<?= $Router::route('change_password_by_token_email',['token'=>$token]) ;?>" method="POST">
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Ancien mot de passe -->
                    <div class="mb-2">
                        <label for="current_password" class="form-label fw-semibold">
                            <i class="fas fa-lock me-2"></i>Mot de passe actuel
                        </label>
                        <div class="password-input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   placeholder="Entrez votre mot de passe actuel">
                            <button type="button" class="password-toggle" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                   <div class="d-flex w-100">
                     <!-- Nouveau mot de passe -->
                    <div class="mb-2 w-100">
                        <label for="new_password" class="form-label fw-semibold">
                            <i class="fas fa-key me-2"></i>Nouveau mot de passe
                        </label>
                        <div class="password-input-group mx-2">
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   placeholder="Créez un nouveau mot de passe">
                            <button type="button" class="password-toggle" data-target="new_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        
                        <!-- Indicateur de force du mot de passe -->
                        <div class="password-strength strength-0 mt-2" id="passwordStrength"></div>
                        
                        <!-- Exigences du mot de passe -->
                        <div class="requirement-list mt-2">
                            <div class="requirement unmet" id="req-length">
                                <i class="fas fa-circle"></i>
                                <span>Au moins 8 caractères</span>
                            </div>
                            <div class="requirement unmet" id="req-lowercase">
                                <i class="fas fa-circle"></i>
                                <span>Une lettre minuscule</span>
                            </div>
                            <div class="requirement unmet" id="req-uppercase">
                                <i class="fas fa-circle"></i>
                                <span>Une lettre majuscule</span>
                            </div>
                            <div class="requirement unmet" id="req-number">
                                <i class="fas fa-circle"></i>
                                <span>Un chiffre</span>
                            </div>
                            <div class="requirement unmet" id="req-special">
                                <i class="fas fa-circle"></i>
                                <span>Un caractère spécial</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirmation du nouveau mot de passe -->
                    <div class="mb-2 w-100">
                        <label for="confirm_password" class="form-label fw-semibold">
                            <i class="fas fa-check-double me-2"></i>Confirmer le nouveau mot de passe
                        </label>
                            <div class="password-input-group w-100 mx-2">
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   required
                                   placeholder="Confirmez votre nouveau mot de passe">
                            <button type="button" class="password-toggle" data-target="confirm_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="invalid-feedback" id="confirmPasswordFeedback">
                            Les mots de passe ne correspondent pas.
                        </div>
                    </div>
                   </div>
                    
                    <!-- Boutons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Mettre à jour le mot de passe
                        </button>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?=ASSETS."js/toast.js";?>"></script>

    
    <script>
        // Fonctionnalité de basculement de visibilité du mot de passe
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
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

        // Validation du mot de passe en temps réel
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordStrength = document.getElementById('passwordStrength');
        const submitBtn = document.getElementById('submitBtn');

        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            
            // Vérifier les exigences
            const hasMinLength = password.length >= 8;
            const hasLowercase = /[a-z]/.test(password);
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[^A-Za-z0-9]/.test(password);
            
            // Mettre à jour les indicateurs visuels
            updateRequirement('req-length', hasMinLength);
            updateRequirement('req-lowercase', hasLowercase);
            updateRequirement('req-uppercase', hasUppercase);
            updateRequirement('req-number', hasNumber);
            updateRequirement('req-special', hasSpecial);
            
            // Calculer la force du mot de passe
            let strength = 0;
            if (hasMinLength) strength++;
            if (hasLowercase) strength++;
            if (hasUppercase) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;
            
            // Mettre à jour la barre de force
            passwordStrength.className = `password-strength strength-${strength} mt-2`;
            
            // Valider la confirmation
            validatePasswordConfirmation();
        });

        confirmPasswordInput.addEventListener('input', validatePasswordConfirmation);

        function validatePasswordConfirmation() {
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword && password !== confirmPassword) {
                confirmPasswordInput.classList.add('is-invalid');
                submitBtn.disabled = true;
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
                submitBtn.disabled = false;
            }
        }

        function updateRequirement(elementId, isMet) {
            const element = document.getElementById(elementId);
            const icon = element.querySelector('i');
            
            if (isMet) {
                element.classList.remove('unmet');
                element.classList.add('met');
                icon.className = 'fas fa-check-circle';
            } else {
                element.classList.remove('met');
                element.classList.add('unmet');
                icon.className = 'fas fa-circle';
            }
        }

        // Validation du formulaire avant soumission
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (!currentPassword || !newPassword || !confirmPassword) {
                e.preventDefault();
                showToast('danger','Veuillez remplir tous les champs.');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                showToast('danger','Les mots de passe ne correspondent pas.');
                return;
            }
            
            // Vérifier la force du mot de passe
            const hasMinLength = newPassword.length >= 8;
            const hasLowercase = /[a-z]/.test(newPassword);
            const hasUppercase = /[A-Z]/.test(newPassword);
            const hasNumber = /[0-9]/.test(newPassword);
            const hasSpecial = /[^A-Za-z0-9]/.test(newPassword);
            
            if (!hasMinLength || !hasLowercase || !hasUppercase || !hasNumber || !hasSpecial) {
                e.preventDefault();
                showToast('danger','Le mot de passe ne respecte pas toutes les exigences de sécurité.');
                return;
            }
            
            // Afficher l'indicateur de chargement
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>