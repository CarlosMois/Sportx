<?php
session_start();

if (
    !isset($_SESSION["autenticado"]) ||
    $_SESSION["autenticado"] !== true
) {
    header("Location: index.html");
    exit;
}

require_once '../Config/Conexion.php';
$database = new Database();
$db = $database->getConnection();

$sport_name = "Voleibol";
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
    <title>SportX | Voleibol</title>

    <link rel="stylesheet" href="../Public/CSS/voleibol.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        /* Estilos para la barra de búsqueda naranja */
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
            border: 3px solid #FDB913;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
        }
        .search-input:focus {
            box-shadow: 0 0 10px rgba(253, 185, 19, 0.5);
            border-color: #e69500;
        }
        .search-btn {
            background: #FDB913;
            color: #0D2860;
            border: none;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }
        .search-btn:hover {
            background: #e69500;
            color: white;
        }
        @media(max-width: 768px) {
            .search-input {
                width: 70%;
            }
        }
    </style>
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
        <h1>Voleibol</h1>
        <p>Encuentra las mejores academias y centros para practicar voleibol en El Salvador.</p>
    </div>

</header>

<main>

<section class="info-general">

    <h2>Información General</h2>

    <div class="info-grid">

        <div class="info-box">
            <i class="fa-solid fa-location-dot"></i>
            <h3>Centros disponibles</h3>
            <p><?php echo count($centros); ?></p>
        </div>

        <div class="info-box">
            <i class="fa-solid fa-users"></i>
            <h3>Modalidades</h3>
            <p>Voleibol Sala y Playa</p>
        </div>

        <div class="info-box">
            <i class="fa-solid fa-dollar-sign"></i>
            <h3>Precio</h3>
            <p>$10 - $25</p>
        </div>

        <div class="info-box">
            <i class="fa-solid fa-calendar-days"></i>
            <h3>Días</h3>
            <p>Lunes a Domingo</p>
        </div>

    </div>

</section>

<!-- BARRA DE BÚSQUEDA -->
<div class="search-container">
    <form action="" method="GET" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="Buscar centro deportivo..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="search-btn">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </form>
</div>

<?php if (!empty($search) && empty($centros)): ?>
    <div class="text-center my-5" style="text-align: center; margin: 50px 0;">
        <h3 style="color: red;">Centro deportivo no disponible :(</h3>
    </div>
<?php elseif (empty($centros)): ?>
    <div class="text-center my-5" style="text-align: center; margin: 50px 0;">
        <h3>No se encontraron centros deportivos que coincidan con tu búsqueda.</h3>
    </div>
<?php else: ?>
    <?php foreach ($centros as $centro): ?>
    <section class="card">
        <h2><?php echo htmlspecialchars($centro['nombre']); ?></h2>

        <p>
        <i class="fa-solid fa-location-dot" style="color: orange;"></i>
        <strong>Ubicación:</strong><br>
        <?php echo htmlspecialchars($centro['direccion']); ?>
        </p>

        <p>
        <i class="fa-solid fa-info-circle" style="color: orange;"></i>
        <strong>Descripción:</strong><br>
        <?php echo htmlspecialchars($centro['descripcion'] ?? 'Información no disponible'); ?>
        </p>

        <p>
        <i class="fa-solid fa-phone" style="color: orange;"></i>
        <strong>Teléfono:</strong><br>
        <?php echo htmlspecialchars($centro['telefono'] ?? 'No disponible'); ?>
        </p>

        <p>
        <i class="fa-solid fa-envelope" style="color: orange;"></i>
        <strong>Correo:</strong><br>
        <?php echo htmlspecialchars($centro['correo'] ?? 'No disponible'); ?>
        </p>

        <div class="buttons">
            <a href="https://maps.google.com/?q=<?php echo urlencode($centro['nombre']); ?>" target="_blank" class="btn">
                <i class="fa-solid fa-location-dot" style="color: orange;"></i>
                Ver ubicación
            </a>
            <a href="tel:<?php echo $centro['telefono']; ?>" class="btn">
                <i class="fa-solid fa-phone" style="color: orange;"></i>
                Llamar
            </a>
        </div>
    </section>
    <?php endforeach; ?>
<?php endif; ?>

</main>

<footer>

<p>© 2026 SportX | Todos los derechos reservados.</p>

</footer>

</body>
</html>