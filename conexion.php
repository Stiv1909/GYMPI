<?php
    $servername = "localhost"; // o la IP del servidor
    $username = "root"; // tu usuario de la base de datos
    $password = "1234"; // tu contraseña
    $dbname = "gympiv2"; // nombre de la base de datos

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
?>
