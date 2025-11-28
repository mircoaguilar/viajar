document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formTransporte");
  const contenedorPisos = document.getElementById("contenedorPisos");
  const btnAgregarPiso = document.getElementById("btnAgregarPiso");
  const descripcion = document.getElementById("descripcion");
  let contadorPisos = 0;

  // ----------------- CONTADOR DESCRIPCIÓN -----------------
  const contador = document.createElement("small");
  contador.id = "contadorDescripcion";
  contador.style.display = "block";
  contador.style.textAlign = "right";
  contador.style.marginTop = "4px";
  contador.textContent = "0 / 500";
  descripcion.parentNode.appendChild(contador);

  descripcion.addEventListener("input", () => {
    const longitud = descripcion.value.length;
    contador.textContent = `${longitud} / 500`;

    if (longitud > 500) {
      descripcion.value = descripcion.value.substring(0, 500);
      contador.textContent = `500 / 500`;
    }

    eliminarError(descripcion);
  });

  // ----------------- ERRORES -----------------
  function mostrarError(input, mensaje) {
    eliminarError(input);
    input.classList.add("error-input");
    const errorMsg = document.createElement("small");
    errorMsg.className = "error-msg";
    errorMsg.textContent = mensaje;
    input.insertAdjacentElement("afterend", errorMsg);
  }

  function eliminarError(input) {
    input.classList.remove("error-input");
    const siguiente = input.nextElementSibling;
    if (siguiente && siguiente.classList.contains("error-msg")) {
      siguiente.remove();
    }
  }

  form.querySelectorAll("input, textarea, select").forEach((campo) => {
    campo.addEventListener("input", () => eliminarError(campo));
    campo.addEventListener("change", () => eliminarError(campo));
  });

  // ----------------- PISOS -----------------
  const errorPisosMsg = document.createElement("small");
  errorPisosMsg.className = "error-msg";
  errorPisosMsg.style.display = "none";
  btnAgregarPiso.insertAdjacentElement("afterend", errorPisosMsg);

  btnAgregarPiso.addEventListener("click", () => {
    if (contadorPisos >= 2) {
      errorPisosMsg.textContent = "No se puede agregar más de 2 pisos por colectivo.";
      errorPisosMsg.style.display = "block";
      return;
    }

    errorPisosMsg.style.display = "none";
    contadorPisos++;

    const pisoDiv = document.createElement("div");
    pisoDiv.classList.add("piso-card");
    pisoDiv.innerHTML = `
      <h4>Piso ${contadorPisos}</h4>
      <div class="grid">
        <div>
          <label>Filas</label>
          <input type="number" name="pisos[${contadorPisos}][filas]" min="1" class="filas-input">
        </div>
        <div>
          <label>Asientos por fila</label>
          <input type="number" name="pisos[${contadorPisos}][asientos_por_fila]" min="1" class="asientos-input">
        </div>
      </div>
      <button type="button" class="btn eliminar-piso">Eliminar piso</button>
    `;

    pisoDiv.querySelector(".eliminar-piso").addEventListener("click", () => {
      pisoDiv.remove();
      contadorPisos--;
      if (contadorPisos < 2) errorPisosMsg.style.display = "none";
    });

    contenedorPisos.appendChild(pisoDiv);
  });

  // ----------------- VALIDAR Y ENVIAR -----------------
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    let valido = true;

    const matricula = document.getElementById("transporte_matricula");
    const capacidad = document.getElementById("transporte_capacidad");
    const tipo = document.getElementById("rela_tipo_transporte");
    const nombre = document.getElementById("nombre_servicio");
    const imagen = document.getElementById("imagen_principal");

    const regexMatricula = /^[A-Z]{3}\d{3}$|^[A-Z]{2}\d{3}[A-Z]{2}$/i;
    const regexNombre = /^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{3,}$/;

    // Matrícula
    if (matricula.value.trim() === "") {
      mostrarError(matricula, "La matrícula es obligatoria.");
      valido = false;
    } else if (!regexMatricula.test(matricula.value.trim())) {
      mostrarError(matricula, "Formato inválido (ej: ABC123 o AB123CD).");
      valido = false;
    }

    // Capacidad
    const num = parseInt(capacidad.value, 10);
    if (isNaN(num) || num < 1 || num > 100) {
      mostrarError(capacidad, "Capacidad entre 1 y 100 personas.");
      valido = false;
    }

    // Tipo
    if (tipo.value === "") {
      mostrarError(tipo, "Seleccioná un tipo de transporte.");
      valido = false;
    }

    // Nombre
    if (!regexNombre.test(nombre.value.trim())) {
      mostrarError(nombre, "Mínimo 3 letras. Solo texto y espacios.");
      valido = false;
    }

    // Descripción
    const descVal = descripcion.value.trim();
    if (descVal === "") {
      mostrarError(descripcion, "Falta datos en descripción.");
      valido = false;
    }

    // Imagen
    const archivo = imagen.files[0];
    if (!archivo) {
      mostrarError(imagen, "Debes subir una imagen principal.");
      valido = false;
    }

    // Pisos completos
    const pisos = contenedorPisos.querySelectorAll(".piso-card");
    pisos.forEach((piso) => {
      const filas = piso.querySelector(".filas-input");
      const asientos = piso.querySelector(".asientos-input");
      if (filas.value.trim() === "" || asientos.value.trim() === "") {
        mostrarError(filas, "Completá todos los campos del piso.");
        mostrarError(asientos, "Completá todos los campos del piso.");
        valido = false;
      }
    });

    if (!valido) return;

    // ----------------- SWEET ALERT CONFIRMACIÓN -----------------
    const confirmacion = await Swal.fire({
      title: "¿Guardar transporte?",
      text: "Se enviará para revisión.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, guardar",
      cancelButtonText: "Cancelar"
    });

    if (!confirmacion.isConfirmed) return;

    // ----------------- ENVIAR FORM -----------------
    const formData = new FormData(form);

    fetch("controllers/transportes/transporte.controlador.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success" || data.status === "ok") {
          Swal.fire({
            title: "Enviado",
            text: "Transporte enviado para revisión correctamente.",
            icon: "success"
          }).then(() => {
            window.location.href = "index.php?page=proveedores_perfil";
          });
        } else {
          Swal.fire({
            title: "Error",
            text: data.mensaje || "No se pudo guardar.",
            icon: "error"
          });
        }
      })
      .catch(() => {
        Swal.fire({
          title: "Error",
          text: "Ocurrió un error al guardar el transporte.",
          icon: "error"
        });
      });

  });
});
