<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reclamos";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_reclamo']) && !isset($_POST['actualizar_respuesta'])) {
    $id_reclamo = $_POST['id_reclamo'];
    $descripcion = $_POST['descripcion'];
    $fecha_hora = $_POST['fecha_hora'];
    $id_admin = 1; // Suponemos admin 1 por defecto

    $stmt = $conn->prepare("INSERT INTO respuestas (id_reclamo, descripcion, fecha_hora, id_admin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $id_reclamo, $descripcion, $fecha_hora, $id_admin);
    $stmt->execute();
    $stmt->close();
    header("Location: respuestas.php");
    exit;
}

// Eliminar respuesta
if (isset($_GET['eliminar'])) {
    $id_respuesta = $_GET['eliminar'];
    $stmt = $conn->prepare("DELETE FROM respuestas WHERE id_respuesta = ?");
    $stmt->bind_param("i", $id_respuesta);
    $stmt->execute();
    $stmt->close();
    header("Location: respuestas.php");
    exit;
}

// Actualizar respuesta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_respuesta'])) {
    $id_respuesta = $_POST['id_respuesta'];
    $descripcion = $_POST['descripcion'];
    $fecha_hora = $_POST['fecha_hora'];
    
    $stmt = $conn->prepare("UPDATE respuestas SET descripcion = ?, fecha_hora = ? WHERE id_respuesta = ?");
    $stmt->bind_param("ssi", $descripcion, $fecha_hora, $id_respuesta);
    $stmt->execute();
    $stmt->close();
    header("Location: respuestas.php");
    exit;
}

// Obtener opciones para el formulario
$reclamos_result = $conn->query("SELECT id_reclamo, descripcion FROM reclamos");

// Obtener respuestas para listar
$respuestas_result = $conn->query("
    SELECT r.id_respuesta, r.id_reclamo, r.descripcion, r.fecha_hora
    FROM respuestas r
    ORDER BY r.fecha_hora DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestión de Respuestas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Gestión de Reclamos</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
        <li class="nav-item"><a class="nav-link" href="reclamos.php">Reclamos</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Respuestas</a></li>
        <li class="nav-item"><a class="nav-link" href="Login.php">Salir</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="mb-4">Listado de Respuestas</h2>
  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalRegistrarRespuesta">Registrar Respuesta</button>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID Respuesta</th>
        <th>ID Reclamo</th>
        <th>Descripción</th>
        <th>Fecha/Hora</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $respuestas_result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id_respuesta'] ?></td>
          <td><?= $row['id_reclamo'] ?></td>
          <td><?= $row['descripcion'] ?></td>
          <td><?= $row['fecha_hora'] ?></td>
          <td>
            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarRespuesta<?= $row['id_respuesta'] ?>">Actualizar</button>
            <a href="respuestas.php?eliminar=<?= $row['id_respuesta'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta respuesta?');">Eliminar</a>
          </td>
        </tr>

        <!-- Modal de Editar Respuesta -->
        <div class="modal fade" id="modalEditarRespuesta<?= $row['id_respuesta'] ?>" tabindex="-1" aria-labelledby="modalEditarRespuestaLabel<?= $row['id_respuesta'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST" action="respuestas.php">
                <div class="modal-header">
                  <h5 class="modal-title">Editar Respuesta</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id_respuesta" value="<?= $row['id_respuesta'] ?>">

                  <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="descripcion" rows="3" required><?= $row['descripcion'] ?></textarea>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Fecha y Hora</label>
                    <input type="datetime-local" class="form-control" name="fecha_hora" value="<?= date('Y-m-d\TH:i', strtotime($row['fecha_hora'])) ?>" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="actualizar_respuesta" class="btn btn-primary">Guardar Cambios</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Modal para Registrar Respuesta -->
<div class="modal fade" id="modalRegistrarRespuesta" tabindex="-1" aria-labelledby="modalRegistrarRespuestaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="respuestas.php">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Nueva Respuesta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="id_reclamo" class="form-label">ID del Reclamo</label>
            <select class="form-select" name="id_reclamo" id="id_reclamo" required>
              <option value="">Seleccione un reclamo</option>
              <?php
              // Re-ejecutar consulta porque ya se usó arriba
              $reclamos_result = $conn->query("SELECT id_reclamo, descripcion FROM reclamos");
              while ($row = $reclamos_result->fetch_assoc()):
              ?>
                <option value="<?= $row['id_reclamo'] ?>"><?= $row['id_reclamo'] ?> - <?= $row['descripcion'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" id="descripcion" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label for="fecha_hora" class="form-label">Fecha y Hora</label>
            <input type="datetime-local" class="form-control" name="fecha_hora" id="fecha_hora" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
  &copy; 2025 Gestión de Reclamos. Todos los derechos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
