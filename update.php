<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$host     = "thomas.proxy.rlwy.net";
$usuario  = "root";
$password = "QvvQBnWPOXVKAEFZdJTaZnIUQEumobFz";
$db_name  = "railway";
$puerto   = 16371;

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $conexion = new mysqli(
        $host,
        $usuario,
        $password,
        $db_name,
        $puerto
    );

    $conexion->set_charset("utf8mb4");

    $data = json_decode(file_get_contents("php://input"), true);

    $id     = $data['id'] ?? null;
    $nombre = $data['nombre'] ?? null;
    $edad   = $data['edad'] ?? null;

    if (!$id || !$nombre || !$edad) {
        echo json_encode([
            "status" => "error",
            "mensaje" => "faltan datos"
        ]);
        exit;
    }

    $stmt = $conexion->prepare(
        "UPDATE estudiantes_re SET nombre=?, edad=? WHERE id=?"
    );

    $stmt->bind_param("sii", $nombre, $edad, $id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "ok",
            "mensaje" => "registro actualizado"
        ]);
    }

    $stmt->close();
    $conexion->close();

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "mensaje" => $e->getMessage()
    ]);
}
?>
