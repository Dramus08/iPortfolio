<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Ismail Inoua | Expert Comptable & Développeur Full-Stack</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lightbox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #7c3aed;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
            padding-top: 76px;
        }
        
        /* Navigation */
        .navbar {
            padding: 1.5rem 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--dark);
        }
        
        .navbar-brand span {
            color: var(--primary);
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            color: var(--dark);
            transition: all 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary);
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }
        
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 100px 0 60px;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .page-header p {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Portfolio Section */
        .portfolio-detailed {
            padding: 100px 0;
        }
        
        .portfolio-filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 3rem;
        }
        
        .portfolio-filter {
            background: white;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .portfolio-filter.active, .portfolio-filter:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .portfolio-item-detailed {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .portfolio-item-detailed:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .portfolio-media-detailed {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        
        .portfolio-media-detailed img,
        .portfolio-media-detailed video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .portfolio-item-detailed:hover .portfolio-media-detailed img,
        .portfolio-item-detailed:hover .portfolio-media-detailed video {
            transform: scale(1.1);
        }
        
        .portfolio-overlay-detailed {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(37, 99, 235, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .portfolio-item-detailed:hover .portfolio-overlay-detailed {
            opacity: 1;
        }
        
        .overlay-content {
            text-align: center;
            color: white;
        }
        
        .overlay-content h4 {
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .portfolio-content-detailed {
            padding: 1.5rem;
        }
        
        .portfolio-meta {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .portfolio-category {
            background: var(--light);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .portfolio-date {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .portfolio-content-detailed h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .portfolio-content-detailed p {
            color: var(--gray);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .portfolio-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .tech-tag {
            background: var(--light);
            color: var(--primary);
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        /* Portfolio Stats */
        .portfolio-stats {
            padding: 80px 0;
            background: var(--light);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .stat-icon i {
            font-size: 1.8rem;
            color: white;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--gray);
            font-weight: 500;
        }
        
        /* Client Logos */
        .client-logos {
            padding: 80px 0;
        }
        
        .client-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 3rem;
            align-items: center;
        }
        
        .client-logo {
            filter: grayscale(100%);
            opacity: 0.6;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .client-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.1);
        }
        
        .client-logo img {
            max-width: 120px;
            height: auto;
        }
        
        /* Portfolio Showcase */
        .portfolio-showcase {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--dark), #0f172a);
            color: white;
        }
        
        .showcase-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .showcase-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .showcase-header p {
            font-size: 1.1rem;
            opacity: 0.8;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .showcase-item-featured {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 3rem;
        }
        
        .showcase-media {
            height: 400px;
            position: relative;
            overflow: hidden;
        }
        
        .showcase-media img,
        .showcase-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .showcase-content-featured {
            padding: 3rem;
        }
        
        .showcase-badge {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .showcase-content-featured h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .showcase-content-featured p {
            font-size: 1.1rem;
            opacity: 0.8;
            margin-bottom: 2rem;
        }
        
        .showcase-stats-featured {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .stat-box-featured {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }
        
        .stat-number-featured {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .stat-label-featured {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        /* CTA Section */
        .cta {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .cta p {
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 2.5rem;
            opacity: 0.9;
        }
        
        .btn-light {
            background: white;
            color: var(--dark);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
        }
        
        /* Footer */
        footer {
            background: #0f172a;
            color: white;
            padding: 80px 0 40px;
        }
        
        .footer-logo {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        
        .footer-logo span {
            color: var(--primary);
        }
        
        .footer-links h5 {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links ul li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links ul li a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links ul li a:hover {
            color: white;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid #334155;
            padding-top: 2rem;
            margin-top: 3rem;
            text-align: center;
            color: #94a3b8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.5rem;
            }
            
            .portfolio-grid {
                grid-template-columns: 1fr;
            }
            
            .showcase-media {
                height: 300px;
            }
            
            .showcase-content-featured {
                padding: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .client-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
       <?php include INCLUDES.'navbar.php' ;?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Mon Portfolio</h1>
            <p>Une collection de mes réalisations les plus significatives, alliant expertise comptable et innovation technologique</p>
        </div>
    </section>

    <!-- Portfolio Detailed -->
    <section class="portfolio-detailed">
        <div class="container">
            <div class="portfolio-filters">
                <button class="portfolio-filter active" data-filter="all">Tous les projets</button>
                <button class="portfolio-filter" data-filter="comptabilite">Solutions Comptables</button>
                <button class="portfolio-filter" data-filter="fintech">Applications FinTech</button>
                <button class="portfolio-filter" data-filter="web">Développement Web</button>
                <button class="portfolio-filter" data-filter="mobile">Applications Mobile</button>
                <button class="portfolio-filter" data-filter="consulting">Consulting</button>
            </div>
            
            <div class="portfolio-grid">
                <!-- Projet 1 -->
                <div class="portfolio-item-detailed" data-category="comptabilite fintech">
                    <div class="portfolio-media-detailed">
                        <img src="https://via.placeholder.com/400x250" alt="Plateforme Comptable Automatisée">
                        <div class="portfolio-overlay-detailed">
                            <div class="overlay-content">
                                <h4>Plateforme Comptable Automatisée</h4>
                                <a href="project-detail.html" class="btn btn-light">Voir le projet</a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-content-detailed">
                        <div class="portfolio-meta">
                            <span class="portfolio-category">Solution Comptable</span>
                            <span class="portfolio-date">2023</span>
                        </div>
                        <h3>Plateforme de Gestion Comptable Intelligente</h3>
                        <p>Solution complète d'automatisation comptable avec reconnaissance de documents et rapprochement bancaire intelligent.</p>
                        <div class="portfolio-tech">
                            <span class="tech-tag">React</span>
                            <span class="tech-tag">Node.js</span>
                            <span class="tech-tag">Python</span>
                            <span class="tech-tag">OCR</span>
                        </div>
                        <a href="project-detail.html" class="btn btn-outline-primary btn-sm">Voir les détails</a>
                    </div>
                </div>
                
                <!-- Projet 2 -->
                <div class="portfolio-item-detailed" data-category="fintech web">
                    <div class="portfolio-media-detailed">
                        <video controls poster="https://via.placeholder.com/400x250">
                            <source src="#" type="video/mp4">
                        </video>
                        <div class="portfolio-overlay-detailed">
                            <div class="overlay-content">
                                <h4>Système de Facturation IA</h4>
                                <a href="project-detail.html" class="btn btn-light">Voir le projet</a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-content-detailed">
                        <div class="portfolio-meta">
                            <span class="portfolio-category">Application FinTech</span>
                            <span class="portfolio-date">2023</span>
                        </div>
                        <h3>Système de Facturation Intelligente</h3>
                        <p>Plateforme de facturation automatisée avec IA pour l'analyse des données et la prédiction des impayés.</p>
                        <div class="portfolio-tech">
                            <span class="tech-tag">Vue.js</span>
                            <span class="tech-tag">Laravel</span>
                            <span class="tech-tag">MySQL</span>
                            <span class="tech-tag">TensorFlow</span>
                        </div>
                        <a href="project-detail.html" class="btn btn-outline-primary btn-sm">Voir les détails</a>
                    </div>
                </div>
                
                <!-- Projet 3 -->
                <div class="portfolio-item-detailed" data-category="mobile fintech">
                    <div class="portfolio-media-detailed">
                        <img src="https://via.placeholder.com/400x250" alt="Application Mobile Financière">
                        <div class="portfolio-overlay-detailed">
                            <div class="overlay-content">
                                <h4>App Budget Personnel</h4>
                                <a href="project-detail.html" class="btn btn-light">Voir le projet</a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-content-detailed">
                        <div class="portfolio-meta">
                            <span class="portfolio-category">Application Mobile</span>
                            <span class="portfolio-date">2023</span>
                        </div>
                        <h3>Application de Gestion Budgétaire</h3>
                        <p>Application mobile pour la gestion du budget personnel avec analyse des dépenses et conseils financiers.</p>
                        <div class="portfolio-tech">
                            <span class="tech-tag">React Native</span>
                            <span class="tech-tag">Firebase</span>
                            <span class="tech-tag">Node.js</span>
                            <span class="tech-tag">Machine Learning</span>
                        </div>
                        <a href="project-detail.html" class="btn btn-outline-primary btn-sm">Voir les détails</a>
                    </div>
                </div>
                
                <!-- Projet 4 -->
                <div class="portfolio-item-detailed" data-category="web consulting">
                    <div class="portfolio-media-detailed">
                        <img src="https://via.placeholder.com/400x250" alt="ERP Comptable">
                        <div class="portfolio-overlay-detailed">
                            <div class="overlay-content">
                                <h4>ERP Comptable</h4>
                                <a href="project-detail.html" class="btn btn-light">Voir le projet</a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-content-detailed">
                        <div class="portfolio-meta">
                            <span class="portfolio-category">Solution Entreprise</span>
                            <span class="portfolio-date">2022</span>
                        </div>
                        <h3>ERP Comptable Intégré</h3>
                        <p>ERP complet pour cabinets comptables avec gestion de clientèle et production comptable automatisée.</p>
                        <div class="portfolio-tech">
                            <span class="tech-tag">Angular</span>
                            <span class="tech-tag">Java</span>
                            <span class="tech-tag">PostgreSQL</span>
                            <span class="tech-tag">Docker</span>
                        </div>
                        <a href="project-detail.html" class="btn btn-outline-primary btn-sm">Voir les détails</a>
                    </div>
                </div>
                
                <!-- Projet 5 -->
                <div class="portfolio-item-detailed" data-category="fintech web">
                    <div class="portfolio-media-detailed">
                        <video controls poster="https://via.placeholder.com/400x250">
                            <source src="#" type="video/mp4">
                        </video>
                        <div class="portfolio-overlay-detailed">
                            <div class="overlay-content">
                                <h4>Plateforme SaaS</h4>
                                <a href="project-detail.html" class="btn btn-light">Voir le projet</a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-content-detailed">
                        <div class="portfolio-meta">
                            <span class="portfolio-category">Plateforme SaaS</span>
                            <span class="portfolio-date">2022</span>
                        </div>
                        <h3>Solution de Reporting Financier</h3>
                        <p>Plateforme SaaS de reporting financier avec visualisation avancée et analytics en temps réel.</p>
                        <div class="portfolio-tech">
                            <span class="tech-tag">React</span>
                            <span class="tech-tag">Python</span>
                            <span class="tech-tag">MongoDB</span>
                            <span class="tech-tag">D3.js</span>
                        </div>
                        <a href="project-detail.html" class="btn btn-outline-primary btn-sm">Voir les détails</a>
                    </div>
                </div>
                
                <!-- Projet 6 -->
                <div class="portfolio-item-detailed" data-category="consulting comptabilite">
                    <div class="portfolio-media-detailed">
                        <img src="https://via.placeholder.com/400x250" alt="Transformation Digitale">
                        <div class="portfolio-overlay-detailed">
                            <div class="overlay-content">
                                <h4>Consulting Digital</h4>
                                <a href="project-detail.html" class="btn btn-light">Voir le projet</a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-content-detailed">
                        <div class="portfolio-meta">
                            <span class="portfolio-category">Consulting</span>
                            <span class="portfolio-date">2022</span>
                        </div>
                        <h3>Transformation Digitale Comptable</h3>
                        <p>Accompagnement complet pour la digitalisation des processus comptables d'un groupe international.</p>
                        <div class="portfolio-tech">
                            <span class="tech-tag">Strategy</span>
                            <span class="tech-tag">Digital Transformation</span>
                            <span class="tech-tag">Process Optimization</span>
                        </div>
                        <a href="project-detail.html" class="btn btn-outline-primary btn-sm">Voir les détails</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Stats -->
    <section class="portfolio-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Projets Réalisés</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">30+</div>
                    <div class="stat-label">Clients Satisfaits</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Technologies Maîtrisées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-number">5+</div>
                    <div class="stat-label">Années d'Expérience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client Logos -->
    <section class="client-logos">
        <div class="container">
            <h2 class="text-center mb-5">Ils m'ont fait confiance</h2>
            <div class="client-grid">
                <div class="client-logo">
                    <img src="https://via.placeholder.com/120x60" alt="Client 1">
                </div>
                <div class="client-logo">
                    <img src="https://via.placeholder.com/120x60" alt="Client 2">
                </div>
                <div class="client-logo">
                    <img src="https://via.placeholder.com/120x60" alt="Client 3">
                </div>
                <div class="client-logo">
                    <img src="https://via.placeholder.com/120x60" alt="Client 4">
                </div>
                <div class="client-logo">
                    <img src="https://via.placeholder.com/120x60" alt="Client 5">
                </div>
                <div class="client-logo">
                    <img src="https://via.placeholder.com/120x60" alt="Client 6">
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Showcase -->
    <section class="portfolio-showcase">
        <div class="container">
            <div class="showcase-header">
                <h2>Projet Vedette</h2>
                <p>Découvrez mon projet le plus ambitieux à ce jour</p>
            </div>
            
            <div class="showcase-item-featured">
                <div class="showcase-media">
                    <img src="https://via.placeholder.com/1200x600" alt="Projet Vedette">
                </div>
                <div class="showcase-content-featured">
                    <span class="showcase-badge">Projet de l'Année 2023</span>
                    <h3>Plateforme Comptable IA Revolution</h3>
                    <p>Une plateforme révolutionnaire qui combine l'intelligence artificielle et l'expertise comptable pour automatiser complètement la gestion financière des entreprises. Cette solution a permis à nos clients de réduire leurs coûts de gestion de 40% tout en améliorant la précision de leurs données financières.</p>
                    
                    <div class="showcase-stats-featured">
                        <div class="stat-box-featured">
                            <div class="stat-number-featured">+40%</div>
                            <div class="stat-label-featured">Efficacité</div>
                        </div>
                        <div class="stat-box-featured">
                            <div class="stat-number-featured">-60%</div>
                            <div class="stat-label-featured">Erreurs</div>
                        </div>
                        <div class="stat-box-featured">
                            <div class="stat-number-featured">25+</div>
                            <div class="stat-label-featured">Entreprises</div>
                        </div>
                        <div class="stat-box-featured">
                            <div class="stat-number-featured">99.9%</div>
                            <div class="stat-label-featured">Satisfaction</div>
                        </div>
                    </div>
                    
                    <div class="portfolio-tech mb-4">
                        <span class="tech-tag" style="background: rgba(255,255,255,0.2); color: white;">React</span>
                        <span class="tech-tag" style="background: rgba(255,255,255,0.2); color: white;">Node.js</span>
                        <span class="tech-tag" style="background: rgba(255,255,255,0.2); color: white;">Python</span>
                        <span class="tech-tag" style="background: rgba(255,255,255,0.2); color: white;">TensorFlow</span>
                        <span class="tech-tag" style="background: rgba(255,255,255,0.2); color: white;">MongoDB</span>
                    </div>
                    
                    <a href="project-detail.html" class="btn btn-light">Voir l'étude de cas complète</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à transformer votre entreprise ?</h2>
            <p>Discutons de votre projet et créons ensemble la solution digitale parfaite pour vos besoins</p>
            <a href="contact.html" class="btn btn-light">Commencer un projet</a>
        </div>
    </section>

    <!-- Footer -->
       <?php include INCLUDES.'footer.php' ;?>

    <!-- Bootstrap 5 JS  and Lightbox Js-->
     <?php include INCLUDES.'vendor-js-file.php' ;?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    
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
        
        // Portfolio filtering
        document.querySelectorAll('.portfolio-filter').forEach(filter => {
            filter.addEventListener('click', function() {
                // Remove active class from all filters
                document.querySelectorAll('.portfolio-filter').forEach(f => {
                    f.classList.remove('active');
                });
                
                // Add active class to clicked filter
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                const portfolioItems = document.querySelectorAll('.portfolio-item-detailed');
                
                portfolioItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category').includes(filterValue)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Lightbox initialization
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'imageFadeDuration': 300
        });
    </script>
</body>
</html>