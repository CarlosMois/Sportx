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

$sport_name = "Ballet";
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
    <title>SportX | Ballet</title>

    <link rel="stylesheet" href="../Public/CSS/ballet.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
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
           Back to the menu
        </a>

    </div>

    <div class="hero-content">

        <h1>Ballet</h1>

        <p>Find the best ballet academies in El Salvador.</p>

    </div>

</header>

<main>

<section class="info-general">

<h2>General Information</h2>

 <div>

<i class="heart fa icon-heart"></i>
</div>

<div class="info-grid">

<div class="info-box">
<i class="fa-solid fa-location-dot"></i>
<h3>Available Centers</h3>
<p><?php echo count($centros); ?> Academies</p>
</div>

<div class="info-box">
<i class="fa-solid fa-users"></i>
<h3>Modality</h3>
<p>Children, Youth, and Adults</p>
</div>

<div class="info-box">
<i class="fa-solid fa-dollar-sign"></i>
<h3>Price</h3>
<p>Free - $60</p>
</div>

<div class="info-box">
<i class="fa-solid fa-calendar-days"></i>
<h3>Days</h3>
<p>Monday to Saturday</p>
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

        <p>
        <i class="fa-solid fa-location-dot"></i>
        <strong>Location:</strong><br>
        <?php echo htmlspecialchars($centro['direccion']); ?>
        </p>

        <p>
        <i class="fa-solid fa-info-circle"></i>
        <strong>Description:</strong><br>
        <?php echo htmlspecialchars($centro['descripcion'] ?? 'Información no disponible'); ?>
        </p>

        <p>
        <i class="fa-solid fa-phone"></i>
        <strong>Phone:</strong><br>
        <?php echo htmlspecialchars($centro['telefono'] ?? 'No disponible'); ?>
        </p>

        <p>
        <i class="fa-solid fa-envelope"></i>
        <strong>Email:</strong><br>
        <?php echo htmlspecialchars($centro['correo'] ?? 'No disponible'); ?>
        </p>

        <div class="buttons">
            <a href="https://maps.google.com/?q=<?php echo urlencode($centro['nombre']); ?>" target="_blank" class="btn">
                <i class="fa-solid fa-location-dot"></i>
                View Location
            </a>
            <a href="tel:<?php echo $centro['telefono']; ?>" class="btn">
                <i class="fa-solid fa-phone"></i>
                Call
            </a>
        </div>
    </section>
    <?php endforeach; ?>
<?php endif; ?>

</main>

<footer>
<p>©2026 SportX | All Rights Reserved.</p>
</footer>

</body>
</html>