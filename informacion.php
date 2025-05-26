<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gympi: Guía de Gimnasios en Ipiales</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="estilos/estilos-info.css" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />


</head>
<body>

<!-- HEADER -->
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
      <nav class="col-12 col-md-9 navbar navbar-expand-md navbar-light bg-nav align-items-center">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" style="color: white;" href="informacion.html">Información</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" style="color: white;" href="tiposEntrenamiento.html">Tipos de Entrenamiento</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" style="color: white;" href="instructores.html">Instructores</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
  </div>
</header>


<!-- MAIN -->
<main class="container-fluid mt-4">
  <div class="row">
    <!-- Columna Izquierda -->
    <div class="col-md-4 text-center">
      <h3 class="mb-3"><strong>Nombre Establecimiento</strong></h3>
      <div class="card">
        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8dGhlJTIwZ3ltfGVufDB8fDB8fHww" alt="Descripción de la imagen 1" width="300*200" />
        <div class="card-body bg-white">
          <h5 class="card-title">Eslogan</h5>
        </div>
      </div>
    </div>

    <!-- Columna Central -->
    <div class="col-md-4">
        <h3 class="mb-3"><strong>Información General</strong></h3>
      

      <div class="info-box d-flex align-items-center">
        <strong>Contacto: 318 501 47 75</strong>
      </div>
      <div class="info-box d-flex align-items-center">
        <strong>Dirección: Cra 2a #2-57</strong>
      </div>
      <div class="info-box d-flex align-items-center">
        <strong>Horario:</strong>
        <ul class="list-unstyled">
            <li><strong> Lunes 6:00 AM - 9:00 PM</strong></li>
            <li><strong> Martes 6:00 AM - 9:00 PM</strong></li>
            <li><strong> Miércoles 6:00 AM - 9:00 PM</strong></li>
            <li><strong> Jueves 6:00 AM - 9:00 PM</strong></li>
            <li><strong> Viernes 6:00 AM - 9:00 PM</strong></li>
            <li><strong> Sábado 8:00 AM - 2:00 PM</strong></li> 
        </ul>
      </div>
    </div>

    <!-- Columna Derecha -->
    <div class="col-md-4 text-center">
      <h3 class="mb-3"><strong>Instalaciones</strong></h3>

      <!-- Carrusel -->
      <div id="carouselInstalaciones" class="carousel slide mb-3" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="imagenes/gym1.jpg" class="d-block w-100" alt="Instalación 1" />
          </div>
          <div class="carousel-item">
            <img src="imagenes/gym2.jpg" class="d-block w-100" alt="Instalación 2" />
          </div>
          <div class="carousel-item">
            <img src="imagenes/gym3.jpg" class="d-block w-100" alt="Instalación 3" />
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInstalaciones" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselInstalaciones" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>

      <!-- Estrellas -->
      <div class="star-rating mb-2">
        <i class="fa-solid fa-star text-black"></i>
        <i class="fa-solid fa-star text-black"></i>
        <i class="fa-solid fa-star text-black"></i>
        <i class="fa-solid fa-star text-white"></i>
        <i class="fa-solid fa-star text-white"></i>
      </div>
      <p class="text-success"><strong>¿Qué te pareció este lugar?</strong></p>
    </div>
  </div>
</main>

<!-- FOOTER -->
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
