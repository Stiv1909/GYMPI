<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usu_id'])) {
    header("Location: login.php"); // Redirigir si no está logueado
    exit();
}

include 'conexion.php';

// Obtener el usuario actual
$usu_id = $_SESSION['usu_id'];

// Obtener la imagen del perfil desde la base de datos
$sql = "SELECT username,imagen_principal FROM usuario WHERE usu_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usu_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($username,$imagen_principal);
$stmt->fetch();
$stmt->close();

$imagen = '';
if (!empty($imagen_principal)) {
    $imagen = 'data:image/jpeg;base64,' . base64_encode($imagen_principal);
}

// Consulta para traer gimnasios
$sql = "SELECT gym_id, eslogan, correo, horario, imagen_principal FROM gym";
$result = $conn->query($sql);

$gimnasios = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imagenData = '';
        if (!empty($row['imagen_principal'])) {
            // Convertir BLOB a base64 para mostrar imagen
            $imagenData = 'data:image/jpeg;base64,' . base64_encode($row['imagen_principal']);
        }
        $gimnasios[] = [
            'id' => $row['gym_id'],
            'eslogan' => $row['eslogan'],
            'correo' => $row['correo'],
            'horario' => $row['horario'],
            'imagen' => $imagenData
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gympi: Guía de Gimnasios en Ipiales</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="estilos/style_sr.css" type="text/css" />
    <link rel="stylesheet" href="estilos/estilos-Af.css" type="text/css" />
</head>
<body>
    <!-- Encabezado -->
    <header class="container-fluid bg-dark header">
        <div class="row h-100">
            <div class="col-6 col-md-1 p-0 h-100 d-flex align-items-center justify-content-end">
                <a href="principal.html"><img class="img-fluid" src="imagenes/logo.png" alt="Logo Gympi" style="max-height: 86px;" /></a>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center h-100 justify-content-start">
                <h1><a class="text-decoration-none tit_principal" href="principal.html">GYMPI</a></h1>
            </div>

            <!-- Menú de Navegación con icono de perfil y menú desplegable -->
            <div class="col-12 col-md-9 d-flex justify-content-end align-items-center">
                <div class="dropdown">
                    <a href="#" class="text-white me-4 dropdown-toggle d-flex align-items-center" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="gap:0.5rem;">
                        <?php if ($imagen): ?>
                            <!-- Mostrar imagen de perfil si está disponible -->
                            <img src="<?= $imagen ?>" alt="Perfil" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        <?php else: ?>
                            <!-- Si no hay imagen, mostrar el icono predeterminado -->
                            <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
                        <?php endif; ?>
                        <span class="d-none d-md-inline fw-bold"><?= htmlspecialchars($username) ?></span></a>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        
                        <li><a class="dropdown-item" href="EditarPerfil.php">Editar perfil</a></li>
                        <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    

    <!-- Contenedor Principal Listar Gimnasios-->
    <main class="container-fluid p-5 main d-flex flex-column align-items-center" style="min-height: 100vh; background-color: #94b6bb;">
    <h2 class="fw-bold text-center mb-5" style="font-size: 2.5rem;">GIMNASIOS DISPONIBLES</h2>
    <div class="row justify-content-center g-4 w-100" style="max-width: 1200px;">
        <?php foreach ($gimnasios as $gym): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <a href="informacion.html" class="text-decoration-none text-dark">
                    <div class="card text-center h-100">
                        <?php if ($gym['imagen']): ?>
                            <img src="<?= $gym['imagen'] ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($gym['eslogan']) ?>" 
                                 style="width: 250px; height: 250px; object-fit: cover; display: block; margin-left: auto; margin-right: auto;">
                        <?php else: ?>
                            <img src="imagenes/gim1.png" 
                                 class="card-img-top" 
                                 alt="Imagen por defecto"
                                 style="width: 250px; height: 250px; object-fit: cover; display: block; margin-left: auto; margin-right: auto;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($gym['eslogan']) ?></h5>
                            <p class="card-text text-muted">
                                Correo: <?= htmlspecialchars($gym['correo']) ?><br>
                                Horario: <?= htmlspecialchars($gym['horario']) ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>


    <!-- Pie de Página -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
