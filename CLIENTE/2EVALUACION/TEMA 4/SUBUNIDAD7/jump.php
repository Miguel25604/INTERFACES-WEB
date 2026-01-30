<?php
header('Content-Type: application/json');

$cookie = "saltos";
$valor = isset($_COOKIE[$cookie]) ? intval($_COOKIE[$cookie]) : 0;
$valor++;

setcookie($cookie, $valor, time()+3600*24, "/");

echo json_encode([
  "jumps" => $valor
]);
?>