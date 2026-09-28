<?php
session_start();

// Verificación de sesión simple
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: sesion.html");
    exit;
}

// Conexión a la base de datos
require_once '../Config/Conexion.php';
$database = new Database();
$db = $database->getConnection();

// Obtener ID del deporte
$id_deporte = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_deporte <= 0) {
    echo "Por favor, ingresa un ID de deporte válido en la URL. Ejemplo: deporte.php?id=1";
    exit;
}

try {
    // Obtener deporte
    $stmt = $db->prepare("SELECT * FROM deportes WHERE id_deporte = ?");
    $stmt->execute([$id_deporte]);
    $deporte = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$deporte) {
        echo "El deporte seleccionado no existe.";
        exit;
    }

    // Obtener centros (Usando GROUP BY para evitar duplicados)
    $stmt_c = $db->prepare("
        SELECT c.*
        FROM centros_deportivos c
        JOIN centro_deporte cd ON c.id_centro = cd.id_centro
        WHERE cd.id_deporte = ?
        GROUP BY c.id_centro
    ");
    $stmt_c->execute([$id_deporte]);
    $centros = $stmt_c->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SportX | <?php echo $deporte['nombre']; ?></title>
    <link rel="stylesheet" href="../Public/CSS/lucha.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<header class="hero">
    <div class="back-container">
        <a href="../Public/index1.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Volver al menú
        </a>
    </div>
    <div class="hero-content">
        <h1><?php echo $deporte['nombre']; ?></h1>
        <p><?php echo $deporte['descripcion']; ?></p>
    </div>
</header>

<main>
    <section class="info-general">
        <h2 style="color:orange">Información General</h2>
        <div class="info-grid">
            <div class="info-box">
                <i class="fa-solid fa-location-dot"></i>
                <h3>Centros disponibles</h3>
                <p><?php echo count($centros); ?></p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-users"></i>
                <h3>Modalidad</h3>
                <p>Masculino y Femenino</p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-dollar-sign"></i>
                <h3>Precio</h3>
                <p>Variable</p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-calendar-days"></i>
                <h3>Días</h3>
                <p>Consulta el centro</p>
            </div>
        </div>
    </section>

    <form id="search-form">
        <input type="text" id="search-input" placeholder="Buscar en el sitio...">
        <button type="submit">Buscar</button>
    </form>
    <div id="search-results"></div>
</main>

<section class="academias">
    <h2>Academias disponibles</h2>
    <div class="academias-grid">
        <?php foreach ($centros as $centro): ?>
            <div class="academia-card">
                <img src="../Public/img/LUCHA-OLIMPICA.jpg" alt="Imagen">
                <h3><?php echo $centro['nombre']; ?></h3>
                <p class="ubicacion">
                    <i class="fa-solid fa-location-dot"></i>
                    <?php
                        // CORRECCIÓN: Asegurar que la ubicación siempre se muestre completa y limpia
                        $ubicacion = trim($centro['direccion'] . ', ' . $centro['municipio'] . ', ' . $centro['departamento']);
                        echo htmlspecialchars($ubicacion);
                    ?>
                </p>
                <div class="card-acciones">
                    <a href="academia_detalle.php?id=<?php echo $centro['id_centro']; ?>" class="academia-btn">
                        Ver información <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <button class="favorito-btn"><i class="fa-regular fa-heart"></i></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<footer>
    <p>© 2026 SportX | Todos los derechos reservados.</p>
</footer>

</body>
</html>
