// Animations au scroll
document.addEventListener('DOMContentLoaded', () => {
    // Menu mobile
    const menuBtn = document.querySelector('.mobile-menu');
    const nav = document.querySelector('nav ul');
    
    if (menuBtn){
         menuBtn.addEventListener('click', () => {
            nav.classList.toggle('show');
        });
    }

    // Animation des compétences
    const skillCards = document.querySelectorAll('.skill-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, { threshold: 0.1 });

    skillCards.forEach(card => {
        observer.observe(card);
    });
});