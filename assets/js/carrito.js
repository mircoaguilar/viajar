async function cargarCarrito() {
    try {
        const resp = await fetch('controllers/carrito/carrito.controlador.php?action=listar');
        const data = await resp.json();
        const cont = document.getElementById('carrito-contenido');
        if (!cont) return;

        if (!data.items || data.items.length === 0) {
            cont.innerHTML = '<p class="mensaje-vacio">Tu carrito está vacío.</p>';
            actualizarContadorCarrito(0);
            return;
        }

        let total = 0;
        let html = `
            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Detalle</th>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
        `;

        data.items.forEach(it => {
            total += parseFloat(it.subtotal);

            let fechas = '-';
            if(it.tipo_servicio === 'hotel') {
                fechas = `${it.fecha_inicio ?? '-'}${it.fecha_fin ? ' → ' + it.fecha_fin : ''}`;
            } else if(it.tipo_servicio === 'tour') {
                fechas = it.fecha_tour ?? '-';
            } else if(it.tipo_servicio === 'transporte') {
                fechas = it.fecha_servicio ?? '-';
            }

            html += `
                <tr>
                    <td>${it.tipo_servicio.charAt(0).toUpperCase() + it.tipo_servicio.slice(1)}</td>
                    <td>${it.nombre_servicio ?? it.id_servicio}</td>
                    <td>${fechas}</td>
                    <td>
                        <input type="number" min="1" value="${it.cantidad}" 
                            onchange="actualizarItem(${it.id_item}, this.value, ${it.precio_unitario}, '${it.fecha_inicio ?? ''}', '${it.fecha_fin ?? ''}', '${it.fecha_tour ?? ''}')">
                    </td>
                    <td>$ ${parseFloat(it.precio_unitario).toFixed(2)}</td>
                    <td>$ ${parseFloat(it.subtotal).toFixed(2)}</td>
                    <td>
                        <button class="btn-accion" onclick="quitarItem(${it.id_item})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
            <div class="carrito-total">
                <h3>Total: $ ${total.toFixed(2)}</h3>
                <button class="btn-checkout" onclick="finalizarCompra()">Finalizar Compra</button>
            </div>
        `;

        cont.innerHTML = html;
        actualizarContadorCarrito(data.items.length);
    } catch (err) {
        console.error('Error cargando carrito:', err);
        Swal.fire('Error', 'No se pudo cargar el carrito. Revisa la consola.', 'error');
    }
}

async function quitarItem(id_item) {
    if (!confirm('¿Seguro que querés eliminar este ítem?')) return;

    const form = new FormData();
    form.append('action', 'quitar');
    form.append('id_item', id_item);

    try {
        const resp = await fetch('controllers/carrito/carrito.controlador.php', {
            method: 'POST',
            body: form
        });
        const data = await resp.json();
        if (data.status === 'success') {
            cargarCarrito();
        } else {
            Swal.fire('Error', data.message || 'No se pudo eliminar el ítem', 'error');
        }
    } catch (err) {
        Swal.fire('Error', 'Ocurrió un error en la solicitud', 'error');
        console.error(err);
    }
}

async function actualizarItem(id_item, cantidad, precio_unitario, checkin = null, checkout = null, fecha_tour = null) {
    if (cantidad <= 0) return;

    const form = new FormData();
    form.append('action', 'actualizar');
    form.append('id_item', id_item);
    form.append('cantidad', cantidad);
    form.append('precio_unitario', precio_unitario);
    if (checkin) form.append('checkin', checkin);
    if (checkout) form.append('checkout', checkout);
    if (fecha_tour) form.append('fecha_tour', fecha_tour);

    try {
        const resp = await fetch('controllers/carrito/carrito.controlador.php', {
            method: 'POST',
            body: form
        });
        const data = await resp.json();
        if (data.status === 'success') {
            cargarCarrito();
        } else {
            Swal.fire('Error', data.message || 'No se pudo actualizar el ítem', 'error');
        }
    } catch (err) {
        Swal.fire('Error', 'Ocurrió un error en la solicitud', 'error');
        console.error(err);
    }
}

async function actualizarContadorCarrito(count = null) {
    const contador = document.getElementById('carrito-count');
    if (!contador) return;

    if (count !== null) {
        contador.textContent = count;
    } else {
        try {
            const resp = await fetch('controllers/carrito/carrito.controlador.php?action=listar');
            const data = await resp.json();
            contador.textContent = data.items ? data.items.length : 0;
        } catch (err) {
            console.error('Error actualizando contador:', err);
        }
    }
}

async function agregarAlCarrito(id_hab, id_hotel, checkin, checkout, personas, precio_unitario) {
    if (!id_hab || !id_hotel || !checkin || !checkout || !personas) {
        Swal.fire('Error', 'Faltan datos obligatorios', 'error');
        return;
    }

    const form = new FormData();
    form.append('action', 'agregar');
    form.append('id_usuario', USER_ID);
    form.append('tipo_servicio', 'hotel');
    form.append('id_servicio', id_hab);
    form.append('cantidad', 1);
    form.append('precio_unitario', precio_unitario);
    form.append('checkin', checkin);
    form.append('checkout', checkout);
    form.append('personas', personas);

    try {
        const resp = await fetch('controllers/carrito/carrito.controlador.php', {
            method: 'POST',
            body: form
        });
        const text = await resp.text();
        let data;
        try { data = JSON.parse(text); } catch(e) { console.error(text); return; }

        if(data.status === 'success'){
            Swal.fire('Agregado', 'Habitación agregada al carrito', 'success');
            actualizarContadorCarrito();
            cargarCarrito();
        } else {
            Swal.fire('Error', data.message || 'No se pudo agregar al carrito', 'error');
        }
    } catch(err) { console.error(err); }
}

async function agregarTourAlCarrito(id_tour, fecha_tour, precio_unitario) {
    if (!id_tour || !fecha_tour || !precio_unitario) {
        Swal.fire('Error', 'Faltan datos del tour', 'error');
        return;
    }

    const form = new FormData();
    form.append('action', 'agregar');
    form.append('id_usuario', USER_ID);
    form.append('tipo_servicio', 'tour');
    form.append('id_servicio', id_tour);
    form.append('cantidad', 1);
    form.append('precio_unitario', precio_unitario);
    form.append('fecha_tour', fecha_tour);

    try {
        const resp = await fetch('controllers/carrito/carrito.controlador.php', {
            method: 'POST',
            body: form
        });
        const text = await resp.text();
        let data;
        try { data = JSON.parse(text); } catch(e) { console.error(text); return; }

        if(data.status === 'success'){
            Swal.fire('Agregado', 'Tour agregado al carrito', 'success');
            cargarCarrito();
        } else {
            Swal.fire('Error', data.message || 'No se pudo agregar al carrito', 'error');
        }
    } catch(err) { console.error(err); }
}

async function finalizarCompra() {
    try {
        const form = new FormData();
        form.append('action', 'crear_reserva');

        const resp = await fetch('controllers/carrito/carrito.controlador.php', {
            method: 'POST',
            body: form
        });
        const data = await resp.json();

        if (data.status === 'success') {
            window.location.href = "views/paginas/checkout_mercadopago.php";
        } else {
            Swal.fire('Error', data.message || 'No se pudo crear la reserva', 'error');
        }
    } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Ocurrió un error al crear la reserva', 'error');
    }
}

window.addEventListener('DOMContentLoaded', () => {
    cargarCarrito();
    actualizarContadorCarrito();
});
