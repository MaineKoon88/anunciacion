<?php
// funciones.php - Funciones auxiliares para el CMS

/**
 * Valida y sanitiza datos de entrada
 */
function limpiar_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Sube una imagen al servidor
 */
function subir_imagen($file_input, $destino) {
    $nombre_archivo = uniqid() . '-' . basename($file_input["name"]);
    $ruta_completa = $destino . $nombre_archivo;
    
    // Validar tipo de archivo
    $tipo_permitido = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file_input["type"], $tipo_permitido)) {
        throw new Exception("Solo se permiten imágenes JPG, PNG o GIF");
    }
    
    if (move_uploaded_file($file_input["tmp_name"], $ruta_completa)) {
        return $nombre_archivo;
    } else {
        throw new Exception("Error al subir la imagen");
    }
}

/**
 * Guarda datos en un archivo JSON
 */
function guardar_en_json($ruta_archivo, $datos) {
    $contenido_actual = [];
    if (file_exists($ruta_archivo)) {
        $contenido_actual = json_decode(file_get_contents($ruta_archivo), true) ?: [];
    }
    
    $contenido_actual[] = $datos;
    return file_put_contents($ruta_archivo, json_encode($contenido_actual, JSON_PRETTY_PRINT));
}
?>