// Animations au scroll
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes de services
    const serviceCards = document.querySelectorAll('.service-card');
    
    const animateCards = () => {
        serviceCards.forEach(card => {
            const cardPosition = card.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;
            
            if (cardPosition < screenPosition) {
                const delay = card.getAttribute('data-delay') || '0s';
                card.style.animation = `fadeInUp 0.5s ${delay} forwards`;
            }
        });
    };

    // Gestion du modal
    const modal = document.querySelector('.modal');
    const modalTriggers = document.querySelectorAll('.modal-trigger');
    const closeModal = document.querySelector('.modal .modal-close');

    if (modal) {
        modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            modal.style.display = 'block';
        });
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    }

    // Initialisation
    window.addEventListener('scroll', animateCards);
    animateCards(); // Lance au chargement

    // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
});