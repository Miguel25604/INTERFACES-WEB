<?php
session_start();
session_unset();

// TODO: Elimina todas las variables de sesión y destruir la sesión actual.

// TODO: Borra las cookies 'usuario' y 'password'

header("Location: login.php");
exit;
?>