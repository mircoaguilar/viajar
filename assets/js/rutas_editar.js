document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formRutaEditar");

    form.addEventListener("submit", function (e) {
        if (!validarFormulario(e)) {
            e.preventDefault();
        }
    });
});

function validarFormulario(event) {
    const nombre = document.getElementById("nombre").value.trim();
    const trayecto = document.getElementById("trayecto").value.trim();
    const duracion = document.getElementById("duracion").value.trim();
    const precio = parseFloat(document.getElementById("precio_por_persona").value);

    if (nombre.length < 3) {
        mostrarAlerta("El nombre debe tener al menos 3 caracteres.", "danger");
        return false;
    }
    if (trayecto.length < 3) {
        mostrarAlerta("El trayecto no es válido.", "danger");
        return false;
    }
    if (!/^\d{1,2}:\d{2}$/.test(duracion)) {
        mostrarAlerta("La duración debe tener formato HH:MM.", "danger");
        return false;
    }
    if (isNaN(precio) || precio <= 0) {
        mostrarAlerta("El precio debe ser mayor a 0.", "danger");
        return false;
    }

    return true; 
}

function mostrarAlerta(mensaje, tipo = "danger") {
    let alerta = document.querySelector(".alerta-rutas");

    if (!alerta) {
        alerta = document.createElement("div");
        alerta.classList.add("alerta-rutas");
        document.querySelector(".panel").prepend(alerta);
    }

    alerta.className = "alerta-rutas " + tipo;
    alerta.textContent = mensaje;

    setTimeout(() => alerta.remove(), 4000);
}
