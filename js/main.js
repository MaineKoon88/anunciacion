document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinksContainer = document.querySelector('.nav-links-container');
    const body = document.body;
    
    if (mobileMenuBtn && navLinksContainer) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Evita que el evento se propague
            navLinksContainer.classList.toggle('active');
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
            
            body.classList.toggle('no-scroll');
        });
    }

    // Cerrar menú al hacer clic en un enlace
    const navButtons = document.querySelectorAll('.nav-btn');
    if (navButtons.length > 0) {
        navButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                if (navLinksContainer) navLinksContainer.classList.remove('active');
                if (mobileMenuBtn) {
                    const icon = mobileMenuBtn.querySelector('i');
                    if (icon) {
                        icon.classList.add('fa-bars');
                        icon.classList.remove('fa-times');
                    }
                }
                body.classList.remove('no-scroll');
            });
        });
    }

    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (navLinksContainer && navLinksContainer.classList.contains('active') &&
            !navLinksContainer.contains(e.target) && 
            (!mobileMenuBtn || !mobileMenuBtn.contains(e.target))) {
            
            navLinksContainer.classList.remove('active');
            if (mobileMenuBtn) {
                const icon = mobileMenuBtn.querySelector('i');
                if (icon) {
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            }
            body.classList.remove('no-scroll');
        }
    });

    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    if (anchorLinks.length > 0) {
        anchorLinks.forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Sticky navbar on scroll
    const navbar = document.querySelector('.navbar');
    const logo = document.querySelector('.logo');
    const heroSection = document.querySelector('.hero');
    
    if (navbar && logo && heroSection) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > heroSection.offsetHeight - 100) {
                navbar.classList.add('sticky');
                logo.style.height = '80px';
            } else {
                navbar.classList.remove('sticky');
                logo.style.height = '120px';
            }
        });
    }

    // Parallax effect for hero section
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            heroSection.style.backgroundPositionY = scrollPosition * 0.5 + 'px';
        });
    }
});