document.addEventListener("DOMContentLoaded", () => {
  const precioAsiento = parseFloat(document.body.dataset.precio) || 0;
  const contador = document.getElementById("contador");
  const total = document.getElementById("total");
  const seleccionados = new Set();
  const viajeId = document.body.dataset.viajeId;

async function fetchOcupados(piso) {
    try {
        const res = await fetch(
            `controllers/viajes/asientos_ocupados.php?viaje=${viajeId}&piso=${piso}`
        );
        const data = await res.json();
        return data.ocupados || [];
    } catch (e) {
        console.error("Error trayendo ocupados:", e);
        return [];
    }
}

async function bloquearAsiento(piso, numero) {
    const form = new FormData();
    form.append("id_viaje", viajeId);      
    form.append("piso", piso);
    form.append("numero_asiento", numero);

    try {
        const res = await fetch("controllers/pasajeros/bloquear_asiento.php", {
            method: "POST",
            body: form
        });
        const data = await res.json();
        return data.status === "success";
    } catch (e) {
        console.error("Error al bloquear asiento", e);
        return false;
    }
}

async function liberarAsiento(piso, numero) {
    const form = new FormData();
    form.append("id_viaje", viajeId);
    form.append("piso", piso);
    form.append("numero_asiento", numero);

    try {
        const res = await fetch("controllers/pasajeros/liberar_asiento.php", {
            method: "POST",
            body: form
        });
        const data = await res.json();
        return data.status === "success";
    } catch (e) {
        console.error("Error al liberar asiento", e);
        return false;
    }
}



  document.querySelectorAll(".bus-container").forEach(async busContainer => {
    const pisoNum = busContainer.dataset.numero;
    const filas = parseInt(busContainer.dataset.filas);
    const columnas = parseInt(busContainer.dataset.asientos);
    const contenedorAsientos = busContainer.querySelector(".asientos");

    const ocupados = await fetchOcupados(pisoNum);
    generarPiso(contenedorAsientos, filas, columnas, ocupados, pisoNum);
  });

  function generarPiso(contenedor, filas, columnas, ocupados, pisoNum) {
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

        const idUnico = `${pisoNum}-${numAsiento}`;

        if (ocupados.includes(numAsiento)) {
          div.classList.add("ocupado");
        } else {
          div.classList.add("disponible");
          div.addEventListener("click", () => toggleSeleccion(div, pisoNum, numAsiento, idUnico));
        }

        contenedor.appendChild(div);
        numAsiento++;
      }
    }
  }

  async function toggleSeleccion(div, piso, numero, idUnico) {

    if (div.classList.contains("ocupado")) {
      Swal.fire({
        icon: "info",
        title: "Asiento ocupado",
        text: "Este asiento acaba de ser tomado por otro usuario."
      });
      return;
    }

    if (div.classList.contains("seleccionado")) {
      div.classList.remove("seleccionado");
      seleccionados.delete(idUnico);
      await liberarAsiento(piso, numero);
      return actualizarResumen();
    }

    const ok = await bloquearAsiento(piso, numero);
    if (!ok) {
      div.classList.remove("disponible");
      div.classList.add("ocupado");

      Swal.fire({
        icon: "info",
        title: "Asiento ocupado",
        text: "Este asiento acaba de ser tomado por otro usuario."
      });

      return;
    }

    div.classList.add("seleccionado");
    seleccionados.add(idUnico);

    actualizarResumen();
  }

  function actualizarResumen() {
    contador.textContent = seleccionados.size;
    total.textContent = (seleccionados.size * precioAsiento).toLocaleString("es-AR");
  }

  const modalCompleto = document.getElementById("modal-completo");
  const modalCerrar = document.getElementById("modal-completo-cerrar");
  const listaAsientos = document.getElementById("lista-asientos");
  const contenedorFormularios = document.getElementById("contenedor-formularios");
  const modalTotal = document.getElementById("modal-total-unique");
  const formPasajeros = document.getElementById("form-pasajeros-completo");

  let datosAuxiliares = null;

  async function cargarDatosAuxiliares() {
    if (datosAuxiliares) return datosAuxiliares;

    try {
      const res = await fetch("controllers/viajes/datos_auxiliares.php");
      if (!res.ok) throw new Error("Error al obtener datos auxiliares");
      datosAuxiliares = await res.json();
      return datosAuxiliares;
    } catch (err) {
      console.error(err);
      return { nacionalidades: [], tipos_documento: [] };
    }
  }

  async function verificarSesion() {
    try {
      const response = await fetch("controllers/usuarios/usuarios.controlador.php?action=verificar_sesion");
      if (!response.ok) throw new Error("No se pudo verificar la sesión");

      const data = await response.json();
      return data.logged_in === true;
    } catch (error) {
      console.error("Error verificando sesión:", error);
      return false;
    }
  }

  document.getElementById("btn-confirmar").addEventListener("click", async () => {
    const logged = await verificarSesion();

    if (!logged) {
      Swal.fire({
        icon: "warning",
        title: "Inicia sesión",
        text: "Debes iniciar sesión para continuar con la compra.",
        showCancelButton: true,
        confirmButtonText: "Ir al inicio de sesión",
        cancelButtonText: "Cancelar"
      }).then(result => {
        if (result.isConfirmed) window.location.href = "index.php?page=login";
      });
      return;
    }

    if (seleccionados.size === 0) {
      Swal.fire({
        icon: "info",
        title: "Sin asientos seleccionados",
        text: "Seleccioná al menos un asiento antes de continuar."
      });
      return;
    }

    listaAsientos.innerHTML = "";
    contenedorFormularios.innerHTML = "";

    const seleccion = Array.from(document.querySelectorAll(".asiento.seleccionado"));
    const aux = await cargarDatosAuxiliares();

    seleccion.forEach((a, i) => {
      const piso = a.closest(".bus-container").dataset.numero;
      const num = a.textContent;

      const p = document.createElement("p");
      p.textContent = `Piso ${piso} - Asiento ${num}`;
      listaAsientos.appendChild(p);

      const formGroup = document.createElement("div");
      formGroup.classList.add("form-pasajero");

      formGroup.innerHTML = `
        <h5>Pasajero ${i + 1} (${piso}-${num})</h5>

        <label>Nombre</label>
        <input type="text" name="pasajeros[${i}][nombre]">

        <label>Apellido</label>
        <input type="text" name="pasajeros[${i}][apellido]">

        <div class="dual">
          <div>
            <label>Tipo documento</label>
            <select id="tipo_doc_${i}" name="pasajeros[${i}][rela_tipo_documento]">
              <option value="">Seleccionar</option>
              ${aux.tipos_documento.map(t => `<option value="${t.id}">${t.nombre}</option>`).join("")}
            </select>
          </div>

          <div>
            <label>Número documento</label>
            <input type="text" name="pasajeros[${i}][numero_documento]">
          </div>

          <div>
            <label>Nacionalidad</label>
            <select id="nacionalidad_${i}" name="pasajeros[${i}][rela_nacionalidad]">
              <option value="">Seleccionar</option>
              ${aux.nacionalidades.map(n => `<option value="${n.id}">${n.nombre}</option>`).join("")}
            </select>
          </div>
        </div>

        <div class="dual">
          <div>
            <label>Sexo</label>
            <select name="pasajeros[${i}][sexo]">
              <option value="">Seleccionar</option>
              <option value="Masculino">Masculino</option>
              <option value="Femenino">Femenino</option>
              <option value="Otro">Otro</option>
            </select>
          </div>

          <div>
            <label>Fecha de nacimiento</label>
            <input type="text" class="flatpickr-date" name="pasajeros[${i}][fecha_nacimiento]">
          </div>
        </div>

        <input type="hidden" name="pasajeros[${i}][asiento_piso]" value="${piso}">
        <input type="hidden" name="pasajeros[${i}][asiento_numero]" value="${num}">
      `;

      contenedorFormularios.appendChild(formGroup);

      try {
        if (typeof $ !== "undefined" && $.fn.select2) {
          $(`#nacionalidad_${i}`).select2({ width: "100%", dropdownParent: $(formGroup) });
          $(`#tipo_doc_${i}`).select2({ width: "100%", dropdownParent: $(formGroup) });
        }
      } catch (err) {}

      try {
        flatpickr(formGroup.querySelector(".flatpickr-date"), {
          locale: "es",
          dateFormat: "d/m/Y",
          maxDate: "today",
          allowInput: true
        });
      } catch (err) {}
    });

    modalTotal.textContent = (seleccionados.size * precioAsiento).toLocaleString("es-AR");
    modalCompleto.style.display = "flex";
  });

  modalCerrar.addEventListener("click", () => (modalCompleto.style.display = "none"));
  document.getElementById("btn-cancelar-pasajeros").addEventListener("click", () => (modalCompleto.style.display = "none"));
  window.addEventListener("click", e => {
    if (e.target === modalCompleto) modalCompleto.style.display = "none";
  });

  formPasajeros.addEventListener("submit", async e => {
    e.preventDefault();
    console.log("Envío de formulario de pasajeros.");
  });

async function refrescarEstado() {
  document.querySelectorAll(".bus-container").forEach(async busContainer => {
    const piso = busContainer.dataset.numero;
    const contenedor = busContainer.querySelector(".asientos");

    try {
      const res = await fetch(`controllers/viajes/asientos_ocupados.php?viaje=${viajeId}&piso=${piso}`);
      const data = await res.json();

      const definitivos = data.debug?.definitivos || [];
      const temporales = data.debug?.temporales || [];

      contenedor.querySelectorAll(".asiento").forEach(div => {
        const numero = parseInt(div.textContent);
        const idUnico = `${piso}-${numero}`;

        if (div.classList.contains("seleccionado")) return;

        div.classList.remove("ocupado", "bloqueado", "disponible");

        if (definitivos.includes(numero)) {
          div.classList.add("ocupado");
        } else if (temporales.includes(numero)) {
          div.classList.add("bloqueado");
        } else {
          div.classList.add("disponible");
        }
      });

    } catch (e) {
      console.error("Error refrescando estado:", e);
    }
  });
}

setInterval(refrescarEstado, 3000);


});
