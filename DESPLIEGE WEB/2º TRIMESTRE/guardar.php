<?php
require_once "conexion.php";

$nombre = trim($_POST["nombre"] ?? "");
$email  = trim($_POST["email"] ?? "");
$pass   = $_POST["password"] ?? "";

if ($nombre === "" || $email === "" || $pass === "") {
  header("Location: index.php?err=" . urlencode("Faltan datos."));
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header("Location: index.php?err=" . urlencode("Email inválido."));
  exit;
}

if (strlen($pass) < 6) {
  header("Location: index.php?err=" . urlencode("La contraseña debe tener al menos 6 caracteres."));
  exit;
}

$password_hash = password_hash($pass, PASSWORD_DEFAULT);

try {
  $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password_hash) VALUES (:nombre, :email, :ph)");
  $stmt->execute([
    ":nombre" => $nombre,
    ":email"  => $email,
    ":ph"     => $password_hash
  ]);

  header("Location: index.php?ok=1");
  exit;

} catch (PDOException $e) {
  // 1062 = clave duplicada (email UNIQUE)
  if ($e->errorInfo[1] == 1062) {
    header("Location: index.php?err=" . urlencode("Ese email ya está registrado."));
    exit;
  }
  header("Location: index.php?err=" . urlencode("Error al guardar el usuario."));
  exit;
}
