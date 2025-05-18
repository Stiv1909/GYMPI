<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reclamos";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se recibió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del cliente
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    // Datos del reclamo
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];
    $fecha_hora = date("Y-m-d H:i:s"); // Fecha y hora actual

    // Insertar cliente
    $sql_cliente = "INSERT INTO clientes (nombre, direccion, telefono, correo) 
                    VALUES ('$nombre', '$direccion', '$telefono', '$correo')";

    if ($conn->query($sql_cliente) === TRUE) {
        // Obtener el id del cliente recién insertado
        $id_cliente = $conn->insert_id;

        // Insertar reclamo asociado (sin estado)
        $sql_reclamo = "INSERT INTO reclamos (id_cliente, fecha_hora, descripcion, tipo)
                        VALUES ('$id_cliente', '$fecha_hora', '$descripcion', '$tipo')";

        if ($conn->query($sql_reclamo) === TRUE) {
            echo "Cliente y reclamo registrados exitosamente.";
        } else {
            echo "Error al registrar el reclamo: " . $conn->error;
        }
    } else {
        echo "Error al registrar el cliente: " . $conn->error;
    }
}

$conn->close();
?>
