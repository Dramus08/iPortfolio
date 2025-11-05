<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ismail Inoua - Expert Comptable & Développeur Full-Stack</title>
    
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
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" fill="%232563eb" opacity="0.05"><polygon points="0,0 1000,500 0,1000"/></svg>') no-repeat;
            background-size: cover;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .hero-content .highlight {
            color: var(--primary);
            position: relative;
        }
        
        .hero-content .highlight::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30%;
            background: rgba(37, 99, 235, 0.1);
            z-index: -1;
        }
        
        .hero-content p.lead {
            font-size: 1.25rem;
            color: var(--gray);
            margin-bottom: 2rem;
        }
        
        .hero-image {
            position: relative;
        }
        
        .hero-image img {
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: transform 0.5s ease;
        }
        
        .hero-image:hover img {
            transform: perspective(1000px) rotateY(0) rotateX(0);
        }
        
        /* Services Section */
        .services {
            padding: 100px 0;
            background: var(--light);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: var(--gray);
            text-align: center;
            max-width: 600px;
            margin: 0 auto 4rem;
        }
        
        .service-card {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .service-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .service-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .service-card p {
            color: var(--gray);
        }
        
        /* Media Gallery for Services */
        .service-media {
            margin-top: 1.5rem;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .service-media img, .service-media video {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .service-media:hover img, .service-media:hover video {
            transform: scale(1.05);
        }
        
        /* Stats Section */
        .stats {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Projects Section */
        .projects {
            padding: 100px 0;
        }
        
        .project-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .project-card:hover {
            transform: translateY(-10px);
        }
        
        .project-media {
            height: 250px;
            position: relative;
            overflow: hidden;
        }
        
        .project-media img, .project-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .project-card:hover .project-media img,
        .project-card:hover .project-media video {
            transform: scale(1.1);
        }
        
        .project-content {
            padding: 1.5rem;
            background: white;
        }
        
        .project-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .project-tag {
            background: var(--light);
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* Portfolio Section */
        .portfolio {
            padding: 100px 0;
            background: var(--light);
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
            border: 2px solid transparent;
            padding: 0.5rem 1.5rem;
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
        
        .portfolio-item {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: white;
        }
        
        .portfolio-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .portfolio-media {
            height: 250px;
            position: relative;
            overflow: hidden;
        }
        
        .portfolio-media img, .portfolio-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .portfolio-item:hover .portfolio-media img,
        .portfolio-item:hover .portfolio-media video {
            transform: scale(1.1);
        }
        
        .portfolio-overlay {
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
        
        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }
        
        .portfolio-content {
            padding: 1.5rem;
        }
        
        /* About Section */
        .about {
            padding: 100px 0;
        }
        
        .about-image {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
        }
        
        .about-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .about-content p {
            font-size: 1.1rem;
            color: var(--gray);
            margin-bottom: 1.5rem;
        }
        
        .skills {
            margin-top: 2rem;
        }
        
        .skill-item {
            margin-bottom: 1.5rem;
        }
        
        .skill-header {
            display: flex;
            justify-content: between;
            margin-bottom: 0.5rem;
        }
        
        .skill-name {
            font-weight: 600;
        }
        
        .skill-percentage {
            color: var(--primary);
            font-weight: 600;
        }
        
        .skill-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .skill-progress {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 10px;
            transition: width 1s ease;
        }
        
        /* Testimonials Section */
        .testimonials {
            padding: 100px 0;
            background: var(--light);
        }
        
        .testimonial-carousel {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .testimonial-item {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            text-align: center;
            margin: 0 1rem;
        }
        
        .testimonial-content {
            font-size: 1.1rem;
            font-style: italic;
            color: var(--gray);
            margin-bottom: 2rem;
            line-height: 1.8;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary);
            margin-right: 1rem;
            overflow: hidden;
        }
        
        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .author-info h5 {
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        
        .author-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .carousel-controls {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .carousel-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--primary);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .carousel-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        /* Contact Section */
        .contact {
            padding: 100px 0;
        }
        
        .contact-info {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 3rem;
            border-radius: 20px;
            height: 100%;
        }
        
        .contact-info h3 {
            margin-bottom: 2rem;
            font-weight: 700;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .contact-details h5 {
            margin-bottom: 0.5rem;
        }
        
        .contact-form {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        /* CTA Section */
        .cta {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--dark), #0f172a);
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
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero::before {
                width: 100%;
                opacity: 0.1;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .portfolio-grid {
                grid-template-columns: 1fr;
            }
            
            .testimonial-item {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include INCLUDES.'navbar.php' ;?>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1>Expert-comptable & <span class="highlight">Développeur Full-Stack</span></h1>
                        <p class="lead">Je transforme vos idées en solutions digitales performantes, alliant expertise comptable et innovation technologique.</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="#projects" class="btn btn-primary">Voir mes projets</a>
                            <a href="#" class="btn btn-outline-primary">Télécharger CV</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center">
                        <img src="https://via.placeholder.com/500x600" alt="Ismail Inoua" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <h2 class="section-title">Mes Services</h2>
            <p class="section-subtitle">Des solutions sur mesure pour répondre à vos besoins en comptabilité et développement</p>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h3>Expertise Comptable</h3>
                        <p>Conseil et accompagnement pour optimiser votre gestion financière et assurer votre conformité fiscale.</p>
                        <div class="service-media">
                            <img src="https://via.placeholder.com/400x200" alt="Expertise Comptable">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3>Développement Web</h3>
                        <p>Création d'applications web modernes, responsives et performantes avec les dernières technologies.</p>
                        <div class="service-media">
                            <video controls>
                                <source src="#" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Solutions Fintech</h3>
                        <p>Développement d'outils sur mesure pour automatiser et optimiser vos processus financiers.</p>
                        <div class="service-media">
                            <img src="https://via.placeholder.com/400x200" alt="Solutions Fintech">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Projets Réalisés</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">5+</div>
                        <div class="stat-label">Années d'Expérience</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">30+</div>
                        <div class="stat-label">Clients Satisfaits</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Technologies Maîtrisées</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="projects" id="projects">
        <div class="container">
            <h2 class="section-title">Projets Récents</h2>
            <p class="section-subtitle">Découvrez quelques-unes de mes réalisations les plus significatives</p>
            
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="project-card">
                        <div class="project-media">
                            <img src="https://via.placeholder.com/400x250" alt="Plateforme de Gestion Comptable">
                        </div>
                        <div class="project-content">
                            <div class="project-tags">
                                <span class="project-tag">React</span>
                                <span class="project-tag">Node.js</span>
                                <span class="project-tag">MongoDB</span>
                            </div>
                            <h4>Plateforme de Gestion Comptable</h4>
                            <p>Application web complète pour la gestion automatisée de la comptabilité des PME.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Voir le projet</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="project-card">
                        <div class="project-media">
                            <video controls>
                                <source src="#" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        </div>
                        <div class="project-content">
                            <div class="project-tags">
                                <span class="project-tag">Vue.js</span>
                                <span class="project-tag">Laravel</span>
                                <span class="project-tag">MySQL</span>
                            </div>
                            <h4>Système de Facturation Intelligent</h4>
                            <p>Solution de facturation automatique avec intégration bancaire et analyse des données.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Voir le projet</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="project-card">
                        <div class="project-media">
                            <img src="https://via.placeholder.com/400x250" alt="Outil d'Analyse Financière">
                        </div>
                        <div class="project-content">
                            <div class="project-tags">
                                <span class="project-tag">Python</span>
                                <span class="project-tag">Django</span>
                                <span class="project-tag">PostgreSQL</span>
                            </div>
                            <h4>Outil d'Analyse Financière</h4>
                            <p>Application d'analyse prédictive pour l'optimisation des performances financières.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Voir le projet</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="#portfolio" class="btn btn-primary">Voir tous les projets</a>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="portfolio" id="portfolio">
        <div class="container">
            <h2 class="section-title">Mon Portfolio</h2>
            <p class="section-subtitle">Une sélection de mes travaux les plus représentatifs</p>
            
            <div class="portfolio-filters">
                <button class="portfolio-filter active" data-filter="all">Tous</button>
                <button class="portfolio-filter" data-filter="web">Développement Web</button>
                <button class="portfolio-filter" data-filter="comptabilite">Comptabilité</button>
                <button class="portfolio-filter" data-filter="fintech">FinTech</button>
            </div>
            
            <div class="portfolio-grid">
                <div class="portfolio-item" data-category="web">
                    <div class="portfolio-media">
                        <img src="https://via.placeholder.com/400x250" alt="Site E-commerce">
                        <div class="portfolio-overlay">
                            <a href="#" class="btn btn-light">Voir les détails</a>
                        </div>
                    </div>
                    <div class="portfolio-content">
                        <h4>Plateforme E-commerce</h4>
                        <p>Site de vente en ligne avec système de paiement sécurisé et gestion des stocks.</p>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="comptabilite">
                    <div class="portfolio-media">
                        <video controls>
                            <source src="#" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                        <div class="portfolio-overlay">
                            <a href="#" class="btn btn-light">Voir les détails</a>
                        </div>
                    </div>
                    <div class="portfolio-content">
                        <h4>Logiciel de Comptabilité</h4>
                        <p>Application de gestion comptable avec génération automatique des états financiers.</p>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="fintech">
                    <div class="portfolio-media">
                        <img src="https://via.placeholder.com/400x250" alt="Application Mobile Financière">
                        <div class="portfolio-overlay">
                            <a href="#" class="btn btn-light">Voir les détails</a>
                        </div>
                    </div>
                    <div class="portfolio-content">
                        <h4>Application Mobile Financière</h4>
                        <p>App de gestion de budget personnel avec analyse des dépenses et objectifs d'épargne.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="about-image">
                        <img src="https://via.placeholder.com/500x600" alt="À propos d'Ismail Inoua">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <h2>À propos de moi</h2>
                        <p>Diplômé en expertise comptable et passionné par les technologies, j'ai développé une double compétence unique qui me permet de créer des solutions digitales innovantes pour le secteur financier et comptable.</p>
                        <p>Avec plus de 5 ans d'expérience, j'accompagne les entreprises dans leur transformation digitale en alliant rigueur comptable et expertise technique.</p>
                        
                        <div class="skills">
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
                                    <span class="skill-name">Expertise Comptable</span>
                                    <span class="skill-percentage">90%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 90%"></div>
                                </div>
                            </div>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Solutions FinTech</span>
                                    <span class="skill-percentage">85%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 85%"></div>
                                </div>
                            </div>
                            
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name">Consulting Digital</span>
                                    <span class="skill-percentage">80%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: 80%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <h2 class="section-title">Témoignages Clients</h2>
            <p class="section-subtitle">Ce que disent mes clients de notre collaboration</p>
            
            <div class="testimonial-carousel">
                <div class="testimonial-item active">
                    <div class="testimonial-content">
                        "Ismail a parfaitement compris nos besoins et a livré une solution qui dépasse nos attentes. Son expertise à la fois comptable et technique est un atout rare. Le projet a été livré dans les délais et le suivi post-livraison est excellent."
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
                        "Le système développé par Ismail a révolutionné notre gestion comptable. Automatisation, précision et gain de temps considérable. Je recommande vivement ses services à toutes les entreprises cherchant à optimiser leurs processus financiers grâce à la technologie."
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
                
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        "Professionalisme, expertise et réactivité. Ismail a su allier ses compétences en développement et en comptabilité pour créer l'outil parfait pour notre entreprise. La formation de notre équipe a été très bien organisée et nous sommes pleinement satisfaits du résultat."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="https://via.placeholder.com/60x60" alt="Sophie Martin">
                        </div>
                        <div class="author-info">
                            <h5>Sophie Martin</h5>
                            <p>Responsable Administrative, Groupe Aura</p>
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
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Contactez-moi</h2>
            <p class="section-subtitle">Discutons de votre projet et trouvons ensemble la solution idéale</p>
            
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="contact-info">
                        <h3>Informations de contact</h3>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Adresse</h5>
                                <p>Paris, France</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Téléphone</h5>
                                <p>+33 6 12 34 56 78</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Email</h5>
                                <p>contact@iicoding.com</p>
                            </div>
                        </div>
                        
                        <div class="social-links mt-4">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="contact-form">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" placeholder="Votre nom" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="email" class="form-control" placeholder="Votre email" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Sujet" required>
                            </div>
                            
                            <div class="mb-3">
                                <textarea class="form-control" rows="5" placeholder="Votre message" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Envoyer le message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à transformer votre vision en réalité ?</h2>
            <p>Discutons de votre projet et trouvons ensemble la solution idéale pour répondre à vos besoins.</p>
            <a href="#contact" class="btn btn-light">Commencer un projet</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include INCLUDES.'footer.php' ;?>

   <?php include INCLUDES.'vendor-js-file.php' ;?> 
    <!-- Custom JS -->
    <script src="assets/js/home.js"></script>
</body>
</html>



























