<?php
  session_start();
  include 'conexion.php';  // Asegúrate de que la conexión a la base de datos esté configurada correctamente.

  $error = "";  // Mensaje de error si algo falla.

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $correo = $_POST['correo'];
      $contraseña = $_POST['contraseña'];

      // Verificar si el correo existe en la tabla 'usuario'
      $sql_usuario = "SELECT * FROM usuario WHERE correo = ?";
      $stmt_usuario = $conn->prepare($sql_usuario);
      $stmt_usuario->bind_param("s", $correo);  // "s" para cadena (string)
      $stmt_usuario->execute();
      $result_usuario = $stmt_usuario->get_result();

      // Verificar si el correo existe en la tabla 'propietario'
      $sql_propietario = "SELECT * FROM propietario WHERE correo = ?";
      $stmt_propietario = $conn->prepare($sql_propietario);
      $stmt_propietario->bind_param("s", $correo);  // "s" para cadena (string)
      $stmt_propietario->execute();
      $result_propietario = $stmt_propietario->get_result();

      // Si el correo existe en la tabla de usuarios
      if ($result_usuario->num_rows > 0) {
          $usuario = $result_usuario->fetch_assoc();  // Obtiene los datos del usuario
          
          // Verificar la contraseña (usando password_verify())
          if (password_verify($contraseña, $usuario['contraseña'])) {
              // Contraseña correcta, iniciar sesión
              $_SESSION['user_id'] = $usuario['usuario_id'];  // Guardar el ID del usuario en la sesión
              $_SESSION['rol'] = 'usuario';  // Guardar el rol en la sesión
              // Redirigir al usuario a la página correspondiente (ListarGimnasios.php)
              header("Location: ListarGimnasios.php");
              exit();
          } else {
              $error = "Contraseña incorrecta.";
          }
      } 
      // Si el correo existe en la tabla de propietarios
      elseif ($result_propietario->num_rows > 0) {
          $propietario = $result_propietario->fetch_assoc();  // Obtiene los datos del propietario
          
          // Verificar la contraseña (usando password_verify())
          if (password_verify($contraseña, $propietario['contraseña'])) {
              // Contraseña correcta, iniciar sesión
              $_SESSION['user_id'] = $propietario['propietario_id'];  // Guardar el ID del propietario en la sesión
              $_SESSION['rol'] = 'propietario';  // Guardar el rol en la sesión
              // Redirigir al propietario a la página correspondiente (ListarGimnasios.php)
              header("Location: ListarGimnasios.php");
              exit();
          } else {
              $error = "Contraseña incorrecta.";
          }
      } else {
          // Si no se encuentra el correo en ninguna de las tablas
          $error = "El correo electrónico no está registrado.";
      }
  }
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gympi: Guía de Gimnasios en Ipiales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/style_sr.css" type="text/css">
    <link rel="stylesheet" href="estilos/estilos-Af.css" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Encabezado -->
    <header class="container-fluid bg-dark header">
        <div class="row h-100"> 
            <div class="col-6 col-md-1 p-0 h-100 d-flex align-items-center justify-content-end">
                <!-- Agrega el logo de la empresa -->
                <a href="principal.html"><img class="img-fluid" src="imagenes/logo.png" alt="Logo Gympi" style="max-height: 86px;"></a>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center h-100 justify-content-start">
                <!-- Título principal -->
                <h1><a class="text-decoration-none tit_principal" href="principal.html">GYMPI</a></h1>
            </div>
            <!-- Menú de Navegación -->
            <nav class="col-12 col-md-9 navbar navbar-expand-md navbar-light bg-nav align-items-center ">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="container-fluid">
                  <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                      <li class="nav-item">
                        <a class="nav-link bg-item" href="Login.html">INICIAR SESIÓN</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link bg-item" href="RegistroUsuario.html">CREAR CUENTA</a>
                      </li>
                    </ul>
                  </div>
                </div>
            </nav>
        </div>
    </header>




    <!-- Contenedor Principal login-->
    <main class="container-fluid p-5 main d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="login-container p-4 rounded shadow bg-white">
          <h2 class="text-center mb-4 fw-bold">Iniciar Sesión</h2>


          <?php if (!empty($error)) { ?>
                <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
          <?php } ?>

          <form action="login.php" method="POST">
            <div class="mb-3 text-start">
              <label for="correo" class="form-label fw-bold">Correo:</label>
              <input type="email" class="form-control" id="correo" name="correo" placeholder="Agregar texto" required>
            </div>
            <div class="mb-3 text-start">
              <label for="password" class="form-label fw-bold">Contraseña:</label>
              <input type="password" class="form-control" id="password" name="contraseña" placeholder="Agregar texto" required>
            </div>
            <div class="mb-3 text-center">
                <a href="LstarGimnacios.php" class="forgot-link text-decoration-none">¿Olvidaste tu contraseña?</a>
              </div>
              
              
              <div class="text-center">
                <a href="ListarGimnasios.html">
                  <button type="submit" class="btn btn-acceder px-4">ACCEDER</button>
                </a>
              </div>
              
              
          </form>
        </div>
      
      
            

            


    </main>

    <!-- Pie de Página -->
    <footer class="container-fluid p-5 bg-dark">
         <!-- Sección Politica de la Empresa -->
        <div class="row">
            <section class="col-12 col-md-9 pb-3" id="Politicas">
                <ul class="list-unstyled d-flex flex-wrap gap-4 py-2 m-0 justify-content-center justify-content-md-start">
                    <li class="px-md-4"><a class="text-nowrap" href="term_cond.html">Terminos y Condiciones</a></li>
                    <li class="px-md-4"><a class="text-nowrap" href="privacidad.html">Privacidad</a></li>
                    <li class="px-md-4"><a class="text-nowrap" href="acerca.html">Acerca de</a></li>
                </ul>
            </section>

            <!-- Sección Redes Sociales -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>