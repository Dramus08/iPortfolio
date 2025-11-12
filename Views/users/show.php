<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur - <?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?> - iPortfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4bb543;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .user-avatar-lg {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            font-size: 3rem;
            font-weight: bold;
        }
        
        .info-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .info-card .card-header {
            background: white;
            border-bottom: 2px solid var(--primary);
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
        }
        
        .info-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--primary);
            min-width: 150px;
        }
        
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        
        .stats-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 10px;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
        }
        
        .action-btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            margin: 0.25rem;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--primary);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid white;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- En-tête du profil -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar-lg bg-white text-primary d-flex align-items-center justify-content-center me-4">
                            <?= strtoupper(substr($user->first_name ?? $user->username, 0, 1)) ?>
                        </div>
                        <div>
                            <h1 class="h2 mb-1"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></h1>
                            <p class="mb-2 opacity-75">@<?= htmlspecialchars($user->username) ?></p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-lg bg-<?= match($user->role) {
                                    'superadmin' => 'danger',
                                    'admin' => 'warning', 
                                    'manager' => 'info',
                                    'staff' => 'secondary',
                                    default => 'light text-dark'
                                } ?>">
                                    <i class="fas fa-<?= match($user->role) {
                                        'superadmin' => 'crown',
                                        'admin' => 'shield-alt',
                                        'manager' => 'user-tie',
                                        'staff' => 'user-check',
                                        default => 'user'
                                    } ?> me-1"></i>
                                    <?= ucfirst($user->role) ?>
                                </span>
                                <span class="badge badge-lg bg-<?= $user->is_active ? 'success' : 'danger' ?>">
                                    <i class="fas fa-<?= $user->is_active ? 'check-circle' : 'ban' ?> me-1"></i>
                                    <?= $user->is_active ? 'Compte Actif' : 'Compte Désactivé' ?>
                                </span>
                                <?php if ($user->email_confirmed): ?>
                                <span class="badge badge-lg bg-success">
                                    <i class="fas fa-envelope me-1"></i>Email Confirmé
                                </span>
                                <?php else: ?>
                                <span class="badge badge-lg bg-warning text-dark">
                                    <i class="fas fa-envelope me-1"></i>Email Non Confirmé
                                </span>
                                <?php endif; ?>
                                <?php if ($user->is_super_admin): ?>
                                <span class="badge badge-lg bg-danger">
                                    <i class="fas fa-star me-1"></i>Super Administrateur
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        <a href="<?= $Router::route('user_edit', ['id' => $user->id]) ?>" class="btn btn-light action-btn">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                            <span class="visually-hidden">Actions</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($user->is_active): ?>
                            <li>
                                <a class="dropdown-item text-warning" href="#" onclick="confirmDeactivate(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                    <i class="fas fa-ban me-2"></i>Désactiver
                                </a>
                            </li>
                            <?php else: ?>
                            <li>
                                <a class="dropdown-item text-success" href="#" onclick="confirmActivate(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                    <i class="fas fa-check me-2"></i>Activer
                                </a>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item text-info" href="#" onclick="confirmResetPassword(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                    <i class="fas fa-key me-2"></i>Réinitialiser MDP
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="confirmDelete(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?>')">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="<?= $Router::route('user_list') ?>" class="btn btn-outline-light action-btn">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <!-- Informations personnelles -->
                <div class="card info-card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-id-card me-2 text-primary"></i>
                        Informations Personnelles
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item d-flex">
                                    <span class="info-label">Prénom:</span>
                                    <span><?= htmlspecialchars($user->first_name) ?></span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Nom:</span>
                                    <span><?= htmlspecialchars($user->last_name) ?></span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Nom complet:</span>
                                    <span><?= htmlspecialchars($user->name) ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item d-flex">
                                    <span class="info-label">Username:</span>
                                    <span>@<?= htmlspecialchars($user->username) ?></span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Email:</span>
                                    <span>
                                        <?= htmlspecialchars($user->email) ?>
                                        <?php if ($user->email_confirmed): ?>
                                            <i class="fas fa-check-circle text-success ms-1" title="Email confirmé"></i>
                                        <?php else: ?>
                                            <i class="fas fa-clock text-warning ms-1" title="En attente de confirmation"></i>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Téléphone:</span>
                                    <span><?= $user->phone ? htmlspecialchars($user->phone) : '<span class="text-muted">Non renseigné</span>' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations du compte -->
                <div class="card info-card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-user-cog me-2 text-primary"></i>
                        Informations du Compte
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item d-flex">
                                    <span class="info-label">ID Utilisateur:</span>
                                    <span class="font-monospace">#<?= $user->id ?></span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Rôle:</span>
                                    <span>
                                        <span class="badge bg-<?= match($user->role) {
                                            'superadmin' => 'danger',
                                            'admin' => 'warning', 
                                            'manager' => 'info',
                                            'staff' => 'secondary',
                                            default => 'light text-dark'
                                        } ?>">
                                            <?= ucfirst($user->role) ?>
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Statut:</span>
                                    <span>
                                        <span class="badge bg-<?= $user->is_active ? 'success' : 'danger' ?>">
                                            <?= $user->is_active ? 'Actif' : 'Inactif' ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item d-flex">
                                    <span class="info-label">Membre du staff:</span>
                                    <span>
                                        <i class="fas fa-<?= $user->is_staff ? 'check text-success' : 'times text-danger' ?>"></i>
                                        <?= $user->is_staff ? 'Oui' : 'Non' ?>
                                    </span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Super admin:</span>
                                    <span>
                                        <i class="fas fa-<?= $user->is_super_admin ? 'check text-success' : 'times text-danger' ?>"></i>
                                        <?= $user->is_super_admin ? 'Oui' : 'Non' ?>
                                    </span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Email confirmé:</span>
                                    <span>
                                        <i class="fas fa-<?= $user->email_confirmed ? 'check text-success' : 'times text-danger' ?>"></i>
                                        <?= $user->email_confirmed ? 'Oui' : 'Non' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historique du compte -->
                <div class="card info-card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Historique du Compte
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <h6 class="mb-1">Compte créé</h6>
                                <p class="text-muted mb-1"><?= date('d/m/Y à H:i', strtotime($user->created_at)) ?></p>
                                <small class="text-muted">Le compte a été créé dans le système</small>
                            </div>
                            <div class="timeline-item">
                                <h6 class="mb-1">Dernière modification</h6>
                                <p class="text-muted mb-1"><?= date('d/m/Y à H:i', strtotime($user->updated_at)) ?></p>
                                <small class="text-muted">Dernière mise à jour des informations</small>
                            </div>
                            <?php if ($user->token_expires_at): ?>
                            <div class="timeline-item">
                                <h6 class="mb-1">Token d'activation</h6>
                                <p class="text-muted mb-1">Expire le <?= date('d/m/Y à H:i', strtotime($user->token_expires_at)) ?></p>
                                <small class="text-muted">Lien d'activation envoyé par email</small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec statistiques et actions -->
            <div class="col-lg-4">
                <!-- Statistiques rapides -->
                <div class="card info-card mb-4">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Statistiques
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-number text-primary">0</div>
                                    <small class="text-muted">Projets</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-number text-success">0</div>
                                    <small class="text-muted">Activités</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stats-card">
                                    <div class="stats-number text-info">0</div>
                                    <small class="text-muted">Connexions</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stats-card">
                                    <div class="stats-number text-warning">0</div>
                                    <small class="text-muted">Jours actif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card info-card mb-4">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Actions Rapides
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= $Router::route('user_edit', ['id' => $user->id]) ?>" class="btn btn-outline-primary text-start">
                                <i class="fas fa-edit me-2"></i>Modifier le profil
                            </a>
                            <?php if ($user->is_active): ?>
                            <button class="btn btn-outline-warning text-start" onclick="confirmDeactivate(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                <i class="fas fa-ban me-2"></i>Désactiver le compte
                            </button>
                            <?php else: ?>
                            <button class="btn btn-outline-success text-start" onclick="confirmActivate(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                <i class="fas fa-check me-2"></i>Activer le compte
                            </button>
                            <?php endif; ?>
                            <button class="btn btn-outline-info text-start" onclick="confirmResetPassword(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                <i class="fas fa-key me-2"></i>Réinitialiser le mot de passe
                            </button>
                            <button class="btn btn-outline-danger text-start" onclick="confirmDelete(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?>')">
                                <i class="fas fa-trash me-2"></i>Supprimer le compte
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="card info-card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-paper-plane me-2 text-primary"></i>
                        Contact
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="mailto:<?= htmlspecialchars($user->email) ?>" class="btn btn-outline-primary text-start">
                                <i class="fas fa-envelope me-2"></i>Envoyer un email
                            </a>
                            <?php if ($user->phone): ?>
                            <a href="tel:<?= htmlspecialchars($user->phone) ?>" class="btn btn-outline-success text-start">
                                <i class="fas fa-phone me-2"></i>Appeler
                            </a>
                            <?php endif; ?>
                            <button class="btn btn-outline-info text-start">
                                <i class="fas fa-comment me-2"></i>Envoyer un message
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals de confirmation -->
    <?php include INCLUDES.'modals.php';  ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   
</body>
</html>