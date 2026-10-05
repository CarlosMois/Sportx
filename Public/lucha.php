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

$sport_name = "Lucha Olímpica";
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
    <title>SportX | Lucha Olímpica</title>

    <link rel="stylesheet" href="../Public/CSS/lucha.css">

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
            <p>Gratis - $60</p>
        </div>

        <div class="info-box">
            <i class="fa-solid fa-calendar-days"></i>
            <h3>Días</h3>
            <p>Lunes a Viernes</p>
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
    <section class="academias">
        <h2 style="text-align: center; margin-bottom: 30px;">Academias disponibles</h2>
        <div class="academias-grid">
            <?php foreach ($centros as $centro): ?>
            <div class="academia-card">
                <img src="../Public/img/LUCHA-OLIMPICA.jpg" alt="<?php echo htmlspecialchars($centro['nombre']); ?>">
                <h3><?php echo htmlspecialchars($centro['nombre']); ?></h3>
                <p class="ubicacion">
                    <i class="fa-solid fa-location-dot"></i>
                    <?php echo htmlspecialchars($centro['direccion']); ?>
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
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

</main>

<footer>

<p>© 2026 SportX | Todos los derechos reservados.</p>

</footer>

</body>
</html>