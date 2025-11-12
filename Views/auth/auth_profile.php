<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Profil - <?= htmlspecialchars($user->name) ?> - iPortfolio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= ASSETS."css/dashboard.css"; ?>">
  <link rel="stylesheet" href="<?= ASSETS."css/toast.css"; ?>">
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
    
    .profile-header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      border-radius: 20px;
      padding: 3rem 2rem;
      margin-bottom: 2rem;
      position: relative;
      overflow: hidden;
    }
    
    .profile-header::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 300px;
      height: 300px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      transform: translate(100px, -100px);
    }
    
    .user-avatar-xl {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      border: 5px solid white;
      font-size: 4rem;
      font-weight: bold;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .profile-card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
      transition: transform 0.3s ease;
    }
    
    .profile-card:hover {
      transform: translateY(-5px);
    }
    
    .profile-card .card-header {
      background: white;
      border-bottom: 3px solid var(--primary);
      border-radius: 20px 20px 0 0 !important;
      font-weight: 600;
      font-size: 1.1rem;
      padding: 1.5rem;
    }
    
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      padding: 1.5rem;
    }
    
    .info-item {
      display: flex;
      align-items: flex-start;
      padding: 1rem;
      border-radius: 12px;
      background: var(--light);
      transition: background-color 0.3s ease;
    }
    
    .info-item:hover {
      background: #e9ecef;
    }
    
    .info-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      background: var(--primary);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .info-content {
      flex: 1;
    }
    
    .info-label {
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 0.25rem;
    }
    
    .info-value {
      color: var(--dark);
      margin-bottom: 0;
    }
    
    .social-links {
      display: flex;
      gap: 0.5rem;
      margin-top: 0.5rem;
    }
    
    .social-link {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: var(--light);
      color: var(--dark);
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .social-link:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
    }
    
    .stats-card {
      text-align: center;
      padding: 2rem 1rem;
      border-radius: 15px;
      background: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
      transform: translateY(-3px);
    }
    
    .stats-number {
      font-size: 2.5rem;
      font-weight: bold;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }
    
    .stats-label {
      color: var(--dark);
      font-weight: 500;
    }
    
    .nav-pills-custom .nav-link {
      border-radius: 12px;
      padding: 1rem 1.5rem;
      margin-bottom: 0.5rem;
      color: var(--dark);
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .nav-pills-custom .nav-link.active {
      background: var(--primary);
      color: white;
      transform: translateX(5px);
    }
    
    .nav-pills-custom .nav-link i {
      margin-right: 0.5rem;
      width: 20px;
    }
    
    .badge-custom {
      border-radius: 10px;
      padding: 0.5rem 1rem;
      font-weight: 500;
    }
    
    .progress-custom {
      height: 8px;
      border-radius: 10px;
    }
    
    .edit-btn {
      border-radius: 12px;
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .edit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .section-title {
      border-left: 4px solid var(--primary);
      padding-left: 1rem;
      margin-bottom: 1.5rem;
      font-weight: 600;
    }
    
    @media (max-width: 768px) {
      .user-avatar-xl {
        width: 120px;
        height: 120px;
        font-size: 3rem;
      }
      
      .profile-header {
        padding: 2rem 1rem;
      }
      
      .info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <?php include INCLUDES.'nav.php'; ?>
  <?php $this->displayToastMessagesBootstrap(); ?>

  <div class="container-fluid py-4">
    <!-- En-tête du profil -->
    <div class="profile-header">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <div class="d-flex align-items-center">
            <div class="user-avatar-xl bg-white text-primary d-flex align-items-center justify-content-center me-4">
              <?= strtoupper(substr($user->first_name ?? $user->username, 0, 1)) ?>
            </div>
            <div class="flex-grow-1">
              <h1 class="h2 mb-2">Bonjour, <?= isset($user->first_name) || isset($user->last_name) ? htmlspecialchars($user->first_name . ' ' . $user->last_name):htmlspecialchars($user->name); ?> 👋</h1>
              <p class="mb-3 opacity-75 lead">Bienvenue sur votre espace personnel iPortfolio</p>
              <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="badge badge-custom bg-light text-dark">
                  <i class="fas fa-user me-1"></i>@<?= htmlspecialchars($user->username) ?>
                </span>
                <span class="badge badge-custom bg-<?= match($user->role) {
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
                <span class="badge badge-custom bg-<?= $user->is_active ? 'success' : 'danger' ?>">
                  <i class="fas fa-<?= $user->is_active ? 'check-circle' : 'ban' ?> me-1"></i>
                  <?= $user->is_active ? 'Compte Actif' : 'Compte Désactivé' ?>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
          <div class="d-flex flex-column flex-sm-row gap-2 justify-content-lg-end">
            <a href="<?= $Router::route('user_profile_edit') ?>" class="btn btn-light edit-btn">
              <i class="fas fa-edit me-2"></i>Modifier le profil
            </a>
            <a href="<?= $Router::route('change_password', ['id' => $user->id]) ?>" class="btn btn-outline-light edit-btn">
              <i class="fas fa-key me-2"></i>Changer MDP
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Navigation latérale -->
      <div class="col-lg-3 mb-4">
        <div class="profile-card">
          <div class="card-header">
            <i class="fas fa-sliders-h me-2"></i>Navigation
          </div>
          <div class="card-body p-3">
            <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist">
              <a class="nav-link active" id="v-pills-overview-tab" data-bs-toggle="pill" href="#v-pills-overview" role="tab">
                <i class="fas fa-chart-line"></i>Aperçu général
              </a>
              <a class="nav-link" id="v-pills-personal-tab" data-bs-toggle="pill" href="#v-pills-personal" role="tab">
                <i class="fas fa-user"></i>Informations personnelles
              </a>
              <a class="nav-link" id="v-pills-professional-tab" data-bs-toggle="pill" href="#v-pills-professional" role="tab">
                <i class="fas fa-briefcase"></i>Informations professionnelles
              </a>
              <a class="nav-link" id="v-pills-social-tab" data-bs-toggle="pill" href="#v-pills-social" role="tab">
                <i class="fas fa-share-alt"></i>Réseaux sociaux
              </a>
              <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings" role="tab">
                <i class="fas fa-cog"></i>Paramètres
              </a>
              <a class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" href="#v-pills-security" role="tab">
                <i class="fas fa-shield-alt"></i>Sécurité
              </a>
            </div>
          </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="profile-card">
          <div class="card-header">
            <i class="fas fa-chart-bar me-2"></i>Statistiques
          </div>
          <div class="card-body">
            <div class="row g-3 text-center">
              <div class="col-6">
                <div class="stats-card">
                  <div class="stats-number">12</div>
                  <div class="stats-label">Projets</div>
                </div>
              </div>
              <div class="col-6">
                <div class="stats-card">
                  <div class="stats-number text-success">47</div>
                  <div class="stats-label">Activités</div>
                </div>
              </div>
              <div class="col-6">
                <div class="stats-card">
                  <div class="stats-number text-info">128</div>
                  <div class="stats-label">Connexions</div>
                </div>
              </div>
              <div class="col-6">
                <div class="stats-card">
                  <div class="stats-number text-warning">365</div>
                  <div class="stats-label">Jours actif</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenu principal -->
      <div class="col-lg-9">
        <div class="tab-content" id="v-pills-tabContent">
          <!-- Aperçu général -->
          <div class="tab-pane fade show active" id="v-pills-overview" role="tabpanel">
            <div class="profile-card">
              <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Aperçu Général
              </div>
              <div class="card-body">
                <div class="row mb-4">
                  <div class="col-md-6">
                    <h5 class="section-title">Profil complété à 75%</h5>
                    <div class="progress progress-custom mb-3">
                      <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                    <p class="text-muted">Complétez votre profil pour améliorer votre visibilité.</p>
                  </div>
                  <div class="col-md-6">
                    <h5 class="section-title">Prochaines actions</h5>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Vérifier l'email</span>
                        <i class="fas fa-check text-success"></i>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Ajouter une photo</span>
                        <i class="fas fa-times text-danger"></i>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Compléter le profil</span>
                        <i class="fas fa-sync text-warning"></i>
                      </li>
                    </ul>
                  </div>
                </div>
                
                <div class="info-grid">
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Email</div>
                      <div class="info-value"><?= htmlspecialchars($user->email) ?></div>
                      <?php if ($user->email_confirmed): ?>
                        <small class="text-success"><i class="fas fa-check-circle"></i> Confirmé</small>
                      <?php else: ?>
                        <small class="text-warning"><i class="fas fa-clock"></i> En attente</small>
                      <?php endif; ?>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Téléphone</div>
                      <div class="info-value">
                        <?= !empty($profile->phone) ? htmlspecialchars($profile->phone) : '<span class="text-muted">Non renseigné</span>' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Localisation</div>
                      <div class="info-value">
                        <?= !empty($profile->city) ? htmlspecialchars($profile->city) . ', ' . htmlspecialchars($profile->country) : '<span class="text-muted">Non renseigné</span>' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Poste actuel</div>
                      <div class="info-value">
                        <?= !empty($profile->job_title) ? htmlspecialchars($profile->job_title) : '<span class="text-muted">Non renseigné</span>' ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Informations personnelles -->
          <div class="tab-pane fade" id="v-pills-personal" role="tabpanel">
            <div class="profile-card">
              <div class="card-header">
                <i class="fas fa-user me-2"></i>Informations Personnelles
              </div>
              <div class="card-body">
                <div class="info-grid">
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-id-card"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Nom complet</div>
                      <div class="info-value"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Nom d'utilisateur</div>
                      <div class="info-value">@<?= htmlspecialchars($user->username) ?></div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-birthday-cake"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Date de naissance</div>
                      <div class="info-value">
                        <?= !empty($profile->date_of_birth) ? date('d/m/Y', strtotime($profile->date_of_birth)) : '<span class="text-muted">Non renseigné</span>' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-venus-mars"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Genre</div>
                      <div class="info-value">
                        <?= !empty($profile->gender) ? ucfirst($profile->gender) : '<span class="text-muted">Non renseigné</span>' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Bio</div>
                      <div class="info-value">
                        <?= !empty($profile->bio) ? htmlspecialchars($profile->bio) : '<span class="text-muted">Aucune biographie renseignée</span>' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-home"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Adresse</div>
                      <div class="info-value">
                        <?= !empty($profile->address) ? htmlspecialchars($profile->address) : '<span class="text-muted">Non renseignée</span>' ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Informations professionnelles -->
          <div class="tab-pane fade" id="v-pills-professional" role="tabpanel">
            <div class="profile-card">
              <div class="card-header">
                <i class="fas fa-briefcase me-2"></i>Informations Professionnelles
              </div>
              <div class="card-body">
                <div class="info-grid">
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-building"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Entreprise</div>
                      <div class="info-value">
                        <?= !empty($profile->company) ? htmlspecialchars($profile->company) : '<span class="text-muted">Non renseignée</span>' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Poste</div>
                      <div class="info-value">
                        <?= !empty($profile->job_title) ? htmlspecialchars($profile->job_title) : '<span class="text-muted">Non renseigné</span>' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-globe"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Site web</div>
                      <div class="info-value">
                        <?php if (!empty($profile->website)): ?>
                          <a href="<?= htmlspecialchars($profile->website) ?>" target="_blank"><?= htmlspecialchars($profile->website) ?></a>
                        <?php else: ?>
                          <span class="text-muted">Non renseigné</span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Réseaux sociaux -->
          <div class="tab-pane fade" id="v-pills-social" role="tabpanel">
            <div class="profile-card">
              <div class="card-header">
                <i class="fas fa-share-alt me-2"></i>Réseaux Sociaux
              </div>
              <div class="card-body">
                <div class="info-grid">
                  <?php
                  $socials = [
                    'facebook' => ['icon' => 'facebook', 'label' => 'Facebook', 'value' => $profile->social_facebook ?? ''],
                    'twitter' => ['icon' => 'twitter', 'label' => 'Twitter', 'value' => $profile->social_twitter ?? ''],
                    'linkedin' => ['icon' => 'linkedin', 'label' => 'LinkedIn', 'value' => $profile->social_linkedin ?? ''],
                    'github' => ['icon' => 'github', 'label' => 'GitHub', 'value' => $profile->social_github ?? '']
                  ];
                  
                  foreach ($socials as $key => $social):
                  ?>
                  <div class="info-item">
                    <div class="info-icon bg-<?= $key === 'facebook' ? 'primary' : ($key === 'twitter' ? 'info' : ($key === 'linkedin' ? 'primary' : 'dark')) ?>">
                      <i class="fab fa-<?= $social['icon'] ?>"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label"><?= $social['label'] ?></div>
                      <div class="info-value">
                        <?php if (!empty($social['value'])): ?>
                          <a href="<?= htmlspecialchars($social['value']) ?>" target="_blank">Voir le profil</a>
                        <?php else: ?>
                          <span class="text-muted">Non renseigné</span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Paramètres -->
          <div class="tab-pane fade" id="v-pills-settings" role="tabpanel">
            <div class="profile-card">
              <div class="card-header">
                <i class="fas fa-cog me-2"></i>Paramètres
              </div>
              <div class="card-body">
                <div class="info-grid">
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-language"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Langue</div>
                      <div class="info-value"><?= !empty($profile->language) ? htmlspecialchars($profile->language) : 'Français' ?></div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Fuseau horaire</div>
                      <div class="info-value"><?= !empty($profile->timezone) ? htmlspecialchars($profile->timezone) : 'Europe/Paris' ?></div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-euro-sign"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Devise</div>
                      <div class="info-value"><?= !empty($profile->currency) ? htmlspecialchars($profile->currency) : 'EUR' ?></div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-bell"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Notifications email</div>
                      <div class="info-value">
                        <?= ($profile->notification_email ?? true) ? 'Activées' : 'Désactivées' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">
                      <i class="fas fa-sms"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Notifications SMS</div>
                      <div class="info-value">
                        <?= ($profile->notification_sms ?? false) ? 'Activées' : 'Désactivées' ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sécurité -->
          <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
            <div class="profile-card">
              <div class="card-header">
                <i class="fas fa-shield-alt me-2"></i>Sécurité
              </div>
              <div class="card-body">
                <div class="info-grid">
                  <div class="info-item">
                    <div class="info-icon bg-success">
                      <i class="fas fa-key"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Mot de passe</div>
                      <div class="info-value">Dernier changement: <?= !empty($profile->password_changed_at) ? date('d/m/Y', strtotime($profile->password_changed_at)) : 'Jamais' ?></div>
                      <a href="<?= $Router::route('change_password', ['id' => $user->id]) ?>" class="btn btn-sm btn-outline-primary mt-2">Changer le mot de passe</a>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon bg-info">
                      <i class="fas fa-eye"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Profil public</div>
                      <div class="info-value">
                        <?= ($profile->privacy_public_profile ?? true) ? 'Visible' : 'Privé' ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon bg-warning">
                      <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Email public</div>
                      <div class="info-value">
                        <?= ($profile->privacy_show_email ?? false) ? 'Visible' : 'Caché' ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= ASSETS."js/toast.js"; ?>"></script>
  <script>
    // Animation pour les onglets
    document.addEventListener('DOMContentLoaded', function() {
      const navPills = document.querySelectorAll('.nav-pills-custom .nav-link');
      navPills.forEach(pill => {
        pill.addEventListener('click', function() {
          navPills.forEach(p => p.classList.remove('active'));
          this.classList.add('active');
        });
      });
    });
  </script>
</body>
</html>