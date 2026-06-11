<?php
// CABECERAS CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Si es una petición de control (OPTIONS), responder 200 y salir
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 1. CONEXIÓN A TU MYSQL DE RAILWAY (Protegida con try-catch)
$host     = "thomas.proxy.rlwy.net";
$usuario  = "root";
$password = "QvvQBnWPOXVKAEFZdJTaZnIUQEumobFz"; 
$db_name  = "railway";                 
$puerto   = 16371;

try {
    // Forzar a mysqli a lanzar excepciones en lugar de errores silenciosos
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    $conexion = new mysqli($host, $usuario, $password, $db_name, $puerto);
    $conexion->set_charset("utf8mb4");

} catch (Exception $e) {
    // Si la conexión a la base de datos falla, devolvemos el error exacto en formato JSON
    http_response_code(500);
    echo json_encode([
        "status" => "error", 
        "mensaje" => "Error de conexión a la base de datos: " . $e->getMessage()
    ]);
    exit;
}

// 2. RECIBIR EL JSON DE LA APP
$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'] ?? null;
$edad   = $data['edad'] ?? null;

if (!$nombre || !$edad) {
    echo json_encode(["status" => "error", "mensaje" => "faltan datos"]);
    exit;
}

// 3. QUERY APUNTANDO A 'estudiantes_re'
try {
    $stmt = $conexion->prepare("INSERT INTO estudiantes_re (nombre, edad) VALUES (?, ?)");
    $stmt->bind_param("si", $nombre, $edad);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "ok", 
            "mensaje" => "registro exitoso",
            "id_insertado" => $stmt->insert_id
        ]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "error insert"]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "mensaje" => "Error al insertar en la tabla: " . $e->getMessage()
    ]);
}

$conexion->close();
?>
