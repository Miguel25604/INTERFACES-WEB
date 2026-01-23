<?php
require_once "conexion.php";

$stmt = $pdo->query("SELECT id, nombre, email, creado_en FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll();

$ok = $_GET["ok"] ?? null;
$err = $_GET["err"] ?? null;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro de usuarios</title>
  <style>
    body{font-family:Arial; margin:20px;}
    form{display:grid; gap:10px; max-width:420px; margin-bottom:20px;}
    input{padding:10px;}
    button{padding:10px 12px; cursor:pointer;}
    .msg{padding:10px; margin:10px 0; border:1px solid #ddd; max-width:600px;}
    .ok{background:#f3fff3;}
    .err{background:#fff3f3;}
    table{border-collapse:collapse; width:100%; margin-top:10px;}
    th,td{border:1px solid #ddd; padding:10px; text-align:left;}
    th{background:#f5f5f5;}
    a{color:#c00; font-weight:bold; text-decoration:none;}
  </style>
</head>
<body>

<h2>Registro de usuarios</h2>

<?php if ($ok): ?>
  <div class="msg ok">✅ Usuario creado correctamente.</div>
<?php endif; ?>

<?php if ($err): ?>
  <div class="msg err">❌ <?= htmlspecialchars($err) ?></div>
<?php endif; ?>

<form action="guardar.php" method="POST" autocomplete="off">
  <input type="text" name="nombre" placeholder="Nombre" required maxlength="80">
  <input type="email" name="email" placeholder="Email" required maxlength="120">
  <input type="password" name="password" placeholder="Contraseña" required minlength="6">
  <button type="submit">Registrar</button>
</form>

<h2>Usuarios registrados</h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Email</th>
      <th>Creado</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($usuarios) === 0): ?>
      <tr><td colspan="5">No hay usuarios.</td></tr>
    <?php else: ?>
      <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u["id"]) ?></td>
          <td><?= htmlspecialchars($u["nombre"]) ?></td>
          <td><?= htmlspecialchars($u["email"]) ?></td>
          <td><?= htmlspecialchars($u["creado_en"]) ?></td>
          <td>
            <a href="eliminar.php?id=<?= urlencode($u["id"]) ?>"
               onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">
              Eliminar
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<p style="margin-top:10px;">Ver solo listado: <a href="listar.php">listar.php</a></p>

</body>
</html>

