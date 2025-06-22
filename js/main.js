document.addEventListener('DOMContentLoaded', function() {
    // ==============================================
    // FRASES MOTIVADORAS
    // ==============================================
    const quotes = [
        {
            text: "La educación es el arma más poderosa para cambiar el mundo",
            author: "Nelson Mandela"
        },
        {
            text: "Enseñar es aprender dos veces",
            author: "Joseph Joubert"
        },
        {
            text: "La educación no es preparación para la vida, es la vida misma",
            author: "John Dewey"
        },
        {
            text: "El arte supremo del maestro es despertar el goce de la expresión creativa y el conocimiento",
            author: "Albert Einstein"
        },
        {
            text: "Educar la mente sin educar el corazón no es educación en absoluto",
            author: "Aristóteles"
        }
    ];

    const quoteContainer = document.querySelector('.motivational-quote');
    const quoteText = document.querySelector('.quote-text');
    const quoteAuthor = document.querySelector('.quote-author');

    if (quoteContainer && quoteText && quoteAuthor) {
        let currentIndex = 0;

        function changeQuote() {
            // Aplicar fade out
            quoteContainer.classList.add('fade-out');
            
            // Esperar a que termine la transición de fade out
            setTimeout(() => {
                // Cambiar el contenido
                currentIndex = (currentIndex + 1) % quotes.length;
                quoteText.textContent = `"${quotes[currentIndex].text}"`;
                quoteAuthor.textContent = `- ${quotes[currentIndex].author}`;
                
                // Aplicar fade in
                quoteContainer.classList.remove('fade-out');
            }, 500);
        }

        // Cambiar frase cada 8 segundos
        setInterval(changeQuote, 8000);
    }

    // ==============================================
    // MENÚ RESPONSIVE MEJORADO
    // ==============================================
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navbar = document.querySelector('.navbar');
    const navLeft = document.querySelector('.nav-left');
    const navRight = document.querySelector('.nav-right');
    const body = document.body;
    
    if (mobileMenuBtn && navbar && navLeft && navRight) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Alternar clase active en el navbar
            navbar.classList.toggle('active');
            
            // Cambiar icono
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
            
            // Bloquear scroll del body
            body.classList.toggle('no-scroll');
        });

        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    navbar.classList.remove('active');
                    const icon = mobileMenuBtn.querySelector('i');
                    if (icon) {
                        icon.classList.add('fa-bars');
                        icon.classList.remove('fa-times');
                    }
                    body.classList.remove('no-scroll');
                }
            });
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (navbar.classList.contains('active') &&
                !navbar.contains(e.target) && 
                (!mobileMenuBtn || !mobileMenuBtn.contains(e.target))) {
                
                navbar.classList.remove('active');
                const icon = mobileMenuBtn.querySelector('i');
                if (icon) {
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
                body.classList.remove('no-scroll');
            }
        });
    }

    // ==============================================
    // EFECTO RIPPLE PARA BOTONES
    // ==============================================
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Crear efecto ripple
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            
            // Posicionar el efecto
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size/2;
            const y = e.clientY - rect.top - size/2;
            
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            // Añadir y luego remover el efecto
            this.appendChild(ripple);
            setTimeout(() => {
                ripple.remove();
            }, 1000);
        });
    });

    // ==============================================
    // SCROLL SUAVE PARA ENLACES
    // ==============================================
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
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

    // ==============================================
    // NAVBAR STICKY AL HACER SCROLL
    // ==============================================
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

    // ==============================================
    // EFECTO PARALLAX PARA HERO SECTION
    // ==============================================
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            heroSection.style.backgroundPositionY = scrollPosition * 0.5 + 'px';
        });
    }
});