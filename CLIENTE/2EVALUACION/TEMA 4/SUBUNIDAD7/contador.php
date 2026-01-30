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

// ===== util: set cookie seguro-ish =====
function set_cookie($name, $value, $days = 30) {
  setcookie($name, $value, [
    "expires"  => time() + (60 * 60 * 24 * $days),
    "path"     => "/",
    "secure"   => false,   // pon true si usas https
    "httponly" => false,   // si no necesitas leerlo desde JS, pon true
    "samesite" => "Lax"
  ]);
}

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

  set_cookie($cookieName, strval($current), 30);

  echo json_encode([
    "ok" => true,
    "jumps" => $current
  ]);
  exit;
}

/* =========================================================
   NUEVO: guardar/cargar/limpiar historiales en cookie
   ========================================================= */

$stateCookie = "nav_state";

// GET: devolver estado de última sesión
if ($action === "state_get") {
  $raw = $_COOKIE[$stateCookie] ?? "";
  $state = null;

  if ($raw !== "") {
    // lo guardamos como base64(json)
    $json = base64_decode($raw, true);
    if ($json !== false) {
      $decoded = json_decode($json, true);
      if (is_array($decoded)) $state = $decoded;
    }
  }

  $jumps = isset($_COOKIE["jump_count"]) ? intval($_COOKIE["jump_count"]) : 0;

  echo json_encode([
    "ok" => true,
    "jumps" => $jumps,
    "state" => $state  // puede ser null si no existe
  ]);
  exit;
}

// POST: guardar estado (actual, atras, adelante)
if ($action === "state_set") {
  $body = file_get_contents("php://input");
  $data = json_decode($body, true);

  if (!is_array($data)) {
    echo json_encode(["ok" => false, "error" => "JSON inválido"]);
    exit;
  }

  // sanitizar: solo permitir IDs de la lista blanca
  $actual = $data["actual"] ?? null;
  if ($actual !== null && !isset($webs[$actual])) $actual = null;

  $atras = $data["atras"] ?? [];
  $adelante = $data["adelante"] ?? [];

  if (!is_array($atras)) $atras = [];
  if (!is_array($adelante)) $adelante = [];

  $atras = array_values(array_filter($atras, fn($x) => is_string($x) && isset($webs[$x])));
  $adelante = array_values(array_filter($adelante, fn($x) => is_string($x) && isset($webs[$x])));

  $state = [
    "actual" => $actual,
    "atras" => $atras,
    "adelante" => $adelante
  ];

  $encoded = base64_encode(json_encode($state, JSON_UNESCAPED_UNICODE));
  set_cookie($stateCookie, $encoded, 30);

  echo json_encode(["ok" => true]);
  exit;
}

// POST: limpiar historiales en servidor (y reiniciar saltos)
if ($action === "state_clear") {
  // borrar cookies poniendo expiración en el pasado
  setcookie($stateCookie, "", time() - 3600, "/");
  setcookie("jump_count", "", time() - 3600, "/");

  echo json_encode(["ok" => true]);
  exit;
}

// ===== Acción inválida =====
echo json_encode([
  "ok" => false,
  "error" => "Acción inválida. Usa action=url | action=jump | action=state_get | action=state_set | action=state_clear"
]);
