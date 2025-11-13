<?php
session_start();

if (!isset($_SESSION["usuario"]) || !isset($_SESSION["rol"])) {
    header("Location: login.php");
    exit;
}

// TODO: Recupera las variables de sesión 'usuario' y 'rol' que se crearon en el login
//       y almacenarlas en las variables $usuario y $rol correspondientes.
$usuario;
$rol;

// --- Permisos base ---
$permisos = [
    "admin" => [
        "saludo" => true,
        "añadir_usuario" => true,
        "modificar_permisos" => true,
        "añadir_tarea" => false,
        "realizar_tarea" => false
    ],
    "director" => [
        "saludo" => true,
        "añadir_usuario" => true,
        "modificar_permisos" => false,
        "añadir_tarea" => false,
        "realizar_tarea" => false
    ],
    "profesor" => [
        "saludo" => true,
        "añadir_usuario" => false,
        "modificar_permisos" => false,
        "añadir_tarea" => true,
        "realizar_tarea" => false
    ],
    "alumno" => [
        "saludo" => true,
        "añadir_usuario" => false,
        "modificar_permisos" => false,
        "añadir_tarea" => false,
        "realizar_tarea" => true
    ]
];

// --- Aplicar permisos personalizados (si hay cookie) ---
if (isset($_COOKIE["permisos_personalizados"])) {
    $permisos_guardados = json_decode($_COOKIE["permisos_personalizados"], true);
    if (is_array($permisos_guardados)) {
        $permisos = array_replace_recursive($permisos, $permisos_guardados);
    }
}

// TODO: Recupera el valor enviado mediante la variable GET 'msg' (si existe)
//       y almacenalo en la variable $mensaje. Si no existe, dejala vacía.
$mensaje;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aula Virtual - Panel</title>
</head>
<body>
    <h2>Panel del Aula Virtual</h2>
    <p><strong>Usuario:</strong> <?php echo htmlspecialchars($usuario); ?></p>

    <?php if ($mensaje): ?>
        <p style="color:green;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <hr>
    <h3>Funciones disponibles:</h3>
    <ul>
        <?php if ($permisos[$rol]["saludo"]) : ?>
            <li><a href="saluda.php">Saludar</a></li>
        <?php endif; ?>

        <?php // TODO: Mostrar este enlace solo si el usuario tiene permiso para añadir usuarios ?>
        <li><a href="añadir_usuario.php">Añadir usuario</a></li>

        <?php // TODO: Mostrar este enlace solo si el usuario tiene permiso para modificar permisos ?>
        <li><a href="modificar_permisos.php">Modificar permisos</a></li>

        <?php // TODO: Mostrar este enlace solo si el usuario tiene permiso para añadir tareas ?>
        <li><a href="añadir_tarea.php">Añadir tarea</a></li>

        <?php // TODO: Mostrar este enlace solo si el usuario tiene permiso para realizar tareas ?>
        <li><a href="realizar_tarea.php">Realizar tarea</a></li>
    </ul>

    <hr>
    <a href="cerrar_sesion.php">Cerrar sesión</a>
    <form method="POST" action="borrar_cookies.php" style="margin-top: 10px;">
        <input type="submit" value="Borrar todas las cookies y recargar">
    </form>
</body>
</html>
