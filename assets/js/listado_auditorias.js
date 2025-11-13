document.addEventListener("DOMContentLoaded", () => {

    $('.select2').select2({
        placeholder: "Seleccione un usuario",
        allowClear: true,
        width: 'resolve'
    });

    flatpickr("#fecha_desde", {
        dateFormat: "Y-m-d",
        locale: "es"
    });
    flatpickr("#fecha_hasta", {
        dateFormat: "Y-m-d",
        locale: "es"
    });

    const formFiltros = document.querySelector('.form-filtros');
    const tablaBody = document.querySelector('.tabla-datos tbody');
    const btnFiltrar = document.querySelector('.btn-filtrar');
    const btnLimpiar = document.querySelector('.btn-limpiar');

    formFiltros.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(formFiltros);
        const params = new URLSearchParams(formData);

        btnFiltrar.disabled = true;
        btnFiltrar.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

        try {
            const response = await fetch(`controllers/auditorias/auditorias.controlador.php?action=filtrar`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success' && Array.isArray(data.auditorias)) {
                actualizarTabla(data.auditorias);
            } else {
                tablaBody.innerHTML = `<tr><td colspan="6" class="no-datos">No se encontraron auditorías.</td></tr>`;
            }

        } catch (error) {
            console.error("Error al filtrar:", error);
        } finally {
            btnFiltrar.disabled = false;
            btnFiltrar.innerHTML = '<i class="fa fa-search"></i>';
        }
    });

    btnLimpiar.addEventListener('click', (e) => {
        e.preventDefault();
        formFiltros.reset();
        $('.select2').val(null).trigger('change');
        document.querySelectorAll(".flatpickr-input").forEach(i => i._flatpickr.clear());
        tablaBody.innerHTML = `<tr><td colspan="6" class="no-datos">Seleccione filtros y presione buscar.</td></tr>`;
    });

    function actualizarTabla(auditorias) {
        if (!auditorias.length) {
            tablaBody.innerHTML = `<tr><td colspan="6" class="no-datos">No se encontraron auditorías.</td></tr>`;
            return;
        }

        tablaBody.innerHTML = auditorias.map(a => `
            <tr>
                <td>${a.id_auditoria}</td>
                <td>${a.usuario_nombre ?? 'Desconocido'}</td>
                <td>${a.perfil_nombre ?? ''}</td>
                <td>${a.accion}</td>
                <td>${a.descripcion}</td>
                <td>${new Date(a.fecha).toLocaleString('es-AR')}</td>
            </tr>
        `).join('');
    }

});
