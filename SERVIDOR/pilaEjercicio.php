<?php

class Pila {
    private $elementos = [];

    public function push($item) {
        array_push($this->elementos, $item);
    }

    public function pop() {
        if (!$this->estaVacia()) {
            return array_pop($this->elementos);
        }
        return null;
    }

    public function peek() {
        if (!$this->estaVacia()) {
            return end($this->elementos);
        }
        return null;
    }

    public function estaVacia() {
        return empty($this->elementos);
    }

    public function size() {
        return count($this->elementos);
    }

    public function mostrar() {
        print_r($this->elementos);
    }

    public function obtenerElementos() {
        return $this->elementos;
    }
}



class Navegador {
    private $historial;      // Pila principal
    private $paginaActual;   // Página que se está viendo
    private $contadorSaltos; // Contador de movimientos

    public function __construct() {
        $this->historial = new Pila();
        $this->paginaActual = null;
        $this->contadorSaltos = 0;
    }

    // Abrir una nueva página
    public function abrirPagina($url) {
        $this->historial->push($url);
        $this->paginaActual = $url;
        $this->contadorSaltos++;
        echo "Se abrió la página: $url <br>";
    }

    // Retroceder (pop del historial)
    public function retroceder() {
        if ($this->historial->size() > 1) {
            // Quita la página actual
            $this->historial->pop();
            // La página anterior pasa a ser la actual
            $this->paginaActual = $this->historial->peek();
            $this->contadorSaltos++;

      
            echo "No hay más páginas para retroceder.<br>";
        }
    }

    // Ver la página actual
    public function verPaginaActual() {
        if ($this->paginaActual !== null) {
            echo "Página actual: {$this->paginaActual}<br>";
            echo "Contenido simulado de {$this->paginaActual}: [Contenido de ejemplo]<br>";
            echo "Contador de saltos: {$this->contadorSaltos}<br>";
        } else {
            echo "No hay ninguna página abierta.<br>";
        }
    }

    // Mostrar el historial completo
    public function historialCompleto() {
        echo "<br><strong>Historial completo:</strong><br>";
        $historial = $this->historial->obtenerElementos();
        foreach ($historial as $i => $pagina) {
            echo ($i + 1) . ". $pagina<br>";
        }
    }
}



$navegador = new Navegador();

$navegador->abrirPagina("https://google.com");
$navegador->abrirPagina("https://wikipedia.org");
$navegador->abrirPagina("https://openai.com");

$navegador->retroceder();
$navegador->verPaginaActual();
$navegador->historialCompleto();

?>

