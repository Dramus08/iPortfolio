        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Pricing toggle functionality
        const toggleSwitch = document.querySelector('.toggle-switch');
        const monthlyLabel = document.querySelector('.toggle-label[data-period="monthly"]');
        const yearlyLabel = document.querySelector('.toggle-label[data-period="yearly"]');
        const monthlyPrices = document.querySelectorAll('.monthly-price');
        const yearlyPrices = document.querySelectorAll('.yearly-price');
        
        toggleSwitch.addEventListener('click', function() {
            this.classList.toggle('monthly');
            this.classList.toggle('yearly');
            
            monthlyLabel.classList.toggle('active');
            yearlyLabel.classList.toggle('active');
            
            if (this.classList.contains('yearly')) {
                monthlyPrices.forEach(price => price.style.display = 'none');
                yearlyPrices.forEach(price => price.style.display = 'inline');
            } else {
                monthlyPrices.forEach(price => price.style.display = 'inline');
                yearlyPrices.forEach(price => price.style.display = 'none');
            }
        });
        
        // Labels also toggle
        monthlyLabel.addEventListener('click', function() {
            if (!this.classList.contains('active')) {
                toggleSwitch.classList.remove('yearly');
                toggleSwitch.classList.add('monthly');
                this.classList.add('active');
                yearlyLabel.classList.remove('active');
                
                monthlyPrices.forEach(price => price.style.display = 'inline');
                yearlyPrices.forEach(price => price.style.display = 'none');
            }
        });
        
        yearlyLabel.addEventListener('click', function() {
            if (!this.classList.contains('active')) {
                toggleSwitch.classList.remove('monthly');
                toggleSwitch.classList.add('yearly');
                this.classList.add('active');
                monthlyLabel.classList.remove('active');
                
                monthlyPrices.forEach(price => price.style.display = 'none');
                yearlyPrices.forEach(price => price.style.display = 'inline');
            }
        });
