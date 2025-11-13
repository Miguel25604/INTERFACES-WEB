<?php
session_start();

// TODO: Recorre todas las cookies existentes utilizando un bucle foreach sobre $_COOKIE y eliminalas

// Recargar página actual (panel si hay sesión, login si no)
if (isset($_SESSION["usuario"])) {
    header("Location: panel.php");
} else {
    header("Location: login.php");
}
exit;
?>
