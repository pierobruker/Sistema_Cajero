<?php
$host = "localhost";
$usuario = "root";
$clave = ""; // Tu contraseña de MySQL si la tienes
$bd = "cajero_db";

$conn = new mysqli($host, $usuario, $clave, $bd);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
