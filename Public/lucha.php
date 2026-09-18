<?php

session_start();

if (
    !isset($_SESSION["autenticado"]) ||
    $_SESSION["autenticado"] !== true
) {
    header("Location: sesion.html");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportX | Lucha Olímpica</title>

    <link rel="stylesheet" href="../Public/CSS/lucha.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<header class="hero">

    <div class="back-container">

    <a href="../Public/index1.php" class="back-btn">

        <i class="fa-solid fa-arrow-left"></i>

        Volver al menú
    </a>

</div>

    <div class="hero-content">

        <h1>Lucha Olímpica</h1>

        <p>Encuentra los mejores lugares para entrenar lucha olímpica en El Salvador.</p>

    </div>

</header>

<main>
    
<section class="info-general">

    <h2 style="color:orange">Información General</h2>

    <div class="info-grid">

        <div class="info-box">
            <i class="fa-solid fa-location-dot"></i>
            <h3>Centros disponibles</h3>
            <p>3</p>
        </div>

        <div class="info-box">
            <i class="fa-solid fa-users"></i>
            <h3>Modalidad</h3>
            <p>Masculino y Femenino</p>
        </div>

        <div class="info-box">
            <i class="fa-solid fa-dollar-sign"></i>
            <h3>Precio</h3>
            <p>Gratis - $60</p>
        </div>

        <div class="info-box">
            <i class="fa-solid fa-calendar-days"></i>
            <h3>Días</h3>
            <p>Lunes a Viernes</p>
        </div>

    </div>


    
</section>

<script>
    const searchForm = document.getElementById('search-form');
const searchInput = document.getElementById('search-input');
const resultsContainer = document.getElementById('search-results');

// Ejemplo de base de datos local de tu página
const articulos = [
  { titulo: 'Cómo crear una web', url: '/crear-web.html' },
  { titulo: 'Aprender diseño CSS', url: '/diseno-css.html' },
  { titulo: 'Contacto y soporte', url: '/contacto.html' }
];

searchForm.addEventListener('submit', function(e) {
  e.preventDefault(); // Evita que la página se recargue
  const query = searchInput.value.toLowerCase();
  resultsContainer.innerHTML = ''; // Limpia resultados anteriores

  if(query.trim() === "") return;

  // Filtrar los artículos que coincidan con la búsqueda
  const resultados = articulos.filter(item => item.titulo.toLowerCase().includes(query));

  if(resultados.length > 0) {
    resultados.forEach(item => {
      const link = document.createElement('a');
      link.href = item.url;
      link.textContent = item.titulo;
      link.style.display = 'block';
      resultsContainer.appendChild(link);
    });
  } else {
    resultsContainer.textContent = 'No se encontraron resultados.';
  }
});
</script>


<form id="search-form">
  <input type="text" id="search-input" placeholder="Buscar en el sitio...">
  <button type="submit">Buscar</button>
</form>
<div id="search-results"></div>
</main>

<!-- ===========================
     TARJETAS DE ACADEMIAS
=========================== -->

<section class="academias">

    <h2>Academias disponibles</h2>

    <div class="academias-grid">

        <!-- Academia 1 -->
        <div class="academia-card">

    <img src="../Public/img/LUCHA-OLIMPICA.jpg" alt="Federación Salvadoreña de Luchas Amateurs">
    

    <h3>Federación Salvadoreña de Luchas Amateurs</h3>
    

    <p class="ubicacion">
        <i class="fa-solid fa-location-dot"></i>
        Prolongación Calle Arce, entre la 45ª y 47ª Avenida Norte #2429,
        Colonia Flor Blanca, San Salvador.
    </p>

    <div class="card-acciones">

    <a href="academias.php" target="_blank" class="academia-btn"> 
        Ver información 
        <i class="fa-solid fa-arrow-right"></i> 
    </a> 

    <button class="favorito-btn" type="button"> 
        <i class="fa-regular fa-heart"></i> 
    </button>

</div>

</div>

        <!-- Academia 2 -->
        <div class="academia-card">

            <img src="../Public/img/IMG-20260529-WA0008(2).jpg" alt="MMA Authority Training Center">

            <h3>MMA Authority Training Center</h3>

         <p class="ubicacion">
        <i class="fa-solid fa-location-dot"></i>
        Calle La Mascota #503, Colonia San Benito, San Salvador.
         </p>


           <div class="card-acciones">

    <a href="academias.php" target="_blank" class="academia-btn"> 
        Ver información 
        <i class="fa-solid fa-arrow-right"></i> 
    </a> 

    <button class="favorito-btn" type="button"> 
        <i class="fa-regular fa-heart"></i> 
    </button>

</div>

        </div>


        <!-- Academia 3 -->
        <div class="academia-card">

            <img src="../Public/img/fondo encabezado lucha.jpg" alt="Complejo Deportivo INDES San Miguel">

            <h3>Complejo Deportivo INDES San Miguel</h3>

            <p class="ubicacion">
        <i class="fa-solid fa-location-dot"></i>
        Avenida San Miguel, San Miguel.
            </p>

           <div class="card-acciones">

    <a href="academias.php" target="_blank" class="academia-btn"> 
        Ver información 
        <i class="fa-solid fa-arrow-right"></i> 
    </a> 

    <button class="favorito-btn" type="button"> 
        <i class="fa-regular fa-heart"></i> 
    </button>

</div>

        </div>

    </div>

</section>




<footer>

<p>© 2026 SportX | Todos los derechos reservados.</p>

</footer>

</body>
</html>