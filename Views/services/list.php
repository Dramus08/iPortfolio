
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : "Services - Ismail Inoua | Expert Comptable & Développeur Full-Stack";?></title>
    
       <?php include INCLUDES.'head.php' ;?>    

    <link rel="stylesheet" href="assets/css/services.css">
</head>
<body>
    <!-- Navigation -->
    <?php include INCLUDES.'navbar.php' ;?> 

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Mes Services</h1>
            <p>Des solutions complètes alliant expertise comptable et innovation technologique pour transformer votre entreprise</p>
        </div>
    </section>

    <!-- Services Detailed -->
    <section class="services-detailed">
        <div class="container">
            <!-- Expertise Comptable -->
            <div class="service-category">
                <h2>Expertise Comptable</h2>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="service-feature">
                            <div class="service-icon-lg">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <h3>Comptabilité d'Entreprise</h3>
                            <p>Gestion complète de votre comptabilité avec une approche moderne et efficace.</p>
                            <ul class="service-features-list">
                                <li>Tenue de livres comptables</li>
                                <li>Établissement des états financiers</li>
                                <li>Gestion de la paie</li>
                                <li>Déclarations fiscales</li>
                                <li>Conseil en optimisation fiscale</li>
                            </ul>
                            <div class="service-media-container">
                                <img src="https://via.placeholder.com/600x300" alt="Comptabilité d'entreprise">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="service-feature">
                            <div class="service-icon-lg">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h3>Analyse Financière</h3>
                            <p>Analyse approfondie de vos données financières pour une prise de décision éclairée.</p>
                            <ul class="service-features-list">
                                <li>Analyse des ratios financiers</li>
                                <li>Prévisions budgétaires</li>
                                <li>Tableaux de bord personnalisés</li>
                                <li>Analyse de rentabilité</li>
                                <li>Reporting stratégique</li>
                            </ul>
                            <div class="service-media-container">
                                <video controls poster="https://via.placeholder.com/600x300">
                                    <source src="#" type="video/mp4">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Développement Web -->
            <div class="service-category">
                <h2>Développement Web</h2>
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="service-feature">
                            <div class="service-icon-lg">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h3>Applications Web Sur Mesure</h3>
                            <p>Développement d'applications web performantes adaptées à vos besoins spécifiques.</p>
                            <ul class="service-features-list">
                                <li>Applications responsives</li>
                                <li>Interfaces utilisateur modernes</li>
                                <li>Bases de données optimisées</li>
                                <li>API RESTful</li>
                                <li>Tests et déploiement</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="service-feature">
                            <div class="service-icon-lg">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3>Applications Mobile</h3>
                            <p>Création d'applications mobiles natives et hybrides pour iOS et Android.</p>
                            <ul class="service-features-list">
                                <li>Applications natives React Native</li>
                                <li>Design adaptatif</li>
                                <li>Intégration d'APIs</li>
                                <li>Publication sur les stores</li>
                                <li>Maintenance et mises à jour</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="service-feature">
                            <div class="service-icon-lg">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h3>E-commerce</h3>
                            <p>Solutions e-commerce complètes pour développer votre activité en ligne.</p>
                            <ul class="service-features-list">
                                <li>Boutiques en ligne personnalisées</li>
                                <li>Paiements sécurisés</li>
                                <li>Gestion des stocks</li>
                                <li>SEO e-commerce</li>
                                <li>Analytics et reporting</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Solutions FinTech -->
            <div class="service-category">
                <h2>Solutions FinTech</h2>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="service-feature">
                            <div class="service-icon-lg">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h3>Automatisation des Processus</h3>
                            <p>Automatisation intelligente de vos processus financiers et comptables.</p>
                            <ul class="service-features-list">
                                <li>Automatisation de la facturation</li>
                                <li>Reconnaissance de documents</li>
                                <li>Intégration bancaire</li>
                                <li>Workflows automatisés</li>
                                <li>Alertes intelligentes</li>
                            </ul>
                            <div class="service-media-container">
                                <img src="https://via.placeholder.com/600x300" alt="Automatisation des processus">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="service-feature">
                            <div class="service-icon-lg">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>Analytics Avancés</h3>
                            <p>Solutions d'analyse de données financières avec intelligence artificielle.</p>
                            <ul class="service-features-list">
                                <li>Analyse prédictive</li>
                                <li>Machine Learning financier</li>
                                <li>Visualisation de données</li>
                                <li>Tableaux de bord temps réel</li>
                                <li>Rapports automatisés</li>
                            </ul>
                            <div class="service-media-container">
                                <video controls poster="https://via.placeholder.com/600x300">
                                    <source src="#" type="video/mp4">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process">
        <div class="container">
            <h2 class="text-center mb-5">Mon Processus de Travail</h2>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">01</div>
                    <h3>Consultation & Analyse</h3>
                    <p>Nous commençons par une analyse approfondie de vos besoins, objectifs et contraintes. Cette phase nous permet de comprendre parfaitement votre projet et de définir une stratégie adaptée.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">02</div>
                    <h3>Conception & Planification</h3>
                    <p>Je crée une architecture détaillée de la solution, établis un planning réaliste et définis les spécifications techniques. Vous êtes impliqué à chaque étape pour validation.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">03</div>
                    <h3>Développement & Implémentation</h3>
                    <p>Phase de développement avec des livraisons régulières pour vous permettre de suivre l'avancement. J'utilise les meilleures pratiques et technologies adaptées à votre projet.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">04</div>
                    <h3>Tests & Optimisation</h3>
                    <p>Tests complets de la solution, optimisation des performances et correction des éventuels problèmes. Validation finale avec vous avant la mise en production.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">05</div>
                    <h3>Livraison & Support</h3>
                    <p>Livraison de la solution finale, formation de votre équipe et mise en place d'un support continu pour assurer le bon fonctionnement à long terme.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing">
        <div class="container">
            <h2 class="text-center mb-5">Tarifs & Forfaits</h2>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <div class="pricing-name">Starter</div>
                            <div class="pricing-price">€1,500</div>
                            <div class="pricing-period">/ projet simple</div>
                        </div>
                        <ul class="pricing-features">
                            <li>Site vitrine basique</li>
                            <li>5 pages maximum</li>
                            <li>Design responsive</li>
                            <li>Formulaire de contact</li>
                            <li>Support 1 mois</li>
                            <li>Livraison 2-3 semaines</li>
                        </ul>
                        <a href="contact.html" class="btn btn-outline-primary">Choisir ce forfait</a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="pricing-card featured">
                        <div class="pricing-header">
                            <div class="pricing-name">Business</div>
                            <div class="pricing-price">€3,500</div>
                            <div class="pricing-period">/ projet</div>
                        </div>
                        <ul class="pricing-features">
                            <li>Application web complète</li>
                            <li>Base de données</li>
                            <li>Backend administrable</li>
                            <li>Design personnalisé</li>
                            <li>Support 3 mois</li>
                            <li>Livraison 4-6 semaines</li>
                        </ul>
                        <a href="contact.html" class="btn btn-primary">Choisir ce forfait</a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <div class="pricing-name">Enterprise</div>
                            <div class="pricing-price">€7,500</div>
                            <div class="pricing-period">/ projet</div>
                        </div>
                        <ul class="pricing-features">
                            <li>Solution sur mesure complète</li>
                            <li>Fonctionnalités avancées</li>
                            <li>Intégrations multiples</li>
                            <li>Optimisation SEO</li>
                            <li>Support 6 mois</li>
                            <li>Maintenance incluse</li>
                        </ul>
                        <a href="contact.html" class="btn btn-outline-primary">Choisir ce forfait</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <h2 class="text-center mb-5">Questions Fréquentes</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Quel est le délai moyen pour un projet ?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Le délai dépend de la complexité du projet. Un site vitrine simple peut prendre 2-3 semaines, tandis qu'une application web complète peut nécessiter 4-8 semaines. Nous établissons un planning détaillé dès le début du projet.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Proposez-vous un support après la livraison ?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Oui, tous mes forfaits incluent une période de support post-livraison. Je propose également des contrats de maintenance continue pour assurer le bon fonctionnement et les mises à jour de votre solution.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Travaillez-vous avec des entreprises à l'international ?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolument ! Je travaille avec des clients partout dans le monde. Les réunions se font par visioconférence et je m'adapte aux fuseaux horaires. J'ai de l'expérience avec les réglementations comptables internationales.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Quelles technologies utilisez-vous ?
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    J'utilise un stack technologique moderne incluant React, Vue.js, Node.js, Python, Laravel, et les bases de données MySQL, PostgreSQL et MongoDB. Je choisis les technologies les plus adaptées à chaque projet.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à démarrer votre projet ?</h2>
            <p>Contactez-moi pour discuter de vos besoins et obtenir un devis personnalisé</p>
            <a href="contact.html" class="btn btn-light">Commencer un projet</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include INCLUDES.'footer.php' ;?> 

    <!-- Bootstrap 5 JS -->
    <?php include INCLUDES.'vendor-js-file.php' ;?>     
    <!-- Custom JS -->
    <script src="assets/js/services.js"></script>
</body>
</html>