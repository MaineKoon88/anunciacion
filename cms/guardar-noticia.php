<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}

require 'funciones.php'; // Ahora está en la misma carpeta

try {
    // Validar y limpiar datos
    $titulo = limpiar_input($_POST['titulo']);
    $contenido = limpiar_input($_POST['contenido']);
    $fecha = limpiar_input($_POST['fecha']);
    
    // Subir imagen
    $imagen_nombre = subir_imagen($_FILES['imagen'], '../noticias/imagenes/');
    
    // Crear array de noticia
    $nueva_noticia = [
        'id' => uniqid(),
        'titulo' => $titulo,
        'imagen' => $imagen_nombre,
        'contenido' => $contenido,
        'fecha' => $fecha
    ];
    
    // Guardar en JSON
    guardar_en_json('../noticias/data.json', $nueva_noticia);
    
    header('Location: index.php');
    exit;

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>