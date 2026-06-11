<?php
header("Content-Type: application/json");

// CONEXIÓN
$conexion = new mysqli("localhost", "root", "mySQL123", "estudiantes");

if ($conexion->connect_error) {
    die(json_encode(["status" => "error", "mensaje" => "DB error"]));
}

// JSON INPUT (para APK / apps)
$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'] ?? null;
$edad   = $data['edad'] ?? null;

if (!$nombre || !$edad) {
    echo json_encode(["status" => "error", "mensaje" => "faltan datos"]);
    exit;
}

// QUERY SEGURA
$stmt = $conexion->prepare("INSERT INTO estudiantes (nombre, edad) VALUES (?, ?)");
$stmt->bind_param("si", $nombre, $edad);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensaje" => "registro exitoso"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "error insert"]);
}

$stmt->close();
$conexion->close();
?>
