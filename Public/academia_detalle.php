<?php
session_start();
require_once 'includes/lang.php';

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

    // Lógica para obtener estadísticas de reseñas (promedio y total)
    try {
        $stmt_stats = $db->prepare("SELECT AVG(calificacion) as promedio, COUNT(*) as total FROM resenas WHERE id_centro = ?");
        $stmt_stats->execute([$id_centro]);
        $stats_resenas = $stmt_stats->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $stats_resenas = ['promedio' => 0, 'total' => 0];
    }

} catch (Exception $e) {
    die("Error de base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportX | <?php echo htmlspecialchars($academia['nombre']); ?></title>
    <link rel="stylesheet" href="../Public/CSS/academias.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

    <header class="academia-header" style="background-image: linear-gradient(rgba(13,40,96,.78), rgba(13,40,96,.78)), url('<?php echo !empty($imagenes[0]['imagen_url']) ? "../" . $imagenes[0]['imagen_url'] : "../Public/img/logo sin fondo.png"; ?>'); background-size: cover; background-position: center;">
        <div class="back-container">
            <?php
            // Lógica para determinar el ID del deporte al que pertenece la academia
            try {
                $stmt_dep_id = $db->prepare("SELECT id_deporte FROM centro_deporte WHERE id_centro = ? LIMIT 1");
                $stmt_dep_id->execute([$id_centro]);
                $dep_id_res = $stmt_dep_id->fetch(PDO::FETCH_ASSOC);

                $url_volver = "deporte.php"; // fallback

                $nombre_volver = "Deportes";

                if ($dep_id_res) {
                    $id_deporte = $dep_id_res['id_deporte'];
                    $url_volver = "deporte.php?id=" . $id_deporte;

                    // Obtener el nombre del deporte para el texto del botón
                    $stmt_dep_name = $db->prepare("SELECT nombre FROM deportes WHERE id_deporte = ?");
                    $stmt_dep_name->execute([$id_deporte]);
                    $dep_name_res = $stmt_dep_name->fetch(PDO::FETCH_ASSOC);
                    if ($dep_name_res) {
                        $nombre_volver = $dep_name_res['nombre'];
                    }
                }
            ?>
            <a href="<?php echo $url_volver; ?>" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> <?php echo __t('ui_general.back'); ?> <?php echo htmlspecialchars($nombre_volver); ?>
            </a>
            <?php } catch (Exception $e) { ?>
            <a href="deportes.php" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> <?php echo __t('ui_general.back'); ?>
            </a>
            <?php } ?>
        </div>

        <div class="header-content">
            <h1><?php echo htmlspecialchars($academia['nombre']); ?></h1>
            <p><?php echo !empty($academia['descripcion']) ? htmlspecialchars($academia['descripcion']) : 'Centro deportivo dedicado a la excelencia atlética y la formación de nuevos talentos.'; ?></p>
        </div>
    </header>

    <main>
        <section class="galeria">
            <h2><?php echo __t('ui_academy.gallery'); ?></h2>
            <div class="carrusel">
                <div class="carrusel-track">
                    <?php if (count($imagenes) > 0): ?>
                        <?php foreach ($imagenes as $img): ?>
                            <div class="imagen-carrusel">
                                <img src="../<?php echo htmlspecialchars($img['imagen_url']); ?>" alt="Imagen">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="imagen-carrusel">
                            <img src="../Public/img/logo sin fondo.png" alt="Imagen por defecto">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="informacion">
            <h2><?php echo __t('ui_academy.academy_info'); ?></h2>
            <div class="info-grid">
                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="info-text">
                        <h3><?php echo __t('ui_general.location'); ?></h3>
                        <p><?php echo !empty($academia['direccion']) ? htmlspecialchars($academia['direccion'] . ', ' . $academia['municipio'] . ', ' . $academia['departamento']) : __t('ui_general.location_not_specified'); ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-dollar-sign"></i>
                    <div class="info-text">
                        <h3>Price</h3>
                        <p>
                            <?php
                            if (count($costos) > 0) {
                                foreach ($costos as $c) {
                                    echo htmlspecialchars($c['nombre_costo']) . ": $" . $c['precio'] . " (" . __t($c['unidad']) . ")<br>";
                                }
                            } else {
                                echo __t('ui_academy.check_prices');
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-dumbbell"></i>
                    <div class="info-text">
                        <h3>Specialties</h3>
                        <p>We have various disciplines adapted to all ages and levels.</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-clock"></i>
                    <div class="info-text">
                        <h3>Schedules</h3>
                        <p>
                            <?php
                            if (count($horarios) > 0) {
                                foreach ($horarios as $h) {
                                    echo __t($h['dia_semana']) . ": " . $h['hora_apertura'] . " - " . $h['hora_cierre'] . "<br>";
                                }
                            } else {
                                echo "Schedules available Monday to Friday. Please check availability.";
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-phone"></i>
                    <div class="info-text">
                        <h3><?php echo __t('ui_general.phone'); ?></h3>
                        <p><?php echo !empty($academia['telefono']) ? htmlspecialchars($academia['telefono']) : __t('ui_general.phone_not_available'); ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-star"></i>
                    <div class="info-text">
                        <h3><?php echo __t('ui_academy.reviews'); ?></h3>
                        <p>
                            <?php
                            if ($stats_resenas['total'] > 0) {
                                echo "⭐ " . round($stats_resenas['promedio'], 1) . " (" . $stats_resenas['total'] . " reviews)";
                            } else {
                                echo __t('ui_academy.no_reviews');
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <section class="acciones">
            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($academia['nombre'] . ' ' . $academia['direccion']); ?>" target="_blank" class="action-btn">
                <i class="fa-solid fa-location-dot"></i> <?php echo __t('ui_general.view_location'); ?>
            </a>
            <a href="tel:<?php echo $academia['telefono']; ?>" class="action-btn">
                <i class="fa-solid fa-phone"></i> Call
            </a>
        </section>

        <section class="sobre-academia">
            <h2>About this academy</h2>
            <p><?php echo !empty($academia['descripcion']) ? htmlspecialchars($academia['descripcion']) : 'A space committed to sports and health, providing high-quality training for all ages.'; ?></p>
        </section>
    </main>

    <section class="reseñas">
        <h2><?php echo __t('ui_academy.leave_review'); ?></h2>
        <p class="reseñas-texto"><?php echo __t('ui_academy.review_prompt'); ?></p>
        <form class="form-reseña" action="procesar_resena.php" method="POST">
            <input type="hidden" name="id_centro" value="<?php echo $id_centro; ?>">
            <div class="campo">
                <label><?php echo __t('ui_academy.rating'); ?></label>
                <div class="estrellas">
                    <input type="radio" name="calificacion" id="est5" value="5"><label for="est5">★</label>
                    <input type="radio" name="calificacion" id="est4" value="4"><label for="est4">★</label>
                    <input type="radio" name="calificacion" id="est3" value="3"><label for="est3">★</label>
                    <input type="radio" name="calificacion" id="est2" value="2"><label for="est2">★</label>
                    <input type="radio" name="calificacion" id="est1" value="1"><label for="est1">★</label>
                </div>
            </div>
            <div class="campo">
                <label for="reseña"><?php echo __t('ui_academy.your_review'); ?></label>
                <textarea id="reseña" name="comentario" rows="5" placeholder="<?php echo __t('ui_academy.review_placeholder'); ?>"></textarea>
            </div>
            <button type="submit" class="reseña-btn"><?php echo __t('ui_academy.post_review'); ?></button>
            <a href="resenas_lista.php?id=<?php echo $id_centro; ?>" class="ver-resenas-btn"><?php echo __t('ui_academy.view_all_reviews'); ?></a>
        </form>
    </section>

    <footer>
        <p>© 2026 SportX | All rights reserved.</p>
    </footer>

</body>
</html>
