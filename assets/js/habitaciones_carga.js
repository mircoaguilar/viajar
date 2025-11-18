document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('form-habitaciones');
    if (!form) return;

    const inputs = form.querySelectorAll("input, select, textarea");

    const descripcion = document.getElementById("descripcion_unidad");
    const contador = document.getElementById("contador-descripcion");

    const alertBox = document.getElementById("form-alert");

    const tabla = document.getElementById("tabla-preview");
    const tbody = tabla ? tabla.querySelector("tbody") : null;

    const customAlert = document.getElementById("custom-alert");
    const customMessage = document.getElementById("custom-alert-message");
    const customBtn = document.getElementById("custom-alert-btn");

    const fotosActualesContainer = document.getElementById("fotos-actuales");
    const nuevasFotosInput = document.getElementById("fotos_habitacion");
    const nuevasFotosPreview = document.getElementById("preview-nuevas-fotos");

    const esEdicion = document.getElementById("id_habitacion") !== null;


    if (descripcion && contador) {
        descripcion.addEventListener("input", () => {
            contador.textContent = `${descripcion.value.length}/200 caracteres`;
        });
    }

    function validarFormulario() {
        let valido = true;
        alertBox.style.display = "none";

        inputs.forEach(input => {
            const errorDiv = input.parentElement.querySelector(".error");
            if (errorDiv) errorDiv.textContent = "";
            input.classList.remove("is-invalid");

            if (input.id === "nombre_tipo_habitacion" && input.value === "") {
                errorDiv.textContent = "El tipo de habitación es obligatorio.";
                input.classList.add("is-invalid");
                valido = false;
            }

            if (input.id === "capacidad_maxima") {
                const valor = parseInt(input.value, 10);
                if (!valor || valor < 1 || valor > 20) {
                    errorDiv.textContent = "La capacidad debe estar entre 1 y 20.";
                    input.classList.add("is-invalid");
                    valido = false;
                }
            }

            if (input.id === "precio_base_noche") {
                const valor = parseFloat(input.value);
                if (isNaN(valor) || valor < 0 || valor > 50000) {
                    errorDiv.textContent = "El precio debe ser entre 0 y 50.000.";
                    input.classList.add("is-invalid");
                    valido = false;
                }
            }

            if (input.id === "descripcion_unidad" && input.value.trim() === "") {
                errorDiv.textContent = "La descripción es obligatoria.";
                input.classList.add("is-invalid");
                valido = false;
            }

            if (input.id === "fotos_habitacion" && input.files.length > 0) {

                if (input.files.length > 5) {
                    errorDiv.textContent = "Máx. 5 imágenes permitidas.";
                    input.classList.add("is-invalid");
                    valido = false;
                }

                for (let file of input.files) {
                    if (!["image/jpeg", "image/png"].includes(file.type)) {
                        errorDiv.textContent = "Solo se permiten imágenes JPEG o PNG.";
                        input.classList.add("is-invalid");
                        valido = false;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        errorDiv.textContent = "Cada imagen debe pesar menos de 5MB.";
                        input.classList.add("is-invalid");
                        valido = false;
                    }
                }
            }
        });

        return valido;
    }

    if (nuevasFotosInput && nuevasFotosPreview) {
        nuevasFotosInput.addEventListener("change", () => {

            nuevasFotosPreview.innerHTML = "";

            Array.from(nuevasFotosInput.files).forEach(file => {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(file);
                img.classList.add("thumb");

                nuevasFotosPreview.appendChild(img);
            });
        });
    }


    if (fotosActualesContainer) {
        fotosActualesContainer.addEventListener("change", (e) => {
            if (e.target.classList.contains("chk-borrar-foto")) {

                const contenedor = e.target.closest(".foto-item");

                if (e.target.checked) {
                    contenedor.classList.add("marcar-borrar");
                } else {
                    contenedor.classList.remove("marcar-borrar");
                }
            }
        });
    }

    const btnPrev = document.getElementById("previsualizar");
    if (btnPrev && tabla && tbody) {
        btnPrev.addEventListener("click", () => {

            if (!validarFormulario()) return;

            const tipoTexto = document.getElementById("nombre_tipo_habitacion").selectedOptions[0].text;
            const cap = document.getElementById("capacidad_maxima").value.trim();
            const precio = document.getElementById("precio_base_noche").value.trim();
            const desc = descripcion.value.trim();
            const fotosInput = document.getElementById("fotos_habitacion");

            tabla.style.display = "table";

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${tipoTexto}</td>
                <td>${cap}</td>
                <td>${precio ? "$" + precio : "—"}</td>
                <td>${desc}</td>
                <td></td>
            `;

            if (fotosInput.files.length > 0) {
                const tdFotos = tr.querySelector("td:last-child");

                Array.from(fotosInput.files).forEach(file => {
                    const img = document.createElement("img");
                    img.src = URL.createObjectURL(file);
                    img.classList.add("thumb-small");
                    tdFotos.appendChild(img);
                });
            }

            tbody.appendChild(tr);
        });
    }
    
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        if (!validarFormulario()) {
            alertBox.textContent = "Por favor, corrija los errores antes de enviar.";
            alertBox.className = "alert alert-danger";
            alertBox.style.display = "block";
            return;
        }

        const formData = new FormData(form);
        formData.append("action", esEdicion ? "actualizar" : "guardar");

        fetch("controllers/habitaciones/habitaciones.controlador.php", {
            method: "POST",
            body: formData
        })
        .then(r => r.json())
        .then(data => {

            if (data.status === "success") {

                customMessage.textContent = esEdicion
                    ? "¡Cambios guardados con éxito!"
                    : "¡Habitación creada! Ahora cargá la disponibilidad.";

                customAlert.style.display = "flex";

                customBtn.onclick = () => {
                    window.location.href = `index.php?page=hoteles_stock&id_habitacion=${data.id}`;
                };

                setTimeout(() => {
                    window.location.href = `index.php?page=hoteles_stock&id_habitacion=${data.id}`;
                }, 2000);

            } else {
                alertBox.textContent = data.message || "Error al guardar.";
                alertBox.style.display = "block";
            }
        });

    });

});
