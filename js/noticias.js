document.addEventListener('DOMContentLoaded', function() {
    const cargarNoticias = async () => {
        const contenedor = document.getElementById('contenedor-noticias');
        
        try {
            console.log("Intentando cargar noticias...");
            const response = await fetch('api/noticias.php?limit=4');
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const noticias = await response.json();
            console.log("Noticias recibidas:", noticias);
            
            if (!noticias || noticias.length === 0) {
                throw new Error("No hay noticias disponibles");
            }
            
            mostrarNoticias(noticias);
        } catch (error) {
            console.error('Error al cargar noticias:', error);
            contenedor.innerHTML = `
                <div class="error-noticias">
                    <p>No se pudieron cargar las noticias. ${error.message}</p>
                    <p><a href="noticias.php">Ver noticias directamente</a></p>
                </div>
            `;
        }
    };

    const mostrarNoticias = (noticias) => {
        const contenedor = document.getElementById('contenedor-noticias');
        
        contenedor.innerHTML = noticias.map(noticia => `
            <article class="noticia-item">
                <a href="noticia.php?id=${noticia.id}" class="enlace-noticia">
                    <div class="noticia-imagen">
                        <img src="${noticia.imagen.startsWith('http') ? noticia.imagen : 'noticias/imagenes/' + noticia.imagen}" 
                             alt="${noticia.titulo}" 
                             onerror="this.src='img/noticia-default.jpg'">
                    </div>
                    <div class="noticia-contenido">
                        <h3>${noticia.titulo}</h3>
                        <div class="noticia-meta">
                            <span class="noticia-fecha">${formatearFecha(noticia.fecha)}</span>
                        </div>
                        <p class="noticia-resumen">${noticia.contenido.substring(0, 150)}...</p>
                        <span class="noticia-leer">Leer más</span>
                    </div>
                </a>
            </article>
        `).join('');
    };

    const formatearFecha = (fechaString) => {
        const opciones = { day: '2-digit', month: 'long', year: 'numeric' };
        return new Date(fechaString).toLocaleDateString('es-ES', opciones);
    };

    // Iniciar la carga de noticias
    cargarNoticias();
});