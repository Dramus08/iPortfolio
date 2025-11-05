document.addEventListener('DOMContentLoaded', function() {
    // Initialisation de Lightbox
    /*
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'showImageNumberLabel': false,
            'positionFromTop': 100
        });
    */

    // Filtrage des projets
    const filterButtons = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    const searchInput = document.getElementById('project-search');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Active le bouton cliqué
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            filterProjects(filterValue);
        });
    });
    
    // Fonction de filtrage
    function filterProjects(filterValue, searchTerm = '') {
        portfolioItems.forEach(item => {
            const matchesFilter = filterValue === 'all' || item.classList.contains(filterValue);
            const matchesSearch = item.textContent.toLowerCase().includes(searchTerm.toLowerCase());
            
            if (matchesFilter && matchesSearch) {
                item.style.display = 'block';
                item.classList.add('animate__fadeInUp');
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    // Recherche de projets
    searchInput.addEventListener('input', function() {
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
        filterProjects(activeFilter, this.value);
    });
    
    // Animation des statistiques
    const statNumbers = document.querySelectorAll('.stat-number');
    const statsSection = document.querySelector('.portfolio-stats');
    
    function animateStats() {
        const sectionPosition = statsSection.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.2;
        
        if (sectionPosition < screenPosition) {
            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-count'));
                const duration = 2000; // 2 secondes
                const increment = target / (duration / 16); // 60fps
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        clearInterval(timer);
                        current = target;
                    }
                    stat.textContent = Math.floor(current);
                }, 16);
            });
            
            // Ne pas répéter l'animation
            window.removeEventListener('scroll', animateStats);
        }
    }
    
    // Animation au scroll
    function animateOnScroll() {
        const elements = document.querySelectorAll('.animate__animated');
        
        elements.forEach(el => {
            const elementPosition = el.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;
            
            if (elementPosition < screenPosition) {
                const delay = el.getAttribute('data-delay') || '0s';
                el.style.animationDelay = delay;
                el.classList.add('animate__fadeInUp');
            }
        });
        
        animateStats();
    }
    
    // Événements
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Lance au chargement
    
    // Initialisation
    filterProjects('all');
});