<?php

// Crear 5 colas usando SplQueue
$pedidos_espera = new SplQueue();   // Cola 1
$parrilla       = new SplQueue();   // Cola 2
$horno          = new SplQueue();   // Cola 3
$plancha        = new SplQueue();   // Cola 4
$emplatar       = new SplQueue();   // Cola 5


// Agregar pedidos a la cola de espera
$pedidos_espera->enqueue("Pedido 1: Hamburguesas con bacon");
$pedidos_espera->enqueue("Pedido 2: Chuletas ");
$pedidos_espera->enqueue("Pedido 3: Arroz al horno");

echo "Pedidos en espera:\n<br><br>";

if (!$pedidos_espera->isEmpty()) {
    echo "Primer pedido en espera: " . $pedidos_espera->bottom() . "<br><br>";
}
function hayHueco(SplQueue $q, int $cap) {
    return $q->count() < $cap; // menor que 3
}


?>