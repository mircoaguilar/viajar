document.addEventListener("DOMContentLoaded", () => {
    // --- Si no está logueado ---
    if (!usuarioLogueado) {
        Swal.fire({
            icon: "warning",
            title: "Debes iniciar sesión",
            text: "Inicia sesión para continuar con tu reserva.",
            confirmButtonText: "Ir al login"
        }).then(() => {
            window.location.href = "/viajar/index.php?page=login";
        });
        return;
    }

    const formPago = document.getElementById("form-pago");
    const tarjetaSection = document.getElementById("tarjeta-section");
    const transferenciaSection = document.getElementById("transferencia-section");

    // Mostrar/ocultar secciones según el método elegido
    document.querySelectorAll("input[name='metodo_pago']").forEach(radio => {
        radio.addEventListener("change", () => {
            if (radio.value === "tarjeta") {
                tarjetaSection.classList.remove("seccion-oculta");
                transferenciaSection.classList.add("seccion-oculta");
                tarjetaSection.querySelectorAll("input").forEach(inp => inp.required = true);
                transferenciaSection.querySelectorAll("input").forEach(inp => inp.required = false);
            } else if (radio.value === "transferencia") {
                transferenciaSection.classList.remove("seccion-oculta");
                tarjetaSection.classList.add("seccion-oculta");
                transferenciaSection.querySelectorAll("input").forEach(inp => inp.required = true);
                tarjetaSection.querySelectorAll("input").forEach(inp => inp.required = false);
            }
        });
    });

    // Manejar el submit
    if (formPago) {
        formPago.addEventListener("submit", async (e) => {
            e.preventDefault();

            const metodo = formPago.querySelector("input[name='metodo_pago']:checked");
            if (!metodo) {
                Swal.fire("Atención", "Selecciona un método de pago", "warning");
                return;
            }

            Swal.fire({
                title: "Procesando reserva...",
                didOpen: () => Swal.showLoading()
            });

            try {
                const data = new FormData();
                data.append('action', 'crear_reserva');
                data.append('id_habitacion', formPago.dataset.idhab);
                data.append('checkin', formPago.dataset.checkin);
                data.append('checkout', formPago.dataset.checkout);
                data.append('personas', formPago.dataset.personas);
                data.append('metodo_pago', metodo.value);
                data.append('monto', formPago.dataset.total);

                if (metodo.value === "transferencia") {
                    const fileInput = document.querySelector("input[name='comprobante']");
                    if (fileInput && fileInput.files.length > 0) {
                        data.append('comprobante', fileInput.files[0]);
                    }
                }

                const response = await fetch('/viajar/controllers/reservas/reservas.controlador.php', {
                    method: 'POST',
                    body: data
                });

                const json = await response.json();

                if (json.status === 'success') {
                    Swal.fire('Éxito', `Reserva creada #${json.id_reserva}`, 'success')
                    .then(() => window.location.href = '/viajar/index.php?page=clientes_mis_reservas');
                } else {
                    Swal.fire('Error', json.message, 'error');
                }

            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'No se pudo procesar la reserva', 'error');
            }
        });
    }
});
