<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

    <!-- Alertes flottantes -->
    <?php //$this->displayToastMessages(); ?>
    <?php $this->displayToastMessagesBootstrap(); ?>
  <!-- Conteneur principal -->
  <div class="container">
      <!-- Page d'attente de validation email -->
      <div id="validation-page" class="auth-container">
        <div class="validation-container">
            <div class="validation-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h3>Recuperation mot de passe par email</h3>

            <p>Vous y etes presque ! </br> <br> Veuillez saisir votre addresse email et un mail vous sera envoye <br><br> Veuillez suivre le lien recu pour reinitialiser votre mot de passe </p>
            <div class="progress mt-4 mb-4">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success " role="progressbar" style="width: 70%"></div>
            </div>
            
           <form action="<?= $Router::route('forgot_password') ?>" method="post" id="check_email">
              <input type="hidden" name="csrf" value="<?= $csrf;?>">
              <div class="mb-3">
                <div class="form-controls">
                    <input type="email" name="email" class="form-control" placeholder="saissisez votre Adresse e-mail" required>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">verifier votre compte email</button>
            </form>
      </div>
    
  </div>
       <!-- Bootstrap JS avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?=ASSETS."js/toast.js";?>"></script>
  <script>
    /*
    document.addEventListener('DOMContentLoaded',()=>{
        let form=document.getElementById('check_email');
        if (form) {
            form.addEventListener('submit', async e => {
                e.preventDefault();
                const formData = new FormData(form);
                console.log(form.action);
                const res = await fetch(form.action, { method: 'POST', body: formData ,headers: {'X-Requested-With': 'XMLHttpRequest'}});
                const data = await res.json();
                console.log(data);
                if (data.success) {
                showToast('success',data.message,15000);
                const resList = await fetch(data.route);
                //const html = await resList.text();
                //mainContent.innerHTML = html;
                //attachProjectEvents();
                }
                else{
                    let element="Erreur lors de la recuperation de l'email";
                    showToast('danger',element,15000)
                }
            });
        }
    });
    */
    

    

  </script>
</body>
</html>