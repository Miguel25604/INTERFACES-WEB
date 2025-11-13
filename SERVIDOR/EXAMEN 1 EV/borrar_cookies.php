<?php
session_start();

foreach ($_COOKIE as $nombre => $valor) {
    setcookie($nombre, "", time() - 3600);
    unset($_COOKIE[$nombre]);
}

// TODO: Recorre todas las cookies existentes utilizando un bucle foreach sobre $_COOKIE y eliminalas

// Recargar página actual (panel si hay sesión, login si no)
if (isset($_SESSION["usuario"])) {
    header("Location: panel.php");
} else {
    header("Location: login.php");
}
exit;
?>
