<?php
$servername = "localhost";
$username = "root"; // Usuario por defecto en XAMPP
$password = ""; // No tiene contraseña por defecto
$database = "tienda_productos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
