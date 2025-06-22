<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}

// 1. Obtener ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) die('ID no válido');

// 2. Cargar noticias
$ruta_json = '../noticias/data.json';
$noticias = json_decode(file_get_contents($ruta_json), true);
$noticia = current(array_filter($noticias, fn($n) => $n['id'] == $id));

if (!$noticia) die('Noticia no encontrada');

// 3. Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noticia_actualizada = [
        'id' => $id,
        'titulo' => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS),
        'contenido' => filter_input(INPUT_POST, 'contenido', FILTER_SANITIZE_SPECIAL_CHARS),
        'fecha' => $noticia['fecha'], // Mantener fecha original
        'imagen' => $noticia['imagen'] // Mantener imagen
    ];

    // Actualizar array
    foreach ($noticias as &$n) {
        if ($n['id'] == $id) $n = $noticia_actualizada;
    }

    if (file_put_contents($ruta_json, json_encode($noticias, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        header('Location: index.php?success=1');
        exit;
    } else {
        $error = "Error al guardar";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Noticia</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="container">
        <h1>Editar Noticia</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label>Título:</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Contenido:</label>
                <textarea name="contenido" required><?= htmlspecialchars($noticia['contenido']) ?></textarea>
            </div>
            
            <button type="submit">Guardar</button>
        </form>
    </div>
</body>
</html>