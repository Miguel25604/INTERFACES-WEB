<?php
session_start();

// Verificamos que la sesión esté activa
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["rol"])) {
    header("Location: login.php");
    exit;
}

// Recuperamos usuario y rol
$usuario = $_SESSION["usuario"];
$rol = $_SESSION["rol"];

// --- Definimos los permisos por rol (idéntico al panel) ---
$permisos = [
    "admin" => ["saludo" => true],
    "director" => ["saludo" => true],
    "profesor" => ["saludo" => true],
    "alumno" => ["saludo" => true]
];

// Si el rol no tiene permiso para saludar, redirige al panel
if (!$permisos[$rol]["saludo"]) {
    header("Location: panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Función Saluda</title>
</head>
<body>
    <h2>👋 ¡Hola, <?php echo htmlspecialchars($usuario); ?>!</h2>
    <p>Tu rol dentro del aula virtual es: <strong><?php echo htmlspecialchars($rol); ?></strong>.</p>
    <br>
    <a href="panel.php">Volver al panel</a>
</body>
</html>
