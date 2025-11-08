document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formTransporte");
  const contenedorPisos = document.getElementById("contenedorPisos");
  const btnAgregarPiso = document.getElementById("btnAgregarPiso");
  const descripcion = document.getElementById("descripcion");
  let contadorPisos = 0;


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

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    let valido = true;

    const matricula = document.getElementById("transporte_matricula");
    const capacidad = document.getElementById("transporte_capacidad");
    const tipo = document.getElementById("rela_tipo_transporte");
    const nombre = document.getElementById("nombre_servicio");
    const imagen = document.getElementById("imagen_principal");

    const regexMatricula = /^[A-Z]{3}\d{3}$|^[A-Z]{2}\d{3}[A-Z]{2}$/i;
    const regexNombre = /^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{3,}$/;

    
    if (matricula.value.trim() === "") {
      mostrarError(matricula, "La matrícula es obligatoria.");
      valido = false;
    } else if (!regexMatricula.test(matricula.value.trim())) {
      mostrarError(matricula, "Formato inválido (ej: ABC123 o AB123CD).");
      valido = false;
    }

    
    const num = parseInt(capacidad.value, 10);
    if (isNaN(num) || num < 1 || num > 100) {
      mostrarError(capacidad, "Capacidad entre 1 y 100 personas.");
      valido = false;
    }

  
    if (tipo.value === "" || tipo.value == null) {
      mostrarError(tipo, "Seleccioná un tipo de transporte.");
      valido = false;
    }

    
    if (!regexNombre.test(nombre.value.trim())) {
      mostrarError(nombre, "Mínimo 3 letras. Solo texto y espacios.");
      valido = false;
    }

    
    const descVal = descripcion.value.trim();
    if (descVal === "") {
      mostrarError(descripcion, "Falta datos en descripción.");
      valido = false;
    } else if (descVal.length > 500) {
      mostrarError(descripcion, "La descripción no puede superar los 500 caracteres.");
      valido = false;
    }

   
    const archivo = imagen.files[0];
    if (!archivo) {
      mostrarError(imagen, "Debes subir una imagen principal.");
      valido = false;
    } else {
      const extPermitidas = ["jpg", "jpeg", "png", "webp"];
      const extension = archivo.name.split(".").pop().toLowerCase();
      const tamano = archivo.size / 1024 / 1024;
      if (!extPermitidas.includes(extension)) {
        mostrarError(imagen, "Formato inválido. Solo JPG, JPEG, PNG o WEBP.");
        valido = false;
      } else if (tamano > 2) {
        mostrarError(imagen, "La imagen no puede superar los 2 MB.");
        valido = false;
      }
    }

    
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

    if (!confirm("¿Deseás guardar este transporte?")) return;

    const formData = new FormData(form);
    formData.append("action", "guardar");

    fetch("controllers/transportes/transporte.controlador.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success" || data.status === "ok") {
          alert("Transporte enviado para revisión correctamente.");
          window.location.href = "index.php?page=proveedores_perfil";
        } else {
          alert("Error: " + (data.mensaje || data.message || "No se pudo guardar."));
        }
      })
      .catch(() => alert("Ocurrió un error al guardar el transporte."));
  });
});
