<?php
session_start();

session_unset();
session_destroy();

setcookie("usuario", "", time() - 3600);
setcookie("password", "", time() - 3600);

unset($_COOKIE["usuario"]);
unset($_COOKIE["password"]);

// TODO: Elimina todas las variables de sesión y destruir la sesión actual.

// TODO: Borra las cookies 'usuario' y 'password'

header("Location: login.php");
exit;
?>