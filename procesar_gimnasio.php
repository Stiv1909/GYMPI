<?php
// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "gympi");
if ($mysqli->connect_errno) {
    die("Error al conectar a la base de datos: " . $mysqli->connect_error);
}

// Carpeta para guardar imágenes
$rutaCarpeta = "imagenes_gym";

// Crear carpeta si no existe
if (!is_dir($rutaCarpeta)) {
    mkdir($rutaCarpeta, 0777, true);
}

// Recoger datos del formulario
$correo = $_POST['correo'] ?? '';
$eslogan = $_POST['eslogan'] ?? '';
$contacto = $_POST['contacto'] ?? '';
$horario = $_POST['horario'] ?? '';
$direccion = $_POST['direccion'] ?? '';

// Aquí debes poner el propietario_id correcto (por ejemplo fijo para prueba)
$propietario_id = 1; // AJUSTA según corresponda

// Procesar imagen principal
$imagen_principal = "";
if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
    $nombreImagen = uniqid() . "-" . basename($_FILES['imagen_principal']['name']);
    $rutaImagen = $rutaCarpeta . "/" . $nombreImagen;

    if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $rutaImagen)) {
        $imagen_principal = $rutaImagen;
    } else {
        die("Error al subir la imagen principal.");
    }
} else {
    die("No se subió ninguna imagen principal o hubo un error.");
}

// Procesar imágenes de instalaciones (múltiples)
$imag_1 = [];
if (isset($_FILES['instalaciones'])) {
    $imagenes = $_FILES['instalaciones'];
    for ($i = 0; $i < count($imagenes['name']); $i++) {
        if ($imagenes['error'][$i] === UPLOAD_ERR_OK) {
            $nombreImg = uniqid() . "-" . basename($imagenes['name'][$i]);
            $rutaImg = $rutaCarpeta . "/" . $nombreImg;

            if (move_uploaded_file($imagenes['tmp_name'][$i], $rutaImg)) {
                $imag_1[] = $rutaImg;
            }
        }
    }
}
// Convertir arreglo de imágenes en cadena separada por comas para guardar en la BD
$imagenes_guardadas = implode(",", $imag_1);

// Insertar datos en tabla gym
$stmt = $mysqli->prepare("INSERT INTO gym (correo, eslogan, contacto, horario, direccion, imagen_principal, imag_1, propietario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssi", $correo, $eslogan, $contacto, $horario, $direccion, $imagen_principal, $imagenes_guardadas, $propietario_id);

if ($stmt->execute()) {
    echo "Gimnasio registrado correctamente.";
} else {
    echo "Error al registrar gimnasio: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
