
<?php
/*
<p>Un archivo PHP puede contener a HTML </p>

    <?php
        echo "Lo que escriba aqui dentro es PHP pero se convierte a HTML";
    ?>

<p>Puedo continuar con HTML.puro </p>
*/
?>

<?php
/*
 $agenda = [];          // Array vacio
 $agenda = [1] => "Juan"; // Asignacion de valor a un indice
 $agenda = [2] => "Ana";
*/

 ?>

 <?php
/*
    $agenda[0] = "Jose Vicente";
    $agenda[1] = "Juan";
    var_dump($agenda);
    echo "<br>";
    array_push($agenda,"Jorge");
    var_dump($agenda);
    echo "<br>";
    array_pop($agenda);
    var_dump($agenda);
    */

?>

<?php
    
    $agenda["nombre"] = "Jose Vicente";
    $agenda["email"] = "info@josevicentecarratala.com";
    $agenda["telefono"] = "6345646";
    var_dump($agenda);
    
?>

<?php
    //MATRIZ
    /*
    $agenda[0]["nombre"] = "Jose Vicente";
    $agenda[0]["email"] = "info@josevicentecarratala.com";
    $agenda[0]["telefono"] = "6345646";

    $agenda[1]["nombre"] = "Juan";
    $agenda[1]["email"] = "juan@josevicentecarratala.com";
    $agenda[1]["telefono"] = "6345646";

    $agenda[2]["nombre"] = "Jorge";
    $agenda[2]["email"] = "jorge@josevicentecarratala.com";
    $agenda[2]["telefono"] = "6345646";

    var_dump($agenda);
    */
?>


//Bucle for

<?php
/*
     for($dia =1; $dia <= 31; $dia++){
        echo "Hoy es el dia": .$dia. "del mes <br>";
     }
        */
?>

<?php
    //Bucle do while
    $dia = 1;
    do{
        echo "te digo hola";
    }while($dia > 5);
?>

<?php
    //Bucle while
    while($dia<=31){
        echo "hoy es el dia ".$dia." del mes <br>";
    }
    
?>

<?php
    //Condicional if
    $edad = 45;
    if($edad < 30){
        echo "Eres un joven";
    }
    
?>

<?php
    //Condicional if else
    $edad = 45;
    if($edad < 30){
        echo "Eres un joven";
    }else{
        echo "ya no eres un joven";
    }
    
?>
<?php
    //Condicional if else anidado
    $edad = 45;
    if($edad < 30){
        if($edad < 10){
        
            echo "Eres un niño";
        }else{
            echo "Eres un joven";
        }
        
    }else{
        if($edad < 70){
        
            echo "Ya no eres un joven";
        }else{
            echo "Eres un senior";
        }
    }
    
?>