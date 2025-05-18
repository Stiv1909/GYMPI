<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";  // Cambiar según tus credenciales
$password = "";  // Cambiar según tus credenciales
$dbname = "reclamos";  // Nombre de la base de datos correcta

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

