<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (
    !isset($_SESSION["autenticado"]) ||
    $_SESSION["autenticado"] !== true
) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "autenticado" => false,
        "message" => "Acceso no autorizado."
    ]);

    exit;
}

echo json_encode([
    "success" => true,
    "autenticado" => true,
    "message" => "Acceso autorizado.",
    "usuario" => [
        "id_usuario" => $_SESSION["id_usuario"] ?? null,
        "nombre" => $_SESSION["nombre"] ?? null,
        "correo" => $_SESSION["correo"] ?? null,
        "rol" => $_SESSION["rol"] ?? null
    ]
]);