document.addEventListener("DOMContentLoaded", () => {
  const precioAsiento = 2500;
  const contador = document.getElementById("contador");
  const total = document.getElementById("total");
  const seleccionados = new Set();

  const modal = document.getElementById("modal-carrito");
  const modalCerrar = document.getElementById("modal-cerrar");
  const btnAgregarCarrito = document.getElementById("btn-agregar-carrito");
  const btnCancelar = document.getElementById("btn-cancelar");
  const modalLista = document.getElementById("modal-lista");
  const modalTotal = document.getElementById("modal-total");

  generarPiso("asientos-piso1", 8, 4, ocupadosPiso1, "Piso 1");
  generarPiso("asientos-piso2", 8, 4, ocupadosPiso2, "Piso 2");

  function generarPiso(idContenedor, filas, columnas, ocupados, pisoNombre) {
    const contenedor = document.getElementById(idContenedor);
    let numAsiento = 1;

    for (let f = 0; f < filas; f++) {
      for (let c = 0; c < columnas + 1; c++) {
        if (c === 2) {
          const pasillo = document.createElement("div");
          pasillo.classList.add("vacio");
          contenedor.appendChild(pasillo);
          continue;
        }

        const div = document.createElement("div");
        div.classList.add("asiento");
        div.textContent = numAsiento;
        div.dataset.info = `${pisoNombre} - Asiento ${numAsiento}`;

        const idUnico = `${pisoNombre}-${numAsiento}`;

        if (ocupados.includes(numAsiento)) {
          div.classList.add("ocupado");
          div.dataset.info += " - Ocupado";
        } else {
          div.classList.add("disponible");
          div.addEventListener("click", () => toggleSeleccion(div, idUnico));
        }

        contenedor.appendChild(div);
        numAsiento++;
      }
    }
  }

  function toggleSeleccion(div, id) {
    if (div.classList.contains("seleccionado")) {
      div.classList.remove("seleccionado");
      seleccionados.delete(id);
      div.dataset.info = div.dataset.info.replace(" - Seleccionado", " - Disponible");
    } else {
      div.classList.add("seleccionado");
      seleccionados.add(id);
      div.dataset.info = div.dataset.info.replace(" - Disponible", " - Seleccionado");
    }
    actualizarResumen();
  }

  function actualizarResumen() {
    contador.textContent = seleccionados.size;
    total.textContent = (seleccionados.size * precioAsiento).toLocaleString("es-AR");
  }

  document.getElementById("btn-confirmar").addEventListener("click", () => {
    if (seleccionados.size === 0) {
      alert("No seleccionaste ningún asiento.");
      return;
    }

    modalLista.innerHTML = "";
    document.querySelectorAll(".asiento.seleccionado").forEach(a => {
      const piso = a.closest(".bus-container").querySelector("h3").textContent;
      const num = a.textContent;
      const p = document.createElement("p");
      p.textContent = `${piso} - Asiento ${num}`;
      modalLista.appendChild(p);
    });

    modalTotal.textContent = (seleccionados.size * precioAsiento).toLocaleString("es-AR");
    modal.style.display = "block";
  });

  modalCerrar.addEventListener("click", () => modal.style.display = "none");
  btnCancelar.addEventListener("click", () => modal.style.display = "none");

  btnAgregarCarrito.addEventListener("click", () => {
    alert("Asientos agregados al carrito");
    modal.style.display = "none";
    document.querySelectorAll(".asiento.seleccionado").forEach(a => {
      a.classList.remove("seleccionado");
      a.classList.add("ocupado");
    });
    seleccionados.clear();
    actualizarResumen();
  });

  window.addEventListener("click", e => {
    if (e.target == modal) modal.style.display = "none";
  });
});
