<?php
// Conexion a la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$bd = "reclamos";

$conn = new mysqli($host, $usuario, $contrasena, $bd);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Función para crear un reclamo por defecto si no existe
function crearReclamosPorDefecto() {
    global $conn;
    $sql = "SELECT id_cliente FROM clientes";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id_cliente = $row['id_cliente'];
        // Verificar si ya existe un reclamo para este cliente
        $checkSql = "SELECT * FROM reclamos WHERE id_cliente = $id_cliente";
        $checkResult = $conn->query($checkSql);
        if ($checkResult->num_rows == 0) {
            $fecha_hora = date("Y-m-d H:i:s");
            $descripcion = "Problema con la factura de marzo";
            $estado = "pendiente";
            $tipo = "factura";
            $insertSql = "INSERT INTO reclamos (id_cliente, fecha_hora, descripcion, estado, tipo) 
                          VALUES ($id_cliente, '$fecha_hora', '$descripcion', '$estado', '$tipo')";
            $conn->query($insertSql);
        }
    }
}

// Crear reclamos por defecto si no existen
crearReclamosPorDefecto();

// Actualizar un reclamo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_reclamo'])) {
    $id = $_POST['id_reclamo'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $tipo = $_POST['tipo'];

    $sql = "UPDATE reclamos SET descripcion=?, estado=?, tipo=? WHERE id_reclamo=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $descripcion, $estado, $tipo, $id);
    $stmt->execute();

    header("Location: reclamos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestión de Reclamos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="#">Gestión de Reclamos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegación">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="clientes.php">Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#reclamos">Reclamos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="respuestas.php">Respuestas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Login.php">Salir</a>
            </li>
            </ul>
        </div>
        </div>
    </nav>

    <div class="container mt-5">
    <h2 class="mb-4">Listado de Reclamos</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID Reclamo</th>
            <th>ID Cliente</th>
            <th>Fecha/Hora</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Tipo</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM reclamos";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id_reclamo']}</td>
                    <td>{$row['id_cliente']}</td>
                    <td>{$row['fecha_hora']}</td>
                    <td>{$row['descripcion']}</td>
                    <td>{$row['estado']}</td>
                    <td>{$row['tipo']}</td>
                    <td>
                        <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#modalActualizarReclamo'
                            onclick='llenarModal(" . json_encode($row) . ")'>Actualizar</button>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No hay reclamos disponibles.</td></tr>";
        }
        ?>
        </tbody>
    </table>
    </div>

    <!-- Modal para Actualizar Reclamo -->
    <div class="modal fade" id="modalActualizarReclamo" tabindex="-1" aria-labelledby="modalActualizarReclamoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="reclamos.php" method="post">
            <div class="modal-header">
            <h5 class="modal-title" id="modalActualizarReclamoLabel">Actualizar Reclamo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">

            <input type="hidden" id="idReclamoModal" name="id_reclamo">

            <div class="mb-3">
                <label for="descripcionModal" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcionModal" name="descripcion" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="estadoModal" class="form-label">Estado del Reclamo</label>
                <select class="form-select" id="estadoModal" name="estado">
                <option value="pendiente">Pendiente</option>
                <option value="en proceso">En proceso</option>
                <option value="resuelto">Resuelto</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipoModal" class="form-label">Tipo de Reclamo</label>
                <select class="form-select" id="tipoModal" name="tipo">
                <option value="factura">Factura</option>
                <option value="corte de servicio">Corte de servicio</option>
                </select>
            </div>

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        &copy; 2025 Gestión de Reclamos. Todos los derechos reservados.
    </footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function llenarModal(reclamo) {
    document.getElementById('idReclamoModal').value = reclamo.id_reclamo;
    document.getElementById('descripcionModal').value = reclamo.descripcion;
    document.getElementById('estadoModal').value = reclamo.estado;
    document.getElementById('tipoModal').value = reclamo.tipo;
}
</script>

</body>
</html>
