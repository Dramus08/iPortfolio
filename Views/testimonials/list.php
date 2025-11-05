<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title)? $title:"Témoignages - Ismail Inoua | Expert Comptable & Développeur Full-Stack";?></title>
    
        <?php include INCLUDES.'head.php' ;?> 
 
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
        
        /* Testimonials Section */
        .testimonials-detailed {
            padding: 100px 0;
        }
        
        .testimonial-filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 3rem;
        }
        
        .testimonial-filter {
            background: white;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .testimonial-filter.active, .testimonial-filter:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 2rem;
        }
        
        .testimonial-card-detailed {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .testimonial-card-detailed:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .testimonial-card-detailed::before {
            content: '"';
            position: absolute;
            top: 20px;
            left: 25px;
            font-size: 4rem;
            color: var(--primary);
            opacity: 0.2;
            font-family: serif;
        }
        
        .testimonial-content-detailed {
            font-size: 1.1rem;
            font-style: italic;
            color: var(--gray);
            margin-bottom: 2rem;
            line-height: 1.8;
            position: relative;
            z-index: 2;
        }
        
        .testimonial-author-detailed {
            display: flex;
            align-items: center;
        }
        
        .author-avatar-detailed {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 1.5rem;
            border: 4px solid var(--light);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .author-avatar-detailed img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .author-info-detailed h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .author-info-detailed p {
            color: var(--gray);
            margin-bottom: 0.5rem;
        }
        
        .author-company {
            color: var(--primary);
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .testimonial-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .testimonial-project {
            background: var(--light);
            color: var(--primary);
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .testimonial-rating {
            color: #ffc107;
        }
        
        /* Video Testimonials */
        .video-testimonials {
            padding: 100px 0;
            background: var(--light);
        }
        
        .video-testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }
        
        .video-testimonial-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .video-testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .video-container {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        
        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .play-button:hover {
            background: white;
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .play-button i {
            color: var(--primary);
            font-size: 1.5rem;
            margin-left: 3px;
        }
        
        .video-content {
            padding: 1.5rem;
        }
        
        /* Stats Section */
        .testimonial-stats {
            padding: 80px 0;
        }
        
        .stats-grid-testimonials {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .stat-card-testimonials {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number-testimonials {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-label-testimonials {
            color: var(--gray);
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        /* Case Studies */
        .case-studies {
            padding: 100px 0;
            background: var(--light);
        }
        
        .case-study-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            margin-bottom: 3rem;
        }
        
        .case-study-header {
            padding: 2rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .case-study-client {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .client-logo-testimonial {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            overflow: hidden;
        }
        
        .client-logo-testimonial img {
            max-width: 40px;
            max-height: 40px;
        }
        
        .case-study-content {
            padding: 2rem;
        }
        
        .case-study-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .case-study-stat {
            text-align: center;
            padding: 1.5rem;
            background: var(--light);
            border-radius: 15px;
        }
        
        .case-study-stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .case-study-stat-label {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .testimonial-quote-large {
            font-size: 1.2rem;
            font-style: italic;
            color: var(--gray);
            text-align: center;
            margin: 2rem 0;
            padding: 2rem;
            background: var(--light);
            border-radius: 15px;
            border-left: 4px solid var(--primary);
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
            
            .testimonial-grid {
                grid-template-columns: 1fr;
            }
            
            .video-testimonial-grid {
                grid-template-columns: 1fr;
            }
            
            .testimonial-card-detailed {
                padding: 2rem;
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
            <h1>Témoignages Clients</h1>
            <p>Découvrez ce que mes clients disent de notre collaboration et des résultats obtenus</p>
        </div>
    </section>

    <!-- Testimonials Detailed -->
    <section class="testimonials-detailed">
        <div class="container">
            <div class="testimonial-filters">
                <button class="testimonial-filter active" data-filter="all">Tous les témoignages</button>
                <button class="testimonial-filter" data-filter="comptabilite">Solutions Comptables</button>
                <button class="testimonial-filter" data-filter="fintech">Applications FinTech</button>
                <button class="testimonial-filter" data-filter="web">Développement Web</button>
                <button class="testimonial-filter" data-filter="consulting">Consulting</button>
            </div>
            
            <div class="testimonial-grid">
                <!-- Témoignage 1 -->
                <div class="testimonial-card-detailed" data-category="comptabilite fintech">
                    <div class="testimonial-content-detailed">
                        "La plateforme développée par Ismail a révolutionné notre gestion comptable. L'automatisation des processus nous a permis de réduire les erreurs de 60% et d'augmenter notre productivité de 40%. Son expertise à la fois technique et comptable est vraiment unique et précieuse."
                    </div>
                    <div class="testimonial-author-detailed">
                        <div class="author-avatar-detailed">
                            <img src="https://via.placeholder.com/70x70" alt="Marie Lambert">
                        </div>
                        <div class="author-info-detailed">
                            <h4>Marie Lambert</h4>
                            <p>Directrice Financière</p>
                            <div class="author-company">FinTech Solutions</div>
                        </div>
                    </div>
                    <div class="testimonial-meta">
                        <span class="testimonial-project">Plateforme Comptable Automatisée</span>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Témoignage 2 -->
                <div class="testimonial-card-detailed" data-category="web consulting">
                    <div class="testimonial-content-detailed">
                        "Ismail a su comprendre nos besoins spécifiques et a développé une solution sur mesure qui dépasse nos attentes. Le projet a été livré dans les délais et le support post-livraison est excellent. Je recommande vivement ses services à toute entreprise cherchant à optimiser ses processus grâce à la technologie."
                    </div>
                    <div class="testimonial-author-detailed">
                        <div class="author-avatar-detailed">
                            <img src="https://via.placeholder.com/70x70" alt="Thomas Dubois">
                        </div>
                        <div class="author-info-detailed">
                            <h4>Thomas Dubois</h4>
                            <p>CEO</p>
                            <div class="author-company">Innov'Compta</div>
                        </div>
                    </div>
                    <div class="testimonial-meta">
                        <span class="testimonial-project">ERP Comptable</span>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Témoignage 3 -->
                <div class="testimonial-card-detailed" data-category="fintech mobile">
                    <div class="testimonial-content-detailed">
                        "Professionalisme, expertise et réactivité. Ismail a su allier ses compétences en développement et en comptabilité pour créer l'outil parfait pour notre entreprise. La formation de notre équipe a été très bien organisée et nous sommes pleinement satisfaits du résultat."
                    </div>
                    <div class="testimonial-author-detailed">
                        <div class="author-avatar-detailed">
                            <img src="https://via.placeholder.com/70x70" alt="Sophie Martin">
                        </div>
                        <div class="author-info-detailed">
                            <h4>Sophie Martin</h4>
                            <p>Responsable Administrative</p>
                            <div class="author-company">Groupe Aura</div>
                        </div>
                    </div>
                    <div class="testimonial-meta">
                        <span class="testimonial-project">Application Mobile Financière</span>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Témoignage 4 -->
                <div class="testimonial-card-detailed" data-category="consulting">
                    <div class="testimonial-content-detailed">
                        "L'accompagnement d'Ismail dans notre transformation digitale a été déterminant. Sa double compétence comptable et technique lui a permis de proposer des solutions parfaitement adaptées à nos besoins. Les résultats sont au rendez-vous : gain de temps considérable et amélioration de la qualité de nos données financières."
                    </div>
                    <div class="testimonial-author-detailed">
                        <div class="author-avatar-detailed">
                            <img src="https://via.placeholder.com/70x70" alt="Alexandre Petit">
                        </div>
                        <div class="author-info-detailed">
                            <h4>Alexandre Petit</h4>
                            <p>Directeur Général</p>
                            <div class="author-company">ComptaPro</div>
                        </div>
                    </div>
                    <div class="testimonial-meta">
                        <span class="testimonial-project">Transformation Digitale</span>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Témoignage 5 -->
                <div class="testimonial-card-detailed" data-category="web fintech">
                    <div class="testimonial-content-detailed">
                        "Le système de reporting développé par Ismail nous fournit des insights précieux pour notre prise de décision. L'interface est intuitive et les données sont toujours à jour. Un véritable atout pour notre stratégie financière."
                    </div>
                    <div class="testimonial-author-detailed">
                        <div class="author-avatar-detailed">
                            <img src="https://via.placeholder.com/70x70" alt="Nathalie Leroy">
                        </div>
                        <div class="author-info-detailed">
                            <h4>Nathalie Leroy</h4>
                            <p>Directrice de la Stratégie</p>
                            <div class="author-company">FinanceVision</div>
                        </div>
                    </div>
                    <div class="testimonial-meta">
                        <span class="testimonial-project">Plateforme de Reporting</span>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Témoignage 6 -->
                <div class="testimonial-card-detailed" data-category="comptabilite">
                    <div class="testimonial-content-detailed">
                        "Ismail a modernisé notre cabinet comptable avec une solution qui allie parfaitement expertise métier et innovation technologique. Nos collaborateurs ont rapidement adopté les nouveaux outils et nos clients sont ravis de la qualité de service améliorée."
                    </div>
                    <div class="testimonial-author-detailed">
                        <div class="author-avatar-detailed">
                            <img src="https://via.placeholder.com/70x70" alt="Philippe Moreau">
                        </div>
                        <div class="author-info-detailed">
                            <h4>Philippe Moreau</h4>
                            <p>Gérant</p>
                            <div class="author-company">Cabinet Moreau & Associés</div>
                        </div>
                    </div>
                    <div class="testimonial-meta">
                        <span class="testimonial-project">Modernisation Cabinet</span>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Testimonials -->
    <section class="video-testimonials">
        <div class="container">
            <h2 class="text-center mb-5">Témoignages en Vidéo</h2>
            <div class="video-testimonial-grid">
                <!-- Vidéo 1 -->
                <div class="video-testimonial-card">
                    <div class="video-container">
                        <video poster="https://via.placeholder.com/400x250">
                            <source src="#" type="video/mp4">
                        </video>
                        <div class="play-button">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="video-content">
                        <div class="testimonial-author-detailed">
                            <div class="author-avatar-detailed">
                                <img src="https://via.placeholder.com/70x70" alt="Marie Lambert">
                            </div>
                            <div class="author-info-detailed">
                                <h4>Marie Lambert</h4>
                                <p>Directrice Financière</p>
                                <div class="author-company">FinTech Solutions</div>
                            </div>
                        </div>
                        <p class="mt-3">"Découvrez comment la plateforme comptable a transformé notre entreprise..."</p>
                    </div>
                </div>
                
                <!-- Vidéo 2 -->
                <div class="video-testimonial-card">
                    <div class="video-container">
                        <video poster="https://via.placeholder.com/400x250">
                            <source src="#" type="video/mp4">
                        </video>
                        <div class="play-button">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="video-content">
                        <div class="testimonial-author-detailed">
                            <div class="author-avatar-detailed">
                                <img src="https://via.placeholder.com/70x70" alt="Thomas Dubois">
                            </div>
                            <div class="author-info-detailed">
                                <h4>Thomas Dubois</h4>
                                <p>CEO</p>
                                <div class="author-company">Innov'Compta</div>
                            </div>
                        </div>
                        <p class="mt-3">"Témoignage sur l'implémentation de notre ERP comptable sur mesure..."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Stats -->
    <section class="testimonial-stats">
        <div class="container">
            <div class="stats-grid-testimonials">
                <div class="stat-card-testimonials">
                    <div class="stat-number-testimonials">98%</div>
                    <div class="stat-label-testimonials">Clients Satisfaits</div>
                </div>
                <div class="stat-card-testimonials">
                    <div class="stat-number-testimonials">4.9/5</div>
                    <div class="stat-label-testimonials">Note Moyenne</div>
                </div>
                <div class="stat-card-testimonials">
                    <div class="stat-number-testimonials">30+</div>
                    <div class="stat-label-testimonials">Témoignages</div>
                </div>
                <div class="stat-card-testimonials">
                    <div class="stat-number-testimonials">100%</div>
                    <div class="stat-label-testimonials">Projets Livrés</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Case Studies -->
    <section class="case-studies">
        <div class="container">
            <h2 class="text-center mb-5">Études de Cas Détaillées</h2>
            
            <!-- Étude de cas 1 -->
            <div class="case-study-card">
                <div class="case-study-header">
                    <div class="case-study-client">
                        <div class="client-logo-testimonial">
                            <img src="https://via.placeholder.com/40x40" alt="FinTech Solutions">
                        </div>
                        <div>
                            <h3>FinTech Solutions</h3>
                            <p>Transformation digitale complète de la gestion comptable</p>
                        </div>
                    </div>
                </div>
                <div class="case-study-content">
                    <h4>Défi</h4>
                    <p>FinTech Solutions faisait face à des processus comptables manuels entraînant des erreurs fréquentes et une perte de temps considérable. L'entreprise avait besoin d'une solution automatisée pour améliorer l'efficacité et la précision de sa gestion financière.</p>
                    
                    <h4 class="mt-4">Solution</h4>
                    <p>Développement d'une plateforme comptable automatisée intégrant la reconnaissance de documents, le rapprochement bancaire intelligent et la génération automatique des états financiers.</p>
                    
                    <div class="case-study-stats">
                        <div class="case-study-stat">
                            <div class="case-study-stat-number">+40%</div>
                            <div class="case-study-stat-label">Productivité</div>
                        </div>
                        <div class="case-study-stat">
                            <div class="case-study-stat-number">-60%</div>
                            <div class="case-study-stat-label">Erreurs</div>
                        </div>
                        <div class="case-study-stat">
                            <div class="case-study-stat-number">+25%</div>
                            <div class="case-study-stat-label">Satisfaction client</div>
                        </div>
                    </div>
                    
                    <div class="testimonial-quote-large">
                        "La plateforme développée par Ismail a transformé notre façon de travailler. Nous avons non seulement gagné en efficacité mais aussi en précision. C'est un investissement qui s'est amorti en moins de 6 mois."
                    </div>
                    
                    <a href="project-detail.html" class="btn btn-primary">Voir l'étude de cas complète</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à obtenir les mêmes résultats ?</h2>
            <p>Rejoignez mes clients satisfaits et transformez votre entreprise avec des solutions digitales sur mesure</p>
            <a href="contact.html" class="btn btn-light">Commencer un projet</a>
        </div>
    </section>

    <!-- Footer -->
       <?php include INCLUDES.'footer.php' ;?> 

    <!-- Bootstrap 5 JS -->
    <?php include INCLUDES.'vendor-js-file.php' ;?> 
    
    <!-- Custom JS -->
   <script src="assets/js/testimonials.js"></script>
</body>
</html>