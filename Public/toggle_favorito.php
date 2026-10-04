<?php
session_start();
require_once '../Config/Conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_centro = isset($_POST['id_centro']) ? intval($_POST['id_centro']) : 0;

if ($id_centro <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de centro inválido']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Verificar si ya es favorito
    $stmt = $db->prepare("SELECT id_favorito FROM Favoritos WHERE id_usuario = ? AND id_centro = ?");
    $stmt->execute([$id_usuario, $id_centro]);
    $favorito = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($favorito) {
        // Quitar favorito
        $stmt_del = $db->prepare("DELETE FROM Favoritos WHERE id_favorito = ?");
        $stmt_del->execute([$favorito['id_favorito']]);
        echo json_encode(['success' => true, 'status' => 'removed']);
    } else {
        // Agregar favorito
        $stmt_ins = $db->prepare("INSERT INTO Favoritos (id_usuario, id_centro) VALUES (?, ?)");
        $stmt_ins->execute([$id_usuario, $id_centro]);
        echo json_encode(['success' => true, 'status' => 'added']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
