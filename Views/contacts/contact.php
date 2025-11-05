<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Ismail Inoua | Expert Comptable & Développeur Full-Stack</title>
    
     <?php include INCLUDES.'head.php' ;?> 
    <link rel="stylesheet" href="assets/css/contact.css">
</head>
<body>
    <!-- Navigation -->
     <?php include INCLUDES.'navbar.php' ;?>  

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Contactez-moi</h1>
            <p>Discutons de votre projet et créons ensemble la solution digitale parfaite pour vos besoins</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-detailed">
        <div class="container">
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
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Horaires</h5>
                                <p>Lun - Ven: 9h00 - 18h00<br>Sam: 10h00 - 14h00</p>
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
                        <h3 class="mb-4">Envoyez-moi un message</h3>
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom complet *</label>
                                        <input type="text" class="form-control" id="name" placeholder="Votre nom" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" placeholder="votre@email.com" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="company" class="form-label">Entreprise</label>
                                <input type="text" class="form-control" id="company" placeholder="Nom de votre entreprise">
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">Sujet *</label>
                                <input type="text" class="form-control" id="subject" placeholder="Sujet de votre message" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="service" class="form-label">Type de service</label>
                                <select class="form-control" id="service">
                                    <option value="">Sélectionnez un service</option>
                                    <option value="comptabilite">Expertise Comptable</option>
                                    <option value="developpement">Développement Web</option>
                                    <option value="fintech">Solutions FinTech</option>
                                    <option value="consulting">Consulting</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="budget" class="form-label">Budget estimé</label>
                                <select class="form-control" id="budget">
                                    <option value="">Sélectionnez une fourchette</option>
                                    <option value="1-3k">1 000€ - 3 000€</option>
                                    <option value="3-7k">3 000€ - 7 000€</option>
                                    <option value="7-15k">7 000€ - 15 000€</option>
                                    <option value="15k+">Plus de 15 000€</option>
                                    <option value="indefini">À définir</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" rows="6" placeholder="Décrivez votre projet en détail..." required></textarea>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="privacy" required>
                                <label class="form-check-label" for="privacy">
                                    J'accepte la politique de confidentialité et le traitement de mes données personnelles
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Envoyer le message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <h2 class="text-center mb-5">Zone d'Intervention</h2>
            <div class="map-container">
                <!-- Intégration Google Maps ou autre service de cartes -->
                <div style="width: 100%; height: 100%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: var(--gray);">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                        <p>Carte interactive - Paris et région Île-de-France</p>
                        <small>Intervention possible dans toute la France et à l'international</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Contact -->
    <section class="faq-contact">
        <div class="container">
            <h2 class="text-center mb-5">Questions Fréquentes</h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-icon">
                        <i class="far fa-clock"></i>
                    </div>
                    <h4>Quel est le délai de réponse ?</h4>
                    <p>Je m'engage à répondre à toutes les demandes sous 24 heures ouvrées. Pour les projets urgents, merci de le préciser dans votre message.</p>
                </div>
                
                <div class="faq-item">
                    <div class="faq-icon">
                        <i class="far fa-comment"></i>
                    </div>
                    <h4>Proposez-vous une consultation gratuite ?</h4>
                    <p>Oui, je propose une consultation initiale gratuite de 30 minutes pour discuter de votre projet et évaluer vos besoins.</p>
                </div>
                
                <div class="faq-item">
                    <div class="faq-icon">
                        <i class="far fa-file-alt"></i>
                    </div>
                    <h4>Comment obtenir un devis détaillé ?</h4>
                    <p>Après notre consultation initiale, je prépare un devis détaillé avec le planning, les spécifications techniques et le budget.</p>
                </div>
                
                <div class="faq-item">
                    <div class="faq-icon">
                        <i class="far fa-handshake"></i>
                    </div>
                    <h4>Travaillez-vous à l'international ?</h4>
                    <p>Absolument ! Je travaille avec des clients partout dans le monde. Les réunions se font par visioconférence aux fuseaux horaires adaptés.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-contact">
        <div class="container">
            <h2 class="text-center mb-5">Comment se déroule notre collaboration</h2>
            <div class="process-steps-contact">
                <div class="process-step-contact">
                    <div class="step-number-contact">1</div>
                    <h4>Consultation Initiale</h4>
                    <p>Nous discutons de votre projet, objectifs et contraintes lors d'un appel découverte gratuit.</p>
                </div>
                
                <div class="process-step-contact">
                    <div class="step-number-contact">2</div>
                    <h4>Analyse & Devis</h4>
                    <p>Je prépare une analyse détaillée et un devis personnalisé pour votre projet.</p>
                </div>
                
                <div class="process-step-contact">
                    <div class="step-number-contact">3</div>
                    <h4>Développement</h4>
                    <p>Phase de développement avec livraisons régulières et validation à chaque étape.</p>
                </div>
                
                <div class="process-step-contact">
                    <div class="step-number-contact">4</div>
                    <h4>Livraison & Support</h4>
                    <p>Livraison finale, formation et mise en place du support continu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à démarrer votre projet ?</h2>
            <p>Contactez-moi dès aujourd'hui pour une consultation gratuite et sans engagement</p>
            <a href="#contactForm" class="btn btn-light">Commencer maintenant</a>
        </div>
    </section>

    <!-- Footer -->
     <?php include INCLUDES.'footer.php' ;?>  

    <!-- Bootstrap 5 JS -->
    <?php include INCLUDES.'vendor-js-file.php' ;?>    
    <!-- Custom JS -->
    <script src="assets/js/contact.js"></script>
</body>
</html>