<?php
session_start();

if (
    !isset($_SESSION["autenticado"]) ||
    $_SESSION["autenticado"] !== true
) {
    header("Location: sesion.html");
    exit;
}

require_once '../Config/Conexion.php';
$database = new Database();
$db = $database->getConnection();

$sport_name = "Fútbol";
$search = $_GET['search'] ?? '';

try {
    $sql = "SELECT cd.*
            FROM centros_deportivos cd
            JOIN centro_deporte cdp ON cd.id_centro = cdp.id_centro
            JOIN deportes d ON cdp.id_deporte = d.id_deporte
            WHERE d.nombre = :sport_name";

    if (!empty($search)) {
        $sql .= " AND (cd.nombre LIKE :search OR cd.direccion LIKE :search)";
    }

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':sport_name', $sport_name);
    if (!empty($search)) {
        $search_param = "%$search%";
        $stmt->bindParam(':search', $search_param);
    }
    $stmt->execute();
    $centros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $centros = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportX | Fútbol</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS INTERNO -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f5f7fa;
            color: #333;
            background:linear-gradient(90deg, #007AA2 0%, #0D2860 100%)
        }

        .hero {
            background:
            linear-gradient(rgba(13, 40, 96, 0.80), rgba(13, 40, 96, 0.80)),
            url("../img/Futbol/futbol1.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            text-align: center;
            padding: 120px 20px;
        }

        .hero h1 {
            font-size: 58px;
            margin-bottom: 20px;
            color: #FFD700;
        }

        .hero p {
            font-size: 22px;
            max-width: 900px;
            margin: auto;
            line-height: 1.8;
        }

        .info-general {
            width: 90%;
            max-width: 1200px;
            margin: 60px auto;
        }

        .info-general h2 {
            text-align: center;
            color: orange;
            font-size: 40px;
            margin-bottom: 45px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 30px;
        }

        .info-box {
            background: white;
            padding: 35px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transition: .3s;
            border-top: 4px solid #007AA2;
        }

        .info-box i {
            font-size: 45px;
            color: #FDB913;
            margin-bottom: 18px;
        }

        .info-box h3 {
            color: #0D2860;
            margin-bottom: 15px;
            font-size: 24px;
        }

        /* ===========================
                  BARRA DE BÚSQUEDA (NARANJA)
        =========================== */
        .search-container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            text-align: center;
        }
        .search-form {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .search-input {
            width: 60%;
            padding: 15px 20px;
            font-size: 1.2rem;
            border: 3px solid #FDB913 !important;
            border-radius: 10px;
            outline: none;
        }
        .search-btn {
            background-color: #FDB913 !important;
            color: #0D2860 !important;
            border: none;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
        }

        .card {
            width: 90%;
            max-width: 1200px;
            margin: 55px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .card h2 {
            color: #0D2860;
            font-size: 34px;
            border-left: 8px solid #FDB913;
            padding-left: 18px;
            margin-bottom: 35px;
        }

        .buttons {
            margin-top: 40px;
        }

        .btn {
            display: inline-block;
            background: #0D2860;
            color: white;
            text-decoration: none;
            padding: 15px 28px;
            border-radius: 10px;
            margin-right: 15px;
            transition: .3s;
            font-size: 18px;
        }

        footer {
            background: linear-gradient(rgba(13, 40, 96, 0.85), rgba(13, 40, 96, 0.85)), url("../img/img futbol.jpg");
            background-size: cover;
            color: white;
            text-align: center;
            padding: 60px 20px;
            margin-top: 70px;
        }

        .back-btn {
            display: inline-block;
            background: #FFD700;
            color: #0D2860;
            text-decoration: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<header class="hero">
    <div class="back-container" style="width: 90%; max-width: 1200px; margin: 25px auto; text-align: left;">
        <a href="/Sportx/Public/index1.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Volver al menú
        </a>
    </div>
    <div class="hero-content">
        <h1>Fútbol</h1>
        <p>Encuentra las mejores academias de desarrollo, escuelas de alto rendimiento y centros de entrenamiento en El Salvador.</p>
    </div>
</header>

<main>
    <section class="info-general">
        <h2>Información General</h2>
        <div class="info-grid">
            <div class="info-box">
                <i class="fa-solid fa-location-dot" style="color: orange;"></i>
                <h3>Centros disponibles</h3>
                <p><?php echo count($centros); ?> Academias</p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-users" style="color: orange;"></i>
                <h3>Modalidad</h3>
                <p>Fútbol 11 y Fútbol 8</p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-dollar-sign" style="color: orange;"></i>
                <h3>Precio</h3>
                <p>Consultar directamente</p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-calendar-days" style="color: orange;"></i>
                <h3>Días</h3>
                <p>Lunes a Sábado</p>
            </div>
        </div>
    </section>

    <div class="search-container">
        <form action="" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Buscar centro deportivo..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="search-btn">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
        </form>
    </div>

    <?php if (!empty($search) && empty($centros)): ?>
        <div style="text-align: center; margin: 50px 0;">
            <h3 style="color: red; font-weight: bold; font-size: 2rem;">Centro deportivo no disponible :(</h3>
        </div>
    <?php elseif (empty($centros)): ?>
        <div style="text-align: center; margin: 50px 0;">
            <h3>No se encontraron centros deportivos.</h3>
        </div>
    <?php else: ?>
        <?php foreach ($centros as $centro): ?>
        <section class="card">
            <h2><?php echo htmlspecialchars($centro['nombre']); ?></h2>
            <p><i class="fa-solid fa-location-dot" style="color: orange;"></i> <strong>Ubicación:</strong><br><?php echo htmlspecialchars($centro['direccion']); ?></p>
            <p><i class="fa-solid fa-info-circle" style="color: orange;"></i> <strong>Descripción:</strong><br><?php echo htmlspecialchars($centro['descripcion'] ?? 'Información no disponible'); ?></p>
            <p><i class="fa-solid fa-phone" style="color: orange;"></i> <strong>Teléfono:</strong><br><?php echo htmlspecialchars($centro['telefono'] ?? 'No disponible'); ?></p>
            <div class="buttons">
                <a href="https://maps.google.com/?q=<?php echo urlencode($centro['nombre']); ?>" target="_blank" class="btn">Ver ubicación</a>
                <a href="tel:<?php echo $centro['telefono']; ?>" class="btn">Llamar</a>
            </div>
        </section>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer>
    <p>© 2026 SportX | Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>