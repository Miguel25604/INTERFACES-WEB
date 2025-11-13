<?php
session_start();

// Cargar cola
// TODO: Crea una cola de tareas usando SplQueue, pero sin asignarle valor todavía.
$cola;

// Cargar cola desde cookie
if (isset($_COOKIE["tareas_pendientes"])) {
    $tareas_guardadas = json_decode($_COOKIE["tareas_pendientes"], true);

    // TODO: Las tareas se guardan en la cookie como un array.
    //       Hay que convertir este array en una cola (SplQueue) añadiendo cada tarea
}

$cambio_realizado = false;

// Completar tareas
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["completar"])) {
    // TODO: Comprueba si la cola no está vacía antes de intentar completar una tarea.
    if(){
        // TODO: Extrae la primera tarea de la cola.
        // TODO: Actualiza la cookie 'tareas_pendientes' guardando la cola resultante
        //       en formato JSON utilizando json_encode(iterator_to_array($cola)) para guardar el valor.

        $mensaje = "Completada la tarea: " . htmlspecialchars($tarea["nombre"]);
        $cambio_realizado = true;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Realizar Tareas</title>
</head>
<body>
    <h2>Realizar Tareas Pendientes</h2>

    <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>

    <?php if ($cola->isEmpty()): ?>
        <p>No hay tareas pendientes.</p>
    <?php else: ?>
        <?php $tarea = $cola->bottom(); ?>
        <h3><?php echo htmlspecialchars($tarea["nombre"]); ?></h3>
        <p><?php echo htmlspecialchars($tarea["descripcion"]); ?></p>

        <form method="POST" action="">
            <input type="submit" name="completar" value="Completar tarea">
        </form>

        <h4>Cola de tareas:</h4>
        <ul>
            <?php foreach ($cola as $t): ?>
                <li><?php echo htmlspecialchars($t["nombre"]); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>
    <?php if ($cambio_realizado): ?>
        <a href="panel.php?msg=Se han completado nuevas tareas">Volver al panel</a>
    <?php else: ?>
        <a href="panel.php">Volver al panel</a>
    <?php endif; ?>
</body>
</html>
