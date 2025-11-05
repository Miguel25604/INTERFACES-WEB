<?php
// === admin.php ===
// Panel CRUD para el portafolio (SQLite + PHP)
// Requiere: config.php, actualiza.php, portfolio.db y carpeta uploads/
require_once __DIR__ . '/config.php';

$err = null;
$msg = null;

// Alta de pieza (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $db = db();
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $author = trim($_POST['author'] ?? 'Miguel');
    $tags = trim($_POST['tags'] ?? '');

    if ($title === '') {
      throw new Exception('El título es obligatorio');
    }

    // Subida de imagen opcional
    $image_path = null;
    if (!empty($_FILES['image']['name'])) {
      ensure_uploads();
      if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir la imagen');
      }
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $safe = uniqid('img_', true) . ($ext ? ('.' . preg_replace('/[^a-zA-Z0-9]/', '', $ext)) : '');
      $dest = __DIR__ . '/uploads/' . $safe;
      if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        throw new Exception('No se pudo mover el archivo subido');
      }
      $image_path = 'uploads/' . $safe;
    }

    $stmt = $db->prepare('INSERT INTO portfolio_items (title, description, image_path, author, tags) VALUES (?,?,?,?,?)');
    $stmt->bindValue(1, $title, SQLITE3_TEXT);
    $stmt->bindValue(2, $description, SQLITE3_TEXT);
    $stmt->bindValue(3, $image_path, SQLITE3_TEXT);
    $stmt->bindValue(4, $author, SQLITE3_TEXT);
    $stmt->bindValue(5, $tags, SQLITE3_TEXT);
    $stmt->execute();

    $msg = 'Pieza creada correctamente';
  } catch (Exception $e) {
    $err = $e->getMessage();
  }
}

