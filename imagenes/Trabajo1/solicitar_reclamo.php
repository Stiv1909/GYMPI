<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestión de Clientes</title>
  <!-- Enlace a Bootstrap 5.3.2 CSS -->
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
            <a class="nav-link" href="solicitar_reclamo.php">Solicitar Reclamo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Login.php">Iniciar Sesion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Registros.php">Registrarse</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenedor para centrar el formulario -->
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
      <div class="card-body">
        <h2 class="text-center mb-4">Solicitar Reclamo</h2>

        <!-- Formulario para registrar un cliente -->
        <form action="guardar_cliente.php" method="POST">

          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre completo" required>
          </div>

          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección del cliente" required>
          </div>

          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ej: +56912345678" required>
          </div>

          <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
          </div>
          <hr class="my-4">

<h5>Datos del Reclamo</h5>

<div class="mb-3">
  <label for="descripcion" class="form-label">Descripción del Reclamo</label>
  <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
</div>

<div class="mb-3">
  <label for="tipo" class="form-label">Tipo de Reclamo</label>
  <select class="form-select" id="tipo" name="tipo" required>
    <option value="factura">Factura</option>
    <option value="corte de servicio">Corte de Servicio</option>
  </select>
</div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    &copy; 2025 Gestión de Reclamos. Todos los derechos reservados.
  </footer>

  <!-- Enlaces a JS de Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
