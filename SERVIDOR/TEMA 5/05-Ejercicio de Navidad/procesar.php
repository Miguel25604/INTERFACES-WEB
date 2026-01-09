<?php
header("Content-Type: text/plain; charset=utf-8");

// -------------------------------------------------------------
// 1) POST x-www-form-urlencoded
// -------------------------------------------------------------
if (isset($_POST["accion"]) && $_POST["accion"] === "post_numero") {
    echo "POST recibido. Número = {$_POST["numero"]}, Texto = {$_POST["texto"]}";
    exit;
}

// -------------------------------------------------------------
// 2) GET
// -------------------------------------------------------------
if (isset($_GET["accion"]) && $_GET["accion"] === "get_numero") {
    echo "GET recibido. Número = {$_GET["numero"]}, Texto = {$_GET["texto"]}";
    exit;
}

// -------------------------------------------------------------
// 3 y 4) JSON
// -------------------------------------------------------------
$rawJSON = file_get_contents("php://input");
$data = json_decode($rawJSON, true);

if (isset($data["accion"]) && $data["accion"] === "json_datos") {
    $edad = $data["persona"]["numero"];
    $nombre = $data["persona"]["texto"];
    echo "Hola $nombre, tienes $edad años.";
    exit;
}

if (isset($data["accion"]) && $data["accion"] === "json_datos_json") {
    header("Content-Type: application/json; charset=utf-8");

    $edad = $data["persona"]["numero"];
    $nombre = $data["persona"]["texto"];

    echo json_encode([
        "saludo" => "Hola $nombre",
        "mensaje" => ($edad >= 18) ? "Eres mayor de edad" : "Eres menor de edad"
    ]);
    exit;
}


// 5) COOKIE-Servidor LEE cookie del cliente

if (isset($_GET["accion"]) && $_GET["accion"] === "leer_cookie") {

    if (isset($_COOKIE["usuario"])) {
        echo "El servidor recibió la cookie = " . $_COOKIE["usuario"];
    } else {
        echo "No se recibió ninguna cookie";
    }
    exit;
}

// 6) COOKIE-COOKIE (Servidor crea cookie)

if (isset($_GET["accion"]) && $_GET["accion"] === "servidor_set_cookie") {

    $nombre = $_COOKIE["usuario"] ?? "desconocido";

    setcookie("mensaje_servidor",
        "Hola $nombre. Esta es la nueva cookie creada por el servidor con la cookie enviada al servidor",
        time() + 3600,
        "/"
    );

    echo "Cookie enviada por el servidor.";
    exit;
}

// Si no se reconoce la acción
echo "Acción no reconocida.";
