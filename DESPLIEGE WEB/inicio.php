<?php

require 'conexion.php';


$email = trim($_POST['email']);
$pass = trim($_POST['contraseña']);

if($email === "" || $pass === ""){
    exit("Debe completar todos los campos.");

}else{
    $stmt = mysqli_prepare(mysql: $conexion,query: "SELECT admin FROM usuarios WHERE email = $email AND contraseña = $pass");
    $res = $stmt->execute();
    mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
    if(res){
        if($datos = myqli_fecht)
    }

}

?>
