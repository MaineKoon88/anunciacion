document.addEventListener('DOMContentLoaded', function() {
    const cargarNoticias = async () => {
        const contenedor = document.getElementById('contenedor-noticias');
        
        try {
            const response = await fetch('api/noticias.php?limit=4');
            
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            
            const data = await response.json();
            const noticias = Array.isArray(data) ? data : (data.data || []);
            
            if (!noticias || noticias.length === 0) {
                contenedor.innerHTML = '<p>No hay noticias disponibles</p>';
                return;
            }

            contenedor.innerHTML = noticias.map(noticia => `
                <div class="noticia-card">
                    <a href="noticia.php?id=${noticia.id}" class="noticia-link">
                        <h3 class="noticia-titulo">${escapeHtml(noticia.titulo)}</h3>
                        <span class="noticia-fecha">${formatearFecha(noticia.fecha)}</span>
                        <p class="noticia-resumen">${escapeHtml(noticia.contenido.substring(0, 120))}...</p>
                    </a>
                </div>
            `).join('');
            
        } catch (error) {
            console.error('Error:', error);
            contenedor.innerHTML = `
                <div class="error-noticias">
                    <p>No se pudieron cargar las noticias. <a href="noticias.php">Ver noticias directamente</a></p>
                </div>
            `;
        }
    };

    // Funciones auxiliares
    const formatearFecha = (fechaString) => {
        const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(fechaString).toLocaleDateString('es-ES', opciones);
    };

    const escapeHtml = (text) => {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    cargarNoticias();
});