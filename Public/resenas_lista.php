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
        .container { max-width: 800px; margin: 40px auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; font-size: 2rem; }
        .btn-volver { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #007bff; font-weight: bold; }
        .btn-volver:hover { text-decoration: underline; }

        .resena-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left: 6px solid #f1c40f;
            transition: transform 0.2s;
        }
        .resena-card:hover { transform: translateY(-5px); }

        .user-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .user-name { font-weight: bold; font-size: 1.1rem; color: #2c3e50; }
        .resena-date { font-size: 0.85rem; color: #95a5a6; }

        .stars { color: #f1c40f; font-size: 1.2rem; margin-bottom: 10px; }
        .comment { color: #555; line-height: 1.6; font-size: 1rem; }

        .no-resenas { text-align: center; color: #7f8c8d; font-style: italic; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="academia_detalle.php?id=<?php echo $id_centro; ?>" class="btn-volver">
            <i class="fa-solid fa-arrow-left"></i> Volver al centro
        </a>

        <div class="header">
            <h1>Opiniones de <?php echo htmlspecialchars($centro['nombre']); ?></h1>
        </div>

        <?php if (empty($resenas)): ?>
            <div class="no-resenas">
                <i class="fa-regular fa-face-frown" style="font-size: 3rem; display: block; margin-bottom: 10px;"></i>
                Aún no hay opiniones para este centro deportivo. ¡Sé el primero en opinar!
            </div>
        <?php else: ?>
            <?php foreach ($resenas as $r): ?>
                <div class="resena-card">
                    <div class="user-info">
                        <span class="user-name">
                            <i class="fa-solid fa-circle-user"></i> <?php echo htmlspecialchars($r['usuario_nombre']); ?>
                        </span>
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
    </div>
</body>
</html>
