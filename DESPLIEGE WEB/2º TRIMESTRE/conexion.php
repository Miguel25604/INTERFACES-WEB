<?php
// conexion.php - AwardSpace (PDO)

$host = "fdb1033.awardspace.net";
$port = "3306";
$db   = "4724818_miguelgavilaceacfp";
$user = "4724818_miguelgavilaceacfp";
$pass = "Miguelon4798";   // <-- pon aquí la contraseña MySQL de AwardSpace
$charset = "utf8mb4";

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

