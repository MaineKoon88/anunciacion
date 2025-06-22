document.addEventListener('DOMContentLoaded', function() {
    const contenedor = document.getElementById('contenedor-noticias');
    
    // Estilos garantizados (como fallback)
    const style = document.createElement('style');
    style.textContent = `
        #contenedor-noticias {
            display: grid;
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .noticia-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .noticia-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }
        .noticia-titulo {
            color: #2c3e50;
            margin: 0 0 0.5rem 0;
            font-size: 1.25rem;
        }
        .noticia-fecha {
            color: #7f8c8d;
            font-size: 0.85rem;
            display: block;
            margin-bottom: 1rem;
        }
        .noticia-resumen {
            color: #34495e;
            line-height: 1.6;
        }
        .error-noticias {
            background: #ffebee;
            padding: 1rem;
            border-radius: 8px;
            color: #c62828;
            text-align: center;
        }
    `;
    document.head.appendChild(style);

    // Función mejorada para cargar noticias
    const cargarNoticias = async () => {
        try {
            // Mostrar estado de carga
            contenedor.innerHTML = `
                <div class="cargando">
                    <div class="spinner"></div>
                    <p>Cargando noticias...</p>
                </div>
            `;

            const response = await fetch('api/noticias.php?limit=4');
            
            if (!response.ok) {
                throw new Error(`Error HTTP ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON');
            }

            const data = await response.json();
            const noticias = Array.isArray(data) ? data : (data.data || []);
            
            if (noticias.length === 0) {
                throw new Error('No hay noticias disponibles');
            }

            // Ordenar por fecha (más recientes primero)
            noticias.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

            mostrarNoticias(noticias);
        } catch (error) {
            console.error('Error al cargar noticias:', error);
            mostrarError(error);
        }
    };

    // Función para mostrar noticias
    const mostrarNoticias = (noticias) => {
        contenedor.innerHTML = noticias.map(noticia => `
            <div class="noticia-card">
                <a href="noticia.php?id=${noticia.id}" class="noticia-link">
                    <h3 class="noticia-titulo">${escapeHtml(noticia.titulo)}</h3>
                    <span class="noticia-fecha">${formatearFecha(noticia.fecha)}</span>
                    <p class="noticia-resumen">${escapeHtml(noticia.contenido.substring(0, 150))}...</p>
                </a>
            </div>
        `).join('');
    };

    // Función para mostrar errores
    const mostrarError = (error) => {
        contenedor.innerHTML = `
            <div class="error-noticias">
                <p>${error.message}</p>
                <a href="noticias.php">Ver noticias directamente</a>
            </div>
        `;
    };

    // Función para formatear fecha
    const formatearFecha = (fechaString) => {
        const opciones = { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        };
        return new Date(fechaString).toLocaleDateString('es-ES', opciones);
    };

    // Función para prevenir XSS
    const escapeHtml = (text) => {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    // Cargar noticias al iniciar
    cargarNoticias();
    
    // Opcional: Recargar cada 30 segundos
    setInterval(cargarNoticias, 30000);
});