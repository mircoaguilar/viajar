document.addEventListener("DOMContentLoaded", function () {
  const menu = document.getElementById("menu");
  const main = document.getElementById("main");
  const overlay = document.getElementById("overlay");
  const toggleButton = document.getElementById("menu-toggle");

  let menuAbierto = false;

  /*  Mostrar menú lateral */
  function abrirMenu() {
    menu.classList.add("menu-visible");
    overlay.classList.add("visible");
    menuAbierto = true;
  }

  /*  Ocultar menú lateral */
  function cerrarMenu() {
    menu.classList.remove("menu-visible");
    overlay.classList.remove("visible");
    menuAbierto = false;
  }

  /*  Botón de apertura/cierre */
  if (toggleButton) {
    toggleButton.addEventListener("click", function () {
      if (menuAbierto) {
        cerrarMenu();
      } else {
        abrirMenu();
      }
    });
  }

  /*  Cerrar al hacer clic en el overlay */
  if (overlay) {
    overlay.addEventListener("click", function () {
      cerrarMenu();
    });
  }
});
