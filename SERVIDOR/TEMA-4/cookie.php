<?php
// ----- Crear una cookie -----

setcookie("usuario", time() + 5, "/"); 


if (isset($_COOKIE["usuario"])) {
    echo "👋 Bienvenido de nuevo, " . $_COOKIE["usuario"] . "!";
} else {
    echo "No se ha detectado ninguna cookie de usuario. Creando una...";
}

// ----- Eliminar una cookie -----

if (isset($_GET["borrar"])) {
    setcookie("usuario", time() - 5, "/");
    echo "<br>La cookie ha sido eliminada.";
}
?>

<hr>
<a href="cookie.php">Refrescar página</a> |
<a href="cookie.php">Borrar cookie</a>