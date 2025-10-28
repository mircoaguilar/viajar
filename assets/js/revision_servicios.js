document.addEventListener("DOMContentLoaded", () => {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;

            // Activar botón
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Mostrar contenido correspondiente
            tabContents.forEach(tc => tc.classList.remove('active'));
            document.getElementById(`tab-${target}`).classList.add('active');
        });
    });

    document.querySelectorAll('.btn-aprobar, .btn-rechazar').forEach(button => {
        button.addEventListener('click', function() {
            const tr = this.closest('tr');
            const id = tr.dataset.id;
            const tipo = tr.closest('table').dataset.tipo;
            const accion = this.classList.contains('btn-aprobar') ? 'aprobar' : 'rechazar';
            const nombreAccion = accion === 'aprobar' ? 'aprobar' : 'rechazar';

            Swal.fire({
                title: `¿Deseas ${nombreAccion} este servicio?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/viajar/controllers/admin/admin.controlador.php?action=revision_servicios`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id, tipo, accion })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('¡Listo!', data.message, 'success');
                            // Remover fila de la tabla
                            tr.remove();
                        } else {
                            Swal.fire('Error', data.message || 'Ocurrió un error', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Ocurrió un error en la conexión', 'error');
                    });
                }
            });
        });
    });

});
