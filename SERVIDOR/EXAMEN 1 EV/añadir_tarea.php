<?php
session_start();

// Cargar cola desde cookie
// TODO: Crea una cola de tareas usando SplQueue, pero sin asignarle valor todavía.
$cola = new SplQueue();

// Cargar cola desde cookie
if (isset($_COOKIE["tareas_pendientes"])) {
    $tareas_guardadas = json_decode($_COOKIE["tareas_pendientes"], true);
    foreach ($tareas_guardadas as $tarea) {
        $cola->enqueue($tarea);
    }
    // TODO: Las tareas se guardan en la cookie como un array.
    //       Hay que convertir este array en una cola (SplQueue) añadiendo cada tarea
}

$cambio_realizado = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar"])) {
    // TODO: Obten los valores enviados desde el formulario (nombre y descripción)
    //       y elimina los espacios sobrantes.
    $nombre = trim ($_POST["nombre"]) ?? '';
    $descripcion = trim($_POST["descripcion"]) ?? '';

    if ($nombre && $descripcion) {
        // TODO: Crea un array asociativo llamado $tarea con las claves 'nombre' y 'descripcion'.
        // TODO: Añadir la nueva tarea a la cola mediante enqueue().
        // TODO: Guardar la cola actualizada en la cookie 'tareas_pendientes'
        //       utilizando json_encode(iterator_to_array($cola)) para guardar el valor.

        $tarea = [
            "nombre" => $nombre,
            "descripcion" => $descripcion
        ];
        
        $cola->enqueue($tarea);
        setcookie("tareas_pendientes", json_encode(iterator_to_array($cola)), time() + 3600);

        $mensaje = "Tarea añadida correctamente.";
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
    <title>Añadir Tareas</title>
</head>
<body>
    <h2>Añadir Nuevas Tareas</h2>

    <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" rows="3" cols="40" required></textarea><br><br>

        <input type="submit" name="agregar" value="Añadir tarea">
    </form>

    <hr>
    <h3>Tareas pendientes:</h3>
    <?php if ($cola->isEmpty()): ?>
        <p>No hay tareas.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cola as $t): ?>
                <li><strong><?php echo htmlspecialchars($t["nombre"]); ?></strong>: 
                    <?php echo htmlspecialchars($t["descripcion"]); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>
    <?php if ($cambio_realizado): ?>
        <a href="panel.php?msg=Se han añadido nuevas tareas">Volver al panel</a>
    <?php else: ?>
        <a href="panel.php">Volver al panel</a>
    <?php endif; ?>
</body>
</html>
