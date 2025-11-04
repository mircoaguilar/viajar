document.addEventListener("DOMContentLoaded", () => {
  const precioAsiento = parseFloat(document.body.dataset.precio) || 0;
  const contador = document.getElementById("contador");
  const total = document.getElementById("total");
  const seleccionados = new Set();
  const viajeId = document.body.dataset.viajeId;

  document.querySelectorAll(".bus-container").forEach(async busContainer => {
    const pisoNum = busContainer.dataset.numero;
    const filas = parseInt(busContainer.dataset.filas);
    const columnas = parseInt(busContainer.dataset.asientos);
    const contenedorAsientos = busContainer.querySelector(".asientos");
    const ocupados = await fetchOcupados(pisoNum);

    generarPiso(contenedorAsientos, filas, columnas, ocupados, `Piso ${pisoNum}`);
  });

  function generarPiso(contenedor, filas, columnas, ocupados, pisoNombre) {
    let numAsiento = 1;

    for (let f = 0; f < filas; f++) {
      for (let c = 0; c < columnas + 1; c++) {
        if (c === Math.floor(columnas / 2)) {
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

  const modal = document.getElementById("modal-carrito");
  const modalCerrar = document.getElementById("modal-cerrar");
  const btnCancelar = document.getElementById("btn-cancelar");
  const btnAgregarCarrito = document.getElementById("btn-agregar-carrito");

  document.getElementById("btn-confirmar").addEventListener("click", () => {
    if (seleccionados.size === 0) {
      alert("No seleccionaste ningún asiento.");
      return;
    }

    const modalLista = document.getElementById("modal-lista");
    const modalTotal = document.getElementById("modal-total");

    modalLista.innerHTML = "";
    document.querySelectorAll(".asiento.seleccionado").forEach(a => {
      const piso = a.closest(".bus-container").dataset.numero;
      const num = a.textContent;
      const p = document.createElement("p");
      p.textContent = `Piso ${piso} - Asiento ${num}`;
      modalLista.appendChild(p);
    });

    modalTotal.textContent = (seleccionados.size * precioAsiento).toLocaleString("es-AR");
    modal.style.display = "flex";
  });

  modalCerrar.addEventListener("click", () => modal.style.display = "none");
  btnCancelar.addEventListener("click", () => modal.style.display = "none");

  btnAgregarCarrito.addEventListener("click", async () => {
    const asientosSeleccionados = Array.from(document.querySelectorAll(".asiento.seleccionado")).map(a => {
      return {
        piso: a.closest(".bus-container").dataset.numero,
        num: parseInt(a.textContent)
      };
    });

    try {
      const res = await fetch("controllers/viajes/ocupar_asientos.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ viajeId, asientos: asientosSeleccionados })
      });
      const data = await res.json();
      if (data.status !== "success") throw new Error(data.message || "Error al ocupar asientos");
    } catch (e) {
      alert("Ocurrió un error al ocupar los asientos: " + e.message);
      return;
    }

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

  async function fetchOcupados(pisoNum) {
    try {
      const response = await fetch(`controllers/viajes/asientos_ocupados.php?viaje=${viajeId}&piso=${pisoNum}`);
      if (!response.ok) throw new Error("Error al traer los asientos ocupados");
      const data = await response.json();
      return data.ocupados || [];
    } catch (e) {
      console.error(e);
      return [];
    }
  }
});
