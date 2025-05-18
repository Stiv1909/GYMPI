<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reclamos";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $query = "UPDATE clientes SET nombre = ?, direccion = ?, telefono = ?, correo = ? WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nombre, $direccion, $telefono, $correo, $id_cliente);

    if ($stmt->execute()) {
        header("Location: clientes.php");
        exit();
    } else {
        echo "Error al actualizar el cliente: " . $stmt->error;
    }
}

// Eliminación
if (isset($_GET['eliminar']) && !empty($_GET['eliminar'])) {
    $id_cliente = $_GET['eliminar'];
    $query = "DELETE FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cliente);
    
    if ($stmt->execute()) {
        header("Location: clientes.php");
        exit();
    } else {
        echo "Error al eliminar el cliente: " . $stmt->error;
    }
}

// Obtener clientes
$query = "SELECT * FROM clientes";
$result = $conn->query($query);

// Obtener cliente para editar (solo si se llama desde JavaScript)
$cliente_json = null;
if (isset($_GET['id_cliente']) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $id_cliente = $_GET['id_cliente'];
    $query = "SELECT * FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente_json = $result->fetch_assoc();
    echo json_encode($cliente_json);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Gestión de Reclamos</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="reclamos.php">Reclamos</a></li>
                <li class="nav-item"><a class="nav-link" href="respuestas.php">Respuestas</a></li>
                <li class="nav-item"><a class="nav-link" href="Login.php">Salir</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Clientes Registrados</h2>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($cliente = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $cliente['id_cliente']; ?></td>
                <td><?php echo $cliente['nombre']; ?></td>
                <td><?php echo $cliente['direccion']; ?></td>
                <td><?php echo $cliente['telefono']; ?></td>
                <td><?php echo $cliente['correo']; ?></td>
                <td>
                    <button class="btn btn-sm btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#modalActualizar"
                            onclick="cargarDatos(<?php echo $cliente['id_cliente']; ?>)">Actualizar</button>
                    <a href="clientes.php?eliminar=<?php echo $cliente['id_cliente']; ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?')">Eliminar</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal Actualizar -->
<div class="modal fade" id="modalActualizar" tabindex="-1" aria-labelledby="modalActualizarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="clientes.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalActualizarLabel">Actualizar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreModal" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreModal" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccionModal" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccionModal" name="direccion" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefonoModal" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefonoModal" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="correoModal" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correoModal" name="correo" required>
                    </div>
                    <input type="hidden" id="idClienteModal" name="id_cliente">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    &copy; 2025 Gestión de Reclamos. Todos los derechos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para cargar datos al modal -->
<script>
function cargarDatos(id) {
    fetch(`clientes.php?id_cliente=${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('idClienteModal').value = data.id_cliente;
        document.getElementById('nombreModal').value = data.nombre;
        document.getElementById('direccionModal').value = data.direccion;
        document.getElementById('telefonoModal').value = data.telefono;
        document.getElementById('correoModal').value = data.correo;
    })
    .catch(error => {
        alert("Error al cargar datos del cliente");
        console.error(error);
    });
}
</script>
</body>
</html>
