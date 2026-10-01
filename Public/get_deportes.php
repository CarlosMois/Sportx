<?php
require_once 'C:\laragon\www\Sportx\Config\Conexion.php';
$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT id_deporte, nombre FROM deportes");
$deportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($deportes as $d) {
    echo $d['id_deporte'] . " | " . $d['nombre'] . "\n";
}
?>
