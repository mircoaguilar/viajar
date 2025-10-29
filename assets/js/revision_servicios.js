document.addEventListener("DOMContentLoaded", () => {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;

            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            tabContents.forEach(tc => tc.classList.remove('active'));
            document.getElementById(`tab-${target}`).classList.add('active');
        });
    });

    document.querySelectorAll('.btn-aprobar, .btn-rechazar').forEach(button => {
        if (!button.querySelector('i')) {
            const icon = document.createElement('i');
            icon.className = button.classList.contains('btn-aprobar') 
                ? 'fas fa-check' 
                : 'fas fa-times';
            button.appendChild(icon);
        }

        button.addEventListener('click', function() {
            const tr = this.closest('tr');
            const id = tr.dataset.id;
            const tipo = tr.closest('table').dataset.tipo;
            const accion = this.classList.contains('btn-aprobar') ? 'aprobar' : 'rechazar';

            if (accion === 'rechazar') {
                Swal.fire({
                    title: 'Rechazar servicio',
                    input: 'textarea',
                    inputLabel: 'Motivo del rechazo',
                    inputPlaceholder: 'Escribe el motivo...',
                    inputAttributes: { 'aria-label': 'Motivo del rechazo' },
                    showCancelButton: true,
                    confirmButtonText: 'Rechazar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (motivo) => {
                        if (!motivo.trim()) {
                            Swal.showValidationMessage('Debes ingresar un motivo');
                        }
                        return motivo;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        enviarAccion(tipo, id, accion, result.value);
                    }
                });
            } else {
                Swal.fire({
                    title: '¿Aprobar servicio?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Aprobar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        enviarAccion(tipo, id, accion);
                    }
                });
            }
        });
    });
});

function enviarAccion(tipo, id, accion, motivo = '') {
    fetch(`/viajar/controllers/admin/revision.controlador.php?action=revision_servicios`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ tipo, id, accion, motivo })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Listo', data.message, 'success');
            document.querySelector(`tr[data-id="${id}"]`)?.remove();
        } else {
            Swal.fire('Error', data.message || 'Error al procesar la acción', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Error de conexión con el servidor', 'error');
    });
}
