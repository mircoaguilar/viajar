document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal-cancelar');
    const modalContent = modal.querySelector('.modal-content');
    const formCancelar = document.getElementById('form-cancelar');
    const idDetalleInput = document.getElementById('id_reserva'); 

    document.querySelectorAll('.btn-cancelar').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            idDetalleInput.value = id; 
            modal.style.display = 'flex'; 
        });
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });

    const closeBtn = modal.querySelector('.modal-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    formCancelar.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/viajar/controllers/cancelacion.controlador.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            modal.style.display = 'none';
            Swal.fire({
                icon: data.status === 'ok' ? 'success' : 'error',
                title: data.mensaje
            }).then(() => {
                window.location.reload(); 
            });
        })
        .catch(err => {
            console.error(err);
            modal.style.display = 'none';
            Swal.fire({ icon: 'error', title: 'Ocurrió un error al procesar la cancelación' });
        });
    });
});
