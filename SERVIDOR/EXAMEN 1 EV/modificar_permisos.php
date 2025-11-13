<?php
session_start();

// Lista completa de permisos
$todos_los_permisos = [
    "saludo",
    "añadir_usuario",
    "modificar_permisos",
    "añadir_tarea",
    "realizar_tarea"
];

// Permisos base
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

// Cargar cookie previa
if (isset($_COOKIE["permisos_personalizados"])) {
    $guardados = json_decode($_COOKIE["permisos_personalizados"], true);
    if (is_array($guardados)) {
        $permisos = array_replace_recursive($permisos, $guardados);
    }
}

$cambio_realizado = false;

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($permisos as $rol => $acciones) {
        foreach ($todos_los_permisos as $permiso) {
            $acciones[$permiso] = isset($_POST[$rol . "_" . $permiso]);
        }
        // Volvemos a guardar los cambios en el array original
        $permisos[$rol] = $acciones;
    }

    setcookie("permisos_personalizados", json_encode($permisos), time() + 86400, "/");
    $mensaje = "Permisos actualizados correctamente.";
    $cambio_realizado = true;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Permisos</title>
</head>
<body>
    <h2>Modificar Permisos por Rol</h2>

    <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>

    <form method="POST" action="">
        <table border="1" cellpadding="6">
            <tr>
                <th>Rol</th>
                <?php foreach ($todos_los_permisos as $permiso): ?>
                    <th><?php echo htmlspecialchars($permiso); ?></th>
                <?php endforeach; ?>
            </tr>

            <?php foreach ($permisos as $rol => $acciones): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($rol); ?></strong></td>
                    <?php foreach ($todos_los_permisos as $permiso): ?>
                        <td style="text-align:center;">
                            <input type="checkbox" name="<?php echo $rol . "_" . $permiso; ?>"
                                   <?php echo !empty($acciones[$permiso]) ? "checked" : ""; ?>>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>

        <br>
        <input type="submit" value="Guardar cambios">
    </form>

    <hr>
    <?php if ($cambio_realizado): ?>
        <a href="panel.php?msg=Se han modificado los permisos disponibles para cada rol">Volver al panel</a>
    <?php else: ?>
        <a href="panel.php">Volver al panel</a>
    <?php endif; ?>
</body>
</html>
