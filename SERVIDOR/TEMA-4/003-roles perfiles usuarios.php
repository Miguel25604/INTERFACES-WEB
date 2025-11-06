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
    ["usuario" => "Victor",      "password" => "1111", "rol" => "lector"], 
    ["usuario" => "Ana",         "password" => "2222", "rol" => "profesor"],
];

// ----------------------------------------------------


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


$rolesPermisos = [
    'admin' => [
        'gestion_usuarios' => true,
        'ver_informe' => true,
        'configurar_app'    => true,
        'catalogar'         => true,
        'prestamos'         => true,
        'reservas'          => true,
        'ver_novedades'     => true,
        'ver_noticias'      => true,
        'ver_libros_nuevos' => true,
    ],
    'bibliotecario' => [
        'gestion_usuarios'  => false,
        'ver_informe'       => false,
        'configurar_app'    => false,
        'catalogar'         => true,
        'prestamos'         => true,
        'reservas'          => true,
        'ver_novedades'     => true,
        'ver_noticias'      => false,
        'ver_libros_nuevos' => true,
    ],
    'lector' => [
        'gestion_usuarios'  => false,
        'ver_informe'       => false,
        'configurar_app'    => false,
        'catalogar'         => false,
        'prestamos'         => false,
        'reservas' => false,
        'ver_novedades' => true,
        'ver_noticias' => true,
        'ver_libros_nuevos' => true,
    ],
    
    'profesor' => [
        'gestion_usuarios' => false,
        'ver_informe' => false,
        'configurar_app' => false,
        'catalogar' => false,
        'prestamos' => false,
        'reservas'  => true, 
        'ver_novedades' => true,
        'ver_noticias' => true,
        'ver_libros_nuevos' => true,
    ],
];

// ----------------------------------------------------
// FUNCIONES COOKIES 
// ----------------------------------------------------
function crearCookieUsuario($usuario, $rol) {
    $datos = json_encode(["usuario" => $usuario, "rol" => $rol]);
    setcookie("auth", $datos, time() + 5, "/"); 
}

function eliminarCookieUsuario() {
    setcookie("auth", "", time() - 5, "/");
}

function permisosDeRol($rol, $rolesPermisos) {
    $rol = strtolower($rol);
    return $rolesPermisos[$rol] ?? [];
}

function tienePermiso($permisos, $permiso) {
    return !empty($permisos[$permiso]);
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["usuario"], $_POST["password"])) {
    $usuarioInput  = trim($_POST["usuario"]);
    $passwordInput = trim($_POST["password"]);

    $rolEncontrado = null;
    $usuarioValido = null;

    foreach ($usuarios as $u) {
        if ($u["usuario"] === $usuarioInput && $u["password"] === $passwordInput) {
            $rolEncontrado = strtolower($u["rol"]);
            $usuarioValido = $u["usuario"];
            break;
        }
    }

    if ($rolEncontrado) {
        $_SESSION["usuario"] = $usuarioValido;
        $_SESSION["rol"]     = $rolEncontrado;
        crearCookieUsuario($usuarioValido, $rolEncontrado);
        header("Location: 003-roles perfiles usuarios.php");
        exit;
    } else {
        $error = "❌ Usuario o contraseña incorrectos.";
    }
}


// COOKIE DE SESIÓN 

if (!isset($_SESSION["usuario"]) && isset($_COOKIE["auth"])) {
    $datos = json_decode($_COOKIE["auth"], true);
    if ($datos && isset($datos["usuario"], $datos["rol"])) {
        $_SESSION["usuario"] = $datos["usuario"];
        $_SESSION["rol"]     = strtolower($datos["rol"]);
        eliminarCookieUsuario();
    }
}


// CERRAR SESIÓN 

if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    eliminarCookieUsuario();
    header("Location: 003-roles perfiles usuarios.php");
    exit;
}


// OBTENER DATOS DE SESIÓN

$usuario = $_SESSION["usuario"] ?? null;
$rol     = $_SESSION["rol"] ?? null;
$permisos = $rol ? permisosDeRol($rol, $rolesPermisos) : [];
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
        .profesor { color: purple; }
        .panel { border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-top: 10px; }
        .no { color:#999; text-decoration: line-through; }
        .ok { color:green; }
        ul { line-height:1.6; }
    </style>
</head>
<body>

<h1>Aplicación con Roles usando Cookies y Sesiones</h1>

<?php if (!$usuario): ?>


    <?php if (isset($error)) echo "<p style='color:red;'>".htmlspecialchars($error)."</p>"; ?>
    <form method="post" action="">
        <label>Nombre de usuario:</label><br>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Iniciar sesión</button>
    </form>

<?php else: ?>

    <p>👋 Bienvenido, <strong><?php echo htmlspecialchars($usuario); ?></strong>
    (rol: <span class="<?php echo htmlspecialchars($rol); ?>"><?php echo htmlspecialchars($rol); ?></span>)</p>
    <p><a href="?logout=1">Cerrar sesión</a></p>

    <div class="panel">
        <h2>Panel (controlado por <em>permisos</em>)</h2>
        <ul>
            <!-- Administración -->
            <li class="<?php echo tienePermiso($permisos,'gestion_usuarios') ? 'ok' : 'no'; ?>">Gestionar usuarios</li>
            <li class="<?php echo tienePermiso($permisos,'ver_informe') ? 'ok' : 'no'; ?>">Ver informes del sistema</li>
            <li class="<?php echo tienePermiso($permisos,'configurar_app') ? 'ok' : 'no'; ?>">Configurar la aplicación</li>

            <!-- Biblioteca -->
            <li class="<?php echo tienePermiso($permisos,'catalogar') ? 'ok' : 'no'; ?>">Catalogar libros</li>
            <li class="<?php echo tienePermiso($permisos,'prestamos') ? 'ok' : 'no'; ?>">Gestionar préstamos y devoluciones</li>
            <li class="<?php echo tienePermiso($permisos,'reservas') ? 'ok' : 'no'; ?>">Administrar reservas</li>

            <!-- Lectura/Noticias -->
            <li class="<?php echo tienePermiso($permisos,'ver_novedades') ? 'ok' : 'no'; ?>">Novedades</li>
            <li class="<?php echo tienePermiso($permisos,'ver_noticias') ? 'ok' : 'no'; ?>">Noticias</li>
            <li class="<?php echo tienePermiso($permisos,'ver_libros_nuevos') ? 'ok' : 'no'; ?>">Libros nuevos</li>
        </ul>
        <p style="color:#666;">* Las opciones tachadas no están disponibles para tu rol.</p>
    </div>

<?php endif; ?>

</body>
</html>



<?php
// ----- 1. Crear una cookie -----
// setcookie(nombre, valor, tiempo_de_expiración, ruta);
setcookie("usuario", "Rosa Melano", time() + 3600, "/");
// La cookie expirará en 1 hora

// ----- 2. Leer una cookie -----
if (isset($_COOKIE["usuario"])) {
    echo "👋 Bienvenido de nuevo, " . $_COOKIE["usuario"] . "!";
} else {
    echo "No se ha detectado ninguna cookie de usuario. Creando una...";
}

// ----- 3. Eliminar una cookie -----
// Para borrar una cookie, se establece con una fecha expirada
if (isset($_GET["borrar"])) {
    setcookie("usuario", "", time() - 3600, "/");
    echo "<br>La cookie ha sido eliminada.";
}
?>

<hr>
<a href="cookies.php">Refrescar página</a> |
<a href="cookies.php?borrar=1">Borrar cookie</a>
