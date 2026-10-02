<?php
session_start();
require_once '../Config/Conexion.php';

// 1. Verificación de Autenticación
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: sesion.html");
    exit;
}

// 2. Validación de Entradas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_centro = isset($_POST['id_centro']) ? intval($_POST['id_centro']) : 0;
    $calificacion = isset($_POST['calificacion']) ? intval($_POST['calificacion']) : 0;
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

    // Usamos id_usuario de la sesión
    $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

    if ($id_centro <= 0 || $calificacion < 1 || $calificacion > 5 || empty($comentario) || $id_usuario <= 0) {
        header("Location: academia_detalle.php?id=$id_centro&error=datos_invalidos");
        exit;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

        // 3. Inserción de la Reseña
        $sql = "INSERT INTO resenas (id_usuario, id_centro, calificacion, comentario, fecha) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);

        if ($stmt->execute([$id_usuario, $id_centro, $calificacion, $comentario])) {
            header("Location: academia_detalle.php?id=$id_centro&success=resena_publicada");
        } else {
            throw new Exception("Error al insertar la reseña.");
        }
    } catch (Exception $e) {
        header("Location: academia_detalle.php?id=$id_centro&error=db_error");
    }
} else {
    header("Location: deportes.php");
}
exit;
?>
