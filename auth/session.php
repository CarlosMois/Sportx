<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

$response = [
    "success" => true,
    "autenticado" => false,
    "usuario" => null
];

if (
    isset($_SESSION["autenticado"]) &&
    $_SESSION["autenticado"] === true
) {

    $response["autenticado"] = true;

    $response["usuario"] = [
        "id_usuario" => $_SESSION["id_usuario"] ?? null,
        "nombre" => $_SESSION["nombre"] ?? null,
        "correo" => $_SESSION["correo"] ?? null,
        "rol" => $_SESSION["rol"] ?? null
    ];
}

echo json_encode($response);