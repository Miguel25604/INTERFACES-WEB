<?php
// ----------------------------------------------------
// INICIO DE SESIÓN
// ----------------------------------------------------
session_start();

// ----------------------------------------------------
// ARRAY DE USUARIOS (usuario, password, rol)
// ----------------------------------------------------


$usuarios = [
    ["usuario" => "Miguel",      "password" => "1234", "rol" => "admin"],
    ["usuario" => "Rafelbunyol", "password" => "0000", "rol" => "bibliotecario"],
    ["usuario" => "Victor",      "password" => "1111", "rol" => "Lector"],
];

$roles = [

    "admin" => [
        "Gestion_usuarios",
        "Ver informe del sistema",
        "Configurar la aplicacion",
    ],

    "bibliotecarios" => [
        "Catalogar_libros",
        "Gestionar prestamos y devoluciones",
        "Administrar reservas",
    ],

    "Lector" => [
        "Novedades",
        "Noticias",
        "Libros nuevos",

    ],
];

// ----------------------------------------------------
// FUNCIONES AUXILIARES (COOKIES)
// ----------------------------------------------------

function crearCookieUsuario($usuario, $rol) {
    $datos = json_encode(["usuario" => $usuario, "rol" => $rol]);
    setcookie("auth", $datos, time() + 5, "/"); 
}

function eliminarCookieUsuario() {
    setcookie("auth", "", time() - 5, "/");
}







// ----------------------------------------------------
// PROCESO DE LOGIN (desde formulario)
// - Sólo usuario + password
// - Se valida contra el array y se asigna el rol
// - Si OK: se guarda en sesión y se crea cookie con usuario+rol
// ----------------------------------------------------


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["usuario"], $_POST["password"])) {
    $usuarioInput  = trim($_POST["usuario"],);
    $passwordInput = trim($_POST["password"]);

    $rolEncontrado = null;
    $usuarioValido = null;

    foreach ($usuarios as $u) {
        // Comparación exacta del nombre tal cual lo tienes en el array
        if ($u["usuario"] === $usuarioInput && $u["password"] === $passwordInput) {
            $rolEncontrado = $u["rol"];
            $usuarioValido = $u["usuario"];
            break;
        }
    }

    if ($rolEncontrado) {
        // Guardamos sólo usuario y rol en sesión
        $_SESSION["usuario"] = $usuarioValido;
        $_SESSION["rol"]     = $rolEncontrado;

        // Creamos cookie temporal con usuario+rol (no contraseña)
        crearCookieUsuario($usuarioValido, $rolEncontrado);

        // Redirigimos para que el navegador establezca la cookie
        header("Location: 003-roles perfiles usuarios.php");
        exit;
    } else {
        $error = "❌ Usuario o contraseña incorrectos.";
    }
}

// ----------------------------------------------------
// SI EXISTE COOKIE Y NO HAY SESIÓN, MIGRAR A SESIÓN
// ----------------------------------------------------
if (!isset($_SESSION["usuario"]) && isset($_COOKIE["auth"])) {
    $datos = json_decode($_COOKIE["auth"], true);
    if ($datos && isset($datos["usuario"], $datos["rol"])) {
        $_SESSION["usuario"] = $datos["usuario"];
        $_SESSION["rol"]     = $datos["rol"];
        eliminarCookieUsuario(); // ya no necesitamos la cookie
    }
}

// ----------------------------------------------------
// CERRAR SESIÓN
// ----------------------------------------------------
if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    eliminarCookieUsuario(); // eliminar también cualquier cookie vieja
    header("Location: 003-roles perfiles usuarios.php");
    exit;
}

// ----------------------------------------------------
// OBTENER DATOS DE SESIÓN (si existen)
// ----------------------------------------------------
$usuario = $_SESSION["usuario"] ?? null;
$rol     = $_SESSION["rol"] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo PHP: Cookies + Sesiones + Roles</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        form { margin-bottom: 20px; }
        .admin { color: red; }
        .bibliotecario { color: green; }
        .lector { color: blue; }
        .panel { border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Aplicación con Roles usando Cookies y Sesiones</h1>

<?php if (!$usuario): ?>

    <!-- FORMULARIO DE LOGIN -->
    <?php if (isset($error)) echo "<p style='color:red;'>".htmlspecialchars($error)."</p>"; ?>
    <form method="post" action="">
        <label>Nombre de usuario:</label><br>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Iniciar sesión</button>
    </form>

<?php else: ?>

    <!-- PANEL PRINCIPAL -->
    <p>👋 Bienvenido, <strong><?php echo htmlspecialchars($usuario); ?></strong> 
    (rol: <span class="<?php echo htmlspecialchars($rol); ?>"><?php echo htmlspecialchars($rol); ?></span>)</p>
    <p><a href="?logout=1">Cerrar sesión</a></p>

    <div class="panel">
        <?php if ($rol === "admin"): ?>
            <h2>Panel de Administrador</h2>
            <ul>
                <li>Gestionar usuarios</li>
                <li>Ver informes del sistema</li>
                <li>Configurar la aplicación</li>
            </ul>

        <?php elseif ($rol === "bibliotecario"): ?>
            <h2>Panel de Biblioteca</h2>
            <ul>
                <li>Catalogar libros</li>
                <li>Gestionar préstamos y devoluciones</li>
                <li>Administrar reservas</li>
            </ul>

        <?php elseif ($rol === "lector"): ?>
            <h2>Panel de Libros</h2>
            <ul>
                <li>Novedades</li>
                <li>Libros destacados</li>
            </ul>

        <?php else: ?>
            <h2>Panel de Visitante</h2>
            <ul>
                <li>Ver artículos públicos</li>
                <li>Suscribirse a noticias</li>
            </ul>
        <?php endif; ?>
    </div>

<?php endif; ?>

</body>
</html>
