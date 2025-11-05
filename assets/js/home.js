        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
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
                const portfolioItems = document.querySelectorAll('.portfolio-item');
                
                portfolioItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
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
        
        // Auto-advance testimonials
        setInterval(() => {
            let newIndex = currentTestimonial + 1;
            if (newIndex >= testimonialItems.length) {
                newIndex = 0;
            }
            showTestimonial(newIndex);
        }, 5000);
        
        // Animate skill bars on scroll
        function animateSkills() {
            const skillBars = document.querySelectorAll('.skill-progress');
            skillBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        }
        
        // Intersection Observer for skill animation
        const aboutSection = document.querySelector('.about');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateSkills();
                    observer.unobserve(aboutSection);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(aboutSection);
        
        // Form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Merci pour votre message ! Je vous répondrai dans les plus brefs délais.');
            this.reset();
        });
