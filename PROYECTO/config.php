<?php
// === config.php ===
// Ruta al archivo SQLite. Coloca este proyecto en tu servidor PHP tal cual.
// Asegúrate de que el proceso de PHP tenga permisos de escritura sobre 'portfolio.db' y la carpeta 'uploads'.
$DB_FILE = __DIR__ . '/portfolio.db';

function db() {
  global $DB_FILE;
  $db = new SQLite3($DB_FILE);
  $db->enableExceptions(true);
  $db->exec('PRAGMA foreign_keys = ON;');
  return $db;
}

function ensure_uploads() {
  $dir = __DIR__ . '/uploads';
  if (!is_dir($dir)) { mkdir($dir, 0775, true); }
  return $dir;
}
?>