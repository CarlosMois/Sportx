<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once("../config/conexion.php");

$response = [
    "success" => false,
    "message" => ""
];

try {

    // Verificar método HTTP
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response["message"] = "Método de solicitud no permitido.";
        echo json_encode($response);
        exit;
    }

    // Crear conexión
    $database = new Database();
    $db = $database->getConnection();

    // Obtener datos
    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validar campos obligatorios
    if ($correo === "" || $password === "") {
        $response["message"] = "El correo y la contraseña son obligatorios.";
        echo json_encode($response);
        exit;
    }

    // Validar correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $response["message"] = "El correo electrónico no es válido.";
        echo json_encode($response);
        exit;
    }

    // Buscar usuario por correo
    $sql = "
        SELECT
            id_usuario,
            nombre,
            correo,
            password_hash,
            rol,
            estado
        FROM Usuarios
        WHERE correo = :correo
        LIMIT 1
    ";

    $stmt = $db->prepare($sql);

    $stmt->execute([
        ":correo" => $correo
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Usuario inexistente
    if (!$usuario) {
        $response["message"] = "Correo o contraseña incorrectos.";
        echo json_encode($response);
        exit;
    }

    // Verificar estado de la cuenta
    if ($usuario["estado"] !== "activo") {
        $response["message"] = "Tu cuenta se encuentra suspendida.";
        echo json_encode($response);
        exit;
    }

    // Verificar contraseña
    if (!password_verify($password, $usuario["password_hash"])) {
        $response["message"] = "Correo o contraseña incorrectos.";
        echo json_encode($response);
        exit;
    }

    // Regenerar ID de sesión por seguridad
    session_regenerate_id(true);

    // Guardar información necesaria en la sesión
    $_SESSION["autenticado"] = true;
    $_SESSION["id_usuario"] = $usuario["id_usuario"];
    $_SESSION["nombre"] = $usuario["nombre"];
    $_SESSION["correo"] = $usuario["correo"];
    $_SESSION["rol"] = $usuario["rol"];

    // Respuesta
    $response["success"] = true;
    $response["message"] = "Inicio de sesión exitoso.";

    $response["usuario"] = [
        "id_usuario" => $usuario["id_usuario"],
        "nombre" => $usuario["nombre"],
        "correo" => $usuario["correo"],
        "rol" => $usuario["rol"]
    ];

} catch (PDOException $e) {

    $response["message"] = "Error de base de datos.";

} catch (Exception $e) {

    $response["message"] = "Ocurrió un error inesperado.";

}

echo json_encode($response);