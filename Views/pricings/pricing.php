<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title)? $title:"Tarifs - Ismail Inoua | Expert Comptable & Développeur Full-Stack";?></title>
    
    <!-- Bootstrap 5 CSS -->
    <?php include INCLUDES.'head.php' ;?>
    
    <link rel="stylesheet" href="assets/css/pricing.css">
</head>
<body>
    <!-- Navigation -->
      <?php include INCLUDES.'navbar.php' ;?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Tarifs Transparents</h1>
            <p>Des forfaits adaptés à vos besoins, avec une qualité de service exceptionnelle et des résultats garantis</p>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-detailed">
        <div class="container">
            <div class="pricing-toggle">
                <span class="toggle-label active" data-period="monthly">Facturation mensuelle</span>
                <div class="toggle-switch monthly">
                    <div class="toggle-slider"></div>
                </div>
                <span class="toggle-label" data-period="yearly">Facturation annuelle</span>
                <small class="text-muted">(-20%)</small>
            </div>
            
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <div class="pricing-name">Starter</div>
                        <div class="pricing-price">
                            <span class="monthly-price">€1,500</span>
                            <span class="yearly-price" style="display: none;">€1,200</span>
                        </div>
                        <div class="pricing-period">/ projet simple</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Site vitrine basique</li>
                        <li><i class="fas fa-check"></i> 5 pages maximum</li>
                        <li><i class="fas fa-check"></i> Design responsive</li>
                        <li><i class="fas fa-check"></i> Formulaire de contact</li>
                        <li><i class="fas fa-check"></i> Support 1 mois</li>
                        <li><i class="fas fa-check"></i> Livraison 2-3 semaines</li>
                    </ul>
                    <a href="contact.html" class="btn btn-outline-primary">Choisir ce forfait</a>
                </div>
                
                <div class="pricing-card featured">
                    <div class="pricing-header">
                        <div class="pricing-name">Business</div>
                        <div class="pricing-price">
                            <span class="monthly-price">€3,500</span>
                            <span class="yearly-price" style="display: none;">€2,800</span>
                        </div>
                        <div class="pricing-period">/ projet</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Application web complète</li>
                        <li><i class="fas fa-check"></i> Base de données</li>
                        <li><i class="fas fa-check"></i> Backend administrable</li>
                        <li><i class="fas fa-check"></i> Design personnalisé</li>
                        <li><i class="fas fa-check"></i> Support 3 mois</li>
                        <li><i class="fas fa-check"></i> Livraison 4-6 semaines</li>
                    </ul>
                    <a href="contact.html" class="btn btn-primary">Choisir ce forfait</a>
                </div>
                
                <div class="pricing-card">
                    <div class="pricing-header">
                        <div class="pricing-name">Enterprise</div>
                        <div class="pricing-price">
                            <span class="monthly-price">€7,500</span>
                            <span class="yearly-price" style="display: none;">€6,000</span>
                        </div>
                        <div class="pricing-period">/ projet</div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Solution sur mesure complète</li>
                        <li><i class="fas fa-check"></i> Fonctionnalités avancées</li>
                        <li><i class="fas fa-check"></i> Intégrations multiples</li>
                        <li><i class="fas fa-check"></i> Optimisation SEO</li>
                        <li><i class="fas fa-check"></i> Support 6 mois</li>
                        <li><i class="fas fa-check"></i> Maintenance incluse</li>
                    </ul>
                    <a href="contact.html" class="btn btn-outline-primary">Choisir ce forfait</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Packages -->
    <section class="service-packages">
        <div class="container">
            <h2 class="text-center mb-5">Forfaits de Services Spécialisés</h2>
            
            <div class="package-card">
                <div class="package-header">
                    <div class="package-info">
                        <h3>Solution Comptable Automatisée</h3>
                        <p>Plateforme complète d'automatisation des processus comptables avec IA</p>
                    </div>
                    <div class="package-price">À partir de €15,000</div>
                </div>
                
                <div class="package-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div>
                            <h5>Reconnaissance Automatique</h5>
                            <p class="text-muted">OCR intelligent pour l'extraction des données</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-sync"></i>
                        </div>
                        <div>
                            <h5>Rapprochement Bancaire</h5>
                            <p class="text-muted">Automatisation du rapprochement avec les banques</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h5>Reporting Avancé</h5>
                            <p class="text-muted">Tableaux de bord et analytics en temps réel</p>
                        </div>
                    </div>
                </div>
                
                <a href="contact.html" class="btn btn-primary">Demander un devis</a>
            </div>
            
            <div class="package-card">
                <div class="package-header">
                    <div class="package-info">
                        <h3>Application FinTech Mobile</h3>
                        <p>Application mobile native pour la gestion financière personnelle ou d'entreprise</p>
                    </div>
                    <div class="package-price">À partir de €12,000</div>
                </div>
                
                <div class="package-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div>
                            <h5>Applications Native</h5>
                            <p class="text-muted">iOS et Android avec React Native</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h5>Sécurité Bancaire</h5>
                            <p class="text-muted">Chiffrement et authentification forte</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div>
                            <h5>IA et Analytics</h5>
                            <p class="text-muted">Recommandations personnalisées et insights</p>
                        </div>
                    </div>
                </div>
                
                <a href="contact.html" class="btn btn-primary">Demander un devis</a>
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
                                    Comment se déroule le processus de paiement ?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Le paiement s'effectue généralement en trois temps : 30% à la signature du contrat, 40% à la mi-parcours du projet, et 30% à la livraison finale. Pour les projets de plus petite envergure, un paiement unique peut être convenu.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Proposez-vous des forfaits de maintenance ?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Oui, je propose des forfaits de maintenance mensuels ou annuels qui incluent les mises à jour de sécurité, les corrections de bugs, l'assistance technique et l'optimisation des performances. Ces forfaits commencent à partir de 200€/mois selon la complexité de votre solution.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Puis-je modifier mon projet en cours de développement ?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolument. J'adopte une approche agile qui permet des ajustements en cours de projet. Les modifications mineures sont généralement incluses, tandis que les changements majeurs peuvent nécessiter un ajustement du budget et des délais, toujours validé avec vous au préalable.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Quel est le délai moyen pour un projet ?
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Les délais varient selon la complexité : sites vitrines simples (2-3 semaines), applications web complètes (4-8 semaines), solutions sur mesure complexes (8-16 semaines). Un planning détaillé est établi dès le début du projet et des livraisons intermédiaires vous permettent de suivre l'avancement.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Guarantee Section -->
    <section class="guarantee">
        <div class="container">
            <h2 class="text-center mb-5">Mes Garanties</h2>
            <div class="guarantee-grid">
                <div class="guarantee-card">
                    <div class="guarantee-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>Qualité Garantie</h3>
                    <p>Code propre, documentation complète et tests approfondis pour une solution robuste et durable.</p>
                </div>
                
                <div class="guarantee-card">
                    <div class="guarantee-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Respect des Délais</h3>
                    <p>Engagement sur les délais convenus avec communication transparente sur l'avancement.</p>
                </div>
                
                <div class="guarantee-card">
                    <div class="guarantee-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Support Réactif</h3>
                    <p>Assistance technique rapide et efficace pendant et après la livraison du projet.</p>
                </div>
                
                <div class="guarantee-card">
                    <div class="guarantee-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Confidentialité</h3>
                    <p>Protection absolue de vos données et respect de la propriété intellectuelle.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à démarrer votre projet ?</h2>
            <p>Contactez-moi pour discuter de vos besoins et obtenir un devis personnalisé adapté à votre budget</p>
            <a href="contact.html" class="btn btn-light">Obtenir un devis gratuit</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include INCLUDES.'footer.php' ;?>

    <!-- Bootstrap 5 JS -->
   <?php include INCLUDES.'vendor-js-file.php' ;?>    
    <!-- Custom JS -->
    <script src="assets/js/pricing.js"></script>
</body>
</html>