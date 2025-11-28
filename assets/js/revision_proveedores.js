document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.btn-aprobar, .btn-rechazar').forEach(button => {
        button.addEventListener('click', function() {
            const tr = this.closest('tr');
            const id = tr.dataset.id;
            const accion = this.classList.contains('btn-aprobar') ? 'aprobar' : 'rechazar';

            Swal.fire({
                title: accion === 'aprobar' ? '¿Aprobar proveedor?' : '¿Rechazar proveedor?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.isConfirmed) enviarAccion(id, accion);
            });
        });
    });
});

function enviarAccion(id, accion) {
    fetch(`/viajar/controllers/proveedores/proveedores.controlador.php`, {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({action: accion, id_proveedor: id})
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire('Listo', data.message, 'success');
            document.querySelector(`tr[data-id="${id}"]`)?.remove();
        } else {
            Swal.fire('Error', data.message || 'Error al procesar', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Error al procesar la solicitud', 'error');
    });
}
