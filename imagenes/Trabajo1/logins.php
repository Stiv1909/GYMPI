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
    $contraseña = $_POST['password'];
    
    // Consulta para buscar al usuario en la base de datos
    $sql = "SELECT * FROM registro WHERE usuario = ?";
    
    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $usuario); // 's' indica que el parámetro es una cadena
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // El usuario existe, obtenemos los datos
            $user = $result->fetch_assoc();
            
            // Verificar si la contraseña coincide
            if ($contraseña == $user['contrasena']) {
                // Si las credenciales son correctas
                session_start();
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['id_usuario'] = $user['ID_admin']; // O cualquier otro dato que necesites
                header("Location: principal.php");
                exit();
            } else {
                // Si la contraseña es incorrecta
                echo "<script>alert('Contraseña incorrecta.'); window.location.href = 'Login.php';</script>";
            }
        } else {
            // Si el usuario no existe
            echo "<script>alert('Usuario no encontrado.'); window.location.href = 'Login.php';</script>";
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
}

$conn->close();
?>
