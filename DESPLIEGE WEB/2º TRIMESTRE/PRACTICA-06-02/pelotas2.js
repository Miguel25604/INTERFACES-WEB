let tiempoRestante = 0;
let puntos = 0;

let intervaloTiempo = null;
let intervaloPelotas = null;

window.addEventListener("load", () => {
  document.getElementById("btnIniciar").addEventListener("click", iniciarJuego);
  document.getElementById("puntos").textContent = "0";
  document.getElementById("tiempo").textContent = "0";
});

function iniciarJuego() {
  // Reset
  puntos = 0;
  document.getElementById("puntos").textContent = puntos;

  tiempoRestante = parseInt(document.getElementById("segundos").value, 10);
  document.getElementById("tiempo").textContent = tiempoRestante;

  // Limpiar pelotas antiguas
  limpiarZona();

  // Evitar doble inicio
  pararJuego();

  // Desactivar botón mientras juega
  document.getElementById("btnIniciar").disabled = true;

  // Temporizador
  intervaloTiempo = setInterval(() => {
    tiempoRestante--;
    document.getElementById("tiempo").textContent = tiempoRestante;

    if (tiempoRestante <= 0) {
      finJuego();
    }
  }, 1000);

  // Crear pelotas cada X ms
  // (ajusta la velocidad si quieres: 600 = más rápido, 1200 = más lento)
  intervaloPelotas = setInterval(() => {
    crearPelota();
  }, 800);
}

function finJuego() {
  pararJuego();
  document.getElementById("btnIniciar").disabled = false;

  // Opcional: borrar pelotas al final
  // limpiarZona();

  alert("⏱ Tiempo finalizado. Puntos logrados: " + puntos);
}

function pararJuego() {
  if (intervaloTiempo) clearInterval(intervaloTiempo);
  if (intervaloPelotas) clearInterval(intervaloPelotas);
  intervaloTiempo = null;
  intervaloPelotas = null;
}

function limpiarZona() {
  const zona = document.getElementById("zona");
  zona.innerHTML = "";
}

function crearPelota() {
  const zona = document.getElementById("zona");

  // Tamaño aleatorio
  const tamano = randInt(40, 110);

  // Posición aleatoria (asegurando que NO se salga del área)
  const maxLeft = zona.clientWidth - tamano;
  const maxTop = zona.clientHeight - tamano;

  const left = randInt(0, Math.max(0, maxLeft));
  const top = randInt(0, Math.max(0, maxTop));

  // Color según selector
  const selectorColor = document.getElementById("color").value;
  const colores = ["red", "green", "blue"];
  const color = (selectorColor === "random")
    ? colores[randInt(0, colores.length - 1)]
    : selectorColor;

  // Crear div pelota
  const circle = document.createElement("div");
  circle.className = "circle";
  circle.style.width = tamano + "px";
  circle.style.height = tamano + "px";
  circle.style.left = left + "px";
  circle.style.top = top + "px";
  circle.style.backgroundColor = color;

  // Click = sumar puntos y borrar pelota
  circle.addEventListener("click", () => {
    puntos++;
    document.getElementById("puntos").textContent = puntos;
    circle.remove();
  });

  // Si no la pulsas, desaparece sola (para hacerlo más juego)
  setTimeout(() => {
    if (circle.isConnected) circle.remove();
  }, 1200);

  zona.appendChild(circle);
}

function randInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}



