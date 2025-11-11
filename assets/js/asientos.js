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
        if (result.isConfirmed) {
          window.location.href = "index.php?page=login";
        }
      });
      return;
    }

    if (seleccionados.size === 0) {
      Swal.fire({
        icon: "info",
        title: "Sin asientos seleccionados",
        text: "Seleccioná al menos un asiento antes de continuar.",
        confirmButtonText: "Entendido"
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
              ${aux.tipos_documento.map(t => `<option value="${t.id}">${escapeHtml(t.nombre)}</option>`).join("")}
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
              ${aux.nacionalidades.map(n => `<option value="${n.id}">${escapeHtml(n.nombre)}</option>`).join("")}
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
            <input type="text" name="pasajeros[${i}][fecha_nacimiento]" class="flatpickr-date">
          </div>
        </div>

        <input type="hidden" name="pasajeros[${i}][asiento_piso]" value="${piso}">
        <input type="hidden" name="pasajeros[${i}][asiento_numero]" value="${num}">
      `;

      contenedorFormularios.appendChild(formGroup);

      try {
        if (typeof $ !== "undefined" && $.fn && $.fn.select2) {
          const $nac = $(`#nacionalidad_${i}`);
          const $tipo = $(`#tipo_doc_${i}`);

          if ($nac.data("select2")) $nac.select2("destroy");
          if ($tipo.data("select2")) $tipo.select2("destroy");

          $nac.select2({
            width: "100%",
            dropdownParent: $(formGroup),
            placeholder: "Seleccionar nacionalidad"
          });
          $tipo.select2({
            width: "100%",
            dropdownParent: $(formGroup),
            placeholder: "Seleccionar tipo documento"
          });
        }
      } catch (err) {
        console.error("Error inicializando select2 para pasajero", i, err);
      }

      try {
        if (typeof flatpickr !== "undefined") {
          flatpickr(formGroup.querySelector(".flatpickr-date"), {
            locale: "es",
            dateFormat: "d/m/Y",
            maxDate: "today",
            allowInput: true
          });
        }
      } catch (err) {
        console.error("Error inicializando flatpickr para pasajero", i, err);
      }
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

    formPasajeros.querySelectorAll(".error-msg").forEach(el => el.remove());
    formPasajeros.querySelectorAll("input, select").forEach(el => el.classList.remove("input-error"));

    let camposInvalidos = 0;

    formPasajeros.querySelectorAll(".form-pasajero").forEach(grupo => {
      const nombre = grupo.querySelector("input[name*='[nombre]']");
      const apellido = grupo.querySelector("input[name*='[apellido]']");
      const tipoDoc = grupo.querySelector("select[name*='[rela_tipo_documento]']");
      const nroDoc = grupo.querySelector("input[name*='[numero_documento]']");
      const nacionalidad = grupo.querySelector("select[name*='[rela_nacionalidad]']");
      const sexo = grupo.querySelector("select[name*='[sexo]']");
      const fecha = grupo.querySelector("input[name*='[fecha_nacimiento]']");

      const campos = [
        { el: nombre, nombre: "Nombre" },
        { el: apellido, nombre: "Apellido" },
        { el: tipoDoc, nombre: "Tipo de documento" },
        { el: nroDoc, nombre: "Número de documento" },
        { el: nacionalidad, nombre: "Nacionalidad" },
        { el: sexo, nombre: "Sexo" },
        { el: fecha, nombre: "Fecha de nacimiento" },
      ];

      campos.forEach(c => {
        if (!c.el.value.trim()) {
          mostrarError(c.el, `${c.nombre} es obligatorio`);
          camposInvalidos++;
        }
      });
    });

    if (camposInvalidos > 0) {
      Swal.fire({
        icon: "warning",
        title: "Campos incompletos",
        text: "Por favor, completá todos los datos de los pasajeros antes de continuar."
      });
      return;
    }

    const formData = new FormData(formPasajeros);
    formData.append("tipo_servicio", "transporte");
    formData.append("id_servicio", viajeId);
    formData.append("cantidad", seleccionados.size);
    formData.append("precio_unitario", precioAsiento);
    formData.append("fecha_servicio", viajeFecha);

    const asientosSeleccionados = Array.from(document.querySelectorAll(".asiento.seleccionado")).map(a => {
      const busContainer = a.closest(".bus-container");
      const columnas = parseInt(busContainer.dataset.asientos, 10);
      const piso = busContainer.dataset.numero;
      const numero = parseInt(a.textContent, 10);
      const fila = Math.ceil(numero / columnas);
      const columna = numero - (fila - 1) * columnas;
      return { piso: parseInt(piso, 10), numero, fila, columna };
    });
    formData.append("asientos", JSON.stringify(asientosSeleccionados));

    try {
      const res = await fetch("controllers/carrito/carrito.controlador.php?action=agregar", {
        method: "POST",
        body: formData
      });

      const contentType = res.headers.get("content-type") || "";
      let data;

      if (contentType.includes("application/json")) {
        data = await res.json();
      } else {
        const text = await res.text();
        console.error("Respuesta no-JSON del servidor:", text);
        Swal.fire({
          icon: "error",
          title: "Error en el servidor",
          html: `<div style="text-align:left;white-space:pre-wrap;max-height:300px;overflow:auto;">${escapeHtml(text).slice(0,1000)}</div>`,
          confirmButtonText: "Cerrar"
        });
        return;
      }

      if (data.status !== "success") throw new Error(data.message || "Error al agregar al carrito");

      await Swal.fire({
        icon: "success",
        title: "¡Agregado al carrito!",
        text: "Los asientos y datos de los pasajeros se guardaron correctamente.",
        confirmButtonText: "Aceptar"
      });

      modalCompleto.style.display = "none";
      document.querySelectorAll(".asiento.seleccionado").forEach(a => {
        a.classList.remove("seleccionado");
        a.classList.add("ocupado");
      });
      seleccionados.clear();
      actualizarResumen();

    } catch (err) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un error: " + err.message
      });
    }
  });

  function mostrarError(elemento, mensaje) {
    const error = document.createElement("div");
    error.classList.add("error-msg");
    error.textContent = mensaje;
    elemento.classList.add("input-error");

    const isSelect2 = elemento.classList.contains("select2-hidden-accessible");

    if (isSelect2) {
      const select2Container = elemento.nextElementSibling;
      if (select2Container && select2Container.classList.contains("select2")) {
        select2Container.classList.add("input-error");
        select2Container.insertAdjacentElement("afterend", error);
        $(elemento).on("change.select2", function () {
          error.remove();
          select2Container.classList.remove("input-error");
        });
        return;
      }
    }

    elemento.insertAdjacentElement("afterend", error);
    elemento.addEventListener("input", () => {
      if (elemento.value.trim() !== "") {
        error.remove();
        elemento.classList.remove("input-error");
      }
    });
    elemento.addEventListener("change", () => {
      if (elemento.value.trim() !== "") {
        error.remove();
        elemento.classList.remove("input-error");
      }
    });
  }

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

  function escapeHtml(str) {
    if (str === null || str === undefined) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }
});
