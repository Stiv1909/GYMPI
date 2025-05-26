<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usu_id']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

$usu_id = $_SESSION['usu_id'];
$error = "";
$success = "";

// Obtener datos actuales para mostrar en el formulario
$sql = "SELECT username, correo, celular, genero, fecha_nac, peso, altura, imagen_principal FROM usuario WHERE usu_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usu_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($username, $correo, $celular, $genero, $fecha_nac, $peso, $altura, $imagen_principal);
$stmt->fetch();
$stmt->close();

$imagenData = '';
if (!empty($imagen_principal)) {
    $imagenData = 'data:image/jpeg;base64,' . base64_encode($imagen_principal);
}

// Procesar actualización si envían el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $celular_nuevo = trim($_POST['celular'] ?? '');
    $genero_nuevo = trim($_POST['genero'] ?? '');
    $fecha_nac_nuevo = trim($_POST['fecha_nac'] ?? '');
    $peso_nuevo = trim($_POST['peso'] ?? '');
    $altura_nuevo = trim($_POST['altura'] ?? '');

    if (!$celular_nuevo || !$genero_nuevo || !$fecha_nac_nuevo || !$peso_nuevo || !$altura_nuevo) {
        $error = "Por favor completa todos los campos.";
    } else {
        $imagen_nueva = null;
        if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
            $imagen_nueva = file_get_contents($_FILES['imagen_principal']['tmp_name']);
        }

        if ($imagen_nueva !== null) {
            $sql_update = "UPDATE usuario SET celular=?, genero=?, fecha_nac=?, peso=?, altura=?, imagen_principal=? WHERE usu_id=?";
            $stmt = $conn->prepare($sql_update);
            if (!$stmt) {
                $error = "Error en la preparación: " . $conn->error;
            } else {
                $null = null;
                $stmt->bind_param("sssdsbi", $celular_nuevo, $genero_nuevo, $fecha_nac_nuevo, $peso_nuevo, $altura_nuevo, $null, $usu_id);
                $stmt->send_long_data(5, $imagen_nueva);
                if ($stmt->execute()) {
                    $success = "Perfil actualizado correctamente.";
                    // Actualizar variables para mostrar los nuevos datos
                    $celular = $celular_nuevo;
                    $genero = $genero_nuevo;
                    $fecha_nac = $fecha_nac_nuevo;
                    $peso = $peso_nuevo;
                    $altura = $altura_nuevo;
                    $imagenData = 'data:image/jpeg;base64,' . base64_encode($imagen_nueva);
                } else {
                    $error = "Error al actualizar: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            // Sin nueva imagen
            $sql_update = "UPDATE usuario SET celular=?, genero=?, fecha_nac=?, peso=?, altura=? WHERE usu_id=?";
            $stmt = $conn->prepare($sql_update);
            if (!$stmt) {
                $error = "Error en la preparación: " . $conn->error;
            } else {
                $stmt->bind_param("sssddi", $celular_nuevo, $genero_nuevo, $fecha_nac_nuevo, $peso_nuevo, $altura_nuevo, $usu_id);
                if ($stmt->execute()) {
                    $success = "Perfil actualizado correctamente.";
                    // Actualizar variables para mostrar los nuevos datos
                    $celular = $celular_nuevo;
                    $genero = $genero_nuevo;
                    $fecha_nac = $fecha_nac_nuevo;
                    $peso = $peso_nuevo;
                    $altura = $altura_nuevo;
                } else {
                    $error = "Error al actualizar: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Editar Perfil | Gympi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="estilos/style_sr.css" />
  <link rel="stylesheet" href="estilos/estilos-Af.css" />
</head>
<body>
<header class="container-fluid bg-dark header">
  <div class="row h-100">
    <div class="col-6 col-md-1 p-0 h-100 d-flex align-items-center justify-content-end">
      <a href="principal.html"><img class="img-fluid" src="imagenes/logo.png" alt="Logo Gympi" style="max-height: 86px;" /></a>
    </div>
    <div class="col-6 col-md-2 d-flex align-items-center h-100 justify-content-start">
      <h1><a class="text-decoration-none tit_principal" href="principal.html">GYMPI</a></h1>
    </div>
    <div class="col-12 col-md-9 d-flex justify-content-end align-items-center">
        <div class="dropdown">
            <a href="#" class="text-white dropdown-toggle d-flex align-items-center gap-2" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                <?php if ($imagenData): ?>
                    <img src="<?= $imagenData ?>" alt="Perfil" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                <?php else: ?>
                    <i class="bi bi-person-circle" style="font-size: 2.5rem;"></i>
                <?php endif; ?>
                <span class="fw-bold d-none d-md-inline"><?= htmlspecialchars($username) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="ListarGimnasios.php">Gimnasios</a></li>
                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
  </div>
</header>

<main class="container-fluid p-5 main d-flex flex-column align-items-center" style="min-height: 100vh; background-color: #94b6bb;">
  <div class="container perfil-wrapper p-4 rounded shadow">
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <div class="row d-flex align-items-center justify-content-center">
      <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
        <div class="perfil-foto border rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 180px; height:180px; overflow:hidden; cursor:pointer;" onclick="document.getElementById('fileInput').click();">
          <?php if ($imagenData): ?>
            <img src="<?= $imagenData ?>" alt="Foto perfil" style="width: 100%; height: 100%; object-fit: cover;">
          <?php else: ?>
            <i class="bi bi-person" style="font-size: 6rem; color:#555;"></i>
          <?php endif; ?>
        </div>
        <input type="file" id="fileInput" name="imagen_principal" accept="image/*" style="display:none" onchange="previewImage(event)" />
        <button class="btn btn-foto" onclick="document.getElementById('fileInput').click();">
          <i class="bi bi-camera-fill me-2"></i> Cambiar foto
        </button>
      </div>

      <div class="col-md-8 perfil-contenedor bg-white p-4 rounded">
        <h3 class="text-center fw-bold mb-4">PERFIL</h3>
        <form method="POST" enctype="multipart/form-data">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Correo:</label>
              <input type="email" class="form-control" value="<?= htmlspecialchars($correo) ?>" disabled>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Username:</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($username) ?>" disabled>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold" for="celular">Contacto:</label>
              <input type="text" id="celular" name="celular" class="form-control" value="<?= htmlspecialchars($celular) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold" for="genero">Género Biológico:</label>
              <select id="genero" name="genero" class="form-select" required>
                <option value="M" <?= $genero === 'M' ? 'selected' : '' ?>>M</option>
                <option value="F" <?= $genero === 'F' ? 'selected' : '' ?>>F</option>
                <option value="O" <?= $genero === 'O' ? 'selected' : '' ?>>Otro</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold" for="fecha_nac">Fecha de Nacimiento:</label>
              <input type="date" id="fecha_nac" name="fecha_nac" class="form-control" value="<?= htmlspecialchars($fecha_nac) ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold" for="peso">Peso (Kg):</label>
              <input type="number" id="peso" name="peso" class="form-control" value="<?= htmlspecialchars($peso) ?>" step="0.01" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold" for="altura">Altura (Cm):</label>
              <input type="number" id="altura" name="altura" class="form-control" value="<?= htmlspecialchars($altura) ?>" step="0.01" required>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-acceder px-5">GUARDAR CAMBIOS</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<footer class="container-fluid p-5 bg-dark">
  <div class="row">
    <section class="col-12 col-md-9 pb-3" id="Politicas">
      <ul class="list-unstyled d-flex flex-wrap gap-4 py-2 m-0 justify-content-center justify-content-md-start">
        <li class="px-md-4"><a class="text-nowrap" href="term_cond.html">Terminos y Condiciones</a></li>
        <li class="px-md-4"><a class="text-nowrap" href="privacidad.html">Privacidad</a></li>
        <li class="px-md-4"><a class="text-nowrap" href="acerca.html">Acerca de</a></li>
      </ul>
    </section>
    <section class="col-12 col-md-3" id="Redes_Sociales">
      <ul class="list-unstyled d-flex gap-4 m-0 py-2 justify-content-center">
        <li><a href="https://www.facebook.com/"><img src="https://cdn-icons-png.flaticon.com/512/20/20673.png" alt="logo_facebook" /></a></li>
        <li><a href="https://www.youtube.com/"><img src="https://cdn.iconscout.com/icon/free/png-256/free-youtube-104-432560.png" alt="logo_youtube" /></a></li>
        <li><a href="https://www.instagram.com/"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384063.png" alt="logo_instagram" /></a></li>
      </ul>
    </section>
  </div>
  <div class="row text-center mt-3 mt-md-5 row-copy">
    <div class="col-12">
      <p class="copy">&copy; 2025 Gympi - Todos los derechos reservados</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function previewImage(event) {
    const input = event.target;
    const previewDiv = document.querySelector('.perfil-foto');
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewDiv.innerHTML = '<img src="' + e.target.result + '" style="width:100%; height:100%; object-fit: cover;">';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
</body>
</html>
