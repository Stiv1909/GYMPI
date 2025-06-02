<?php
session_start();
include 'conexion.php';  // conexión mysqli (objeto $conn)

if (!isset($_SESSION['usu_id']) || $_SESSION['rol'] !== 'propietario') {
    die("Acceso no autorizado");
}

$propietario_id = $_SESSION['usu_id'];

// Obtener gym_id asociado al propietario
$stmt = $conn->prepare("SELECT gym_id FROM gym WHERE propietario_id = ?");
$stmt = $conn->prepare("SELECT gym_id, imagen_principal FROM gym WHERE propietario_id = ?");
$stmt->bind_param("i", $propietario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No hay gimnasio registrado para este propietario.");
}

$gym = $result->fetch_assoc();
$gym_id = $gym['gym_id'];
$imagenPrincipal = $gym['imagen_principal'];
$stmt->close();

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

                $sql = "INSERT INTO instructores (nombre_instr, descripcion, foto_instr, gym_id) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt === false) {
                    $errores[] = "Error al preparar la consulta: " . $conn->error;
                    continue;
                }

                $null = NULL;
                $stmt->bind_param("ssbi", $nombre, $area, $null, $gym_id);
                $stmt->send_long_data(2, $foto_blob);

                if (!$stmt->execute()) {
                    $errores[] = "Error al guardar instructor '{$nombre}': " . $stmt->error;
                }
                $stmt->close();
            } else {
                $errores[] = "Error al subir la foto para instructor '{$nombre}'.";
            }
        }

        if (count($errores) === 0) {
            // Redirige 
            header('Location: ListarGimnasios.php');
            exit();
        }
    } else {
        echo '<div class="alert alert-warning">No se enviaron datos válidos.</div>';
    }
}

if (count($errores) > 0) {
    foreach ($errores as $error) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
}
?>
<!-- Aquí sigue todo el HTML igual que antes -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Instructores | Gympi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="estilos/style_sr.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>

<!-- HEADER -->
<header class="container-fluid bg-dark header">
  <div class="row h-100">
    <div class="col-6 col-md-1 p-0 h-100 d-flex align-items-center justify-content-end">
      <a href="principal.html"><img class="img-fluid" src="imagenes/logo.png" alt="Logo Gympi" style="max-height: 86px;"></a>
    </div>
    <div class="col-6 col-md-2 d-flex align-items-center h-100 justify-content-start">
      <h1><a class="text-decoration-none tit_principal" href="principal.html">GYMPI</a></h1>
    </div>

    <nav class="col-12 col-md-9 navbar navbar-expand-md navbar-light bg-nav align-items-center">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="container-fluid">
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav align-items-center">
            <li class="nav-item dropdown">
              <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0;">
                <img src="data:image/jpeg;base64,<?= base64_encode($imagenPrincipal) ?>" alt="Imagen Principal" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid white;">
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</header>

<!-- MAIN -->
<main class="container my-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <h2 class="text-center mb-4">Instructores</h2>
      <form action="" method="POST" enctype="multipart/form-data">
        <div id="contenedor-instructores">
          <div class="mb-3 row">
            <div class="col-9">
              <label class="form-label">Nombre del Instructor</label>
              <input type="text" name="nombre_instructor[]" class="form-control mb-2" placeholder="Nombre del instructor" required />
            </div>
            <div class="col-3 d-flex align-items-end">
              <label class="w-100">
                <input type="file" name="foto[]" class="d-none" required />
                <button type="button" class="btn btn-secondary w-100 seleccionar-foto">
                  <i class="fa-solid fa-upload"></i> Foto
                </button>
              </label>
            </div>
            <div class="col-12 mt-2">
              <label class="form-label">Área del Instructor</label>
              <input type="text" name="area[]" class="form-control" placeholder="Área del instructor" required />
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-center mb-4">
          <button type="button" class="btn btn-outline-primary" id="agregar-instructor">+ Agregar</button>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</main>

<!-- FOOTER -->
<footer class="container-fluid p-5 bg-dark">
  <div class="row">
    <section class="col-12 col-md-9 pb-3" id="Politicas">
      <ul class="list-unstyled d-flex flex-wrap gap-4 py-2 m-0 justify-content-center justify-content-md-start">
        <li class="px-md-4"><a class="text-nowrap" href="term_cond.html" >Términos y Condiciones</a></li>
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
  function asignarEventosFoto() {
    document.querySelectorAll('.seleccionar-foto').forEach(btn => {
      btn.onclick = function () {
        const input = this.parentElement.querySelector('input[type="file"]');
        if (input) input.click();
      };
    });
  }

  document.getElementById("agregar-instructor").addEventListener("click", function () {
    const contenedor = document.getElementById("contenedor-instructores");
    const nuevo = document.createElement("div");
    nuevo.className = "mb-3 row";
    nuevo.innerHTML = `
      <div class="col-9">
        <label class="form-label">Nombre del Instructor</label>
        <input type="text" name="nombre_instructor[]" class="form-control mb-2" placeholder="Nombre del instructor" required />
      </div>
      <div class="col-3 d-flex align-items-end">
        <label class="w-100">
          <input type="file" name="foto[]" class="d-none" required />
          <button type="button" class="btn btn-secondary w-100 seleccionar-foto">
            <i class="fa-solid fa-upload"></i> Foto
          </button>
        </label>
      </div>
      <div class="col-12 mt-2">
        <label class="form-label">Área del Instructor</label>
        <input type="text" name="area[]" class="form-control" placeholder="Área del instructor" required />
      </div>
    `;
    contenedor.appendChild(nuevo);
    asignarEventosFoto();
  });

  asignarEventosFoto();
</script>

</body>
</html>
