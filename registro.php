<?php
// CABECERAS CORS (Añade estas líneas al principio de tu archivo)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Si es una petición de control (OPTIONS) que hacen los navegadores/dispositivos, responder 200 y salir
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 1. CONEXIÓN A TU MYSQL DE RAILWAY
$host     = "thomas.proxy.rlwy.net";
$usuario  = "root";
// ... el resto de tu código permanece exactamente igual
$password = "QvvQBnWPOXVKAEFZdJTaZnIUQEumobFz"; // Pon aquí la contraseña que te da Railway en la pestaña 'Variables'
$db_name  = "railway";                 // Por defecto Railway la nombra así, o pon el nombre de tu DB
$puerto   = 16371;

$conexion = new mysqli($host, $usuario, $password, $db_name, $puerto);

if ($conexion->connect_error) {
    die(json_encode(["status" => "error", "mensaje" => "Error de conexión a Railway"]));
}

$conexion->set_charset("utf8mb4");

// 2. RECIBIR EL JSON DE LA APP
$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'] ?? null;
$edad   = $data['edad'] ?? null;

if (!$nombre || !$edad) {
    echo json_encode(["status" => "error", "mensaje" => "faltan datos"]);
    exit;
}

// 3. QUERY APUNTANDO A 'estudiantes_re'
// Asegúrate de que las columnas en tu tabla se llamen exactamente 'nombre' y 'edad'
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
$conexion->close();
?>
