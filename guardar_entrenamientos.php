<?php
// guardar_entrenamientos.php

// Configuración de la conexión a la base de datos
$host = "127.0.0.1";
$db   = "gympi";
$user = "root";    // Cambiado a root
$pass = "";        // Contraseña vacía, ajusta si tienes password
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Gym ID existente para relacionar los entrenamientos
$gym_id = 6;  // Asegúrate que este ID existe en tu tabla gym

// Verificamos que se hayan enviado los datos
if (
    isset($_POST['nombre_entrenamiento'], $_POST['descripcion']) &&
    isset($_FILES['foto']) &&
    is_array($_POST['nombre_entrenamiento']) &&
    is_array($_POST['descripcion'])
) {

    $nombres = $_POST['nombre_entrenamiento'];
    $descripciones = $_POST['descripcion'];
    $fotos = $_FILES['foto'];

    // Contar la cantidad mínima para evitar errores
    $countEntrenos = min(count($nombres), count($descripciones), count($fotos['name']));

    for ($i = 0; $i < $countEntrenos; $i++) {
        $nombre = trim($nombres[$i]);
        $descripcion = trim($descripciones[$i]);

        // Procesar la foto subida si no hubo error
        if (isset($fotos['error'][$i]) && $fotos['error'][$i] === UPLOAD_ERR_OK) {
            $foto_tmp = $fotos['tmp_name'][$i];
            $foto_blob = file_get_contents($foto_tmp);

            // Preparar la consulta para insertar datos
            $sql = "INSERT INTO tipos_entrenamiento (nombre_entrena, descripción, foto_entren, gym_id)
                    VALUES (:nombre, :descripcion, :foto, :gym_id)";

            $stmt = $pdo->prepare($sql);
            try {
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':descripcion' => $descripcion,
                    ':foto' => $foto_blob,
                    ':gym_id' => $gym_id
                ]);
            } catch (PDOException $e) {
                echo "Error al guardar el entrenamiento '" . htmlspecialchars($nombre) . "': " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Error al subir la foto para el entrenamiento: " . htmlspecialchars($nombre) . "<br>";
        }
    }

    echo "Entrenamientos guardados correctamente para el gym_id $gym_id.";

} else {
    echo "No se enviaron datos válidos.";
}
?>
