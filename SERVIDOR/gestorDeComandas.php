<?php

// Constante llamada APP_NAME y le asignamos el valor "Gestor de Comandas PHP" y se muestre en pantalla

const APP_NAME = "Gestor de Comandas PHP";
echo "<h1>" . APP_NAME . "</h1>";

// Declaracion de variables de distintos tipos de datos

$producto = "Tortilla de trufa"; // String
$precio = 10.20; // Float
$cantidad = 1; // Integer
$disponible = true; // Boolean

// Mostrar en pantalla el tipo de dato de cada variable con var_dump()
echo "<br><br>";
echo "<br> Producto: " .var_dump($producto);
echo "<br> Precio: " . var_dump($precio);
echo "<br> Cantidad: " . var_dump($cantidad);
echo "<br> Disponible: " . var_dump($disponible);

//Mostar en pantalla el tipo de dato de cada variable con gettype()
echo "<br><br>";
echo "Tipo de dato de la variable producto: " . gettype($producto);
echo "<br> Tipo de dato de la variable precio: " . gettype($precio);
echo "<br> Tipo de dato de la variable cantidad: " . gettype($cantidad);
echo "<br> Tipo de dato de la variable disponible: " . gettype($disponible);

$comanda = "- Ensalada: 1, - Paella: 2, - Tarta de quesito: 2";

// Recoger variables por la URL con GET 
$usuario = $_GET['usuario'];
$mesa = $_GET['mesa']; 

// Mostrar en pantalla el usuario y la mesa que ha reservado y usamos los puntos para concatenar las variables.
echo "<br><br> El usuario " . $usuario . " ha reservado la mesa " . $mesa . ". Tome asiento<br>";
echo "<br>";
//Convertimos la variable $usuario a mayusculas.
$usuario = strtoupper($usuario) . "<br>";
echo "Usuario en mayusculas: " . $usuario;


//Calcular la longitud de la variable $usuario con strlen() y mostrarla en pantalla.
$longitud = strlen($usuario);
echo "La longitud del nombre de usuario es: " . $longitud . "<br>";
echo "<br>";


//Voy a descomponer la comanda en un array con explode() y mostrarlo en pantalla y que me quite los guiones.
$comanda_array = explode(", ", $comanda);
    $descomoponer = str_replace("- ", "", $comanda_array);
    echo "Comanda descompuesta en array: <br>";
    foreach ($descomoponer as $item) {
        echo $item . "<br>";
    }
echo "<br>";

//Buscar un tipo de plato especifico en la comanda con strpos() y mostrarlo en pantalla
$platoBuscado = "Paella";
$cantidad = 1;

foreach ($descomoponer as $item) {
    // Verifica si el plato buscado está dentro del elemento actual
    if (strpos($item, $platoBuscado) !== false) {
        echo "El plato " . $platoBuscado . " se encuentra en la comanda.<br>";
        echo "Cantidad: " . $cantidad . "<br>";
        $encontrado = true; // Marca que se encontró
        break; // Sale del bucle (ya lo encontró)
    }
}

// Si después del bucle no se encontró el plato
if (empty($encontrado)) {
    echo "El plato " . $platoBuscado . " no se encuentra en la comanda.<br>";
}
?>



