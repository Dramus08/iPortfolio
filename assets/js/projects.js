        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Project filtering
        document.querySelectorAll('.project-filter').forEach(filter => {
            filter.addEventListener('click', function() {
                // Remove active class from all filters
                document.querySelectorAll('.project-filter').forEach(f => {
                    f.classList.remove('active');
                });
                
                // Add active class to clicked filter
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                const projectCards = document.querySelectorAll('.project-card-detailed');
                
                projectCards.forEach(card => {
                    if (filterValue === 'all' || card.getAttribute('data-category').includes(filterValue)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Testimonial carousel
        const testimonialItems = document.querySelectorAll('.testimonial-item');
        const prevBtn = document.querySelector('.carousel-btn.prev');
        const nextBtn = document.querySelector('.carousel-btn.next');
        let currentTestimonial = 0;
        
        function showTestimonial(index) {
            testimonialItems.forEach(item => {
                item.classList.remove('active');
            });
            
            testimonialItems[index].classList.add('active');
            currentTestimonial = index;
        }
        
        prevBtn.addEventListener('click', function() {
            let newIndex = currentTestimonial - 1;
            if (newIndex < 0) {
                newIndex = testimonialItems.length - 1;
            }
            showTestimonial(newIndex);
        });
        
        nextBtn.addEventListener('click', function() {
            let newIndex = currentTestimonial + 1;
            if (newIndex >= testimonialItems.length) {
                newIndex = 0;
            }
            showTestimonial(newIndex);
        });
        
        // Lightbox initialization
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'imageFadeDuration': 300
        });