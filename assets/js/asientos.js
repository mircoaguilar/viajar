document.addEventListener("DOMContentLoaded", () => {
    const precioAsiento = parseFloat(document.body.dataset.precio) || 0;
    const contador = document.getElementById("contador");
    const total = document.getElementById("total");
    const seleccionados = new Set();
    const viajeId = document.body.dataset.viajeId;
    const BLOCK_TIME_SECONDS = 5 * 60; 
    let countdownInterval;

    function startCountdown() {
        const timerElement = document.getElementById("countdown-timer");
        const timeLeftElement = document.getElementById("time-left");
        let seconds = BLOCK_TIME_SECONDS;

        timerElement.style.display = 'block';

        if (countdownInterval) clearInterval(countdownInterval);

        countdownInterval = setInterval(() => {
            seconds--;

            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;

            timeLeftElement.textContent = 
                `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;

            if (seconds <= 0) {
                clearInterval(countdownInterval);
                timerElement.style.display = 'none';

                Swal.fire({
                    icon: "warning",
                    title: "Tiempo Expirado",
                    text: "El tiempo para completar tu reserva ha terminado. Los asientos seleccionados han sido liberados.",
                    allowOutsideClick: false,
                    showConfirmButton: true
                }).then(() => {
                    window.location.reload(); 
                });
            }
        }, 1000);
    }
    
    function stopCountdown() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            document.getElementById("countdown-timer").style.display = 'none';
        }
    }

    async function fetchOcupados(piso) {
        try {
            const res = await fetch(`controllers/viajes/asientos_ocupados.php?viaje=${viajeId}&piso=${piso}`);
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

            if (data.status === "success") {
                return true;  
            } else {
                console.error("Error al bloquear asiento:", data.message); 
                return false;
            }
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
            const res = await fetch("controllers/pasajeros/liberar_bloqueo.php", {
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
        const asientosPorLado = Math.floor(columnas / 2); 
        const totalDeElementosPorFila = columnas + 1; 

        for (let f = 0; f < filas; f++) {
            const filaDiv = document.createElement("div");
            filaDiv.classList.add("bus-fila");

            for (let c = 0; c < totalDeElementosPorFila; c++) {
                
                if (c === asientosPorLado) {
                    const pasillo = document.createElement("div");
                    pasillo.classList.add("vacio");
                    filaDiv.appendChild(pasillo);
                    continue; 
                }
                const numeroAsientoActual = numAsiento;

                const div = document.createElement("div");
                div.classList.add("asiento");
                div.textContent = numeroAsientoActual;  

                const idUnico = `${pisoNum}-${numeroAsientoActual}`;

                if (ocupados.includes(numeroAsientoActual)) {
                    div.classList.add("ocupado");
                } else {
                    div.classList.add("disponible");
                    div.addEventListener("click", () => toggleSeleccion(div, pisoNum, numeroAsientoActual, idUnico)); 
                }

                filaDiv.appendChild(div);
                numAsiento++;
            }
            contenedor.appendChild(filaDiv);
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
          
          if (seleccionados.size === 0) {
              stopCountdown(); 
          }

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

      if (seleccionados.size === 1) {
          startCountdown(); 
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

    async function buscarPasajero(docNum) {
        const form = new FormData();
        form.append("numero_documento", docNum);
        try {
            const res = await fetch("controllers/pasajeros/check.php", {
                method: "POST",
                body: form
            });
            return await res.json();
        } catch (e) {
            console.error("Error buscando pasajero:", e);
            return { status: "error" };
        }
    }

    function llenarFormulario(formElement, data) {
        formElement.querySelector(`[name$="[nombre]"]`).value = data.nombre || '';
        formElement.querySelector(`[name$="[apellido]"]`).value = data.apellido || '';
        formElement.querySelector(`[name$="[sexo]"]`).value = data.sexo || '';

        const fechaNacimientoInput = formElement.querySelector(`[name$="[fecha_nacimiento]"]`);
        if (data.fecha_nacimiento) {
            const [year, month, day] = data.fecha_nacimiento.split('-');
            fechaNacimientoInput.value = `${day}/${month}/${year}`;
        } else {
             fechaNacimientoInput.value = '';
        }
        
        $(`#nacionalidad_${formElement.dataset.index}`).val(data.rela_nacionalidad).trigger('change');
        $(`#tipo_doc_${formElement.dataset.index}`).val(data.rela_tipo_documento).trigger('change');
    }

    function limpiarFormulario(formElement) {
        formElement.querySelector(`[name$="[nombre]"]`).value = '';
        formElement.querySelector(`[name$="[apellido]"]`).value = '';
        formElement.querySelector(`[name$="[sexo]"]`).value = '';
        formElement.querySelector(`[name$="[fecha_nacimiento]"]`).value = '';
        
        const index = formElement.dataset.index;
        $(`#nacionalidad_${index}`).val('').trigger('change');
        $(`#tipo_doc_${index}`).val('').trigger('change');
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
            formGroup.dataset.index = i; 

            formGroup.innerHTML = `
                <input type="hidden" name="pasajeros[${i}][id_pasajeros]" value="">
                <h5 style="display:flex; justify-content:space-between; align-items:center;">
                    Pasajero ${i + 1} (${piso}-${num})
                    <span class="error-msg document-error-${i}" style="display:none; margin:0;"></span>
                </h5>

                <div class="field-row">
                    <label>Nombre</label>
                    <input type="text" name="pasajeros[${i}][nombre]" >
                </div>

                <div class="field-row">
                    <label>Apellido</label>
                    <input type="text" name="pasajeros[${i}][apellido]" >
                </div>

                <div class="dual">
                    <div>
                        <label>Tipo documento</label>
                        <select id="tipo_doc_${i}" name="pasajeros[${i}][rela_tipo_documento]" >
                            <option value="">Seleccionar</option>
                            ${aux.tipos_documento.map(t => `<option value="${t.id}">${t.nombre}</option>`).join("")}
                        </select>
                    </div>

                    <div>
                        <label>Número documento</label>
                        <input type="text" name="pasajeros[${i}][numero_documento]" class="documento-input" data-index="${i}" >
                    </div>

                    <div>
                        <label>Nacionalidad</label>
                        <select id="nacionalidad_${i}" name="pasajeros[${i}][rela_nacionalidad]" required>
                            <option value="">Seleccionar</option>
                            ${aux.nacionalidades.map(n => `<option value="${n.id}">${n.nombre}</option>`).join("")}
                        </select>
                    </div>
                </div>

                <div class="dual">
                    <div>
                        <label>Sexo</label>
                        <select name="pasajeros[${i}][sexo]" >
                            <option value="">Seleccionar</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div>
                        <label>Fecha de nacimiento</label>
                        <input type="text" class="flatpickr-date" name="pasajeros[${i}][fecha_nacimiento]" >
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

            formGroup.querySelector('.documento-input').addEventListener('input', async (event) => {
                const docNum = event.target.value.trim();
                const errorSpan = document.querySelector(`.document-error-${i}`);
                const ownerIdInput = formGroup.querySelector(`[name$="[id_pasajeros]"]`);

                errorSpan.style.display = 'none';
                ownerIdInput.value = '';

                if (docNum.length < 5) { 
                    return;
                }

                const result = await buscarPasajero(docNum);

                if (result.status === 'error') {
                    console.error("Error en la verificación del DNI.");
                    return;
                }
                
                if (result.found) {
                    if (result.owner) {
                        Swal.fire({
                            title: 'Pasajero Registrado',
                            text: `¿El pasaje es para ${result.data.nombre} ${result.data.apellido}?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, Cargar Datos',
                            cancelButtonText: 'No, Dejar Manual'
                        }).then(swalResult => {
                            if (swalResult.isConfirmed) {
                                llenarFormulario(formGroup, result.data);
                                ownerIdInput.value = result.data.id_pasajeros; 
                            }
                        });
                    } else {
                        limpiarFormulario(formGroup); 
                        errorSpan.textContent = 'Doc. registrado por otro usuario.';
                        errorSpan.style.display = 'block';
                    }
                } else {
                    errorSpan.style.display = 'none';
                }
            });
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
      const formulariosPasajeros = document.querySelectorAll(".form-pasajero");
      let valid = true;
      let primerCampoVacio = null;
      
      const camposObligatorios = [
          { name: 'nombre', label: 'Nombre' },
          { name: 'apellido', label: 'Apellido' },
          { name: 'rela_tipo_documento', label: 'Tipo documento' },
          { name: 'numero_documento', label: 'Número documento' },
          { name: 'rela_nacionalidad', label: 'Nacionalidad' },
          { name: 'sexo', label: 'Sexo' },
          { name: 'fecha_nacimiento', label: 'Fecha de nacimiento' }
      ];

      formulariosPasajeros.forEach(formGroup => {
          
          camposObligatorios.forEach(campo => {
              const input = formGroup.querySelector(`[name$="[${campo.name}]"]`);
              
              if (input && input.value.trim() === '') {
                  valid = false;
                  
                  if (!primerCampoVacio) {
                      primerCampoVacio = { input: input, label: campo.label };
                  }
                  
                  input.style.borderColor = 'red'; 
              } else if (input) {
                  input.style.borderColor = ''; 
              }
          });
      });

      if (!valid) {
          Swal.fire({
              icon: 'warning',
              title: 'Datos Obligatorios',
              text: `Por favor, completa el campo: ${primerCampoVacio.label}`
          }).then(() => {
              primerCampoVacio.input.focus();
          });
          return; 
      }

      const formData = new FormData(formPasajeros);

      formData.append('action', 'agregar');
      formData.append('tipo_servicio', 'transporte');
      formData.append('id_servicio', viajeId); 
      formData.append('id_viaje', viajeId);
      formData.append('cantidad', seleccionados.size);
      formData.append('precio_unitario', precioAsiento);

      const asientosArray = Array.from(seleccionados).map(idUnico => {
          const [piso, numero] = idUnico.split('-');
          return { piso: parseInt(piso), numero: parseInt(numero) };
      });
      formData.append('asientos', JSON.stringify(asientosArray)); 

      const fechaServicio = document.body.dataset.fechaServicio || new Date().toISOString().split('T')[0]; 
      formData.append('fecha_servicio', fechaServicio);
      
      try {
          const res = await fetch("controllers/carrito/carrito.controlador.php", {
              method: "POST",
              body: formData
          });

          stopCountdown(); 
          
          const data = await res.json();

          if (data.status === "success") {
              Swal.fire({
                  icon: "success",
                  title: "¡Reserva exitosa!",
                  text: "Los asientos han sido agregados a tu carrito."
              }).then(() => {
                  modalCompleto.style.display = "none";
                  window.location.reload(); 
              });
          } else {
              Swal.fire({
                  icon: "error",
                  title: "Error al agregar",
                  text: data.message || "Ocurrió un error inesperado al procesar la reserva."
              });
          }
      } catch (error) {
          console.error("Error en la petición de reserva:", error);
          Swal.fire({
              icon: "error",
              title: "Error de conexión",
              text: "No se pudo conectar con el servidor para finalizar la reserva."
          });
      }
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