<?php
$noticias = json_decode(file_get_contents('noticias/data.json'), true);
$noticias = array_reverse($noticias); // Mostrar las más recientes primero
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$total_paginas = ceil(count($noticias) / 5);
$noticias_pagina = array_slice($noticias, ($pagina_actual - 1) * 5, 5);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Noticias - Colegio Parroquial</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/noticias.css">
</head>
<body>
    <!-- Navbar (usando el mismo que en index.html) -->
    <?php include 'navbar.php'; ?>
    
    <section class="noticias-container">
        <h1>Últimas Noticias</h1>
        
        <div class="lista-noticias">
            <?php foreach ($noticias_pagina as $noticia): ?>
            <article class="noticia-resumen">
                <a href="noticia.php?id=<?= $noticia['id'] ?>">
                    <h2><?= htmlspecialchars($noticia['titulo']) ?></h2>
                    <p class="fecha"><?= date('d/m/Y', strtotime($noticia['fecha'])) ?></p>
                    <p class="resumen"><?= htmlspecialchars(substr($noticia['contenido'], 0, 150)) ?>...</p>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        
        <div class="paginacion">
            <?php if ($pagina_actual > 1): ?>
                <a href="?pagina=<?= $pagina_actual - 1 ?>" class="btn-paginacion">Anterior</a>
            <?php endif; ?>
            
            <span>Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>
            
            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?pagina=<?= $pagina_actual + 1 ?>" class="btn-paginacion">Siguiente</a>
            <?php endif; ?>
        </div>
    </section>
    
    <script src="js/noticias.js"></script>
</body>
</html>