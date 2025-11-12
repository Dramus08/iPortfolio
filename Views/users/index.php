<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - iPortfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4bb543;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        .stats-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .table-actions .btn {
            padding: 0.25rem 0.5rem;
            margin: 0.1rem;
        }
        
        .search-box {
            max-width: 300px;
        }
        
        .sortable {
            cursor: pointer;
            user-select: none;
        }
        
        .sortable:hover {
            background-color: var(--light);
        }
        
        .pagination .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: none;
        }
        
        .filter-badge {
            cursor: pointer;
        }
        
        .table-responsive {
            border-radius: 10px;
        }
        
        .action-dropdown {
            min-width: 200px;
        }
        
        .status-badge, .role-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
        
        .bulk-actions {
            background-color: var(--light);
            border-radius: 10px;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-users me-2 text-primary"></i>
                            Gestion des Utilisateurs
                        </h1>
                        <p class="text-muted mb-0">Administration complète des utilisateurs du système</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= $Router::route('user_create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvel Utilisateur
                        </a>
                        <a href="<?= $Router::route('dashboard_admin') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card card bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total</h6>
                                <h4 class="mb-0"><?= $stats['total'] ?? 0 ?></h4>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-25">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card card bg-success text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Actifs</h6>
                                <h4 class="mb-0"><?= $stats['active'] ?? 0 ?></h4>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-25">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card card bg-warning text-dark">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Confirmés</h6>
                                <h4 class="mb-0"><?= $stats['confirmed'] ?? 0 ?></h4>
                            </div>
                            <div class="stats-icon bg-dark bg-opacity-25">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card card bg-danger text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Inactifs</h6>
                                <h4 class="mb-0"><?= $stats['inactive'] ?? 0 ?></h4>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-25">
                                <i class="fas fa-user-slash"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card card bg-info text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Admins</h6>
                                <h4 class="mb-0"><?= $stats['admin_count'] ?? 0 ?></h4>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-25">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card card bg-dark text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Staff</h6>
                                <h4 class="mb-0"><?= $stats['staff_count'] ?? 0 ?></h4>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-25">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de contrôle -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Recherche -->
                            <div class="col-md-4 mb-2 mb-md-0">
                                <form method="GET" action="<?= $Router::route('user_search') ?>" class="d-flex">
                                    <div class="input-group search-box">
                                        <input type="text" name="q" class="form-control" placeholder="Rechercher..." 
                                               value="<?= htmlspecialchars($search ?? '') ?>">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Filtres -->
                            <div class="col-md-5 mb-2 mb-md-0">
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="text-muted small">Filtrer par:</span>
                                    <select class="form-select form-select-sm" style="width: auto;" id="roleFilter">
                                        <option value="">Tous les rôles</option>
                                        <option value="user">Utilisateur</option>
                                        <option value="staff">Staff</option>
                                        <option value="manager">Manager</option>
                                        <option value="admin">Administrateur</option>
                                        <option value="superadmin">Super Admin</option>
                                    </select>
                                    <select class="form-select form-select-sm" style="width: auto;" id="statusFilter">
                                        <option value="">Tous les statuts</option>
                                        <option value="active">Actifs</option>
                                        <option value="inactive">Inactifs</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="resetFilters()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Actions groupées -->
                            <div class="col-md-3 text-md-end">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-cog me-1"></i>Actions
                                    </button>
                                    <ul class="dropdown-menu action-dropdown">
                                        <li><a class="dropdown-item" href="#" onclick="exportUsers()">
                                            <i class="fas fa-download me-2"></i>Exporter
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="bulkActivate()">
                                            <i class="fas fa-check me-2"></i>Activer la sélection
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="bulkDeactivate()">
                                            <i class="fas fa-ban me-2"></i>Désactiver la sélection
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkDelete()">
                                            <i class="fas fa-trash me-2"></i>Supprimer la sélection
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="row">
            <div class="col-12">
                <div class="card stats-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>
                            Liste des Utilisateurs
                            <span class="badge bg-primary ms-2" id="userCount"><?= count($users ?? []) ?></span>
                        </h5>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label small" for="selectAll">
                                Tout sélectionner
                            </label>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="usersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" class="form-check-input" id="selectAllHeader">
                                        </th>
                                        <th class="sortable" data-sort="name">
                                            Utilisateur
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th class="sortable" data-sort="email">
                                            Email
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th class="sortable" data-sort="role">
                                            Rôle
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th class="sortable" data-sort="status">
                                            Statut
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th class="sortable" data-sort="created_at">
                                            Création
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th width="150" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">Aucun utilisateur trouvé</p>
                                            <a href="<?= $Router::route('user_create') ?>" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Créer le premier utilisateur
                                            </a>
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                        <tr data-user-id="<?= $user->id ?>" data-role="<?= $user->role ?>" data-status="<?= $user->is_active ? 'active' : 'inactive' ?>">
                                            <td>
                                                <input type="checkbox" class="form-check-input user-checkbox" value="<?= $user->id ?>">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                                        <?= strtoupper(substr($user->first_name ?? $user->username, 0, 1)) ?>
                                                    </div>
                                                    <div>
                                                        <strong class="d-block"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></strong>
                                                        <small class="text-muted">@<?= htmlspecialchars($user->username) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($user->email) ?>
                                                <?php if ($user->email_confirmed): ?>
                                                    <i class="fas fa-check-circle text-success ms-1" title="Email confirmé"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-clock text-warning ms-1" title="En attente de confirmation"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge role-badge bg-<?= match($user->role) {
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
                                            </td>
                                            <td>
                                                <span class="badge status-badge bg-<?= $user->is_active ? 'success' : 'danger' ?>">
                                                    <?= $user->is_active ? 'Actif' : 'Inactif' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y', strtotime($user->created_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center table-actions">
                                                    <!-- Voir -->
                                                    <a href="<?= $Router::route('user_show', ['id' => $user->id]) ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Voir le profil">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <!-- Éditer -->
                                                    <a href="<?= $Router::route('user_edit', ['id' => $user->id]) ?>" 
                                                       class="btn btn-sm btn-outline-warning mx-1" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Menu déroulant actions -->
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                                type="button" data-bs-toggle="dropdown"
                                                                title="Plus d'actions">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <!-- Activation/Désactivation -->
                                                            <?php if ($user->is_active): ?>
                                                            <li>
                                                                <a class="dropdown-item text-warning" href="#" 
                                                                   onclick="confirmDeactivate(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                                                    <i class="fas fa-ban me-2"></i>Désactiver
                                                                </a>
                                                            </li>
                                                            <?php else: ?>
                                                            <li>
                                                                <a class="dropdown-item text-success" href="#" 
                                                                   onclick="confirmActivate(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                                                    <i class="fas fa-check me-2"></i>Activer
                                                                </a>
                                                            </li>
                                                            <?php endif; ?>
                                                            
                                                            <!-- Réinitialisation mot de passe -->
                                                            <li>
                                                                <a class="dropdown-item text-info" href="#" 
                                                                   onclick="confirmResetPassword(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name) ?>')">
                                                                    <i class="fas fa-key me-2"></i>Réinitialiser MDP
                                                                </a>
                                                            </li>
                                                            
                                                            <li><hr class="dropdown-divider"></li>
                                                            
                                                            <!-- Suppression -->
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#" 
                                                                   onclick="confirmDelete(<?= $user->id ?>, '<?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?>')">
                                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Affichage de <strong><?= $pagination['start'] ?></strong> à <strong><?= $pagination['end'] ?></strong> 
                                sur <strong><?= $pagination['total'] ?></strong> utilisateurs
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <!-- Précédent -->
                                    <li class="page-item <?= $pagination['current_page'] <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= $Router::route('user_list') ?>?page=<?= $pagination['current_page'] - 1 ?>&search=<?= urlencode($search ?? '') ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    
                                    <!-- Pages -->
                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <?php if ($i == $pagination['current_page']): ?>
                                        <li class="page-item active">
                                            <span class="page-link"><?= $i ?></span>
                                        </li>
                                        <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= $Router::route('user_list') ?>?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <!-- Suivant -->
                                    <li class="page-item <?= $pagination['current_page'] >= $pagination['total_pages'] ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= $Router::route('user_list') ?>?page=<?= $pagination['current_page'] + 1 ?>&search=<?= urlencode($search ?? '') ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals de confirmation -->
    <?php include INCLUDES.'modals.php';  ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion de la sélection
        document.getElementById('selectAllHeader').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionCount();
        });

        document.getElementById('selectAll').addEventListener('change', function() {
            document.getElementById('selectAllHeader').checked = this.checked;
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionCount();
        });

        function updateSelectionCount() {
            const selected = document.querySelectorAll('.user-checkbox:checked').length;
            document.getElementById('userCount').textContent = `${selected} sélectionné(s)`;
        }

        // Filtres
        function resetFilters() {
            document.getElementById('roleFilter').value = '';
            document.getElementById('statusFilter').value = '';
            filterTable();
        }

        function filterTable() {
            const roleFilter = document.getElementById('roleFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                const role = row.getAttribute('data-role');
                const status = row.getAttribute('data-status');
                let show = true;
                
                if (roleFilter && role !== roleFilter) show = false;
                if (statusFilter && status !== statusFilter) show = false;
                
                row.style.display = show ? '' : 'none';
            });
            
            updateUserCount();
        }

        function updateUserCount() {
            const visibleRows = document.querySelectorAll('#usersTable tbody tr[style=""]').length;
            document.getElementById('userCount').textContent = visibleRows;
        }

        // Tri du tableau
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', function() {
                const sortBy = this.getAttribute('data-sort');
                sortTable(sortBy);
            });
        });

        function sortTable(sortBy) {
            // Implémentation du tri (à adapter selon vos besoins)
            console.log('Tri par:', sortBy);
        }

        // Actions groupées
        function getSelectedUsers() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        function bulkActivate() {
            const selected = getSelectedUsers();
            if (selected.length === 0) {
                alert('Veuillez sélectionner au moins un utilisateur');
                return;
            }
            if (confirm(`Activer ${selected.length} utilisateur(s) ?`)) {
                // Implémentez l'activation groupée
                console.log('Activation des utilisateurs:', selected);
            }
        }

        function bulkDeactivate() {
            const selected = getSelectedUsers();
            if (selected.length === 0) {
                alert('Veuillez sélectionner au moins un utilisateur');
                return;
            }
            if (confirm(`Désactiver ${selected.length} utilisateur(s) ?`)) {
                // Implémentez la désactivation groupée
                console.log('Désactivation des utilisateurs:', selected);
            }
        }

        function bulkDelete() {
            const selected = getSelectedUsers();
            if (selected.length === 0) {
                alert('Veuillez sélectionner au moins un utilisateur');
                return;
            }
            if (confirm(`Supprimer définitivement ${selected.length} utilisateur(s) ? Cette action est irréversible !`)) {
                // Implémentez la suppression groupée
                console.log('Suppression des utilisateurs:', selected);
            }
        }

        function exportUsers() {
            // Implémentez l'export
            console.log('Export des utilisateurs');
        }

        // Événements des filtres
        document.getElementById('roleFilter').addEventListener('change', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            updateUserCount();
        });
    </script>
</body>
</html>
