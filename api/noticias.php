<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$ruta_json = __DIR__ . '/../noticias/data.json';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;

try {
    if (!file_exists($ruta_json)) {
        throw new Exception('Archivo no encontrado');
    }

    $contenido = file_get_contents($ruta_json);
    $noticias = json_decode($contenido, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON inválido');
    }

    // Ordenar por fecha (nuevas primero)
    usort($noticias, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });

    // Aplicar límite
    if ($limit) {
        $noticias = array_slice($noticias, 0, $limit);
    }

    // Devuelve siempre un array, incluso si está vacío
    echo json_encode($noticias ?: []);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}