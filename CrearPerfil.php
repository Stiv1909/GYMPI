<?php
session_start();
include 'conexion.php';

$error = "";
$success = "";

// Verificar sesión
if (!isset($_SESSION['usu_id']) || $_SESSION['rol'] !== 'usuario') {
    die("Acceso no autorizado");
}

$usu_id = $_SESSION['usu_id']; // ID del usuario actual

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpieza básica
    $celular = trim($_POST['celular'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $fecha_nac = trim($_POST['fecha_nac'] ?? '');
    $peso = trim($_POST['peso'] ?? '');
    $altura = trim($_POST['altura'] ?? '');

    if (!$celular || !$genero || !$fecha_nac || !$peso || !$altura) {
        $error = "Por favor completa todos los campos.";
    } else {
        // Procesar imagen
        $imagen_principal = null;
        if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
            $imagen_principal = file_get_contents($_FILES['imagen_principal']['tmp_name']);
        }

        if ($imagen_principal !== null) {
            $sql = "UPDATE usuario SET celular=?, genero=?, fecha_nac=?, peso=?, altura=?, imagen_principal=? WHERE usu_id=?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $error = "Error en la preparación de la consulta: " . $conn->error;
            } else {
                $null = null;
                $stmt->bind_param("sssdsbi", $celular, $genero, $fecha_nac, $peso, $altura, $null, $usu_id);
                $stmt->send_long_data(5, $imagen_principal);

                if ($stmt->execute()) {
                    $success = "Registro Exitoso.";
                    header("Location: ListarGimnasios.php");
                    exit();
                } else {
                    $error = "Error al registrar usuario: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $sql = "UPDATE usuario SET celular=?, genero=?, fecha_nac=?, peso=?, altura=? WHERE usu_id=?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $error = "Error en la preparación de la consulta: " . $conn->error;
            } else {
                $stmt->bind_param("sssddi", $celular, $genero, $fecha_nac, $peso, $altura, $usu_id);

                if ($stmt->execute()) {
                    $success = "Registro Exitoso.";
                } else {
                    $error = "Error al registrar usuario: " . $stmt->error;
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Completar Perfil | Gympi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="estilos/footer.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    .form-container {
      background-color: #f1f1f1;
      border-radius: 10px;
      padding: 30px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }
    .form-left, .form-right {
      flex: 1 1 45%;
      margin-bottom: 20px;
    }
    .form-title {
      font-weight: 700;
      font-size: 28px;
      text-align: center;
      margin-bottom: 25px;
    }
    #imgPreview {
      background-color: #ddd;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: #555;
      cursor: pointer;
      overflow: hidden;
      position: relative;
      margin: 0 auto 1rem;
      transition: background-color 0.3s;
    }
    #imgPreview:hover {
      background-color: #ccc;
    }
    #previewPrincipal {
      display: none;
      width: 100%;
      height: 100%;
      object-fit: cover;
      position: absolute;
      top: 0; left: 0;
      transition: opacity 0.3s;
    }
    #defaultIcon {
      font-size: 6rem;
      color: #555;
      transition: opacity 0.3s;
    }
    .btn-camera {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      cursor: pointer;
      margin: 0 auto;
      background-color: #0d6efd;
      color: white;
      border: none;
    }
    .btn-camera:hover {
      background-color: #0b5ed7;
    }
    input[type="file"] {
      display: none;
    }
  </style>
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
            <a href="ListarGimnasios.php" class="text-white me-4">
                <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
            </a>
        </div>
    </div>
</header>

<div class="container my-5">
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <main class="container my-5">
    <form method="POST" enctype="multipart/form-data" id="formGimnasio">
      <div class="d-flex form-container shadow">
        <div class="form-left pe-md-4" style="flex: 1; max-width: 250px;">
          <div id="imgPreview" onclick="document.getElementById('imagenPrincipalInput').click();">
            <i id="defaultIcon" class="bi bi-person"></i>
            <img id="previewPrincipal" src="" alt="Imagen Principal" />
          </div>

          <button type="button" class="btn-camera" onclick="document.getElementById('imagenPrincipalInput').click();" title="Seleccionar imagen">
            <i class="bi bi-camera-fill"></i>
          </button>

          <input type="file" accept="image/*" id="imagenPrincipalInput" name="imagen_principal" />
        </div>
        <div class="form-right ps-md-4" style="flex: 2;">
          <h2 class="form-title">COMPLETAR PERFIL</h2>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Contacto:</label>
              <input type="text" name="celular" class="form-control" placeholder="CELULAR" required />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Género Biológico:</label>
              <select name="genero" class="form-select" required>
                <option value="M">M</option>
                <option value="F">F</option>
                <option value="O">Otro</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Fecha de Nacimiento:</label>
              <input type="date" name="fecha_nac" class="form-control" required />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold">Peso (Kg):</label>
              <input type="number" name="peso" class="form-control" step="0.01" required />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold">Altura (Cm):</label>
              <input type="number" name="altura" class="form-control" step="0.01" required />
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-acceder px-5">GUARDAR</button>
          </div>
        </div>
      </div>
    </form>
  </main>
</div>

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
  const inputImagen = document.getElementById('imagenPrincipalInput');
  const previewImg = document.getElementById('previewPrincipal');
  const defaultIcon = document.getElementById('defaultIcon');

  inputImagen.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
        previewImg.style.opacity = '1';
        defaultIcon.style.opacity = '0';
        setTimeout(() => {
          defaultIcon.style.display = 'none';
        }, 300);
      }
      reader.readAsDataURL(file);
    } else {
      previewImg.style.opacity = '0';
      setTimeout(() => {
        previewImg.style.display = 'none';
        defaultIcon.style.display = 'block';
        defaultIcon.style.opacity = '1';
      }, 300);
    }
  });
</script>
</body>
</html>
