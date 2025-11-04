document.addEventListener("DOMContentLoaded", () => {
  const precioAsiento = parseFloat(document.body.dataset.precio) || 0;
  const contador = document.getElementById("contador");
  const total = document.getElementById("total");
  const seleccionados = new Set();
  const viajeId = document.body.dataset.viajeId;
  const viajeFecha = document.body.dataset.fecha; 

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

  modalCerrar.addEventListener("click", () => (modal.style.display = "none"));
  btnCancelar.addEventListener("click", () => (modal.style.display = "none"));

  btnAgregarCarrito.addEventListener("click", async () => {
    const cantidad = seleccionados.size;
    if (cantidad === 0) {
      alert("No seleccionaste ningún asiento.");
      return;
    }

    const asientosSeleccionados = Array.from(document.querySelectorAll(".asiento.seleccionado")).map(a => {
      const busContainer = a.closest(".bus-container");
      const columnas = parseInt(busContainer.dataset.asientos, 10);
      const piso = busContainer.dataset.numero;
      const numero = parseInt(a.textContent, 10);

      const fila = Math.ceil(numero / columnas);
      const columna = numero - (fila - 1) * columnas;

      return { piso: parseInt(piso, 10), numero, fila, columna };
    });

    const formData = new FormData();
    formData.append("tipo_servicio", "transporte");
    formData.append("id_servicio", viajeId);
    formData.append("cantidad", cantidad);
    formData.append("precio_unitario", precioAsiento);
    formData.append("asientos", JSON.stringify(asientosSeleccionados));
    formData.append("fecha_servicio", viajeFecha);

    try {
      const res = await fetch("controllers/carrito/carrito.controlador.php?action=agregar", {
        method: "POST",
        body: formData
      });

      const data = await res.json();
      if (data.status !== "success") throw new Error(data.message || "Error al agregar al carrito");

      alert("Asientos agregados al carrito correctamente");

      modal.style.display = "none";
      document.querySelectorAll(".asiento.seleccionado").forEach(a => {
        a.classList.remove("seleccionado");
        a.classList.add("ocupado");
      });
      seleccionados.clear();
      actualizarResumen();

    } catch (e) {
      alert("Ocurrió un error al agregar los asientos: " + e.message);
    }
  });

  window.addEventListener("click", e => {
    if (e.target === modal) modal.style.display = "none";
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