// Eliminación (?delete=ID)
if (isset($_GET['delete']) && $_GET['delete'] !== '') {
  try {
    $id = (int)$_GET['delete'];
    $db = db();

    // Borrar también el fichero si es local
    $sel = $db->prepare('SELECT image_path FROM portfolio_items WHERE id = ?');
    $sel->bindValue(1, $id, SQLITE3_INTEGER);
    $row = $sel->execute()->fetchArray(SQLITE3_ASSOC);
    if ($row && $row['image_path'] && !preg_match('#^https?://#', $row['image_path'])) {
      $path = __DIR__ . '/' . $row['image_path'];
      if (is_file($path)) @unlink($path);
    }

    $del = $db->prepare('DELETE FROM portfolio_items WHERE id = ?');
    $del->bindValue(1, $id, SQLITE3_INTEGER);
    $del->execute();

    header('Location: admin.php?msg=deleted');
    exit;
  } catch (Exception $e) {
    $err = $e->getMessage();
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel Portafolio</title>
  <style>
    body{display:flex;gap:30px;align-items:flex-start;justify-content:center;padding:30px;background:aliceblue;font-family:sans-serif;font-size:14px;}
    table{padding:20px;background:white;border-radius:10px;box-shadow:0 10px 20px rgba(0,0,0,0.2);flex:3;border-collapse:collapse;min-width:780px;}
    th,td{border-bottom:1px solid #eee;padding:8px 10px;vertical-align:top;}
    th{background:#fafafa;text-align:left;}
    form.card{flex:1;display:flex;flex-direction:column;gap:10px;padding:20px;background:white;border-radius:10px;box-shadow:0 10px 20px rgba(0,0,0,0.2);}
    input[type=text], textarea{padding:8px;border:1px solid #ccc;border-radius:6px;width:100%;}
    .msg{padding:8px 12px;border-radius:6px;margin-bottom:10px;}
    .ok{background:#e8fff1;border:1px solid #b6efc7;}
    .err{background:#ffecec;border:1px solid #ffb3b3;}
    img.thumb{width:80px;height:60px;object-fit:cover;border:1px solid #ccc;border-radius:6px;background:#eee;}
    a.btn-del{color:#b00;text-decoration:none;}
    small.hint{color:#666}
  </style>
</head>
<body>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Miniatura</th>
        <th>Título</th>
        <th>Descripción</th>
        <th>Autor</th>
        <th>Tags</th>
        <th>Fecha</th>
        <th>Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php
        try {
          $db = db();
          $res = $db->query('SELECT id,title,description,image_path,author,tags,created_at FROM portfolio_items ORDER BY datetime(created_at) DESC, id DESC');
          while ($fila = $res->fetchArray(SQLITE3_ASSOC)) {
            $src = $fila['image_path'];
            if ($src && !preg_match('#^https?://#', $src)) { $src = $src; }
            echo '<tr>';
            echo '<td>'.(int)$fila['id'].'</td>';
            echo '<td>'.($src ? "<img class='thumb' src='".htmlspecialchars($src,ENT_QUOTES)."' alt=''>" : "<small class='hint'>(sin imagen)</small>").'</td>';
            echo '<td identificador="'.(int)$fila['id'].'" columna="title">'.htmlspecialchars($fila['title']).'</td>';
            echo '<td identificador="'.(int)$fila['id'].'" columna="description">'.htmlspecialchars($fila['description']).'</td>';
            echo '<td identificador="'.(int)$fila['id'].'" columna="author">'.htmlspecialchars($fila['author']).'</td>';
            echo '<td identificador="'.(int)$fila['id'].'" columna="tags">'.htmlspecialchars($fila['tags']).'</td>';
            echo '<td>'.htmlspecialchars($fila['created_at']).'</td>';
            echo '<td><a class="btn-del" href="?delete='.(int)$fila['id'].'" onclick="return confirm(\'¿Eliminar?\');">❌</a></td>';
            echo '</tr>';
          }
        } catch (Exception $e) {
          echo '<tr><td colspan="8"><div class="msg err">'.htmlspecialchars($e->getMessage()).'</div></td></tr>';
        }
      ?>
    </tbody>
  </table>

  <form class="card" action="admin.php" method="POST" enctype="multipart/form-data">
    <h3>Nueva pieza</h3>
    <?php if (isset($_GET['msg']) && $_GET['msg']==='deleted'): ?>
      <div class="msg ok">Pieza eliminada</div>
    <?php endif; ?>
    <?php if ($msg): ?>
      <div class="msg ok"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    <?php if ($err): ?>
      <div class="msg err"><?php echo htmlspecialchars($err); ?></div>
    <?php endif; ?>

    <label>Título
      <input type="text" name="title" required>
    </label>
    <label>Descripción
      <textarea name="description" rows="4"></textarea>
    </label>
    <label>Autor
      <input type="text" name="author" value="Miguel">
    </label>
    <label>Tags (coma separadas)
      <input type="text" name="tags" placeholder="web, ui, print">
    </label>
    <label>Imagen (JPG/PNG)
      <input type="file" name="image" accept="image/*">
    </label>
    <button type="submit">Guardar</button>
    <small class="hint">Doble clic en las celdas para editar. Al salir, se guarda.</small>
  </form>

  <!-- A partir de aquí ya NO hay PHP. El JS está fuera para evitar que PHP lo interprete -->
  <script>
    // Edición inline con fetch a actualiza.php
    const celdas = document.querySelectorAll('td[columna]');
    celdas.forEach(function(celda){
      celda.ondblclick = function(){
        this.setAttribute('contenteditable', 'true');
        this.focus();
      };
      celda.onblur = function(){
        this.setAttribute('contenteditable', 'false');
        const contenido = this.textContent.trim();
        const columna = this.getAttribute('columna');
        const identificador = this.getAttribute('identificador');
        fetch('actualiza.php?identificador=' + encodeURIComponent(identificador) +
              '&columna=' + encodeURIComponent(columna) +
              '&contenido=' + encodeURIComponent(contenido))
          .then(r => r.json())
          .then(d => {
            if (!d.ok) { alert('Error: ' + d.error); }
          })
          .catch(() => alert('Error de red'));
      };
    });
  </script>
</body>
</html>

