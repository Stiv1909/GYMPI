<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    die("ID del gimnasio no especificado.");
}

$gym_id = intval($_GET['id']);

$sql = "SELECT nombre_instr, descripcion, foto_instr FROM instructores WHERE gym_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $gym_id);
$stmt->execute();
$result = $stmt->get_result();

$instructores = [];
while ($row = $result->fetch_assoc()) {
    // Convertir blob a base64 para mostrar imagen
    $row['foto_instr'] = 'data:image/jpeg;base64,' . base64_encode($row['foto_instr']);
    $instructores[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Instructores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="estilos/estilos-tiposentre.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

  <!-- HEADER -->
  <header class="container-fluid bg-dark header">
    <div class="row h-100"> 
      <div class="col-6 col-md-1 p-0 h-100 d-flex align-items-center justify-content-end">
        <a href="ListarGimnasios.php"><img class="img-fluid" src="imagenes/logo.png" alt="Logo Gympi" style="max-height: 86px;"></a>
      </div>
      <div class="col-6 col-md-2 d-flex align-items-center h-100 justify-content-start">
        <h1><a class="text-decoration-none tit_principal" href="ListarGimnasios.php">GYMPI</a></h1>
      </div>
      <nav class="col-12 col-md-9 navbar navbar-expand-md navbar-light bg-nav align-items-center">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" style="color: white;" href="informacion.php?id=<?= $gym_id ?>">Información</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" style="color: white;" href="tiposEntrenamiento.php?id=<?= $gym_id ?>">Tipos de Entrenamiento</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" style="color: white;" href="instructores.php?id=<?= $gym_id ?>">Instructores</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <!-- MAIN -->
  <main>
    <div class="container my-4">
      <?php if (count($instructores) > 0): ?>
        <?php foreach ($instructores as $instr): ?>
          <div class="training-card">
            <img src="<?= $instr['foto_instr'] ?>" alt="<?= htmlspecialchars($instr['nombre_instr']) ?>" width="300" class="training-img">
            <div class="training-info">
              <h3><?= htmlspecialchars($instr['nombre_instr']) ?></h3>
              <p><?= htmlspecialchars($instr['descripcion']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center">No se encontraron instructores para este gimnasio.</p>
      <?php endif; ?>
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
          <li><a href="https://www.facebook.com/"><img src="https://cdn-icons-png.flaticon.com/512/20/20673.png" alt="Facebook" width="24"></a></li>
          <li><a href="https://www.youtube.com/"><img src="https://cdn.iconscout.com/icon/free/png-256/free-youtube-104-432560.png" alt="YouTube" width="24"></a></li>
          <li><a href="https://www.instagram.com/"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384063.png" alt="Instagram" width="24"></a></li>
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
