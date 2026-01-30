<?php
header('Content-Type: application/json');

$webs = [
  "google" => "https://www.google.com",
  "wikipedia" => "https://es.wikipedia.org",
  "github" => "https://github.com"
];

$site = $_GET["site"] ?? "";

if (!isset($webs[$site])) {
  echo json_encode(["error" => "Web no válida"]);
  exit;
}

echo json_encode([
  "url" => $webs[$site]
]);
?>