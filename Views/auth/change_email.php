<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement d'adresse email</title>
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
        --danger-color: #dc3545;
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
    
    .btn-outline-secondary {
        border-radius: 8px;
        padding: 12px;
        font-weight: 500;
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
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
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
    
    .instruction-box {
        background-color: #f8f9fa;
        border-left: 4px solid var(--primary-color);
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 0 8px 8px 0;
    }
    
    .instruction-box p {
        margin-bottom: 5px;
        font-size: 14px;
        color: #495057;
    }
    
    .instruction-box i {
        color: var(--primary-color);
        margin-right: 8px;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #495057;
    }
    
    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 25px;
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100px;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
        color: #6c757d;
    }
    
    .step.active .step-circle {
        background-color: var(--primary-color);
        color: white;
    }
    
    .step.completed .step-circle {
        background-color: var(--success-color);
        color: white;
    }
    
    .step-line {
        flex-grow: 1;
        height: 2px;
        background-color: #e9ecef;
        margin-top: 19px;
    }
    
    .step:last-child .step-line {
        display: none;
    }
    
    .step.completed .step-line {
        background-color: var(--success-color);
    }
    
    .step-text {
        font-size: 12px;
        text-align: center;
        color: #6c757d;
    }
    
    .step.active .step-text {
        color: var(--primary-color);
        font-weight: 500;
    }
    
    .confirmation-modal .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .confirmation-modal .modal-header {
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        color: white;
        border-bottom: none;
    }
    
    .confirmation-modal .modal-body {
        padding: 30px;
        text-align: center;
    }
    
    .confirmation-icon {
        font-size: 60px;
        color: var(--success-color);
        margin-bottom: 20px;
    }
    
    @media (max-width: 576px) {
        .auth-body {
            padding: 20px;
        }
        
        .validation-container {
            padding: 30px 20px;
        }
        
        .step {
            width: 80px;
        }
        
        .step-text {
            font-size: 11px;
        }
    }
</style>

<!-- Alertes flottantes -->
<?php //$this->displayToastMessages(); ?>
<?php //$this->displayToastMessagesBootstrap(); ?>


<!-- Conteneur principal -->
<div class="container">
    <!-- Page de changement d'email -->
    <div id="validation-page" class="auth-container">
        <div class="auth-header">
            <h2><i class="fas fa-envelope me-2"></i>Changement d'adresse email</h2>
        </div>
        
        <div class="auth-body">
            <!-- Indicateur d'étapes -->
            <div class="step-indicator">
                <div class="step completed">
                    <div class="step-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-text">Identification</div>
                </div>
                <div class="step active">
                    <div class="step-circle">2</div>
                    <div class="step-line"></div>
                    <div class="step-text">Nouvel email</div>
                </div>
                <div class="step">
                    <div class="step-circle">3</div>
                    <div class="step-text">Confirmation</div>
                </div>
            </div>
            
            <div class="validation-container">
                <div class="validation-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h3 class="mb-4">Mise à jour de votre adresse email</h3>
                
                <div class="instruction-box">
                    <p><i class="fas fa-info-circle"></i>Veuillez saisir votre nouvelle adresse email</p>
                    <p><i class="fas fa-envelope"></i>Un lien de confirmation vous sera envoyé</p>
                    <p><i class="fas fa-shield-alt"></i>Votre sécurité est notre priorité</p>
                </div>
                
                <div class="progress mt-4 mb-4">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 70%"></div>
                </div>
                
                <form action="<?= $Router::route('change_email',['slug'=>$_SESSION['slug']]) ?>" method="post" id="check_email">
                    <input type="hidden" name="csrf" value="<?= $csrf;?>">
                    <div class="mb-4">
                        <label for="email" class="form-label">Nouvelle adresse email</label>
                        <div class="form-controls">
                            <input type="email" name="email" id="email" class="form-control" placeholder="saisissez votre nouvelle adresse email" required>
                        </div>
                        <div class="form-text text-muted mt-2">
                            Nous enverrons un lien de confirmation à cette adresse.
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= $_SESSION['email_confirmed']? $Router::route('auth_profile', ['slug' => $_SESSION['slug']]) :$Router::route('waiting_confirmation_mail', ['slug' => $_SESSION['slug']]) ;?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Mettre à jour l'email
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal fade confirmation-modal" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation requise</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4 class="mb-3">Confirmer le changement d'email</h4>
                <p class="mb-4">Êtes-vous sûr de vouloir changer votre adresse email pour <strong id="newEmailDisplay"></strong> ?</p>
                <p class="text-muted">Un email de confirmation sera envoyé à cette nouvelle adresse.</p>
                <div class="d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmChange">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS avec Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?=ASSETS."js/toast.js";?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('check_email');
        const emailInput = document.getElementById('email');
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        const newEmailDisplay = document.getElementById('newEmailDisplay');
        const confirmChangeBtn = document.getElementById('confirmChange');
        
        // Validation de l'email en temps réel
        emailInput.addEventListener('blur', function() {
            if (this.value && !isValidEmail(this.value)) {
                this.classList.add('is-invalid');
                // Ajouter un message d'erreur si nécessaire
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        // Interception de la soumission du formulaire
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validation de l'email
                if (!isValidEmail(emailInput.value)) {
                    showToast('danger', 'Veuillez saisir une adresse email valide', 5000);
                    return;
                }
                
                // Afficher la modal de confirmation
                newEmailDisplay.textContent = emailInput.value;
                confirmationModal.show();
            });
        }
        
        // Confirmation du changement d'email
        confirmChangeBtn.addEventListener('click', async function() {
            confirmationModal.hide();
            
            const formData = new FormData(form);
            
            try {
                const res = await fetch(form.action, { 
                    method: 'POST', 
                    body: formData,
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                
                const data = await res.json();
                
                if (data.success) {
                    //console.log(data);
                    showToast('success', data.message, 10000);
                    
                    // Mettre à jour l'interface si nécessaire
                    setTimeout(() => {
                        // Redirection ou mise à jour de l'interface
                        // window.location.href = data.redirect || '/';
                    }, 8000);
                } else {
                    showToast('danger', data.message || 'Erreur lors de la mise à jour de l\'email', 8000);
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('danger', 'Une erreur s\'est produite. Veuillez réessayer.', 8000);
            }
        });
        
        // Fonction de validation d'email
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
       
    });
</script>
</body>
</html>