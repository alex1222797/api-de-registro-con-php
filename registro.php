<?php
$conexion = new mysqli("localhost" , "root" , "mySQL123" , "estudiantes");

if ($conexion->connect_error){
    die("Error: " . $conexion->connect_error);
}

$nombre = $_POST['nombre'];
$edad = $_POST['edad'];

$sql = "INSERT INTO estudiantes (nombre,edad) Values ('$nombre','$edad')";

if ($conexion->query($sql) === TRUE) {
    echo json_encode(["status" => "ok" , "mensaje" => "registro exitoso"]);
}else{
       echo json_encode(["status" => "error" , "mensaje" => "conexion-error"]);
}
$conexion->close();
?>