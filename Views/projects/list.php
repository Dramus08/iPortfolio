<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets - Ismail Inoua | Expert Comptable & Développeur Full-Stack</title>
    
    <!-- Bootstrap 5 CSS -->
      <?php include INCLUDES.'head.php' ;?>    
 
    
   <link rel="stylesheet" href="assets/css/projects.css">
</head>
<body>
    <!-- Navigation -->
       <?php include INCLUDES.'navbar.php' ;?>    


    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Mes Projets</h1>
            <p>Découvrez mes réalisations les plus significatives, alliant expertise comptable et innovation technologique</p>
        </div>
    </section>

    <!-- Projects Detailed -->
    <section class="projects-detailed">
        <div class="container">
            <div class="project-filters">
                <button class="project-filter active" data-filter="all">Tous les projets</button>
                <button class="project-filter" data-filter="comptabilite">Solutions Comptables</button>
                <button class="project-filter" data-filter="fintech">Applications FinTech</button>
                <button class="project-filter" data-filter="web">Développement Web</button>
                <button class="project-filter" data-filter="mobile">Applications Mobile</button>
            </div>
            
            <!-- Projet 1 -->
            <div class="project-card-detailed" data-category="comptabilite fintech">
                <div class="project-media-detailed">
                    <img src="https://via.placeholder.com/1200x600" alt="Plateforme de Gestion Comptable">
                    <div class="project-overlay">
                        <a href="project-detail.html" class="btn btn-light">Voir les détails</a>
                    </div>
                </div>
                <div class="project-content-detailed">
                    <div class="project-meta">
                        <span class="project-category">Solution Comptable</span>
                        <span class="project-date"><i class="far fa-calendar me-1"></i> Janvier 2023</span>
                    </div>
                    <h3>Plateforme de Gestion Comptable Automatisée</h3>
                    <p>Une plateforme complète de gestion comptable qui automatise les processus de saisie, de rapprochement bancaire et de génération d'états financiers pour les PME et cabinets comptables.</p>
                    
                    <ul class="project-features">
                        <li>Reconnaissance automatique des documents</li>
                        <li>Rapprochement bancaire intelligent</li>
                        <li>Génération automatique des écritures</li>
                        <li>Tableaux de bord temps réel</li>
                        <li>Conformité fiscale intégrée</li>
                    </ul>
                    
                    <div class="project-tech">
                        <span class="tech-tag">React</span>
                        <span class="tech-tag">Node.js</span>
                        <span class="tech-tag">MongoDB</span>
                        <span class="tech-tag">Python</span>
                        <span class="tech-tag">OCR</span>
                    </div>
                    
                    <div class="project-gallery">
                        <h5>Galerie du projet</h5>
                        <div class="gallery-grid">
                            <a href="https://via.placeholder.com/800x600" data-lightbox="project1" class="gallery-item">
                                <img src="https://via.placeholder.com/300x200" alt="Interface dashboard">
                            </a>
                            <a href="https://via.placeholder.com/800x600" data-lightbox="project1" class="gallery-item">
                                <img src="https://via.placeholder.com/300x200" alt="Reconnaissance de documents">
                            </a>
                            <a href="https://via.placeholder.com/800x600" data-lightbox="project1" class="gallery-item">
                                <video controls>
                                    <source src="#" type="video/mp4">
                                </video>
                            </a>
                        </div>
                    </div>
                    
                    <a href="project-detail.html" class="btn btn-primary mt-3">Voir le projet complet</a>
                </div>
            </div>
            
            <!-- Projet 2 -->
            <div class="project-card-detailed" data-category="fintech web">
                <div class="project-media-detailed">
                    <video controls poster="https://via.placeholder.com/1200x600">
                        <source src="#" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                    <div class="project-overlay">
                        <a href="project-detail.html" class="btn btn-light">Voir les détails</a>
                    </div>
                </div>
                <div class="project-content-detailed">
                    <div class="project-meta">
                        <span class="project-category">Application FinTech</span>
                        <span class="project-date"><i class="far fa-calendar me-1"></i> Mars 2023</span>
                    </div>
                    <h3>Système de Facturation Intelligente</h3>
                    <p>Une solution de facturation automatisée avec intelligence artificielle pour optimiser les processus de facturation, de relance et d'analyse des données financières.</p>
                    
                    <ul class="project-features">
                        <li>Génération automatique de factures</li>
                        <li>Relance des impayés intelligente</li>
                        <li>Analyse de la trésorerie</li>
                        <li>Intégration bancaire sécurisée</li>
                        <li>Reporting personnalisable</li>
                    </ul>
                    
                    <div class="project-tech">
                        <span class="tech-tag">Vue.js</span>
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">MySQL</span>
                        <span class="tech-tag">TensorFlow</span>
                        <span class="tech-tag">API Banking</span>
                    </div>
                    
                    <a href="project-detail.html" class="btn btn-primary">Voir le projet complet</a>
                </div>
            </div>
            
            <!-- Projet 3 -->
            <div class="project-card-detailed" data-category="mobile fintech">
                <div class="project-media-detailed">
                    <img src="https://via.placeholder.com/1200x600" alt="Application Mobile Financière">
                    <div class="project-overlay">
                        <a href="project-detail.html" class="btn btn-light">Voir les détails</a>
                    </div>
                </div>
                <div class="project-content-detailed">
                    <div class="project-meta">
                        <span class="project-category">Application Mobile</span>
                        <span class="project-date"><i class="far fa-calendar me-1"></i> Juin 2023</span>
                    </div>
                    <h3>Application de Gestion Budgétaire Personnelle</h3>
                    <p>Une application mobile intuitive pour la gestion du budget personnel, avec analyse des dépenses, objectifs d'épargne et conseils financiers personnalisés.</p>
                    
                    <ul class="project-features">
                        <li>Suivi des dépenses en temps réel</li>
                        <li>Catégorisation automatique</li>
                        <li>Objectifs d'épargne personnalisés</li>
                        <li>Alertes de budget</li>
                        <li>Conseils financiers IA</li>
                    </ul>
                    
                    <div class="project-tech">
                        <span class="tech-tag">React Native</span>
                        <span class="tech-tag">Firebase</span>
                        <span class="tech-tag">Node.js</span>
                        <span class="tech-tag">Machine Learning</span>
                        <span class="tech-tag">Push Notifications</span>
                    </div>
                    
                    <a href="project-detail.html" class="btn btn-primary">Voir le projet complet</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Showcase -->
    <section class="project-showcase">
        <div class="container">
            <h2 class="text-center mb-5">Projets en Vedette</h2>
            
            <!-- Showcase 1 -->
            <div class="showcase-item">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="showcase-content">
                            <span class="project-category">Solution Entreprise</span>
                            <h3>ERP Comptable Intégré</h3>
                            <p>Un ERP complet spécialement conçu pour les cabinets comptables, intégrant gestion de clientèle, production comptable, et collaboration en temps réel.</p>
                            
                            <div class="showcase-stats">
                                <div class="stat-box">
                                    <div class="stat-number">+40%</div>
                                    <div class="stat-label">Efficacité</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-number">-60%</div>
                                    <div class="stat-label">Erreurs</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-number">25+</div>
                                    <div class="stat-label">Clients</div>
                                </div>
                            </div>
                            
                            <a href="project-detail.html" class="btn btn-primary">Voir l'étude de cas</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="project-media-detailed">
                            <img src="https://via.placeholder.com/600x400" alt="ERP Comptable">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Showcase 2 -->
            <div class="showcase-item">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="showcase-content">
                            <span class="project-category">Plateforme SaaS</span>
                            <h3>Solution de Reporting Financier</h3>
                            <p>Une plateforme SaaS de reporting financier qui transforme les données brutes en insights actionnables grâce à l'IA et la visualisation avancée.</p>
                            
                            <div class="showcase-stats">
                                <div class="stat-box">
                                    <div class="stat-number">15K+</div>
                                    <div class="stat-label">Utilisateurs</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-number">99.9%</div>
                                    <div class="stat-label">Disponibilité</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-number">4.8/5</div>
                                    <div class="stat-label">Satisfaction</div>
                                </div>
                            </div>
                            
                            <a href="project-detail.html" class="btn btn-primary">Voir l'étude de cas</a>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="project-media-detailed">
                            <video controls>
                                <source src="#" type="video/mp4">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="text-center mb-5">Ce que disent mes clients</h2>
            
            <div class="testimonial-carousel">
                <div class="testimonial-item active">
                    <div class="testimonial-content">
                        "La plateforme développée par Ismail a transformé notre façon de travailler. L'automatisation des processus comptables nous a permis de réduire les erreurs de 60% et d'augmenter notre productivité de 40%. Son expertise à la fois technique et comptable est vraiment unique."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="https://via.placeholder.com/60x60" alt="Marie Lambert">
                        </div>
                        <div class="author-info">
                            <h5>Marie Lambert</h5>
                            <p>Directrice Financière, FinTech Solutions</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        "Ismail a su comprendre nos besoins spécifiques en matière de gestion comptable et a développé une solution sur mesure qui dépasse nos attentes. Le projet a été livré dans les délais et le support post-livraison est excellent."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="https://via.placeholder.com/60x60" alt="Thomas Dubois">
                        </div>
                        <div class="author-info">
                            <h5>Thomas Dubois</h5>
                            <p>CEO, Innov'Compta</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="carousel-controls">
                <button class="carousel-btn prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Vous avez un projet en tête ?</h2>
            <p>Discutons de votre idée et créons ensemble la solution parfaite pour votre entreprise</p>
            <a href="contact.html" class="btn btn-light">Démarrer un projet</a>
        </div>
    </section>

    <!-- Footer -->
       <?php include INCLUDES.'footer.php' ;?>    


    <!-- Bootstrap 5 JS and Lighbox js-->
    <?php include INCLUDES.'vendor-js-file.php' ;?>    

    <!-- Custom JS -->
   <script src="assets/js/projects.js"></script>
</body>
</html>