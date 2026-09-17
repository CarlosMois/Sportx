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

    <title>SportX | Federación Salvadoreña de Luchas Amateurs</title>

    <link rel="stylesheet" href="../Public/CSS/academias.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

    <!-- ENCABEZADO -->
    <header class="academia-header">

        <div class="back-container">
            <a href="lucha.php" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>
        </div>

        <div class="header-content">

            <h1>Federación Salvadoreña de Luchas Amateurs</h1>

            <p>
                Centro deportivo dedicado a la práctica y formación
                de atletas en lucha olímpica.
            </p>

        </div>

    </header>


    <main>

        <!-- GALERÍA -->

<section class="galeria">

    <h2>Galería</h2>

    <div class="carrusel">

        <div class="carrusel-track">

            <div class="imagen-carrusel">
                <img src="../Public/img/LUCHA-OLIMPICA.jpg" alt="Federación Salvadoreña de Luchas Amateurs">
            </div>

            <div class="imagen-carrusel">
                <img src="../Public/img/fondo encabezado lucha.jpg" alt="Entrenamiento de lucha olímpica">
            </div>

            <div class="imagen-carrusel">
                <img src="../Public/img/pie de pagina lucha.jpg" alt="Lucha olímpica">
            </div>

            <!-- Repetimos las imágenes para que el movimiento sea continuo -->

            <div class="imagen-carrusel">
                <img src="../Public/img/LUCHA-OLIMPICA.jpg" alt="Federación Salvadoreña de Luchas Amateurs">
            </div>

            <div class="imagen-carrusel">
                <img src="../Public/img/fondo encabezado lucha.jpg" alt="Entrenamiento de lucha olímpica">
            </div>

            <div class="imagen-carrusel">
                <img src="../Public/img/pie de pagina lucha.jpg" alt="Lucha olímpica">
            </div>

        </div>

    </div>

</section>
        <!-- INFORMACIÓN -->
        <section class="informacion">

            <h2>Información de la academia</h2>

            <div class="info-grid">

                <div class="info-item">

                    <i class="fa-solid fa-location-dot"></i>

                    <div>
                        <h3>Ubicación</h3>

                        <p>
                            Prolongación Calle Arce, entre la 45ª y 47ª
                            Avenida Norte #2429, Colonia Flor Blanca,
                            San Salvador.
                        </p>
                    </div>

                </div>


                <div class="info-item">

                    <i class="fa-solid fa-dollar-sign"></i>

                    <div>
                        <h3>Precio</h3>

                        <p>
                            Gratis para menores de edad.
                            $15 para mayores de edad.
                        </p>
                    </div>

                </div>


                <div class="info-item">

                    <i class="fa-solid fa-dumbbell"></i>

                    <div>
                        <h3>Disciplinas</h3>

                        <p>
                            Lucha Libre Masculina y Femenina.
                        </p>
                    </div>

                </div>


                <div class="info-item">

                    <i class="fa-solid fa-clock"></i>

                    <div>
                        <h3>Horario de atención</h3>

                        <p>
                            Lunes a Viernes, de 8:30 a.m. a 4:00 p.m.
                        </p>
                    </div>

                </div>


                <div class="info-item">

                    <i class="fa-solid fa-calendar-days"></i>

                    <div>
                        <h3>Horario de clases</h3>

                        <p>
                            Lunes a Viernes, de 3:30 p.m. a 5:30 p.m.
                        </p>
                    </div>

                </div>


                <div class="info-item">

                    <i class="fa-solid fa-phone"></i>

                    <div>
                        <h3>Teléfono</h3>

                        <p>
                            +503 2298-4121
                        </p>
                    </div>

                </div>

            </div>

        </section>


        <!-- BOTONES -->
        <section class="acciones">

            <a
                href="https://maps.google.com/?q=Federación+Salvadoreña+de+Luchas+Amateurs,+Flor+Blanca,+San+Salvador"
                target="_blank"
                class="action-btn">

                <i class="fa-solid fa-location-dot"></i>
                Ver ubicación

            </a>


            <a
                href="tel:+50322984121"
                class="action-btn">

                <i class="fa-solid fa-phone"></i>
                Llamar

            </a>

        </section>


        <!-- SOBRE LA ACADEMIA -->
        <section class="sobre-academia">

            <h2>Sobre esta academia</h2>

            <p>
                La Federación Salvadoreña de Luchas Amateurs ofrece
                espacios para la práctica de lucha libre masculina y
                femenina, permitiendo que niños, jóvenes y adultos
                puedan desarrollar sus habilidades deportivas.

                Su objetivo es fomentar la práctica de la lucha y
                brindar un espacio para el entrenamiento y formación
                de atletas.
            </p>

        </section>

    </main>



<!-- RESEÑAS -->

<section class="reseñas">

    <h2>Deja tu reseña</h2>

    <p class="reseñas-texto">
        ¿Has visitado esta academia?
        Comparte tu experiencia con otros usuarios.
    </p>

    <form class="form-reseña">

        <div class="campo">
            <label>Calificación</label>

    <div class="estrellas">

        <input type="radio" name="calificacion" id="estrella1" value="1">
        <label for="estrella1">★</label>

        <input type="radio" name="calificacion" id="estrella2" value="2">
        <label for="estrella2">★</label>

        <input type="radio" name="calificacion" id="estrella3" value="3">
        <label for="estrella3">★</label>

        <input type="radio" name="calificacion" id="estrella4" value="4">
        <label for="estrella4">★</label>

        <input type="radio" name="calificacion" id="estrella5" value="5">
        <label for="estrella5">★</label>

    </div>
</div>
        </div>

        <div class="campo">
            <label for="reseña">Tu reseña</label>

            <textarea
                id="reseña"
                rows="5"
                placeholder="Escribe tu experiencia..."
            ></textarea>
        </div>

        <button type="submit" class="reseña-btn">
            Publicar reseña
        </button>

<a href="reseñas.html" class="ver-resenas-btn">
    Ver todas las reseñas
</a>


    </form>

</section>



    <!-- PIE DE PÁGINA -->
    <footer>

        <p>
            © 2026 SportX | Todos los derechos reservados.
        </p>

    </footer>

</body>
</html>