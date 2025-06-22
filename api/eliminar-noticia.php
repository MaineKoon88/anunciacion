<?php
// 1. Configuración inicial
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Access-Control-Allow-Origin: *');

// 2. Limpiar buffer de salida
while (ob_get_level()) ob_end_clean();

// 3. Iniciar sesión segura
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
    'cookie_httponly' => true,
    'cookie_secure'   => true
]);

// 4. Validar administrador
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(403);
    die(json_encode([
        'success' => false,
        'error' => 'Acceso no autorizado',
        'session_status' => session_status()
    ]));
}

// 5. Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode([
        'success' => false,
        'error' => 'Solo se permite método POST'
    ]));
}

// 6. Obtener y validar input JSON
$inputJSON = file_get_contents('php://input');
if (empty($inputJSON)) {
    http_response_code(400);
    die(json_encode([
        'success' => false,
        'error' => 'No se recibieron datos JSON'
    ]));
}

$data = json_decode($inputJSON, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    die(json_encode([
        'success' => false,
        'error' => 'JSON inválido: ' . json_last_error_msg(),
        'raw_input' => substr($inputJSON, 0, 100) . '...'
    ]));
}

// 7. Validar y normalizar ID
$id = $data['id'] ?? null;
if ($id === null || $id === '') {
    http_response_code(400);
    die(json_encode([
        'success' => false,
        'error' => 'ID no proporcionado'
    ]));
}

// Normalizar ID (acepta string o número)
$id = is_numeric($id) ? (int)$id : trim($data['id']);

// 8. Manejo del archivo JSON
$ruta_json = realpath(__DIR__ . '/../noticias/data.json');
if ($ruta_json === false || !file_exists($ruta_json)) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'error' => 'Archivo de datos no encontrado',
        'path' => __DIR__ . '/../noticias/data.json'
    ]));
}

// Bloquear archivo para escritura
$fp = fopen($ruta_json, 'r+');
if (!flock($fp, LOCK_EX)) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'error' => 'No se pudo bloquear el archivo'
    ]));
}

// Leer y validar JSON
$json_content = file_get_contents($ruta_json);
$noticias = json_decode($json_content, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    flock($fp, LOCK_UN);
    fclose($fp);
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'error' => 'Error en datos JSON: ' . json_last_error_msg(),
        'json_sample' => substr($json_content, 0, 100) . '...'
    ]));
}

// 9. Filtrar noticias (comparación segura)
$noticia_encontrada = false;
$noticias_actualizadas = array_values(array_filter($noticias, function($n) use ($id, &$noticia_encontrada) {
    $nid = is_numeric($n['id']) ? (int)$n['id'] : $n['id'];
    $compareId = is_numeric($id) ? (int)$id : $id;
    
    if ($nid == $compareId) {
        $noticia_encontrada = true;
        return false;
    }
    return true;
}));

// 10. Verificar si se eliminó
if (!$noticia_encontrada) {
    flock($fp, LOCK_UN);
    fclose($fp);
    http_response_code(404);
    die(json_encode([
        'success' => false,
        'error' => 'Noticia no encontrada',
        'id_buscado' => $id,
        'tipo_id_buscado' => gettype($id),
        'ids_disponibles' => array_map(function($n) {
            return [
                'id' => $n['id'],
                'tipo' => gettype($n['id'])
            ];
        }, $noticias)
    ]));
}

// 11. Guardar cambios
ftruncate($fp, 0);
rewind($fp);
$bytes_escritos = fwrite($fp, json_encode($noticias_actualizadas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
flock($fp, LOCK_UN);
fclose($fp);

if ($bytes_escritos === false) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'error' => 'Error al guardar cambios'
    ]));
}

// 12. Limpiar caché del frontend (opcional)
$cache_files = [
    '../index.html',
    '../noticias/noticias.php'
];

foreach ($cache_files as $cache_file) {
    if (file_exists($cache_file)) {
        touch($cache_file); // Actualizar timestamp
    }
}

// 13. Respuesta exitosa
die(json_encode([
    'success' => true,
    'message' => 'Noticia eliminada permanentemente',
    'id_eliminado' => $id,
    'total_restantes' => count($noticias_actualizadas),
    'cache_actualizado' => true
]));