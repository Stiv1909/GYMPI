<?php
include("conexion.php");

// Validar si se recibió el ID por GET y es numérico
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Consulta con JOIN para traer datos de gym y nombre del gimnasio del propietario
    $consulta = "SELECT gym.*, propietario.nombre_gym 
                 FROM gym 
                 INNER JOIN propietario ON gym.propietario_id = propietario.propietario_id
                 WHERE gym.gym_id = $id
                 LIMIT 1";
    $resultado = mysqli_query($conn, $consulta);

    // Validar resultado
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $gym = mysqli_fetch_assoc($resultado);

        // Convertir imágenes BLOB a base64 (validando que existan)
        $imagen_principal_base64 = $gym['imagen_principal'] ? base64_encode($gym['imagen_principal']) : null;
        $imag_1_base64 = $gym['imag_1'] ? base64_encode($gym['imag_1']) : null;
        $imag_2_base64 = $gym['imag_2'] ? base64_encode($gym['imag_2']) : null;
        $imag_3_base64 = $gym['imag_3'] ? base64_encode($gym['imag_3']) : null;
        $imag_4_base64 = $gym['imag_4'] ? base64_encode($gym['imag_4']) : null;

    } else {
        die("⚠️ Gimnasio no encontrado.");
    }
} else {
    die("⚠️ ID de gimnasio no especificado correctamente.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gympi: <?= htmlspecialchars($gym['nombre_gym']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="estilos/estilos-info.css" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
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
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" style="color: white;" href="informacion.php?id=<?= $id ?>">Información</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" style="color: white;" href="tiposEntrenamiento.php?id=<?= $id ?>">Tipos de Entrenamiento</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" style="color: white;" href="instructores.php?id=<?= $id ?>">Instructores</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</header>

<!-- MAIN -->
<main class="container-fluid mt-4">
  <div class="row">
    <!-- Columna Izquierda -->
    <div class="col-md-4 text-center">
      <h3 class="mb-3"><strong><?= htmlspecialchars($gym['nombre_gym']) ?></strong></h3>
      <div class="card">
        <?php if ($imagen_principal_base64): ?>
          <img src="data:image/jpeg;base64,<?= $imagen_principal_base64 ?>" alt="Imagen principal del gimnasio" class="img-fluid" />
        <?php else: ?>
          <p>No hay imagen principal disponible.</p>
        <?php endif; ?>
        <div class="card-body bg-white">
          <h5 class="card-title"><?= htmlspecialchars($gym['eslogan']) ?></h5>
        </div>
      </div>
    </div>

    <!-- Columna Central -->
    <div class="col-md-4">
      <h3 class="mb-3"><strong>Información General</strong></h3>
      <div class="info-box d-flex align-items-center">
        <strong>Contacto: <?= htmlspecialchars($gym['contacto']) ?></strong>
      </div>
      <div class="info-box d-flex align-items-center">
        <strong>Dirección: <?= htmlspecialchars($gym['direccion']) ?></strong>
      </div>
      <div class="info-box d-flex align-items-center">
        <strong>Horario:</strong>
        <p class="ms-2"><?= nl2br(htmlspecialchars($gym['horario'])) ?></p>
      </div>
    </div>

    <!-- Columna Derecha -->
    <div class="col-md-4 text-center">
      <h3 class="mb-3"><strong>Instalaciones</strong></h3>
      <div id="carouselInstalaciones" class="carousel slide mb-3" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php if ($imag_1_base64): ?>
          <div class="carousel-item active">
            <img src="data:image/jpeg;base64,<?= $imag_1_base64 ?>" class="d-block w-100" alt="Instalación 1" />
          </div>
          <?php endif; ?>

          <?php if ($imag_2_base64): ?>
          <div class="carousel-item <?= !$imag_1_base64 ? 'active' : '' ?>">
            <img src="data:image/jpeg;base64,<?= $imag_2_base64 ?>" class="d-block w-100" alt="Instalación 2" />
          </div>
          <?php endif; ?>

          <?php if ($imag_3_base64): ?>
          <div class="carousel-item <?= (!$imag_1_base64 && !$imag_2_base64) ? 'active' : '' ?>">
            <img src="data:image/jpeg;base64,<?= $imag_3_base64 ?>" class="d-block w-100" alt="Instalación 3" />
          </div>
          <?php endif; ?>

          <?php if ($imag_4_base64): ?>
          <div class="carousel-item <?= (!$imag_1_base64 && !$imag_2_base64 && !$imag_3_base64) ? 'active' : '' ?>">
            <img src="data:image/jpeg;base64,<?= $imag_4_base64 ?>" class="d-block w-100" alt="Instalación 4" />
          </div>
          <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInstalaciones" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselInstalaciones" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
      <p class="text-success"><strong>¿Qué te pareció este lugar?</strong></p>
    </div>
  </div>
</main>

<!-- FOOTER -->
<footer class="container-fluid p-5 bg-dark">
  <div class="row">
    <section class="col-12 col-md-9 pb-3" id="Politicas">
      <ul class="list-unstyled d-flex flex-wrap gap-4 py-2 m-0 justify-content-center justify-content-md-start">
        <li class="px-md-4"><a class="text-nowrap" href="term_cond.html">Términos y Condiciones</a></li>
        <li class="px-md-4"><a class="text-nowrap" href="privacidad.html">Privacidad</a></li>
        <li class="px-md-4"><a class="text-nowrap" href="acerca.html">Acerca de</a></li>
      </ul>
    </section>
    <section class="col-12 col-md-3" id="Redes_Sociales">
      <ul class="list-unstyled d-flex gap-4 m-0 py-2 justify-content-center">
        <li><a href="https://www.facebook.com/"><img src="https://cdn-icons-png.flaticon.com/512/20/20673.png" alt="logo_facebook"></a></li>
        <li><a href="https://www.youtube.com/"><img src="https://cdn.iconscout.com/icon/free/png-256/free-youtube-104-432560.png" alt="logo_youtube" ></a></li>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
