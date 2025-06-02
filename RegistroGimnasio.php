<?php
session_start();
include 'conexion.php';

$error = "";
$success = "";

if (!isset($_SESSION['usu_id']) || $_SESSION['rol'] !== 'propietario') {
    die("Acceso no autorizado");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo'] ?? '');
    $eslogan = trim($_POST['eslogan'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $horario = trim($_POST['horario'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $propietario_id = $_SESSION['usu_id'];

    if (!$correo || !$eslogan || !$contacto || !$horario || !$direccion) {
        $error = "Por favor completa todos los campos.";
    } else {
        $check_sql = "SELECT COUNT(*) AS total FROM gym WHERE correo = ? OR eslogan = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $correo, $eslogan);
        $check_stmt->execute();
        $check_stmt->bind_result($total);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($total > 0) {
            $error = "Ya existe un gimnasio registrado con ese correo o eslogan.";
        } else {
            function getFileContent($file) {
                return (isset($file) && $file['error'] === UPLOAD_ERR_OK) ? file_get_contents($file['tmp_name']) : null;
            }

            $imagen_principal = getFileContent($_FILES['imagen_principal'] ?? null);

            $sql = "INSERT INTO gym (correo, eslogan, contacto, direccion, horario, imagen_principal, propietario_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $error = "Error en la preparación de la consulta: " . $conn->error;
            } else {
                $stmt->bind_param("ssssssi", $correo, $eslogan, $contacto, $direccion, $horario, $imagen_principal, $propietario_id);

                if ($stmt->execute()) {
                    $gym_id = $conn->insert_id;

                    // Procesar imágenes instalaciones individualmente
                    $imag1 = getFileContent($_FILES['imag_1'] ?? null);
                    $imag2 = getFileContent($_FILES['imag_2'] ?? null);
                    $imag3 = getFileContent($_FILES['imag_3'] ?? null);
                    $imag4 = getFileContent($_FILES['imag_4'] ?? null);

                    $updateSql = "UPDATE gym SET imag_1 = ?, imag_2 = ?, imag_3 = ?, imag_4 = ? WHERE gym_id = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    if ($updateStmt) {
                        // Usamos 's' para strings (BLOBs), 'i' para entero
                        $updateStmt->bind_param("ssssi", $imag1, $imag2, $imag3, $imag4, $gym_id);
                        $updateStmt->execute();
                        $updateStmt->close();
                    } else {
                        $error .= " Error preparando actualización de imágenes: " . $conn->error;
                    }

                    if (empty($error)) {
                        header("Location: RegistroTiposentrenamiento.php");
                        exit();
                    }
                } else {
                    $error = "Error al registrar gimnasio: " . $stmt->error;
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
  <title>Registrar Gimnasio | Gympi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="estilos/footer.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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
    .img-preview {
      background-color: #ddd;
      width: 100%;
      height: 200px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: #555;
    }
    .btn-next {
      background-color: #38d16a;
      border: none;
      padding: 10px 20px;
      font-weight: bold;
      width: auto !important;
      display: block;
      margin: 0 auto;
    }
    .file-label {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: white;
      border: 1px solid #ccc;
      padding: 8px 12px;
      border-radius: 5px;
      cursor: pointer;
    }
    .file-label:hover {
      background-color: #f0f0f0;
    }
    input[type="file"] {
      display: none;
    }
    /* Estilos para los botones de carga de imágenes */
    .btn-upload {
      display: block;
      cursor: pointer;
      width: 120px;
      height: 120px;
      border-radius: 10px;
      border: 2px dashed #0d6efd;
      position: relative;
      overflow: hidden;
      color: #0d6efd;
      font-weight: 600;
      font-size: 1rem;
      line-height: 120px;
      text-align: center;
      user-select: none;
      transition: background-color 0.3s, color 0.3s;
    }
    .btn-upload:hover {
      background-color: #0d6efd;
      color: white;
    }
    .preview-img {
      margin-top: 8px;
      width: 120px;
      height: 80px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #ccc;
      display: none;
    }
  </style>
</head>
<body>

<header class="container-fluid bg-dark header">
  <div class="row h-100"> 
    <div class="col-6 col-md-1 p-0 h-100 d-flex align-items-center justify-content-end">
      <a href="principal.html"><img class="img-fluid" src="imagenes/logo.png" alt="Logo Gympi" style="max-height: 86px;"></a>
    </div>
    <div class="col-6 col-md-2 d-flex align-items-center h-100 justify-content-start">
      <h1><a class="text-decoration-none tit_principal" href="principal.html">GYMPI</a></h1>
    </div>
    <nav class="col-12 col-md-9 navbar navbar-expand-md navbar-light bg-nav align-items-center">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link bg-item" href="logout.php">CERRAR SESIÓN</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
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
          <label class="form-label fw-bold mb-2 text-center d-block">IMAGEN PRINCIPAL:</label>
          <div id="imgPreview" 
               class="d-flex align-items-center justify-content-center border-radius: 8px; mx-auto mb-3" 
               style="width: 200px; height: 200px; background-color: #ddd; overflow: hidden; cursor: pointer; position: relative;">
            <i id="defaultIcon" class="bi bi-person" style="font-size: 6rem; color: #555;"></i>
            <img id="previewPrincipal" src="" alt="Imagen Principal" 
                 style="display: none; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;" />
          </div>
          <label for="imagenPrincipalInput" 
                 class="btn btn-primary d-flex align-items-center justify-content-center mx-auto" 
                 style="width: 50px; height: 50px; border-radius: 50%; cursor: pointer; margin-top: 10px;">
            <i class="bi bi-camera-fill" style="font-size: 1.5rem;"></i>
          </label>
          <input type="file" accept="image/*" id="imagenPrincipalInput" name="imagen_principal" style="display: none;">
        </div>

        <div class="form-right ps-md-4" style="flex: 2;">
          <h2 class="form-title">REGISTRAR GIMNASIO</h2>
          
          <div class="mb-3">
            <label for="correo" class="form-label">Correo:</label>
            <input type="email" name="correo" class="form-control" id="correo" placeholder="Correo electrónico" required>
          </div>
          <div class="mb-3">
            <label for="eslogan" class="form-label">Nombre Establecimiento:</label>
            <input type="text" name="eslogan" class="form-control" id="eslogan" placeholder="Nombre Establecimiento" required>
          </div>
          <div class="mb-3">
            <label for="contacto" class="form-label">Contacto:</label>
            <input type="text" name="contacto" class="form-control" id="contacto" placeholder="Nombre del encargado o contacto" required>
          </div>
          <div class="mb-3">
            <label for="horario" class="form-label">Horario:</label>
            <input type="text" name="horario" class="form-control" id="horario" readonly required>
            <button type="button" class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#modalDias">Seleccionar días</button>
          </div>
          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección:</label>
            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección del gimnasio" required>
          </div>

          <!-- NUEVA SECCION DE 4 BOTONES DE IMAGEN -->
          <div class="mb-3">
            <label class="form-label d-block fw-bold mb-2">Imágenes Instalaciones:</label>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
              <div style="text-align: center;">
                <label for="imag_1" class="btn-upload">Imagen 1
                  <input type="file" accept="image/*" name="imag_1" id="imag_1">
                </label>
                <img id="preview1" class="preview-img" alt="Preview 1">
              </div>
              <div style="text-align: center;">
                <label for="imag_2" class="btn-upload">Imagen 2
                  <input type="file" accept="image/*" name="imag_2" id="imag_2">
                </label>
                <img id="preview2" class="preview-img" alt="Preview 2">
              </div>
              <div style="text-align: center;">
                <label for="imag_3" class="btn-upload">Imagen 3
                  <input type="file" accept="image/*" name="imag_3" id="imag_3">
                </label>
                <img id="preview3" class="preview-img" alt="Preview 3">
              </div>
              <div style="text-align: center;">
                <label for="imag_4" class="btn-upload">Imagen 4
                  <input type="file" accept="image/*" name="imag_4" id="imag_4">
                </label>
                <img id="preview4" class="preview-img" alt="Preview 4">
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-next">SIGUIENTE</button>
        </div>
      </div>
    </form>
  </main>
</div>

<!-- Modal para seleccionar días y horarios -->
<div class="modal fade" id="modalDias" tabindex="-1" aria-labelledby="modalDiasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDiasLabel">Seleccionar días y horarios</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
         <div class="d-flex mb-2" style="margin-left: 90px; font-weight: 600;">
          <div style="min-width: 100px; margin-right: 10px;">Apertura</div>
          <div style="min-width: 100px;">Cierre</div>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="Lunes" id="lunes" name="dias[]">
          <label class="form-check-label me-2" for="lunes">Lunes</label>
          <input type="time" id="lunesInicio" name="hora_apertura[]" disabled>
          <input type="time" id="lunesFin" name="hora_cierre[]" disabled>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="Martes" id="martes" name="dias[]">
          <label class="form-check-label me-2" for="martes">Martes</label>
          <input type="time" id="martesInicio" name="hora_apertura[]" disabled>
          <input type="time" id="martesFin" name="hora_cierre[]" disabled>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="Miércoles" id="miercoles" name="dias[]">
          <label class="form-check-label me-2" for="miercoles">Miércoles</label>
          <input type="time" id="miercolesInicio" name="hora_apertura[]" disabled>
          <input type="time" id="miercolesFin" name="hora_cierre[]" disabled>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="Jueves" id="jueves" name="dias[]">
          <label class="form-check-label me-2" for="jueves">Jueves</label>
          <input type="time" id="juevesInicio" name="hora_apertura[]" disabled>
          <input type="time" id="juevesFin" name="hora_cierre[]" disabled>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="Viernes" id="viernes" name="dias[]">
          <label class="form-check-label me-2" for="viernes">Viernes</label>
          <input type="time" id="viernesInicio" name="hora_apertura[]" disabled>
          <input type="time" id="viernesFin" name="hora_cierre[]" disabled>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="Sábado" id="sabado" name="dias[]">
          <label class="form-check-label me-2" for="sabado">Sábado</label>
          <input type="time" id="sabadoInicio" name="hora_apertura[]" disabled>
          <input type="time" id="sabadoFin" name="hora_cierre[]" disabled>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="Domingo" id="domingo" name="dias[]">
          <label class="form-check-label me-2" for="domingo">Domingo</label>
          <input type="time" id="domingoInicio" name="hora_apertura[]" disabled>
          <input type="time" id="domingoFin" name="hora_cierre[]" disabled>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="guardarDias">Guardar días</button>
      </div>
    </div>
  </div>
</div>

<footer class="container-fluid p-5 bg-dark">
  <div class="row">
    <section class="col-12 col-md-9 pb-3" id="Politicas">
      <ul class="list-unstyled d-flex flex-wrap gap-4 py-2 m-0 justify-content-center justify-content-md-start">
        <li class="px-md-4"><a class="text-nowrap" href="term_cond.html" >Terminos y Condiciones</a></li>
        <li class="px-md-4"><a class="text-nowrap" href="privacidad.html" >Privacidad</a></li>
        <li class="px-md-4"><a class="text-nowrap" href="acerca.html" >Acerca de</a></li>
      </ul>
    </section>
    <section class="col-12 col-md-3" id="Redes_Sociales">
      <ul class="list-unstyled d-flex gap-4 m-0 py-2 justify-content-center">
        <li><a href="https://www.facebook.com/"><img src="https://cdn-icons-png.flaticon.com/512/20/20673.png" alt="logo_facebook"></a></li>
        <li><a href="https://www.youtube.com/"><img src="https://cdn.iconscout.com/icon/free/png-256/free-youtube-104-432560.png" alt="logo_youtube"></a></li>
        <li><a href="https://www.instagram.com/"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384063.png" alt="logo_instagram"></a></li>
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
  // Preview imagen principal
  const inputImagen = document.getElementById('imagenPrincipalInput');
  const previewImg = document.getElementById('previewPrincipal');
  const previewText = document.getElementById('defaultIcon');

  inputImagen.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
        previewText.style.display = 'none';
      }
      reader.readAsDataURL(file);
    } else {
      previewImg.src = '';
      previewImg.style.display = 'none';
      previewText.style.display = 'flex';
    }
  });

  // Preview para imágenes instalaciones
  function setupImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    input.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }
    });
  }

  setupImagePreview('imag_1', 'preview1');
  setupImagePreview('imag_2', 'preview2');
  setupImagePreview('imag_3', 'preview3');
  setupImagePreview('imag_4', 'preview4');

  // Control de habilitar/deshabilitar inputs de horario segun checkboxes
  const dias = ["lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"];
  dias.forEach(dia => {
    const checkbox = document.getElementById(dia);
    const inicio = document.getElementById(dia + "Inicio");
    const fin = document.getElementById(dia + "Fin");

    checkbox.addEventListener('change', () => {
      inicio.disabled = !checkbox.checked;
      fin.disabled = !checkbox.checked;
    });
  });

  // Guardar días y horas en el input horario
  document.getElementById("guardarDias").addEventListener('click', () => {
    let horarios = [];
    dias.forEach(dia => {
      const checkbox = document.getElementById(dia);
      const inicio = document.getElementById(dia + "Inicio");
      const fin = document.getElementById(dia + "Fin");
      if (checkbox.checked && inicio.value && fin.value) {
        horarios.push(dia.charAt(0).toUpperCase() + dia.slice(1) + ": " + inicio.value + " - " + fin.value);
      }
    });
    document.getElementById("horario").value = horarios.join("; ");
    var modal = bootstrap.Modal.getInstance(document.getElementById('modalDias'));
    modal.hide();
  });
</script>

</body>
</html>
