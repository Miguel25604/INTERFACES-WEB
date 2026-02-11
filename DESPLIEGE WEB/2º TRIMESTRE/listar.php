<?php
require_once "conexion.php";

$stmt = $pdo->query("SELECT id, nombre, email, creado_en FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Listado de usuarios</title>
  <style>
    body{font-family:Arial; margin:20px;}
    table{border-collapse:collapse; width:100%;}
    th,td{border:1px solid #ddd; padding:10px; text-align:left;}
    th{background:#f5f5f5;}
  </style>
</head>
<body>

<h2>Listado de usuarios</h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Email</th>
      <th>Creado</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($usuarios) === 0): ?>
      <tr><td colspan="4">No hay usuarios.</td></tr>
    <?php else: ?>
      <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u["id"]) ?></td>
          <td><?= htmlspecialchars($u["nombre"]) ?></td>
          <td><?= htmlspecialchars($u["email"]) ?></td>
          <td><?= htmlspecialchars($u["creado_en"]) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<p><a href="index.php">Volver</a></p>

</body>
</html>

