<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ?$title:"À Propos - Ismail Inoua | Expert Comptable & Développeur Full-Stack";?></title>
    <?php include INCLUDES.'head.php' ;?>   
    <link rel="stylesheet" href="assets/css/about.css">
</head>
<body>
   
    <?php include INCLUDES.'navbar.php' ;?>  
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>À Propos de Moi</h1>
            <p>Découvrez mon parcours, mes compétences et ma passion pour allier expertise comptable et innovation technologique</p>
        </div>
    </section>

    <!-- About Hero -->
    <section class="about-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="about-image">
                        <img src="https://via.placeholder.com/600x700" alt="Ismail Inoua">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <h2>Expert-comptable & Développeur Full-Stack</h2>
                        <p>Je m'appelle Ismail Inoua et je possède une double expertise unique : expert-comptable de formation et développeur full-stack passionné par l'innovation technologique.</p>
                        <p>Avec plus de 5 ans d'expérience, j'ai développé une approche distinctive qui combine la rigueur comptable avec l'agilité technologique pour créer des solutions digitales performantes spécialement conçues pour le secteur financier et comptable.</p>
                        <p>Ma mission est d'accompagner les entreprises dans leur transformation digitale en développant des outils sur mesure qui optimisent leurs processus, améliorent leur efficacité et leur permettent de prendre des décisions éclairées basées sur des données précises.</p>
                        
                        <div class="about-highlights">
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h4>Diplôme d'Expertise Comptable</h4>
                                <p>Formation complète en comptabilité et finance</p>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <h4>Développeur Full-Stack</h4>
                                <p>+5 ans d'expérience en développement</p>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                <h4>50+ Projets</h4>
                                <p>Réalisations réussies</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section class="skills">
        <div class="container">
            <div class="skills-content">
                <h2>Mes Compétences</h2>
                
                <div class="row">
                    <div class="col-lg-6">
                        <div class="skill-category">
                            <h3>Expertise Comptable</h3>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Comptabilité générale</span>
                                    <span class="skill-percentage">95%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 95%"></div>
                                </div>
                            </div>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Fiscalité</span>
                                    <span class="skill-percentage">90%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 90%"></div>
                                </div>
                            </div>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Audit financier</span>
                                    <span class="skill-percentage">85%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 85%"></div>
                                </div>
                            </div>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Analyse financière</span>
                                    <span class="skill-percentage">92%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 92%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="skill-category">
                            <h3>Développement & Technologie</h3>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Développement Full-Stack</span>
                                    <span class="skill-percentage">95%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 95%"></div>
                                </div>
                            </div>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Intelligence Artificielle</span>
                                    <span class="skill-percentage">80%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 80%"></div>
                                </div>
                            </div>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Solutions FinTech</span>
                                    <span class="skill-percentage">88%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 88%"></div>
                                </div>
                            </div>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Architecture Cloud</span>
                                    <span class="skill-percentage">85%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section class="experience">
        <div class="container">
            <h2 class="text-center mb-5">Mon Parcours Professionnel</h2>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">2021<br>Présent</div>
                    <div class="timeline-content">
                        <h3>Consultant Indépendant & Développeur Full-Stack</h3>
                        <div class="timeline-company">IICoding - Freelance</div>
                        <p>Consulting et développement de solutions digitales sur mesure pour des clients dans les secteurs de la comptabilité et de la finance. Spécialisation dans l'automatisation des processus comptables et le développement d'applications FinTech.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-date">2019<br>2021</div>
                    <div class="timeline-content">
                        <h3>Développeur Full-Stack Senior</h3>
                        <div class="timeline-company">FinTech Innovations</div>
                        <p>Développement d'applications financières et comptables avec focus sur l'IA et le machine learning. Gestion d'une équipe de 4 développeurs et responsable de l'architecture technique des projets majeurs.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-date">2017<br>2019</div>
                    <div class="timeline-content">
                        <h3>Expert-Comptable</h3>
                        <div class="timeline-company">Cabinet Comptable International</div>
                        <p>Gestion de portefeuille clients, audit financier, conseil en optimisation fiscale et accompagnement dans la digitalisation des processus comptables. Certification d'Expertise Comptable obtenue en 2018.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-date">2015<br>2017</div>
                    <div class="timeline-content">
                        <h3>Développeur Web Full-Stack</h3>
                        <div class="timeline-company">TechSolutions SARL</div>
                        <p>Développement d'applications web et mobiles pour divers clients. Spécialisation progressive dans les solutions pour le secteur financier et comptable.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications -->
    <section class="certifications">
        <div class="container">
            <h2 class="text-center mb-5">Certifications & Formations</h2>
            
            <div class="cert-grid">
                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3>Diplôme d'Expertise Comptable</h3>
                    <p>Ordre des Experts-Comptables</p>
                    <span class="text-muted">2018</span>
                </div>
                
                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>Full-Stack Development</h3>
                    <p>Google Professional Certificate</p>
                    <span class="text-muted">2020</span>
                </div>
                
                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Machine Learning Specialization</h3>
                    <p>Stanford University - Coursera</p>
                    <span class="text-muted">2021</span>
                </div>
                
                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="fas fa-cloud"></i>
                    </div>
                    <h3>AWS Solutions Architect</h3>
                    <p>Amazon Web Services</p>
                    <span class="text-muted">2022</span>
                </div>
                
                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Cybersecurity Fundamentals</h3>
                    <p>ISC2 Certified</p>
                    <span class="text-muted">2022</span>
                </div>
                
                <div class="cert-card">
                    <div class="cert-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Data Analytics Professional</h3>
                    <p>Google Career Certificate</p>
                    <span class="text-muted">2023</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Philosophy Section -->
    <section class="philosophy">
        <div class="container">
            <h2 class="text-center mb-5">Ma Philosophie de Travail</h2>
            
            <div class="philosophy-grid">
                <div class="philosophy-card">
                    <div class="philosophy-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Orientation Résultats</h3>
                    <p>Je me concentre sur la livraison de solutions qui génèrent des résultats mesurables et un retour sur investissement concret pour mes clients.</p>
                </div>
                
                <div class="philosophy-card">
                    <div class="philosophy-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Collaboration Étroite</h3>
                    <p>Je considère chaque client comme un partenaire et m'implique pleinement dans la réussite de son projet, avec une communication transparente.</p>
                </div>
                
                <div class="philosophy-card">
                    <div class="philosophy-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation Continue</h3>
                    <p>Je reste constamment à jour des dernières technologies et tendances pour proposer des solutions modernes et compétitives.</p>
                </div>
                
                <div class="philosophy-card">
                    <div class="philosophy-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Excellence Technique</h3>
                    <p>Je m'engage à fournir un code de qualité, des architectures robustes et des solutions durables qui résistent à l'épreuve du temps.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à collaborer ensemble ?</h2>
            <p>Discutons de votre projet et voyons comment je peux vous aider à atteindre vos objectifs</p>
            <a href="contact.html" class="btn btn-light">Commencer un projet</a>
        </div>
    </section>
    <?php include INCLUDES.'footer.php' ;?>

    <!-- Custom JS -->
    <script src="assets/js/about.js"></script>
</body>
</html>