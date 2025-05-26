<?php
  include 'conexion.php';  // Conectar a la base de datos

  // Inicializar las variables de mensaje y error
  $mensaje = "";
  $error = "";

  // Verificar que el formulario se ha enviado
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los valores del formulario
      $username = $_POST['username'];
      $correo = $_POST['correo'];
      $contraseña = $_POST['contraseña'];

      // Validar que el correo no exista ya en la base de datos
      $sql_check_correo = "SELECT * FROM usuario WHERE correo = '$correo'";
      $result_check_correo = $conn->query($sql_check_correo);
      if ($result_check_correo->num_rows > 0) {
          $error = "El correo electrónico ya está registrado.";
      }

      // Validar que el nombre del usuario no exista ya en la base de datos
      $sql_check_nombre = "SELECT * FROM usuario WHERE username = '$username'";
      $result_check_nombre = $conn->query($sql_check_nombre);
      if ($result_check_nombre->num_rows > 0) {
          $error = "El nombre del usuario ya está registrado.";
      }

      // Si no hay errores, proceder con el registro
      if (empty($error)) {
          // Encriptar la contraseña antes de almacenarla
          $contraseña_encriptada = password_hash($contraseña, PASSWORD_DEFAULT);

          // Asignar un valor de rol por defecto (esto debe estar en base de datos)
          $rol_id = 1; // Cambia este valor según cómo gestiones los roles

          // Preparar la consulta para insertar los datos
          $sql = "INSERT INTO usuario (username, correo, contraseña, rol_id) 
                  VALUES ('$username', '$correo', '$contraseña_encriptada', '$rol_id')";

          if ($conn->query($sql) === TRUE) {
              $mensaje = "Nuevo usuario registrado exitosamente.";

              // Redirigir a Login.php después de registrar el usuario
              header("Location: Login.php");
              exit(); // Asegura que no se ejecute más código después de la redirección
          } else {
              $error = "Error al registrar usuario: " . $conn->error;
          }
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
                        <a class="nav-link bg-item" href="Login.php">INICIAR SESIÓN</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link bg-item" href="RegistroUsuario.php">CREAR CUENTA</a>
                      </li>
                    </ul>
                  </div>
                </div>
            </nav>
        </div>
    </header>




    <!-- Contenedor Principal Registro-->
    <main class="container-fluid p-5 main d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        
        <div class="login-container p-4 rounded shadow bg-white">
            <!-- Encabezado -->
            <div class="tab-container d-flex">
                <a href="RegistroUsuario.php" class="tab active-tab w-50 text-center">Usuario</a>
                <a href="RegistroPropietario.php" class="tab w-50 text-center">Propietario</a>
              </div>
              
      
            <!-- Título -->
            <h2 class="text-center fw-bold mb-4">Crear Cuenta</h2>


            <?php
              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($mensaje) && $mensaje != "") {
                    echo "<div class='alert alert-success mt-4'>$mensaje</div>";
                }
                if (isset($error) && $error != "") {
                    echo "<div class='alert alert-danger mt-4'>$error</div>";
                }
              }
            ?>
      
            <!-- Formulario -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            
              <div class="mb-3">
                <label for="nomest" class="form-label fw-bold">Username:</label>
                <input type="text" class="form-control" id="nomest" name="username" placeholder="Agregar texto" required>
              </div>
        
              <div class="mb-3">
                <label for="email" class="form-label fw-bold">Correo:</label>
                <input type="email" class="form-control" id="email" name="correo" placeholder="Agregar texto" required>
              </div>
        
              <div class="mb-3">
                <label for="password" class="form-label fw-bold">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="contraseña" placeholder="Agregar texto" required>
              </div>
      
              <div class="text-center">
                <button type="submit" class="btn btn-acceder px-4">REGISTRAR</button>
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