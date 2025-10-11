<?php

    /* Recibir 4 números por la URL y mostrarlos por pantalla. */
    $n1 = $_GET['n1'];
    $n2 = $_GET['n2'];
    $n3 = $_GET['n3'];
    $n4 = $_GET['n4'];


    echo "Número 1: $n1 <br>";
    echo "Número 2: $n2 <br>";
    echo "Número 3: $n3 <br>";
    echo "Número 4: $n4 <br>";


?>

<?php
    /* ARRAY VACIO */
    
    $numeros = array();


?>

<?php

    /* ARRAY TODOS LOS NUMEROS AL FINAL DEL ARRAY */
    $numeros = array();

    $numeros[] = 4;
    $numeros[] = 8;
    $numeros[] = 15;
    $numeros[] = 16;
    
    /*MOSTRAMOS EL ARRAY */
    var_dump($numeros);
?>