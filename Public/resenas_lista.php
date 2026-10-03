<?php
session_start();
require_once '../Config/Conexion.php';

// 1. Obtener el ID del centro desde la URL
$id_centro = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_centro <= 0) {
    echo "ID de centro no válido.";
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // 2. Obtener el nombre del centro deportivo para el título
    $stmt_centro = $db->prepare("SELECT nombre FROM centros_deportivos WHERE id_centro = ?");
    $stmt_centro->execute([$id_centro]);
    $centro = $stmt_centro->fetch(PDO::FETCH_ASSOC);

    if (!$centro) {
        die("El centro deportivo no existe.");
    }

    // 3. Consultar las reseñas uniendo con la tabla Usuarios para obtener el nombre
    // Usamos un INNER JOIN para asegurar que solo mostramos reseñas de usuarios existentes
    $sql = "SELECT u.nombre as usuario_nombre, r.calificacion, r.comentario, r.fecha
            FROM resenas r
            INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario
            WHERE r.id_centro = ?
            ORDER BY r.fecha DESC";

    $stmt_resenas = $db->prepare($sql);
    $stmt_resenas->execute([$id_centro]);
    $resenas = $stmt_resenas->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error en el servidor: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opiniones - <?php echo htmlspecialchars($centro['nombre']); ?></title>
    <link rel="stylesheet" href="CSS/academias.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }

        /* Header Estilo Academia */
        .academia-header {
            background: linear-gradient(rgba(13, 40, 96, 0.8), rgba(13, 40, 96, 0.8)), url('img/logo sin fondo.png');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header-content h1 { font-size: 2.5rem; margin-bottom: 10px; color: #FFD700; }
        .header-content p { font-size: 1.1rem; opacity: 0.9; }

        .back-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            text-align: center;
        }
        .back-btn {
            display: inline-block;
            background: #FFD700;
            color: #0D2860;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
            transition: .3s;
        }
        .back-btn:hover { background: #0D2860; color: white; }

        .container { max-width: 800px; margin: 0 auto; padding: 20px; }

        .resena-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 6px solid #f1c40f;
            transition: transform 0.2s;
        }
        .resena-card:hover { transform: translateY(-5px); }

        .user-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #f1c40f; }
        .user-name { font-weight: bold; font-size: 1.1rem; color: #2c3e50; }
        .resena-date { font-size: 0.85rem; color: #95a5a6; }

        .stars { color: #f1c40f; font-size: 1.3rem; margin-bottom: 12px; }
        .comment { color: #555; line-height: 1.6; font-size: 1rem; font-style: italic; }

        .no-resenas { text-align: center; color: #7f8c8d; font-style: italic; margin-top: 50px; }

        .main-footer {
            background: linear-gradient(rgba(13, 40, 96, 0.9), rgba(13, 40, 96, 0.9)), url('img/logo sin fondo.png');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 40px 20px;
            margin-top: 50px;
        }
    </style>

</head>
<body>
    <header class="academia-header">
        <div class="back-container">
            <a href="academia_detalle.php?id=<?php echo $id_centro; ?>" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Volver al centro
            </a>
        </div>

        <div class="header-content">
            <h1>Opiniones de <?php echo htmlspecialchars($centro['nombre']); ?></h1>
            <p>Descubre lo que otros usuarios opinan sobre este centro deportivo</p>
        </div>
    </header>


        <?php if (empty($resenas)): ?>
            <div class="no-resenas">
                <i class="fa-regular fa-face-frown" style="font-size: 3rem; display: block; margin-bottom: 10px;"></i>
                Aún no hay opiniones para este centro deportivo. ¡Sé el primero en opinar!
            </div>
        <?php else: ?>
            <?php foreach ($resenas as $r): ?>
                <div class="resena-card">
                    <div class="user-info">
                        <div class="user-profile">
                            <img src="img/fotoperfil.jpg" alt="Perfil" class="user-avatar" id="resena-avatar">
                            <span class="user-name"><?php echo htmlspecialchars($r['usuario_nombre']); ?></span>
                        </div>
                        <span class="resena-date"><?php echo date('d M, Y', strtotime($r['fecha'])); ?></span>
                    </div>

                    <div class="stars">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $r['calificacion']) ? '★' : '☆';
                        }
                        ?>
                    </div>

                    <div class="comment">
                        "<?php echo nl2br(htmlspecialchars($r['comentario'])); ?>"
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <footer class="main-footer">
        <p>© 2026 SportX | Todos los derechos reservados.</p>
    </footer>
    <script src="JS/resenas_perfil.js"></script>
</body>
</html>