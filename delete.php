<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE, POST, OPTIONS");
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

    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode([
            "status" => "error",
            "mensaje" => "id requerido"
        ]);
        exit;
    }

    $stmt = $conexion->prepare(
        "DELETE FROM estudiantes_re WHERE id=?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "ok",
            "mensaje" => "registro eliminado"
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
