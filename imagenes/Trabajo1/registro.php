<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // Usuario de MySQL (por defecto en XAMPP es 'root')
$password = ""; // Contraseña de MySQL (por defecto en XAMPP está vacía)
$dbname = "reclamos"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario = $_POST['username'];
    $correo = $_POST['email'];
    $contraseña = $_POST['password']; // Contraseña en texto plano
    
    // Obtener el id_rol de la tabla 'rol' (asumimos que el primer rol es 'ADMIN')
    $sql_rol = "SELECT id_rol FROM rol WHERE tipo = 'ADMIN' LIMIT 1";
    $result_rol = $conn->query($sql_rol);

    if ($result_rol->num_rows > 0) {
        $rol = $result_rol->fetch_assoc();
        $id_rol = $rol['id_rol']; // Obtenemos el id_rol de 'ADMIN'
        
        // Consulta para insertar el nuevo usuario con el id_rol obtenido
        $sql = "INSERT INTO registro (usuario, correo, contrasena, id_rol) VALUES (?, ?, ?, ?)";
        
        // Preparar la consulta
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $usuario, $correo, $contraseña, $id_rol); // 'i' para el parámetro 'id_rol' (entero)
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('Usuario registrado correctamente.'); window.location.href = 'Login.php';</script>";
        } else {
            echo "Error al registrar usuario: " . $conn->error;
        }
    } else {
        echo "No se encontró el rol 'ADMIN' en la tabla 'rol'. Asegúrate de que exista.";
    }
}

$conn->close();
?>
