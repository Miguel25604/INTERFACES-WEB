<?php
// === actualiza.php ===
// Actualiza un campo de un registro (llamado por fetch desde admin.php)
require_once __DIR__ . '/config.php';
header('Content-Type: application/json; charset=utf-8');
try {
  $id = intval($_GET['identificador'] ?? 0);
  $col = $_GET['columna'] ?? '';
  $val = $_GET['contenido'] ?? '';
  $permitidas = ['title','description','author','tags','image_path'];
  if ($id <= 0) throw new Exception('ID inválido');
  if (!in_array($col, $permitidas, true)) throw new Exception('Columna no permitida');

  $db = db();
  $stmt = $db->prepare("UPDATE portfolio_items SET $col = :val WHERE id = :id");
  $stmt->bindValue(':val', $val, SQLITE3_TEXT);
  $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
  $stmt->execute();
  echo json_encode(['ok'=>true]);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
?>