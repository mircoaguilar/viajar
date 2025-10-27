document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-habitaciones');
    const inputs = form.querySelectorAll("input, select, textarea");
    const descripcion = document.getElementById("descripcion_unidad");
    const contador = document.getElementById("contador-descripcion");
    const alertBox = document.getElementById("form-alert");
    const tabla = document.getElementById("tabla-preview");
    const tbody = tabla.querySelector("tbody");

    const customAlert = document.getElementById("custom-alert");
    const customMessage = document.getElementById("custom-alert-message");
    const customBtn = document.getElementById("custom-alert-btn");

    // --- Contador de caracteres ---
    descripcion.addEventListener("input", () => {
        contador.textContent = `${descripcion.value.length}/200 caracteres`;
    });

    // --- Validación completa ---
    function validarFormulario() {
        let valido = true;
        alertBox.style.display = "none";

        inputs.forEach(input => {
            const errorDiv = input.parentElement.querySelector(".error");
            if (errorDiv) errorDiv.textContent = "";
            input.classList.remove("is-invalid");

            // Tipo de habitación
            if (input.id === "nombre_tipo_habitacion" && input.value === "") {
                errorDiv.textContent = "El tipo de habitación es obligatorio.";
                input.classList.add("is-invalid");
                valido = false;
            }

            // Capacidad
            if (input.id === "capacidad_maxima") {
                const valor = parseInt(input.value, 10);
                if (!valor || valor < 1 || valor > 20) {
                    errorDiv.textContent = "La capacidad debe estar entre 1 y 20.";
                    input.classList.add("is-invalid");
                    valido = false;
                }
            }

            // Precio
            if (input.id === "precio_base_noche") {
                const valor = parseFloat(input.value);
                if (isNaN(valor) || valor < 0 || valor > 50000) {
                    errorDiv.textContent = "El precio debe ser entre 0 y 50.000.";
                    input.classList.add("is-invalid");
                    valido = false;
                }
            }

            // Descripción
            if (input.id === "descripcion_unidad" && input.value.trim() === "") {
                errorDiv.textContent = "La descripción es obligatoria.";
                input.classList.add("is-invalid");
                valido = false;
            }

            // Fotos
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

    // --- Quitar error al escribir ---
    inputs.forEach(input => {
        input.addEventListener("input", () => {
            input.classList.remove("is-invalid");
            const errorDiv = input.parentElement.querySelector(".error");
            if (errorDiv) errorDiv.textContent = "";
        });
    });

    // --- Previsualización ---
    const btnPrev = document.getElementById("previsualizar");
    btnPrev.addEventListener("click", () => {
        if (!validarFormulario()) return;

        const tipoSelect = document.getElementById("nombre_tipo_habitacion");
        const tipoTexto = tipoSelect.options[tipoSelect.selectedIndex].text;
        const cap = document.getElementById("capacidad_maxima").value.trim();
        const precio = document.getElementById("precio_base_noche").value.trim();
        const desc = descripcion.value.trim();
        const fotosInput = document.getElementById("fotos_habitacion");

        if (tabla.style.display === "none") tabla.style.display = "table";

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
                img.style.maxWidth = "60px";
                img.style.maxHeight = "60px";
                img.style.objectFit = "cover";
                img.style.marginRight = "5px";
                tdFotos.appendChild(img);
            });
        }

        tbody.appendChild(tr);
    });

    // --- Envío AJAX ---
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        if (!validarFormulario()) {
            alertBox.textContent = "Por favor, corrija los errores antes de enviar.";
            alertBox.className = "alert alert-danger";
            alertBox.style.display = "block";
            return;
        }

        const formData = new FormData(form);
        formData.append('action', 'guardar'); // seguridad extra

        fetch("controllers/habitaciones/habitaciones.controlador.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                // Alerta personalizada
                customMessage.textContent = "¡Habitación guardada con éxito! Serás redirigido para cargar la disponibilidad.";
                customAlert.style.display = "flex";

                customBtn.onclick = () => {
                    window.location.href = `index.php?page=hoteles_stock&id_habitacion=${data.id}`;
                };

                // Redirección automática en 3 segundos
                setTimeout(() => {
                    window.location.href = `index.php?page=hoteles_stock&id_habitacion=${data.id}`;
                }, 3000);
            } else {
                alertBox.textContent = data.message || "Error al guardar la habitación.";
                alertBox.className = "alert alert-danger";
                alertBox.style.display = "block";
            }
        })
        .catch(err => {
            console.error("Error en la conexión:", err);
            alertBox.textContent = "Error en la conexión con el servidor.";
            alertBox.className = "alert alert-danger";
            alertBox.style.display = "block";
        });
    });
});
