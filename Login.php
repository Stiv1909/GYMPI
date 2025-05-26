<?php
session_start();
include 'conexion.php';  // Asegúrate que conexión esté bien configurada

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $correo = trim($_POST['correo'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');

    if (!$correo || !$contraseña) {
        $error = "Por favor ingresa correo y contraseña.";
    } else {
        // Buscar en tabla usuario
        $sql_usuario = "SELECT * FROM usuario WHERE correo = ?";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("s", $correo);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();

        // Buscar en tabla propietario
        $sql_propietario = "SELECT * FROM propietario WHERE correo = ?";
        $stmt_propietario = $conn->prepare($sql_propietario);
        $stmt_propietario->bind_param("s", $correo);
        $stmt_propietario->execute();
        $result_propietario = $stmt_propietario->get_result();

        if ($result_usuario->num_rows > 0) {
            $usuario = $result_usuario->fetch_assoc();

            if (password_verify($contraseña, $usuario['contraseña'])) {
                $_SESSION['usu_id'] = $usuario['usu_id'];
                $_SESSION['rol'] = 'usuario';

                // Verificar si perfil completo (ejemplo: celular)
                $celular = $usuario['celular'] ?? '';

                if (empty($celular)) {
                    header("Location: CrearPerfil.php");
                    exit();
                } else {
                    header("Location: ListarGimnasios.php");
                    exit();
                }
            } else {
                $error = "Contraseña incorrecta.";
            }
        } elseif ($result_propietario->num_rows > 0) {
            $propietario = $result_propietario->fetch_assoc();

            if (password_verify($contraseña, $propietario['contraseña'])) {
                // Guarda la sesión con misma clave para evitar confusión
                $_SESSION['usu_id'] = $propietario['propietario_id'];
                $_SESSION['rol'] = 'propietario';

                // Verificar si propietario tiene gimnasio registrado
                $sql_gym = "SELECT gym_id FROM gym WHERE propietario_id = ?";
                $stmt_gym = $conn->prepare($sql_gym);
                $stmt_gym->bind_param("i", $_SESSION['usu_id']);
                $stmt_gym->execute();
                $stmt_gym->store_result();

                if ($stmt_gym->num_rows > 0) {
                    // Tiene gimnasio, redirige a lista
                    header("Location: ListarGimnasios.php");
                } else {
                    // No tiene gimnasio, redirige a registro
                    header("Location: RegistroGimnasio.php");
                }
                $stmt_gym->close();
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "El correo electrónico no está registrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gympi: Guía de Gimnasios en Ipiales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="estilos/style_sr.css" type="text/css" />
    <link rel="stylesheet" href="estilos/estilos-Af.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
<header class="container-fluid bg-dark header">
    <div class="row h-100">
        <div class="col-6 col-md-1 p-0 h-100 d-flex align-items-center justify-content-end">
            <a href="principal.html"><img class="img-fluid" src="imagenes/logo.png" alt="Logo Gympi" style="max-height: 86px;" /></a>
        </div>
        <div class="col-6 col-md-2 d-flex align-items-center h-100 justify-content-start">
            <h1><a class="text-decoration-none tit_principal" href="principal.html">GYMPI</a></h1>
        </div>
        <nav class="col-12 col-md-9 navbar navbar-expand-md navbar-light bg-nav align-items-center ">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link bg-item" href="Login.php">INICIAR SESIÓN</a></li>
                        <li class="nav-item"><a class="nav-link bg-item" href="RegistroUsuario.php">CREAR CUENTA</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

<main class="container-fluid p-5 main d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-container p-4 rounded shadow bg-white" style="max-width: 400px; width: 100%;">
        <h2 class="text-center mb-4 fw-bold">Iniciar Sesión</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mt-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" novalidate>
            <div class="mb-3 text-start">
                <label for="correo" class="form-label fw-bold">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" required />
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label fw-bold">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="contraseña" placeholder="Contraseña" required />
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-acceder px-4">ACCEDER</button>
            </div>
        </form>
    </div>
</main>

<footer class="container-fluid p-5 bg-dark">
    <div class="row">
        <section class="col-12 col-md-9 pb-3" id="Politicas">
            <ul class="list-unstyled d-flex flex-wrap gap-4 py-2 m-0 justify-content-center justify-content-md-start">
                <li class="px-md-4"><a class="text-nowrap" href="term_cond.html">Terminos y Condiciones</a></li>
                <li class="px-md-4"><a class="text-nowrap" href="privacidad.html">Privacidad</a></li>
                <li class="px-md-4"><a class="text-nowrap" href="acerca.html">Acerca de</a></li>
            </ul>
        </section>

        <section class="col-12 col-md-3" id="Redes_Sociales">
            <ul class="list-unstyled d-flex gap-4 m-0 py-2 justify-content-center">
                <li><a href="https://www.facebook.com/"><img src="https://cdn-icons-png.flaticon.com/512/20/20673.png" alt="logo_facebook" /></a></li>
                <li><a href="https://www.youtube.com/"><img src="https://cdn.iconscout.com/icon/free/png-256/free-youtube-104-432560.png" alt="logo_youtube" /></a></li>
                <li><a href="https://www.instagram.com/"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384063.png" alt="logo_instagram" /></a></li>
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
</body>
</html>
