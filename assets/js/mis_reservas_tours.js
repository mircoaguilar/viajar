document.addEventListener("DOMContentLoaded", () => {
    const botonesVer = document.querySelectorAll(".btn-ver-reserva");
    const modal = document.getElementById("modalVerReserva");
    const btnCerrarHeader = document.getElementById("cerrarModalBtn");
    const btnCerrarFooter = document.getElementById("cerrarModalBtnFooter");

    botonesVer.forEach(boton => {
        boton.addEventListener("click", function () {
            const idReserva = this.dataset.id;

            if (!idReserva) {
                console.error("No llegó el ID de la reserva");
                return;
            }

            cargarReservaTour(idReserva);
        });
    });

    btnCerrarHeader.addEventListener('click', cerrarModalReserva);
    btnCerrarFooter.addEventListener('click', cerrarModalReserva);

    modal.addEventListener('click', (e) => {
        if (e.target.id === 'modalVerReserva') {
            cerrarModalReserva();
        }
    });
});

function cargarReservaTour(idReserva) {
    fetch(`controllers/reservas/reservas.controlador.php?action=ver_tour&id=${idReserva}`)
        .then(response => response.json())
        .then(resp => {
            if (!resp || !resp.data) {
                alert("No se pudo cargar la información de la reserva.");
                return;
            }

            renderizarModalTour(resp.data);
            abrirModalReserva();
        })
        .catch(err => console.error("Error al cargar reserva:", err));
}

function renderizarModalTour(data) {
    document.getElementById("info-reserva").innerHTML = `
        <strong>ID:</strong> ${data.id_reservas}<br>
        <strong>Fecha creación:</strong> ${data.fecha_creacion}<br>
        <strong>Estado:</strong> ${data.reservas_estado}<br>
        <strong>Total:</strong> $${parseFloat(data.total).toLocaleString('es-AR')}<br>
        <strong>Cliente:</strong> ${data.cliente || 'No disponible'}
    `;

    let filas = "";
    if (data.detalles && data.detalles.length > 0) {
        data.detalles.forEach(det => {
            filas += `
                <tr>
                    <td>${det.tipo_servicio}</td>
                    <td>${det.cantidad}</td>
                    <td>$${parseFloat(det.precio_unitario).toLocaleString('es-AR')}</td>
                    <td>$${parseFloat(det.subtotal).toLocaleString('es-AR')}</td>
                </tr>
            `;
        });
    } else {
        filas = `<tr><td colspan="4">No hay detalles disponibles</td></tr>`;
    }
    document.getElementById("tabla-detalles").innerHTML = filas;

    let detallesExtra = "";
    data.detalles.forEach(det => {
        if (det.tipo_servicio === 'tour') {
            detallesExtra += `<h6>Detalles del tour</h6>`;
            detallesExtra += `
                <strong>Tour:</strong> ${det.nombre_tour || 'No disponible'}<br>
                <strong>Fecha del tour:</strong> ${det.fecha_tour || 'No disponible'}<br>
                <strong>Estado:</strong> ${det.estado || ''}<br><br>
            `;
        }
    });
    document.getElementById("detalles-extra").innerHTML = detallesExtra;
}

function abrirModalReserva() {
    document.getElementById("modalVerReserva").style.display = "flex";
}

function cerrarModalReserva() {
    document.getElementById("modalVerReserva").style.display = "none";
}
