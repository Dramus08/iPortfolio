<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=isset($title)?$title:"Plateforme Comptable Automatisée - Ismail Inoua";?></title>
    
   <?php include INCLUDES.'head.php' ;?> 
    <link rel="stylesheet" href="<?=ASSETS."css/project_detail.css";?>">
</head>
<body>
    <!-- Navigation -->
    <?php include INCLUDES.'navbar.php' ;?> 

    <!-- Project Hero -->
    <section class="project-hero">
        <div class="container">
            <nav class="project-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="projects.html">Projets</a></li>
                    <li class="breadcrumb-item active">Plateforme Comptable Automatisée</li>
                </ol>
            </nav>
            
            <div class="project-header">
                <div class="project-title-section">
                    <h1>Plateforme Comptable Automatisée</h1>
                    <div class="project-meta">
                        <span class="project-category">Solution Comptable & FinTech</span>
                        <span class="project-date"><i class="far fa-calendar"></i> Janvier 2023 - Avril 2023</span>
                    </div>
                    <p class="lead">Une plateforme révolutionnaire qui automatise complètement la gestion comptable grâce à l'intelligence artificielle et l'apprentissage automatique.</p>
                </div>
                <div class="project-stats">
                    <div class="stat-item">
                        <div class="stat-number">+40%</div>
                        <div class="stat-label">Efficacité</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">-60%</div>
                        <div class="stat-label">Erreurs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">25+</div>
                        <div class="stat-label">Clients</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Gallery -->
    <section class="project-gallery-detailed">
        <div class="container">
            <div class="main-gallery-item">
                <img src="https://via.placeholder.com/1200x500" alt="Dashboard de la plateforme comptable" data-lightbox="project-gallery">
            </div>
            <div class="gallery-thumbnails">
                <a href="https://via.placeholder.com/800x600" data-lightbox="project-gallery" class="gallery-thumb">
                    <img src="https://via.placeholder.com/300x200" alt="Interface de saisie automatique">
                </a>
                <a href="https://via.placeholder.com/800x600" data-lightbox="project-gallery" class="gallery-thumb">
                    <img src="https://via.placeholder.com/300x200" alt="Tableaux de bord analytics">
                </a>
                <a href="https://via.placeholder.com/800x600" data-lightbox="project-gallery" class="gallery-thumb">
                    <video poster="https://via.placeholder.com/300x200">
                        <source src="#" type="video/mp4">
                    </video>
                </a>
                <a href="https://via.placeholder.com/800x600" data-lightbox="project-gallery" class="gallery-thumb">
                    <img src="https://via.placeholder.com/300x200" alt="Gestion des documents">
                </a>
            </div>
        </div>
    </section>

    <!-- Project Content -->
    <section class="project-content">
        <div class="container">
            <div class="content-grid">
                <div class="project-description">
                    <h2>Description du Projet</h2>
                    <p>Cette plateforme comptable automatisée a été développée pour répondre aux défis croissants de la digitalisation dans le secteur comptable. En combinant l'expertise comptable traditionnelle avec les dernières avancées en intelligence artificielle, nous avons créé une solution qui transforme radicalement la façon dont les entreprises gèrent leur comptabilité.</p>
                    
                    <p>Le système permet l'automatisation complète des processus de saisie, de classification et de rapprochement des documents comptables, réduisant ainsi considérablement le temps passé sur les tâches manuelles tout en améliorant la précision des données.</p>
                    
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h4>Reconnaissance Intelligente</h4>
                            <p>OCR avancé avec machine learning pour l'extraction automatique des données des factures, reçus et autres documents comptables.</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-sync"></i>
                            </div>
                            <h4>Rapprochement Automatique</h4>
                            <p>Algorithme intelligent de rapprochement bancaire qui identifie et corrèle automatiquement les transactions.</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4>Analytics en Temps Réel</h4>
                            <p>Tableaux de bord interactifs avec indicateurs de performance et alertes prédictives.</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4>Sécurité Renforcée</h4>
                            <p>Chiffrement de bout en bout et conformité aux normes de sécurité les plus strictes.</p>
                        </div>
                    </div>
                </div>
                
                <div class="project-sidebar">
                    <div class="sidebar-card">
                        <h3>Technologies Utilisées</h3>
                        <div class="tech-stack">
                            <span class="tech-tag">React</span>
                            <span class="tech-tag">Node.js</span>
                            <span class="tech-tag">Python</span>
                            <span class="tech-tag">TensorFlow</span>
                            <span class="tech-tag">MongoDB</span>
                            <span class="tech-tag">Docker</span>
                            <span class="tech-tag">AWS</span>
                            <span class="tech-tag">OCR</span>
                        </div>
                        
                        <h3>Informations du Projet</h3>
                        <ul class="project-info-list">
                            <li>
                                <span class="info-label">Client:</span>
                                <span class="info-value">FinTech Solutions</span>
                            </li>
                            <li>
                                <span class="info-label">Durée:</span>
                                <span class="info-value">4 mois</span>
                            </li>
                            <li>
                                <span class="info-label">Budget:</span>
                                <span class="info-value">€45,000</span>
                            </li>
                            <li>
                                <span class="info-label">Équipe:</span>
                                <span class="info-value">3 développeurs</span>
                            </li>
                            <li>
                                <span class="info-label">Statut:</span>
                                <span class="info-value">Livré & Maintenu</span>
                            </li>
                        </ul>
                        
                        <a href="contact.html" class="btn btn-primary w-100 mt-3">Discuter d'un projet similaire</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Challenge Solution -->
    <section class="challenge-solution">
        <div class="container">
            <div class="challenge-grid">
                <div class="challenge-card">
                    <h3><i class="fas fa-exclamation-triangle"></i> Le Défi</h3>
                    <p>Le client faisait face à des processus comptables manuels chronophages entraînant :</p>
                    <ul>
                        <li>Plus de 15 heures par semaine consacrées à la saisie manuelle</li>
                        <li>Taux d'erreur de 8% dans la saisie des données</li>
                        <li>Délais de traitement des documents de 3-5 jours</li>
                        <li>Difficulté à scaler avec la croissance de l'entreprise</li>
                        <li>Manque de visibilité en temps réel sur la santé financière</li>
                    </ul>
                </div>
                
                <div class="solution-card">
                    <h3><i class="fas fa-lightbulb"></i> La Solution</h3>
                    <p>Nous avons développé une plateforme complète qui :</p>
                    <ul>
                        <li>Automatise 85% des processus de saisie comptable</li>
                        <li>Réduit le taux d'erreur à moins de 1%</li>
                        <li>Traite les documents en temps réel</li>
                        <li>Fournit des analytics prédictifs</li>
                        <li>S'intègre avec les systèmes existants</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Testimonial -->
    <section class="project-testimonial">
        <div class="container">
            <div class="testimonial-card-large">
                <div class="testimonial-content">
                    "La plateforme développée par Ismail a complètement transformé notre fonctionnement. Nous avons non seulement réduit nos coûts opérationnels de 40%, mais nous avons également amélioré la précision de nos données financières de façon spectaculaire. L'expertise combinée en comptabilité et en développement d'Ismail a été déterminante pour le succès de ce projet."
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <img src="https://via.placeholder.com/80x80" alt="Marie Lambert">
                    </div>
                    <div class="author-info">
                        <h4>Marie Lambert</h4>
                        <p>Directrice Financière, FinTech Solutions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Results Section -->
    <section class="project-results">
        <div class="container">
            <h2 class="text-center mb-5">Résultats Concrets</h2>
            <div class="results-grid">
                <div class="result-card">
                    <div class="result-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="result-number">+70%</div>
                    <div class="result-label">Gain de Temps</div>
                    <p class="mt-2 text-muted">Réduction du temps de traitement des documents</p>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="result-number">-92%</div>
                    <div class="result-label">Réduction d'Erreurs</div>
                    <p class="mt-2 text-muted">Amélioration de la précision des données</p>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                    <div class="result-number">€28K</div>
                    <div class="result-label">Économies Annuelles</div>
                    <p class="mt-2 text-muted">Réduction des coûts opérationnels</p>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="result-number">98%</div>
                    <div class="result-label">Satisfaction Client</div>
                    <p class="mt-2 text-muted">Score de satisfaction des utilisateurs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Vous avez un projet similaire ?</h2>
            <p>Discutons de la façon dont je peux vous aider à automatiser vos processus et améliorer votre efficacité</p>
            <a href="contact.html" class="btn btn-light">Démarrer un projet</a>
        </div>
    </section>

    <!-- Footer -->
   <?php include INCLUDES.'footer.php' ;?> 


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?=ASSETS."js/project_detail.js";?>"></script>
</body>
</html>