<?php
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';

// Lista blanca (ID => URL)
$webs = [
  "google"    => "https://www.google.com",
  "wikipedia" => "https://es.wikipedia.org",
  "github"    => "https://github.com",
  "openai"    => "https://openai.com",
  "elpais"    => "https://elpais.com"
];

// ===== Acción: devolver URL =====
if ($action === "url") {
  $site = $_GET["site"] ?? "";

  if (!isset($webs[$site])) {
    echo json_encode([
      "ok" => false,
      "error" => "Web no válida o no permitida"
    ]);
    exit;
  }

  echo json_encode([
    "ok" => true,
    "site" => $site,
    "url" => $webs[$site]
  ]);
  exit;
}

// ===== Acción: incrementar saltos en cookie =====
if ($action === "jump") {
  $cookieName = "jump_count";
  $current = isset($_COOKIE[$cookieName]) ? intval($_COOKIE[$cookieName]) : 0;
  $current++;

  // Cookie 30 días
  setcookie($cookieName, strval($current), [
    "expires"  => time() + (60 * 60 * 24 * 30),
    "path"     => "/",
    "secure"   => false,   // true si usas https
    "httponly" => false,
    "samesite" => "Lax"
  ]);

  echo json_encode([
    "ok" => true,
    "jumps" => $current
  ]);
  exit;
}

// ===== Acción inválida =====
echo json_encode([
  "ok" => false,
  "error" => "Acción inválida. Usa action=url o action=jump"
]);
