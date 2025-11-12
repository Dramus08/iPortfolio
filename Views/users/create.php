<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Utilisateur - iPortfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #4361ee, #3f37c9);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        
        .form-section {
            border-left: 4px solid #4361ee;
            padding-left: 1rem;
            margin-bottom: 2rem;
        }
        
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="form-container">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-plus me-2 text-primary"></i>
                        Créer un Utilisateur
                    </h1>
                    <p class="text-muted mb-0">Ajouter un nouvel utilisateur au système</p>
                </div>
                <a href="<?= $Router::route('user_list') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <!-- Formulaire -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        Informations de l'utilisateur
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= $Router::route('user_create') ?>">
                        <!-- Informations de base -->
                        <div class="form-section">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-id-card me-2"></i>Informations personnelles
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?= htmlspecialchars($formData['first_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                           value="<?= htmlspecialchars($formData['last_name'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                                       placeholder="Sera automatiquement rempli si vide">
                            </div>
                        </div>

                        <!-- Identifiants -->
                        <div class="form-section">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-key me-2"></i>Identifiants de connexion
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Nom d'utilisateur *</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                           value="<?= htmlspecialchars($formData['username'] ?? '') ?>" required>
                                    <div class="form-text">Doit être unique</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe *</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="password" name="password" required
                                           minlength="8" placeholder="Minimum 8 caractères">
                                    <span class="password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div class="form-text">
                                    Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.
                                </div>
                            </div>
                        </div>

                        <!-- Rôle et permissions -->
                        <div class="form-section">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shield-alt me-2"></i>Rôle et permissions
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Rôle *</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Sélectionner un rôle</option>
                                        <option value="user" <?= ($formData['role'] ?? '') === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                        <option value="staff" <?= ($formData['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Staff</option>
                                        <option value="manager" <?= ($formData['role'] ?? '') === 'manager' ? 'selected' : '' ?>>Manager</option>
                                        <option value="admin" <?= ($formData['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                                        <?php if ($this->isSuperAdmin()): ?>
                                        <option value="superadmin" <?= ($formData['role'] ?? '') === 'superadmin' ? 'selected' : '' ?>>Super Administrateur</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                           value="<?= htmlspecialchars($formData['phone'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_staff" name="is_staff" 
                                               <?= ($formData['is_staff'] ?? false) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_staff">Membre du staff</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               <?= ($formData['is_active'] ?? true) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">Compte actif</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_confirmed" name="email_confirmed"
                                               <?= ($formData['email_confirmed'] ?? false) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="email_confirmed">Email confirmé</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="<?= $Router::route('user_list') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.parentNode.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
        
        // Génération automatique du nom complet
        document.getElementById('first_name').addEventListener('input', updateFullName);
        document.getElementById('last_name').addEventListener('input', updateFullName);
        
        function updateFullName() {
            const firstName = document.getElementById('first_name').value;
            const lastName = document.getElementById('last_name').value;
            const fullName = document.getElementById('name');
            
            if (firstName && lastName && !fullName.value) {
                fullName.value = firstName + ' ' + lastName;
            }
        }
        
        // Génération automatique du username
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const usernameField = document.getElementById('username');
            
            if (email && !usernameField.value) {
                usernameField.value = email.split('@')[0];
            }
        });
    </script>
</body>
</html>