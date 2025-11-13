<?php
session_start();

// --- Definimos los usuarios registrados (usuario, contraseña, rol) ---
$usuarios = [
    ["usuario" => "admin", "password" => "admin123", "rol" => "admin"],
    ["usuario" => "director", "password" => "dir123", "rol" => "director"],
    ["usuario" => "profesor", "password" => "prof123", "rol" => "profesor"],
    ["usuario" => "alumno", "password" => "alu123", "rol" => "alumno"]
];

// --- Comprobar si existen usuarios añadidos mediante cookie ---
if (isset($_COOKIE["usuarios_nuevos"])) {
    $usuarios_nuevos = json_decode($_COOKIE["usuarios_nuevos"], true);
    if (is_array($usuarios_nuevos)) {
        $usuarios = array_merge($usuarios, $usuarios_nuevos);
    }
}

// --- Si ya existe la cookie, saltamos directamente al panel ---
if (isset($_COOKIE["usuario"]) && isset($_COOKIE["password"])) {
    $usuario_cookie = $_COOKIE["usuario"];
    $password_cookie = $_COOKIE["password"];

    foreach ($usuarios as $user) {
        if ($user["usuario"] === $usuario_cookie && $user["password"] === $password_cookie) {
            $_SESSION["usuario"] = $user["usuario"];
            $_SESSION["rol"] = $user["rol"];
            
            header("Location: panel.php");
            exit;
        }
    }

    // TODO: Recorre el array $usuarios y comprueba si existe un usuario cuyo
    //       nombre y contraseña coincidan con los valores almacenados en las cookies.
    //       Si existe, crea las variables de sesión 'usuario' y 'rol',
    //       y redirige al archivo 'panel.php'.
}

// --- Si se envía el formulario ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["usuario"])) {

    $username = $_POST["usuario"] ?? '';
    $password = $_POST["password"]  ?? '';

    $encontrado = false;
    foreach ($usuarios as $usuario) {
        if ($usuarios['usuario'] === $username && $usuario['password'] === $password) {
            $encontrado = true;
            
            setcookie("usuario", $username, time() + 3600); 
            setcookie("password", $password, time() + 3600); 

            $_SESSION["usuario"] = $usuario["usuario"];
            $_SESSION["rol"] = $usuario["rol"];
            header("Location: panel.php");
            exit;
        }
    }
    
    // TODO: Obten los valores enviados desde el formulario mediante $_POST.
    // TODO: Inicializa una variable de control ($encontrado = false).
    // TODO: Recorre el array $usuarios para comprobar si las credenciales coinciden.
    // TODO: Si las credenciales son correctas:
    //       - Crea cookies 'usuario' y 'password' con una duración de 1 hora.
    //       - Guarda en la sesión el 'usuario' y el 'rol'.
    //       - Redirige al archivo 'panel.php'.

    if (!$encontrado) {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aula Virtual - Inicio de Sesión</title>
</head>
<body>
    <h2>Bienvenido al Aula Virtual</h2>
    <p>Por favor, introduce tus credenciales para acceder al sistema.</p>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="">
        <label for="usuario">Usuario:</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>