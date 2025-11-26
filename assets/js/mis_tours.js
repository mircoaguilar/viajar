// assets/js/mis_tours.js

function mostrarMotivo(texto) {
    document.getElementById('textoMotivo').textContent = texto;
    document.getElementById('modalMotivo').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalMotivo').style.display = 'none';
}

function reenviarTour(id_tour) {
    if (!confirm('¿Querés volver a enviar este tour para revisión?')) return;

    const form = new FormData();
    form.append('action', 'reenviar');
    form.append('id_tour', id_tour);

    fetch('controllers/tours/tours.controlador.php', {
        method: 'POST',
        body: form,
        credentials: 'same-origin'
    })
    .then(resp => resp.json())
    .then(data => {
        if (data.status === 'ok') {
            // Recarga la página para mostrar estado actualizado (simple y seguro)
            location.reload();
        } else {
            alert('Error: ' + (data.mensaje || 'No se pudo reenviar'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error de red al reenviar');
    });
}
