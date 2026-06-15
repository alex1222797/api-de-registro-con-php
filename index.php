<?php

$ruta = $_GET['accion'] ?? '';

switch ($ruta) {
    case 'registro':
        require 'registro.php';
        break;

    case 'update':
        require 'update.php';
        break;

    case 'delete':
        require 'delete.php';
        break;

    default:
        echo json_encode([
            "status" => "error",
            "mensaje" => "ruta no encontrada"
        ]);
}
