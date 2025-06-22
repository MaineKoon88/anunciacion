<?php
$noticias = json_decode(file_get_contents('noticias/data.json'), true);
$id_noticia = $_GET['id'];
$noticia = null;

foreach ($noticias as $n) {
    if ($n['id'] == $id_noticia) {
        $noticia = $n;
        break;
    }
}

if (!$noticia) {
    header('Location: noticias.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($noticia['titulo']) ?> - Colegio Parroquial</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/noticias.css">
    <style>
        .banner-noticia {
            background-image: url('noticias/imagenes/<?= $noticia['imagen'] ?>');
        }
    </style>
</head>
<body>
    <!-- Navbar (usando el mismo que en index.html) -->
    <?php include 'navbar.php'; ?>
    
    <section class="banner-noticia">
        <div class="banner-contenido">
            <h1><?= htmlspecialchars($noticia['titulo']) ?></h1>
        </div>
    </section>
    
    <section class="contenido-noticia">
        <p class="fecha-publicacion">Publicado el <?= date('d/m/Y', strtotime($noticia['fecha'])) ?></p>
        
        <div class="texto-noticia">
            <?= nl2br(htmlspecialchars($noticia['contenido'])) ?>
        </div>
    </section>
    
    <a href="noticias.php" class="btn-volver">Volver a Noticias</a>
</body>
</html>