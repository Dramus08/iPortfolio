document.addEventListener('DOMContentLoaded', function() {
    // Carrousel de témoignages
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prev-testimonial');
    const nextBtn = document.getElementById('next-testimonial');
    let currentIndex = 0;
    
    // Afficher le témoignage à l'index donné
    function showTestimonial(index) {
        // Masquer tous les témoignages
        testimonialCards.forEach(card => card.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Afficher le témoignage sélectionné
        testimonialCards[index].classList.add('active');
        dots[index].classList.add('active');
        currentIndex = index;
    }
    
    // Navigation précédente
    if(prevBtn){
        prevBtn.addEventListener('click', function() {
            let newIndex = currentIndex - 1;
            if (newIndex < 0) {
                newIndex = testimonialCards.length - 1;
            }
            showTestimonial(newIndex);
        });
    }
    // Navigation suivante
    if(nextBtn){
        nextBtn.addEventListener('click', function() {
        let newIndex = currentIndex + 1;
        if (newIndex >= testimonialCards.length) {
            newIndex = 0;
        }
        showTestimonial(newIndex);
    });
    }
    
    // Navigation par points
    dots.forEach(dot => {
        dot.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            showTestimonial(index);
        });
    });
    
    // Auto-rotation (optionnel)
    let carouselInterval = setInterval(() => {
        let newIndex = currentIndex + 1;
        if (newIndex >= testimonialCards.length) {
            newIndex = 0;
        }
        showTestimonial(newIndex);
    }, 5000);
    
    // Pause au survol
    const carousel = document.querySelector('.testimonials-carousel');
    carousel.addEventListener('mouseenter', () => {
        clearInterval(carouselInterval);
    });
    
    carousel.addEventListener('mouseleave', () => {
        carouselInterval = setInterval(() => {
            let newIndex = currentIndex + 1;
            if (newIndex >= testimonialCards.length) {
                newIndex = 0;
            }
            showTestimonial(newIndex);
        }, 5000);
    });
    
    // Animation au scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.animate__animated');
        
        elements.forEach(el => {
            const elementPosition = el.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;
            
            if (elementPosition < screenPosition) {
                el.classList.add('animate__fadeInUp');
            }
        });
    };
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Lance au chargement
});