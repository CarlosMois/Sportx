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

// Obtener ID del usuario de la sesión
$id_usuario = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;

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

    // LÓGICA DE IMAGEN PARA EL ENCABEZADO (HERO)
    $nombre_deporte = $deporte['nombre'];
    $hero_img = '../Public/img/logo sin fondo.png'; // Default

    if (stripos($nombre_deporte, 'Soccer') !== false || stripos($nombre_deporte, 'Futbol') !== false || stripos($nombre_deporte, 'Fútbol') !== false) {
        $hero_img = '../Public/img/Futbol/futbol1.jpg';
    } elseif (stripos($nombre_deporte, 'Volleyball') !== false || stripos($nombre_deporte, 'Voleibol') !== false) {
        $hero_img = '../Public/img/Bole/voleibol10.jpg';
    } elseif (stripos($nombre_deporte, 'Basketball') !== false || stripos($nombre_deporte, 'Basquet') !== false || stripos($nombre_deporte, 'Baloncesto') !== false) {
        $hero_img = '../Public/img/basquet/basquet1.jpg';
    } elseif (stripos($nombre_deporte, 'Ballet') !== false) {
        $hero_img = '../Public/img/Chopiniana_Baku.jpg';
    } elseif (stripos($nombre_deporte, 'Wrestling') !== false || stripos($nombre_deporte, 'Lucha') !== false) {
        $hero_img = '../Public/img/lucha/lucha1.jpeg';
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SportX | <?php echo $deporte['nombre']; ?></title>
    <link rel="stylesheet" href="../Public/CSS/lucha.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<header class="hero" style="background-image: linear-gradient(rgba(13,40,96,.78), rgba(13,40,96,.78)), url('<?php echo $hero_img; ?>'); background-size: cover; background-position: center;">
    <div class="back-container">
        <a href="../Public/index1.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Back to Menu
        </a>
    </div>
    <div class="hero-content">
        <h1><?php echo $deporte['nombre']; ?></h1>
        <p><?php echo $deporte['descripcion']; ?></p>
    </div>
</header>


<main>
    <section class="info-general">
        <h2 style="color:orange">General Information</h2>
        <div class="info-grid">
            <div class="info-box">
                <i class="fa-solid fa-location-dot"></i>
                <h3>Available Centers</h3>
                <p><?php echo count($centros); ?></p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-users"></i>
                <h3>Modality</h3>
                <p>Male and Female</p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-dollar-sign"></i>
                <h3>Price</h3>
                <p>Variable</p>
            </div>
            <div class="info-box">
                <i class="fa-solid fa-calendar-days"></i>
                <h3>Days</h3>
                <p>Check with the center</p>
            </div>
        </div>
    </section>
</main>

<section class="academias">
    <h2>Available Academies</h2>

    <form id="search-form">
        <div class="search-input-container">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="search-input" placeholder="Search sports center...">
        </div>
        <button type="submit">Search</button>
    </form>
    <div id="search-results"></div>

    <div class="academias-grid">
        <?php foreach ($centros as $centro):
            // Verificar si el usuario ya tiene este centro en favoritos
            $is_fav = false;
            if ($id_usuario > 0) {
                $stmt_fav = $db->prepare("SELECT 1 FROM Favoritos WHERE id_usuario = ? AND id_centro = ?");
                $stmt_fav->execute([$id_usuario, $centro['id_centro']]);
                if ($stmt_fav->fetch()) {
                    $is_fav = true;
                }
            }

            // OBTENER IMAGEN DINÁMICA DE LA BASE DE DATOS
            $stmt_img = $db->prepare("SELECT imagen_url FROM imagenes WHERE id_centro = ? LIMIT 1");
            $stmt_img->execute([$centro['id_centro']]);
            $img_res = $stmt_img->fetch(PDO::FETCH_ASSOC);

            // Si no hay imagen en la BD, usar una por defecto
            $img_path = $img_res ? $img_res['imagen_url'] : '../Public/img/logo sin fondo.png';
        ?>
            <div class="academia-card">
                <img src="../<?php echo $img_path; ?>" alt="Image" style="width: 100%; height: 200px; object-fit: cover; object-position: center; image-rendering: -webkit-optimize-contrast;">
                <h3><?php echo $centro['nombre']; ?></h3>
                <p class="ubicacion">
                    <i class="fa-solid fa-location-dot"></i>
                    <?php
                        $ubicacion = trim($centro['direccion'] . ', ' . $centro['municipio'] . ', ' . $centro['departamento']);
                        echo htmlspecialchars($ubicacion);
                    ?>
                </p>
                <div class="card-acciones">
                    <a href="academia_detalle.php?id=<?php echo $centro['id_centro']; ?>" class="academia-btn">
                        View information <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <button class="favorito-btn <?php echo $is_fav ? 'activo' : ''; ?>" data-id="<?php echo $centro['id_centro']; ?>">
                        <i class="<?php echo $is_fav ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
    });

    document.getElementById('search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('.academia-card');

        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const location = card.querySelector('.ubicacion').textContent.toLowerCase();

            if (name.includes(searchTerm) || location.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Lógica para los Favoritos
    document.querySelectorAll('.favorito-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCentro = this.getAttribute('data-id');
            const icon = this.querySelector('i');

            fetch('toggle_favorito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id_centro=' + idCentro
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.status === 'added') {
                        this.classList.add('activo');
                        icon.classList.replace('fa-regular', 'fa-solid');
                    } else {
                        this.classList.remove('activo');
                        icon.classList.replace('fa-solid', 'fa-regular');
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

<footer>
    <p>© 2026 SportX | All rights reserved.</p>
</footer>

</body>
</html>
