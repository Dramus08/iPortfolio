<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title:"Application FinTech Mobile - Portfolio | Ismail Inoua";?></title>
    
    <!-- Bootstrap 5 CSS -->
    <?php include INCLUDES.'head.php' ;?> 
    <link rel="stylesheet" href="<?=ASSETS."css/portfolio_detail.css";?>">

</head>
<body>
    <!-- Navigation -->
    <?php include INCLUDES.'navbar.php' ;?> 

    <!-- Portfolio Hero -->
    <section class="portfolio-hero">
        <div class="container">
            <nav class="portfolio-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="portfolio.html">Portfolio</a></li>
                    <li class="breadcrumb-item active">Application FinTech Mobile</li>
                </ol>
            </nav>
            
            <div class="portfolio-header">
                <div class="portfolio-title-section">
                    <h1>Application FinTech Mobile</h1>
                    <div class="portfolio-meta">
                        <span class="portfolio-category">Application Mobile & FinTech</span>
                        <span class="portfolio-date"><i class="far fa-calendar"></i> Mars 2023 - Juin 2023</span>
                    </div>
                    <p class="lead">Une application mobile révolutionnaire pour la gestion financière personnelle, combinant interface intuitive et technologies d'intelligence artificielle pour offrir une expérience utilisateur exceptionnelle.</p>
                </div>
                <div class="portfolio-stats">
                    <div class="stat-item">
                        <div class="stat-number">4.8/5</div>
                        <div class="stat-label">Note App Store</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Téléchargements</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Gallery -->
    <section class="portfolio-gallery-detailed">
        <div class="container">
            <div class="gallery-main">
                <img src="https://via.placeholder.com/1200x500" alt="Application FinTech Mobile" data-lightbox="portfolio-gallery">
            </div>
            <div class="gallery-thumbnails">
                <a href="https://via.placeholder.com/800x600" data-lightbox="portfolio-gallery" class="gallery-thumb">
                    <img src="https://via.placeholder.com/300x200" alt="Interface principale">
                </a>
                <a href="https://via.placeholder.com/800x600" data-lightbox="portfolio-gallery" class="gallery-thumb">
                    <img src="https://via.placeholder.com/300x200" alt="Tableaux de bord">
                </a>
                <a href="https://via.placeholder.com/800x600" data-lightbox="portfolio-gallery" class="gallery-thumb">
                    <video poster="https://via.placeholder.com/300x200">
                        <source src="#" type="video/mp4">
                    </video>
                </a>
                <a href="https://via.placeholder.com/800x600" data-lightbox="portfolio-gallery" class="gallery-thumb">
                    <img src="https://via.placeholder.com/300x200" alt="Fonctionnalités avancées">
                </a>
            </div>
        </div>
    </section>

    <!-- Portfolio Content -->
    <section class="portfolio-content">
        <div class="container">
            <div class="content-grid">
                <div class="portfolio-description">
                    <h2>Application de Gestion Financière Intelligente</h2>
                    <p>Cette application mobile FinTech a été conçue pour révolutionner la façon dont les utilisateurs gèrent leurs finances personnelles. En combinant une interface utilisateur intuitive avec des algorithmes d'intelligence artificielle avancés, l'application offre une expérience complète de gestion financière accessible à tous.</p>
                    
                    <p>L'objectif principal était de créer une application qui non seulement suit les dépenses, mais qui fournit également des insights actionnables et des recommandations personnalisées pour aider les utilisateurs à atteindre leurs objectifs financiers.</p>
                    
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h4>Analyse des Dépenses</h4>
                            <p>Catégorisation automatique des transactions et visualisation intuitive des habitudes de dépenses avec graphiques interactifs.</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h4>Objectifs Personnalisés</h4>
                            <p>Création et suivi d'objectifs d'épargne avec recommandations personnalisées basées sur le comportement de l'utilisateur.</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h4>Assistant IA</h4>
                            <p>Assistant virtuel intelligent qui analyse les finances et propose des optimisations en temps réel.</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4>Sécurité Renforcée</h4>
                            <p>Authentification biométrique et chiffrement de bout en bout pour protéger les données sensibles.</p>
                        </div>
                    </div>
                    
                    <h3>Défis et Solutions</h3>
                    <p>Le principal défi était de créer une interface suffisamment simple pour les débutants tout en offrant des fonctionnalités avancées pour les utilisateurs expérimentés. La solution a été de développer un système d'interface adaptative qui présente progressivement les fonctionnalités selon le niveau de compétence de l'utilisateur.</p>
                </div>
                
                <div class="portfolio-sidebar">
                    <div class="sidebar-card">
                        <h3>Technologies Utilisées</h3>
                        <div class="tech-stack">
                            <span class="tech-tag">React Native</span>
                            <span class="tech-tag">Node.js</span>
                            <span class="tech-tag">Firebase</span>
                            <span class="tech-tag">TensorFlow</span>
                            <span class="tech-tag">MongoDB</span>
                            <span class="tech-tag">AWS</span>
                        </div>
                        
                        <h3>Détails du Projet</h3>
                        <ul class="portfolio-info-list">
                            <li>
                                <span class="info-label">Client:</span>
                                <span class="info-value">FinanceVision</span>
                            </li>
                            <li>
                                <span class="info-label">Durée:</span>
                                <span class="info-value">4 mois</span>
                            </li>
                            <li>
                                <span class="info-label">Plateformes:</span>
                                <span class="info-value">iOS & Android</span>
                            </li>
                            <li>
                                <span class="info-label">Équipe:</span>
                                <span class="info-value">2 développeurs</span>
                            </li>
                            <li>
                                <span class="info-label">Statut:</span>
                                <span class="info-value">Live & Maintenu</span>
                            </li>
                        </ul>
                        
                        <a href="contact.html" class="btn btn-primary w-100 mt-3">Discuter d'un projet similaire</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Design Process -->
    <section class="design-process">
        <div class="container">
            <h2 class="text-center mb-5">Processus de Conception</h2>
            <div class="process-timeline">
                <div class="process-step">
                    <div class="process-step-number">1</div>
                    <div class="process-content">
                        <h4>Recherche Utilisateur</h4>
                        <p>Analyse approfondie des besoins des utilisateurs et étude des applications concurrentes. Création de personas et scénarios d'utilisation.</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-step-number">2</div>
                    <div class="process-content">
                        <h4>Wireframes & Prototypes</h4>
                        <p>Création de wireframes détaillés et prototypes interactifs pour valider l'expérience utilisateur avant le développement.</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-step-number">3</div>
                    <div class="process-content">
                        <h4>Design UI/UX</h4>
                        <p>Développement du design final avec une attention particulière à l'accessibilité et à la cohérence visuelle sur toutes les plateformes.</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-step-number">4</div>
                    <div class="process-content">
                        <h4>Développement & Tests</h4>
                        <p>Implémentation avec React Native, intégration des APIs et tests approfondis sur différents appareils et versions d'OS.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Results Section -->
    <section class="portfolio-results">
        <div class="container">
            <h2 class="text-center mb-5">Résultats Obtenus</h2>
            <div class="results-grid">
                <div class="result-card">
                    <div class="result-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="result-number">50K+</div>
                    <div class="result-label">Téléchargements</div>
                    <p class="mt-2 text-muted">Dans les 3 premiers mois</p>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="result-number">4.8/5</div>
                    <div class="result-label">Note Moyenne</div>
                    <p class="mt-2 text-muted">Sur l'App Store et Google Play</p>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="result-number">+35%</div>
                    <div class="result-label">Épargne Utilisateurs</div>
                    <p class="mt-2 text-muted">Augmentation moyenne de l'épargne</p>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="result-number">95%</div>
                    <div class="result-label">Satisfaction</div>
                    <p class="mt-2 text-muted">Score de satisfaction des utilisateurs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Next Project -->
    <section class="next-project">
        <div class="container">
            <div class="next-project-card">
                <h3>Projet Suivant</h3>
                <p>Découvrez comment j'ai développé une plateforme SaaS de reporting financier pour les entreprises</p>
                <a href="portfolio-detail-2.html" class="btn btn-light">Voir le projet suivant</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Intéressé par une application similaire ?</h2>
            <p>Discutons de votre projet mobile et créons ensemble la prochaine application à succès</p>
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
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Lightbox initialization
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'imageFadeDuration': 300,
            'albumLabel': "Image %1 sur %2"
        });
    </script>
</body>
</html>