document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validación básica
            const nombre = document.getElementById('nombre').value.trim();
            const email = document.getElementById('email').value.trim();
            const consulta = document.getElementById('consulta').value.trim();
            
            if (!nombre || !email || !consulta) {
                alert('Por favor complete todos los campos obligatorios.');
                return;
            }
            
            if (!validateEmail(email)) {
                alert('Por favor ingrese un email válido.');
                return;
            }
            
            // Simular envío (en producción sería una petición AJAX)
            alert('Gracias por su consulta. Nos pondremos en contacto pronto.');
            contactForm.reset();
            
            // Aquí normalmente harías una petición AJAX al servidor
            // fetch('procesar-formulario.php', {
            //     method: 'POST',
            //     body: new FormData(contactForm)
            // })
            // .then(response => response.json())
            // .then(data => {
            //     alert(data.message);
            //     contactForm.reset();
            // })
            // .catch(error => {
            //     console.error('Error:', error);
            //     alert('Hubo un error al enviar el formulario.');
            // });
        });
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});