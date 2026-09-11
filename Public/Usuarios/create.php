```php
<?php

header('Content-Type: application/json; charset=utf-8');

require_once("../config/conexion.php");

$database = new Database();
$db = $database->getConnection();

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido."
        ]);
        exit;
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validar campos obligatorios
    if ($nombre === '' || $correo === '' || $password === '') {
        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios."
        ]);
        exit;
    }

    // Validar nombre
    if (mb_strlen($nombre) > 100) {
        echo json_encode([
            "success" => false,
            "message" => "El nombre no puede superar los 100 caracteres."
        ]);
        exit;
    }

    // Validar correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "success" => false,
            "message" => "Ingresa un correo electrónico válido."
        ]);
        exit;
    }

    if (mb_strlen($correo) > 250) {
        echo json_encode([
            "success" => false,
            "message" => "El correo no puede superar los 250 caracteres."
        ]);
        exit;
    }

    // Validar contraseña
    if (strlen($password) < 6) {
        echo json_encode([
            "success" => false,
            "message" => "La contraseña debe tener al menos 6 caracteres."
        ]);
        exit;
    }

    // Comprobar si el correo ya existe
    $sqlVerificar = "SELECT id_usuario FROM Usuarios WHERE correo = :correo LIMIT 1";

    $stmtVerificar = $db->prepare($sqlVerificar);
    $stmtVerificar->execute([
        ":correo" => $correo
    ]);

    if ($stmtVerificar->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode([
            "success" => false,
            "message" => "El correo electrónico ya está registrado."
        ]);
        exit;
    }

    // Generar hash seguro de la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($passwordHash === false) {
        echo json_encode([
            "success" => false,
            "message" => "No fue posible procesar la contraseña."
        ]);
        exit;
    }

    // Insertar usuario
    $sql = "INSERT INTO Usuarios (nombre, correo, password_hash)
            VALUES (:nombre, :correo, :password_hash)";

    $stmt = $db->prepare($sql);

    $stmt->execute([
        ":nombre" => $nombre,
        ":correo" => $correo,
        ":password_hash" => $passwordHash
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Registro exitoso. Ya puedes iniciar sesión."
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "success" => false,
        "message" => "Ocurrió un error al registrar el usuario."
    ]);
}

