<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'guardar-noticia.php';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Noticia</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="admin-container">
        <h1>Crear Nueva Noticia</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            
            <div class="form-group">
                <label for="imagen">Imagen del Banner:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
            </div>
            
            <div class="form-group">
                <label for="contenido">Contenido:</label>
                <textarea id="contenido" name="contenido" rows="10" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required value="<?= date('Y-m-d') ?>">
            </div>
            
            <button type="submit" class="btn">Guardar Noticia</button>
            <a href="index.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>