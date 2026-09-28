<?php
session_start();

// Verificación de sesión
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: sesion.html");
    exit;
}

require_once '../Config/Conexion.php';
$database = new Database();
$db = $database->getConnection();

$id_centro = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_centro <= 0) {
    header("Location: deportes.php");
    exit;
}

try {
    $stmt = $db->prepare("SELECT * FROM centros_deportivos WHERE id_centro = ?");
    $stmt->execute([$id_centro]);
    $academia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$academia) {
        die("Error: La academia seleccionada no existe.");
    }

    $stmt_img = $db->prepare("SELECT imagen_url FROM imagenes WHERE id_centro = ?");
    $stmt_img->execute([$id_centro]);
    $imagenes = $stmt_img->fetchAll(PDO::FETCH_ASSOC);

    $stmt_hor = $db->prepare("SELECT * FROM horarios WHERE id_centro = ? ORDER BY FIELD(dia_semana, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo')");
    $stmt_hor->execute([$id_centro]);
    $horarios = $stmt_hor->fetchAll(PDO::FETCH_ASSOC);

    $stmt_cost = $db->prepare("
        SELECT cc.*
        FROM costo_centro cc
        JOIN centro_deporte cd ON cc.id_centro_deporte = cd.id_centro_deporte
        WHERE cd.id_centro = ?
    ");
    $stmt_cost->execute([$id_centro]);
    $costos = $stmt_cost->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error de base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportX | <?php echo htmlspecialchars($academia['nombre']); ?></title>
    <link rel="stylesheet" href="../Public/CSS/academias.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

    <header class="academia-header">
        <div class="back-container">
            <!-- CORRECCIÓN: Volver a la página de deportes dinámica -->
            <a href="deportes.php" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="header-content">
            <h1><?php echo htmlspecialchars($academia['nombre']); ?></h1>
            <p><?php echo !empty($academia['descripcion']) ? htmlspecialchars($academia['descripcion']) : 'Centro deportivo dedicado a la excelencia atlética y la formación de nuevos talentos.'; ?></p>
        </div>
    </header>

    <main>
        <section class="galeria">
            <h2>Galería</h2>
            <div class="carrusel">
                <div class="carrusel-track">
                    <?php if (count($imagenes) > 0): ?>
                        <?php foreach ($imagenes as $img): ?>
                            <div class="imagen-carrusel">
                                <img src="<?php echo htmlspecialchars($img['imagen_url']); ?>" alt="Imagen">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="imagen-carrusel">
                            <img src="../Public/img/LUCHA-OLIMPICA.jpg" alt="Imagen por defecto">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="informacion">
            <h2>Información de la academia</h2>
            <div class="info-grid">
                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="info-text">
                        <h3>Ubicación</h3>
                        <p><?php echo !empty($academia['direccion']) ? htmlspecialchars($academia['direccion'] . ', ' . $academia['municipio'] . ', ' . $academia['departamento']) : 'Ubicación no especificada.'; ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-dollar-sign"></i>
                    <div class="info-text">
                        <h3>Precio</h3>
                        <p>
                            <?php
                            if (count($costos) > 0) {
                                foreach ($costos as $c) {
                                    echo htmlspecialchars($c['nombre_costo']) . ": $" . $c['precio'] . " (" . $c['unidad'] . ")<br>";
                                }
                            } else {
                                echo "Consulta los precios actualizados directamente con la administración.";
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-dumbbell"></i>
                    <div class="info-text">
                        <h3>Especialidades</h3>
                        <p>Contamos con diversas disciplinas adaptadas a todas las edades y niveles.</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-clock"></i>
                    <div class="info-text">
                        <h3>Horarios</h3>
                        <p>
                            <?php
                            if (count($horarios) > 0) {
                                foreach ($horarios as $h) {
                                    echo htmlspecialchars(ucfirst($h['dia_semana'])) . ": " . $h['hora_apertura'] . " - " . $h['hora_cierre'] . "<br>";
                                }
                            } else {
                                echo "Horarios disponibles de Lunes a Viernes. Consulta disponibilidad.";
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-phone"></i>
                    <div class="info-text">
                        <h3>Teléfono</h3>
                        <p><?php echo !empty($academia['telefono']) ? htmlspecialchars($academia['telefono']) : 'Teléfono no disponible.'; ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="acciones">
            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($academia['nombre'] . ' ' . $academia['direccion']); ?>" target="_blank" class="action-btn">
                <i class="fa-solid fa-location-dot"></i> Ver ubicación
            </a>
            <a href="tel:<?php echo $academia['telefono']; ?>" class="action-btn">
                <i class="fa-solid fa-phone"></i> Llamar
            </a>
        </section>

        <section class="sobre-academia">
            <h2>Sobre esta academia</h2>
            <p><?php echo !empty($academia['descripcion']) ? htmlspecialchars($academia['descripcion']) : 'Un espacio comprometido con el deporte y la salud, brindando entrenamiento de alta calidad para todas las edades.'; ?></p>
        </section>
    </main>

    <section class="reseñas">
        <h2>Deja tu reseña</h2>
        <p class="reseñas-texto">¿Has visitado esta academia? Comparte tu experiencia con otros usuarios.</p>
        <form class="form-reseña" action="procesar_resena.php" method="POST">
            <input type="hidden" name="id_centro" value="<?php echo $id_centro; ?>">
            <div class="campo">
                <label>Calificación</label>
                <div class="estrellas">
                    <input type="radio" name="calificacion" id="est1" value="1"><label for="est1">★</label>
                    <input type="radio" name="calificacion" id="est2" value="2"><label for="est2">★</label>
                    <input type="radio" name="calificacion" id="est3" value="3"><label for="est3">★</label>
                    <input type="radio" name="calificacion" id="est4" value="4"><label for="est4">★</label>
                    <input type="radio" name="calificacion" id="est5" value="5"><label for="est5">★</label>
                </div>
            </div>
            <div class="campo">
                <label for="reseña">Tu reseña</label>
                <textarea id="reseña" name="comentario" rows="5" placeholder="Escribe tu experiencia..."></textarea>
            </div>
            <button type="submit" class="reseña-btn">Publicar reseña</button>
            <a href="resenas_lista.php?id=<?php echo $id_centro; ?>" class="ver-resenas-btn">Ver todas las reseñas</a>
        </form>
    </section>

    <footer>
        <p>© 2026 SportX | Todos los derechos reservados.</p>
    </footer>

</body>
</html>
