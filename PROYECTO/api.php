<?php
// === api.php ===
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';

try {
  $db = db();
  $res = $db->query('SELECT id, title, description, image_path, author, tags, created_at FROM portfolio_items ORDER BY datetime(created_at) DESC, id DESC');
  $items = [];
  while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    // Exponer image_url amigable si es ruta relativa
    $row['image_url'] = (preg_match('#^https?://#', $row['image_path'])) ? $row['image_path'] : ('uploads/' . basename($row['image_path']));
    $items[] = $row;
  }
  echo json_encode(['ok'=>true, 'items'=>$items], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
?>