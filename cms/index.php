<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}

$noticias = json_decode(file_get_contents('../noticias/data.json'), true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Noticias</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 0.75rem;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .acciones a {
            margin-right: 0.75rem;
            text-decoration: none;
        }
        .btn-eliminar {
            color: #e74c3c;
            cursor: pointer;
        }
        .btn-eliminar:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Administrar Noticias</h1>
        <a href="crear-noticia.php" class="btn">Crear Nueva Noticia</a>
        
        <table id="tabla-noticias">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($noticias) as $noticia): ?>
                <tr data-id="<?= $noticia['id'] ?>">
                    <td><?= htmlspecialchars($noticia['titulo']) ?></td>
                    <td><?= date('d/m/Y', strtotime($noticia['fecha'])) ?></td>
                    <td class="acciones">
    <a href="editar-noticia.php?id=<?= $noticia['id'] ?>" class="btn-editar">Editar</a>
    <a href="#" class="btn-eliminar" data-id="<?= $noticia['id'] ?>">Eliminar</a>
</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript al final del body -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const tabla = document.getElementById('tabla-noticias');
    
    tabla.addEventListener('click', async function(e) {
        if (e.target.classList.contains('btn-eliminar')) {
            e.preventDefault();
            const id = e.target.dataset.id;
            
            if (!confirm('¿Eliminar esta noticia permanentemente?')) return;

            try {
                // Mostrar estado de carga
                e.target.innerHTML = '<span class="spinner"></span> Eliminando...';
                e.target.disabled = true;

                const response = await fetch('../api/eliminar-noticia.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });

                const data = await response.json();
                
                if (!response.ok || !data.success) {
                    throw new Error(data.error || `Error HTTP ${response.status}`);
                }

                // Eliminar visualmente con animación
                const fila = e.target.closest('tr');
                fila.style.opacity = '0';
                setTimeout(() => fila.remove(), 300);

                // Forzar recarga del index.html después de 1 segundo
                setTimeout(() => {
                    fetch('../index.html', { cache: 'reload' })
                        .then(() => console.log('Cache de index.html limpiado'))
                        .catch(err => console.error('Error recargando cache:', err));
                }, 1000);

            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
                
                // Restaurar botón
                e.target.innerHTML = 'Eliminar';
                e.target.disabled = false;
            }
        }
    });
});
</script>
</body>
</html>