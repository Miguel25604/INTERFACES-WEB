<?php
session_start();

// Cargar usuarios añadidos previamente desde la cookie
$usuarios_nuevos = [];
if (isset($_COOKIE["usuarios_nuevos"])) {
    $usuarios_nuevos = json_decode($_COOKIE["usuarios_nuevos"], true);
    if (!is_array($usuarios_nuevos)) {
        $usuarios_nuevos = [];
    }
}

$cambio_realizado = false;

// Procesar formulario de nuevo usuario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar"])) {
    // TODO: Obten los datos enviados por el formulario: usuario, contraseña y rol.
    $nuevo_usuario = $POST ["usuario"];
    $nueva_password = $POST ["password"];
    $nuevo_rol = $POST ["rol"];

    if ($nuevo_usuario && $nueva_password && $nuevo_rol) {
    // TODO: Crea un nuevo elemento (array asociativo) con las claves 'usuario', 'password' y 'rol' 
    //       y añadirlo al array $usuarios_nuevos. Con "usuarios_nuevos[] = " añadís nuevos elementos al final
        $usuarios_nuevos[] = [
            "usuario" => $nuevo_usuario,
            "password" => $nueva_password,
            "rol" => $nuevo_rol
            ];

    // TODO: Guarda el array actualizado en una cookie llamada 'usuarios_nuevos'
    //       codificando su contenido en formato JSON mediante json_encode().
        setcookie("usuarios_nuevos", json_encode($usuarios_nuevos), time() + 3600);
        
        $mensaje = "Usuario '$nuevo_usuario' añadido correctamente.";
        $cambio_realizado = true;
    } else {
        $error = "Por favor, rellena todos los campos.";
    }
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Usuario</title>
</head>
<body>
    <h2>Añadir Nuevos Usuarios</h2>

    <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="">
        <label for="usuario">Nombre de usuario:</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="rol">Rol:</label><br>
        <select id="rol" name="rol" required>
            <option value="">--Selecciona un rol--</option>
            <option value="admin">Admin</option>
            <option value="director">Director</option>
            <option value="profesor">Profesor</option>
            <option value="alumno">Alumno</option>
        </select><br><br>

        <input type="submit" name="agregar" value="Añadir usuario">
    </form>

    <hr>
    <h3>Usuarios añadidos recientemente:</h3>
    <?php if (empty($usuarios_nuevos)): ?>
        <p>No hay usuarios nuevos registrados.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($usuarios_nuevos as $u): ?>
                <li>
                    <strong><?php echo htmlspecialchars($u["usuario"]); ?></strong>
                    (Rol: <?php echo htmlspecialchars($u["rol"]); ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>
    <?php if ($cambio_realizado): ?>
        <a href="panel.php?msg=Se han añadido nuevos usuarios">Volver al panel</a>
    <?php else: ?>
        <a href="panel.php">Volver al panel</a>
    <?php endif; ?>
</body>
</html>
