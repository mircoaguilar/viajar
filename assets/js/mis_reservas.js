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

            cargarReserva(idReserva);
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

function cargarReserva(idReserva) {
    fetch(`controllers/reservas/reservas.controlador.php?action=ver&id=${idReserva}`)
        .then(response => response.json())
        .then(resp => {
            if (!resp || !resp.data) {
                alert("No se pudo cargar la información de la reserva.");
                return;
            }

            renderizarModalReserva(resp.data); // pasamos data directamente
            abrirModalReserva();
        })
        .catch(err => console.error("Error al cargar reserva:", err));
}

function renderizarModalReserva(data) {
    // Info general de la reserva
    document.getElementById("info-reserva").innerHTML = `
        <strong>ID:</strong> ${data.id_reservas}<br>
        <strong>Fecha:</strong> ${data.fecha_creacion}<br>
        <strong>Estado:</strong> ${data.reservas_estado}<br>
        <strong>Total:</strong> $${parseFloat(data.total).toLocaleString('es-AR')}<br>
        <strong>Cliente:</strong> ${data.cliente || 'No disponible'}
    `;

    // Detalles de los servicios
    let filas = "";
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
    document.getElementById("tabla-detalles").innerHTML = filas;

    // Detalles extra de hotel, transporte o tour
    let detallesExtra = "";
    if (data.hotel) {
        detallesExtra += `
            <h6>Detalles de hotel</h6>
            <strong>Check-in:</strong> ${data.hotel.check_in}<br>
            <strong>Check-out:</strong> ${data.hotel.check_out}<br>
            <strong>Noches:</strong> ${data.hotel.noches}<br>
            <strong>Habitación:</strong> ${data.hotel.tipo_habitacion}<br>
            <strong>Descripción:</strong> ${data.hotel.habitacion_descripcion}<br>
        `;
    }
    if (data.transporte && data.transporte.length > 0) {
        detallesExtra += `<h6>Detalles de transporte</h6>`;
        data.transporte.forEach(t => {
            detallesExtra += `
                <strong>Origen:</strong> ${t.origen}<br>
                <strong>Destino:</strong> ${t.destino}<br>
                <strong>Salida:</strong> ${t.hora_salida}<br>
                <strong>Llegada:</strong> ${t.hora_llegada}<br><br>
            `;
        });
    }
    if (data.tour) {
        detallesExtra += `
            <h6>Detalles de tour</h6>
            <strong>Nombre:</strong> ${data.tour.tour_nombre}<br>
        `;
    }

    document.getElementById("detalles-extra").innerHTML = detallesExtra;
}

function abrirModalReserva() {
    document.getElementById("modalVerReserva").style.display = "flex";
}

function cerrarModalReserva() {
    document.getElementById("modalVerReserva").style.display = "none";
}
