<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier <?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?> - iPortfolio</title>
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
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4361ee, #3f37c9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
        }
        
        .form-section {
            border-left: 4px solid #4361ee;
            padding-left: 1rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="form-container">
            <!-- En-tête avec avatar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <div class="user-avatar me-3">
                        <?= strtoupper(substr($user->first_name ?? $user->username, 0, 1)) ?>
                    </div>
                    <div>
                        <h1 class="h3 mb-0">Modifier l'utilisateur</h1>
                        <p class="text-muted mb-0"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></p>
                    </div>
                </div>
                <a href="<?= $Router::route('user_list') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <!-- Formulaire -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier les informations
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= $Router::route('user_edit', ['id' => $user->id]) ?>">
                        <!-- Informations de base -->
                        <div class="form-section">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-id-card me-2"></i>Informations personnelles
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?= htmlspecialchars($formData['first_name'] ?? $user->first_name) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                           value="<?= htmlspecialchars($formData['last_name'] ?? $user->last_name) ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="<?= htmlspecialchars($formData['name'] ?? $user->name) ?>">
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
                                           value="<?= htmlspecialchars($formData['username'] ?? $user->username) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?= htmlspecialchars($formData['email'] ?? $user->email) ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Laisser vide pour ne pas modifier">
                                <div class="form-text">Minimum 8 caractères</div>
                            </div>
                        </div>

                        <!-- Rôle et statut -->
                        <div class="form-section">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shield-alt me-2"></i>Rôle et statut
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Rôle *</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="user" <?= ($formData['role'] ?? $user->role) === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                        <option value="staff" <?= ($formData['role'] ?? $user->role) === 'staff' ? 'selected' : '' ?>>Staff</option>
                                        <option value="manager" <?= ($formData['role'] ?? $user->role) === 'manager' ? 'selected' : '' ?>>Manager</option>
                                        <option value="admin" <?= ($formData['role'] ?? $user->role) === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                                        <?php if ($this->isSuperAdmin()): ?>
                                        <option value="superadmin" <?= ($formData['role'] ?? $user->role) === 'superadmin' ? 'selected' : '' ?>>Super Administrateur</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                           value="<?= htmlspecialchars($formData['phone'] ?? $user->phone) ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_staff" name="is_staff" 
                                               <?= ($formData['is_staff'] ?? $user->is_staff) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_staff">Membre du staff</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               <?= ($formData['is_active'] ?? $user->is_active) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">Compte actif</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_confirmed" name="email_confirmed"
                                               <?= ($formData['email_confirmed'] ?? $user->email_confirmed) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="email_confirmed">Email confirmé</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de compte -->
                        <div class="form-section">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informations du compte
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de création</label>
                                    <input type="text" class="form-control" value="<?= date('d/m/Y H:i', strtotime($user->created_at)) ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Dernière modification</label>
                                    <input type="text" class="form-control" value="<?= date('d/m/Y H:i', strtotime($user->updated_at)) ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <a href="<?= $Router::route('user_list') ?>" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <a href="<?= $Router::route('user_show', ['id' => $user->id]) ?>" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i>Voir
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
