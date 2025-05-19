<?php
// guardar_instructores.php

// Configuración de la conexión
$host = "127.0.0.1";
$db   = "gympi";
$user = "root";  // Cambiar si usas otro usuario
$pass = "";      // Cambiar si tienes contraseña
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

$gym_id = 6; // Ajusta a tu gym_id válido

if (
    isset($_POST['nombre_instructor'], $_POST['area']) &&
    isset($_FILES['foto']) &&
    is_array($_POST['nombre_instructor']) &&
    is_array($_POST['area'])
) {
    $nombres = $_POST['nombre_instructor'];
    $areas = $_POST['area'];
    $fotos = $_FILES['foto'];

    $count = min(count($nombres), count($areas), count($fotos['name']));

    for ($i = 0; $i < $count; $i++) {
        $nombre = trim($nombres[$i]);
        $area = trim($areas[$i]);

        if (isset($fotos['error'][$i]) && $fotos['error'][$i] === UPLOAD_ERR_OK) {
            $foto_tmp = $fotos['tmp_name'][$i];
            $foto_blob = file_get_contents($foto_tmp);

            $sql = "INSERT INTO instructores (nombre_instr, descripcion, foto_instr, gym_id)
                    VALUES (:nombre, :descripcion, :foto, :gym_id)";
            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':descripcion' => $area,
                    ':foto' => $foto_blob,
                    ':gym_id' => $gym_id
                ]);
            } catch (PDOException $e) {
                echo "Error al guardar el instructor '" . htmlspecialchars($nombre) . "': " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Error al subir la foto para el instructor: " . htmlspecialchars($nombre) . "<br>";
        }
    }

    echo "Instructores guardados correctamente para gym_id $gym_id.";
} else {
    echo "No se enviaron datos válidos.";
}
?>
