<?php
  include 'conexion.php';

  if (!isset($_GET['id'])) {
    die("ID del gimnasio no especificado.");
  }

  $gym_id = intval($_GET['id']);

  $sql = "SELECT nombre_entrena, descripcion, foto_entren FROM tipos_entrenamiento WHERE gym_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $gym_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $tipos_entrenamiento = [];
  while ($row = $result->fetch_assoc()) {
    $row['foto_entren'] = 'data:image/jpeg;base64,' . base64_encode($row['foto_entren']);
    $tipos_entrenamiento[] = $row;
  }

  $stmt->close();
  $conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tipos de Entrenamiento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link rel="stylesheet" href="estilos/estilos-tiposentre.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

  <!-- HEADER (igual al original) -->
  <header class="container-fluid bg-dark header">
    <div class="row h-100"> 
      <div class="col-6 col-md-1 p-0 h-100 d-flex align-items-center justify-content-end">
        <a href="ListarGimnasios.php"><img class="img-fluid" src="imagenes/logo.png" alt="Logo Gympi" style="max-height: 86px;"></a>
      </div>
      <div class="col-6 col-md-2 d-flex align-items-center h-100 justify-content-start">
        <h1><a class="text-decoration-none tit_principal" href="ListarGimnasios.php">GYMPI</a></h1>
      </div>
      <nav class="col-12 col-md-9 navbar navbar-expand-md navbar-light bg-nav align-items-center">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="container-fluid">
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="informacion.php?id=<?= $gym_id ?>">Información</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="tiposEntrenamiento.php?id=<?= $gym_id ?>">Tipos de Entrenamiento</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="instructores.php?id=<?= $gym_id ?>">Instructores</a></li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <!-- MAIN -->
  <main class="container my-5">
    <div class="d-flex flex-column align-items-center gap-4">
      <?php if (count($tipos_entrenamiento) > 0): ?>
        <?php foreach ($tipos_entrenamiento as $tipo): ?>
          <div class="training-card">
            <img src="<?= $tipo['foto_entren'] ?>" alt="<?= htmlspecialchars($tipo['nombre_entrena']) ?>" class="training-img">
            <div class="training-info">
              <h3><?= htmlspecialchars($tipo['nombre_entrena']) ?></h3>
              <p><?= htmlspecialchars($tipo['descripcion']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center">No se encontraron tipos de entrenamiento para este gimnasio.</p>
      <?php endif; ?>
    </div>
  </main>

  <!-- FOOTER (sin cambios) -->
  <footer class="container-fluid p-5 bg-dark text-white">
    <div class="row">
      <section class="col-12 col-md-9 pb-3" id="Politicas">
        <ul class="list-unstyled d-flex flex-wrap gap-4 py-2 m-0 justify-content-center justify-content-md-start">
          <li class="px-md-4"><a class="text-nowrap text-white" href="term_cond.html" >Términos y Condiciones</a></li>
          <li class="px-md-4"><a class="text-nowrap text-white" href="privacidad.html" >Privacidad</a></li>
          <li class="px-md-4"><a class="text-nowrap text-white" href="acerca.html" >Acerca de</a></li>
        </ul>
      </section>
      <section class="col-12 col-md-3" id="Redes_Sociales">
        <ul class="list-unstyled d-flex gap-4 m-0 py-2 justify-content-center">
          <li><a href="https://www.facebook.com/"><img src="https://cdn-icons-png.flaticon.com/512/20/20673.png" alt="Facebook" width="24"></a></li>
          <li><a href="https://www.youtube.com/"><img src="https://cdn.iconscout.com/icon/free/png-256/free-youtube-104-432560.png" alt="YouTube" width="24"></a></li>
          <li><a href="https://www.instagram.com/"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384063.png" alt="Instagram" width="24"></a></li>
        </ul>
      </section>
    </div>
    <div class="row text-center mt-4">
      <div class="col-12">
        <p class="copy">&copy; 2025 Gympi - Todos los derechos reservados</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
