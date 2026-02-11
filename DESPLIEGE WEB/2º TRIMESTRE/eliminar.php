<?php
require_once "conexion.php";

$id = $_GET["id"] ?? null;

if (!$id || !ctype_digit($id)) {
  die("ID inválido.");
}

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
$stmt->execute([":id" => (int)$id]);

header("Location: index.php?ok=1");
exit;
