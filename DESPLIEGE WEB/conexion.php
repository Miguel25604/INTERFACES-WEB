<?php
$conexion = mysqli_connect("localhost", "root", "", "Crud");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
else
{
    // echo "Conexión exitosa a la base de datos.";
}


?>
