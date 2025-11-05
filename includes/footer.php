 <!-- Footer -->
  <?php var_dump($Router); ?>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5">
                    <div class="footer-logo">IICoding</div>
                    <p class="mb-4">Expert-comptable & Développeur Full-Stack spécialisé en solutions technologiques pour la comptabilité et la finance.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-5">
                    <div class="footer-links">
                        <h5>Navigation</h5>
                        <ul>
                            <li><a href="<?=$Router::route('index');?>">Accueil</a></li>
                            <li><a href="<?=$Router::route('about');?>">À propos</a></li>
                            <li><a href="<?=$Router::route('service_list');?>">Services</a></li>
                            <li><a href="<?=$Router::route('project_list');?>">Projets</a></li>
                            <li><a href="<?=$Router::route('portfolio_list');?>">Portfolio</a></li>
                            <li><a href="<?=$Router::route('contact');?>">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-5">
                    <div class="footer-links">
                        <h5>Services</h5>
                        <ul>
                            <li><a href="<?=$Router::route('service_list');?>">Expertise Comptable</a></li>
                            <li><a href="<?=$Router::route('service_list');?>">Développement Web</a></li>
                            <li><a href="<?=$Router::route('service_list');?>">Solutions Fintech</a></li>
                            <li><a href="<?=$Router::route('service_list');?>">Consulting</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-5">
                    <div class="footer-links">
                        <h5>Contact</h5>
                        <ul>
                            <li><i class="fas fa-envelope me-2"></i> contact@iicoding-tech.com</li>
                            <li><i class="fas fa-phone me-2"></i> +237 6 94 74 43 94</li>
                            <li><i class="fas fa-map-marker-alt me-2"></i> Paris, France</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 IICoding. Tous droits réservés.</p>
            </div>
        </div>
    </footer>