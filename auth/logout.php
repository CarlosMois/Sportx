<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

$response = [
    "success" => false,
    "message" => ""
];

try {

    // Vaciar variables de sesión
    $_SESSION = [];

    // Eliminar cookie de sesión si existe
    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            "",
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destruir sesión
    session_destroy();

    $response["success"] = true;
    $response["message"] = "Sesión cerrada correctamente.";

} catch (Exception $e) {

    $response["message"] = "No fue posible cerrar la sesión.";

}

echo json_encode($response);