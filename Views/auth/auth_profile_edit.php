<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon Profil - iPortfolio</title>
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
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .form-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .form-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .form-card .card-header {
            background: white;
            border-bottom: 3px solid var(--primary);
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .form-section {
            padding: 2rem;
        }
        
        .section-title {
            border-left: 4px solid var(--primary);
            padding-left: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        .avatar-upload {
            text-align: center;
            padding: 2rem;
            border: 2px dashed #dee2e6;
            border-radius: 15px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .avatar-upload:hover {
            border-color: var(--primary);
            background: #e9ecef;
        }
        
        .avatar-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #e1e5ee;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .social-input-group {
            position: relative;
        }
        
        .social-input-group .input-group-text {
            border-radius: 10px 0 0 10px;
            background: var(--light);
            border: 1px solid #e1e5ee;
        }
        
        .nav-pills-custom .nav-link {
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .nav-pills-custom .nav-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .nav-pills-custom .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .progress-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }
            
            .profile-header {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include INCLUDES.'nav.php'; ?>
    <?php $this->displayToastMessagesBootstrap(); ?>

    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 mb-2">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier mon Profil
                    </h1>
                    <p class="mb-0 opacity-75">Complétez et personnalisez votre profil iPortfolio</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="<?= $Router::route('user_profile') ?>" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Retour au profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Indicateur de progression -->
        <div class="progress-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2">Profil complété à 65%</h5>
                    <div class="progress bg-white bg-opacity-25" style="height: 8px;">
                        <div class="progress-bar bg-white" style="width: 65%"></div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <small>Remplissez tous les champs pour compléter à 100%</small>
                </div>
            </div>
        </div>

        <div class="form-container">
            <form method="POST" action="<?= $Router::route('user_update_profile') ?>" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?= $user->id ?>">
                
                <div class="row">
                    <!-- Navigation latérale -->
                    <div class="col-lg-3 mb-4">
                        <div class="form-card">
                            <div class="card-header">
                                <i class="fas fa-sliders-h me-2"></i>Sections
                            </div>
                            <div class="form-section p-3">
                                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist">
                                    <a class="nav-link active" id="v-pills-avatar-tab" data-bs-toggle="pill" href="#v-pills-avatar" role="tab">
                                        <i class="fas fa-camera"></i>Photo de profil
                                    </a>
                                    <a class="nav-link" id="v-pills-personal-tab" data-bs-toggle="pill" href="#v-pills-personal" role="tab">
                                        <i class="fas fa-user"></i>Informations personnelles
                                    </a>
                                    <a class="nav-link" id="v-pills-professional-tab" data-bs-toggle="pill" href="#v-pills-professional" role="tab">
                                        <i class="fas fa-briefcase"></i>Professionnel
                                    </a>
                                    <a class="nav-link" id="v-pills-social-tab" data-bs-toggle="pill" href="#v-pills-social" role="tab">
                                        <i class="fas fa-share-alt"></i>Réseaux sociaux
                                    </a>
                                    <a class="nav-link" id="v-pills-location-tab" data-bs-toggle="pill" href="#v-pills-location" role="tab">
                                        <i class="fas fa-map-marker-alt"></i>Localisation
                                    </a>
                                    <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings" role="tab">
                                        <i class="fas fa-cog"></i>Paramètres
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-card">
                            <div class="form-section">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Enregistrer
                                    </button>
                                    <a href="<?= $Router::route('user_profile') ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu des formulaires -->
                    <div class="col-lg-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <!-- Photo de profil -->
                            <div class="tab-pane fade show active" id="v-pills-avatar" role="tabpanel">
                                <div class="form-card">
                                    <div class="card-header">
                                        <i class="fas fa-camera me-2"></i>Photo de Profil
                                    </div>
                                    <div class="form-section">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="avatar-upload">
                                                    <?php if (!empty($profile->avatar)): ?>
                                                        <img src="<?= htmlspecialchars($profile->avatar) ?>" alt="Avatar" class="avatar-preview" id="avatarPreview">
                                                    <?php else: ?>
                                                        <div class="avatar-preview bg-primary text-white d-flex align-items-center justify-content-center mx-auto">
                                                            <i class="fas fa-user fa-3x"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="mb-3">
                                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" onchange="previewImage(this)">
                                                    </div>
                                                    <small class="text-muted">Formats supportés: JPG, PNG, GIF (max. 2MB)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="section-title">Recommandations</h6>
                                                <ul class="list-unstyled">
                                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Image carrée recommandée</li>
                                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Résolution minimale: 200x200px</li>
                                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Arrière-plan neutre</li>
                                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visage bien visible</li>
                                                </ul>
                                                
                                                <?php if (!empty($profile->avatar)): ?>
                                                <div class="mt-4">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeAvatar()">
                                                        <i class="fas fa-trash me-2"></i>Supprimer la photo
                                                    </button>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations personnelles -->
                            <div class="tab-pane fade" id="v-pills-personal" role="tabpanel">
                                <div class="form-card">
                                    <div class="card-header">
                                        <i class="fas fa-user me-2"></i>Informations Personnelles
                                    </div>
                                    <div class="form-section">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="first_name" class="form-label">Prénom *</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                                       value="<?= htmlspecialchars($user->first_name ?? '') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="last_name" class="form-label">Nom *</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name"
                                                       value="<?= htmlspecialchars($user->last_name ?? '') ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="date_of_birth" class="form-label">Date de naissance</label>
                                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                                       value="<?= !empty($profile->date_of_birth) ? htmlspecialchars($profile->date_of_birth) : '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="gender" class="form-label">Genre</label>
                                                <select class="form-select" id="gender" name="gender">
                                                    <option value="">Sélectionner...</option>
                                                    <option value="male" <?= ($profile->gender ?? '') === 'male' ? 'selected' : '' ?>>Homme</option>
                                                    <option value="female" <?= ($profile->gender ?? '') === 'female' ? 'selected' : '' ?>>Femme</option>
                                                    <option value="other" <?= ($profile->gender ?? '') === 'other' ? 'selected' : '' ?>>Autre</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="bio" class="form-label">Biographie</label>
                                            <textarea class="form-control" id="bio" name="bio" rows="4" 
                                                      placeholder="Décrivez-vous en quelques mots..."><?= htmlspecialchars($profile->bio ?? '') ?></textarea>
                                            <div class="form-text">Maximum 500 caractères</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations professionnelles -->
                            <div class="tab-pane fade" id="v-pills-professional" role="tabpanel">
                                <div class="form-card">
                                    <div class="card-header">
                                        <i class="fas fa-briefcase me-2"></i>Informations Professionnelles
                                    </div>
                                    <div class="form-section">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="company" class="form-label">Entreprise</label>
                                                <input type="text" class="form-control" id="company" name="company"
                                                       value="<?= htmlspecialchars($profile->company ?? '') ?>"
                                                       placeholder="Nom de votre entreprise">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="job_title" class="form-label">Poste</label>
                                                <input type="text" class="form-control" id="job_title" name="job_title"
                                                       value="<?= htmlspecialchars($profile->job_title ?? '') ?>"
                                                       placeholder="Votre poste actuel">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="website" class="form-label">Site web personnel</label>
                                            <input type="url" class="form-control" id="website" name="website"
                                                   value="<?= htmlspecialchars($profile->website ?? '') ?>"
                                                   placeholder="https://example.com">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Réseaux sociaux -->
                            <div class="tab-pane fade" id="v-pills-social" role="tabpanel">
                                <div class="form-card">
                                    <div class="card-header">
                                        <i class="fas fa-share-alt me-2"></i>Réseaux Sociaux
                                    </div>
                                    <div class="form-section">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="social_facebook" class="form-label">Facebook</label>
                                                <div class="social-input-group input-group">
                                                    <span class="input-group-text bg-primary text-white">
                                                        <i class="fab fa-facebook-f"></i>
                                                    </span>
                                                    <input type="url" class="form-control" id="social_facebook" name="social_facebook"
                                                           value="<?= htmlspecialchars($profile->social_facebook ?? '') ?>"
                                                           placeholder="https://facebook.com/votre-profil">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="social_twitter" class="form-label">Twitter</label>
                                                <div class="social-input-group input-group">
                                                    <span class="input-group-text bg-info text-white">
                                                        <i class="fab fa-twitter"></i>
                                                    </span>
                                                    <input type="url" class="form-control" id="social_twitter" name="social_twitter"
                                                           value="<?= htmlspecialchars($profile->social_twitter ?? '') ?>"
                                                           placeholder="https://twitter.com/votre-profil">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="social_linkedin" class="form-label">LinkedIn</label>
                                                <div class="social-input-group input-group">
                                                    <span class="input-group-text bg-primary text-white">
                                                        <i class="fab fa-linkedin-in"></i>
                                                    </span>
                                                    <input type="url" class="form-control" id="social_linkedin" name="social_linkedin"
                                                           value="<?= htmlspecialchars($profile->social_linkedin ?? '') ?>"
                                                           placeholder="https://linkedin.com/in/votre-profil">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="social_github" class="form-label">GitHub</label>
                                                <div class="social-input-group input-group">
                                                    <span class="input-group-text bg-dark text-white">
                                                        <i class="fab fa-github"></i>
                                                    </span>
                                                    <input type="url" class="form-control" id="social_github" name="social_github"
                                                           value="<?= htmlspecialchars($profile->social_github ?? '') ?>"
                                                           placeholder="https://github.com/votre-profil">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Localisation -->
                            <div class="tab-pane fade" id="v-pills-location" role="tabpanel">
                                <div class="form-card">
                                    <div class="card-header">
                                        <i class="fas fa-map-marker-alt me-2"></i>Localisation
                                    </div>
                                    <div class="form-section">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Adresse</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                   value="<?= htmlspecialchars($profile->address ?? '') ?>"
                                                   placeholder="Numéro et nom de rue">
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="postal_code" class="form-label">Code postal</label>
                                                <input type="text" class="form-control" id="postal_code" name="postal_code"
                                                       value="<?= htmlspecialchars($profile->postal_code ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="city" class="form-label">Ville</label>
                                                <input type="text" class="form-control" id="city" name="city"
                                                       value="<?= htmlspecialchars($profile->city ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="country" class="form-label">Pays</label>
                                                <input type="text" class="form-control" id="country" name="country"
                                                       value="<?= htmlspecialchars($profile->country ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Paramètres -->
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel">
                                <div class="form-card">
                                    <div class="card-header">
                                        <i class="fas fa-cog me-2"></i>Paramètres
                                    </div>
                                    <div class="form-section">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="language" class="form-label">Langue</label>
                                                <select class="form-select" id="language" name="language">
                                                    <option value="fr" <?= ($profile->language ?? 'fr') === 'fr' ? 'selected' : '' ?>>Français</option>
                                                    <option value="en" <?= ($profile->language ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                                                    <option value="es" <?= ($profile->language ?? '') === 'es' ? 'selected' : '' ?>>Español</option>
                                                    <option value="de" <?= ($profile->language ?? '') === 'de' ? 'selected' : '' ?>>Deutsch</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="timezone" class="form-label">Fuseau horaire</label>
                                                <select class="form-select" id="timezone" name="timezone">
                                                    <option value="Europe/Paris" <?= ($profile->timezone ?? 'Europe/Paris') === 'Europe/Paris' ? 'selected' : '' ?>>Europe/Paris</option>
                                                    <option value="Europe/London" <?= ($profile->timezone ?? '') === 'Europe/London' ? 'selected' : '' ?>>Europe/London</option>
                                                    <option value="America/New_York" <?= ($profile->timezone ?? '') === 'America/New_York' ? 'selected' : '' ?>>America/New_York</option>
                                                    <option value="Asia/Tokyo" <?= ($profile->timezone ?? '') === 'Asia/Tokyo' ? 'selected' : '' ?>>Asia/Tokyo</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="currency" class="form-label">Devise</label>
                                                <select class="form-select" id="currency" name="currency">
                                                    <option value="EUR" <?= ($profile->currency ?? 'EUR') === 'EUR' ? 'selected' : '' ?>>Euro (€)</option>
                                                    <option value="USD" <?= ($profile->currency ?? '') === 'USD' ? 'selected' : '' ?>>Dollar US ($)</option>
                                                    <option value="GBP" <?= ($profile->currency ?? '') === 'GBP' ? 'selected' : '' ?>>Livre Sterling (£)</option>
                                                    <option value="JPY" <?= ($profile->currency ?? '') === 'JPY' ? 'selected' : '' ?>>Yen Japonais (¥)</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <h6 class="section-title">Notifications</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="notification_email" name="notification_email" 
                                                           <?= ($profile->notification_email ?? true) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="notification_email">Notifications par email</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="notification_sms" name="notification_sms"
                                                           <?= ($profile->notification_sms ?? false) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="notification_sms">Notifications par SMS</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h6 class="section-title">Confidentialité</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="privacy_public_profile" name="privacy_public_profile"
                                                           <?= ($profile->privacy_public_profile ?? true) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="privacy_public_profile">Profil public</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="privacy_show_email" name="privacy_show_email"
                                                           <?= ($profile->privacy_show_email ?? false) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="privacy_show_email">Afficher l'email publiquement</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prévisualisation de l'image
        function previewImage(input) {
            const preview = document.getElementById('avatarPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!preview) {
                        // Créer l'élément preview s'il n'existe pas
                        const uploadDiv = input.closest('.avatar-upload');
                        const newPreview = document.createElement('img');
                        newPreview.id = 'avatarPreview';
                        newPreview.className = 'avatar-preview';
                        newPreview.src = e.target.result;
                        uploadDiv.insertBefore(newPreview, uploadDiv.firstChild);
                    } else {
                        preview.src = e.target.result;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Supprimer l'avatar
        function removeAvatar() {
            if (confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')) {
                const preview = document.getElementById('avatarPreview');
                const input = document.getElementById('avatar');
                
                if (preview) {
                    preview.remove();
                }
                if (input) {
                    input.value = '';
                }
                
                // Ajouter un champ caché pour indiquer la suppression
                let deleteInput = document.getElementById('delete_avatar');
                if (!deleteInput) {
                    deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.id = 'delete_avatar';
                    deleteInput.name = 'delete_avatar';
                    deleteInput.value = '1';
                    document.querySelector('form').appendChild(deleteInput);
                }
            }
        }
        
        // Navigation automatique vers la section avec des erreurs
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier s'il y a des erreurs de validation
            const errorFields = document.querySelectorAll('.is-invalid');
            if (errorFields.length > 0) {
                const firstError = errorFields[0];
                const tabId = firstError.closest('.tab-pane').id + '-tab';
                const tabElement = document.getElementById(tabId);
                if (tabElement) {
                    new bootstrap.Tab(tabElement).show();
                }
            }
            
            // Gestion de la navigation par onglets
            const navPills = document.querySelectorAll('.nav-pills-custom .nav-link');
            navPills.forEach(pill => {
                pill.addEventListener('click', function() {
                    navPills.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
        
        // Validation en temps réel
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('blur', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
    </script>
</body>
</html>