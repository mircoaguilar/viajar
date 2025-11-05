async function agregarTourAlCarrito(id_tour, fecha_tour, cantidad, precio_unitario) {
    console.log({ id_tour, fecha_tour, cantidad, precio_unitario, USER_ID });

    if (!USER_ID) {
        Swal.fire({
            icon: "warning",
            title: "Atención",
            text: "Debes iniciar sesión para agregar al carrito."
        });
        return;
    }

    if (!id_tour || !fecha_tour || cantidad <= 0) {
        Swal.fire("Error", "Debes seleccionar una fecha y cantidad válidas", "error");
        return;
    }

    const form = new FormData();
    form.append("action", "agregar");
    form.append("tipo_servicio", "tour");
    form.append("id_servicio", id_tour);
    form.append("cantidad", cantidad);
    form.append("precio_unitario", precio_unitario);
    form.append("fecha_tour", fecha_tour);


    try {
        const resp = await fetch("controllers/carrito/carrito.controlador.php", {
            method: "POST",
            body: form
        });

        const text = await resp.text();
        console.log("RESPUESTA DEL SERVIDOR:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch (err) {
            Swal.fire("Error", "Respuesta no válida del servidor", "error");
            console.error("Error parseando JSON:", err, text);
            return;
        }

        if (data.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Tour agregado",
                text: "El tour se agregó correctamente al carrito",
                timer: 2000,
                showConfirmButton: false
            });
            actualizarContadorCarrito();
            cargarCarrito();
        } else {
            Swal.fire("Error", data.message || "No se pudo agregar el tour", "error");
        }

    } catch (err) {
        console.error(err);
        Swal.fire("Error", "Ocurrió un problema al procesar la solicitud", "error");
    }
}
