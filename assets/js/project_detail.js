        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Lightbox initialization
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'imageFadeDuration': 300,
            'albumLabel': "Image %1 sur %2"
        });
