<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Reclamos</title>
    <!-- Enlace a Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <!-- Enlace a archivo CSS personalizado -->
    <link href="estilos/Estilos.css" rel="stylesheet">

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

</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h3 class="text-center mb-4">Crear Cuenta</h3>
                <!-- El formulario se envía al archivo registro.php -->
                <form action="registro.php" method="POST">
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                </form>
                <p class="text-center mt-3">¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        &copy; 2025 Gestión de Reclamos. Todos los derechos reservados.
    </footer>

    <!-- Enlaces a JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</body>
</html>
