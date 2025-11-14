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

    const base_url = "/viajar/controllers/auditorias/auditorias.controlador.php?action=listar_auditorias";
    const $resultados = $('#resultados-auditoria');
    const $formFiltros = $('#form-filtros-auditoria'); 
    const $currentPageInput = $('#current_page_input'); 
    const $btnLimpiar = $('#btn-limpiar-filtros'); 

    function getFiltrosData(page) {
        $currentPageInput.val(page);
        let data = $formFiltros.serialize();
        return data + '&ajax=true';
    }

    function cargarTabla(page) {
        let data = getFiltrosData(page);

        $resultados.html('<p class="loading-message" style="text-align: center; padding: 20px;"><i class="fa fa-spinner fa-spin"></i> Cargando registros...</p>');

        $.ajax({
            url: base_url, 
            type: 'GET',
            data: data, 
            success: function(response) {
                $resultados.html(response);
            },
            error: function() {
                $resultados.html('<p class="error-message" style="color: red; text-align: center; padding: 20px;">Error al cargar los datos. Por favor, inténtelo de nuevo.</p>');
            }
        });
    }

    
    $formFiltros.on('submit', function(e) {
        e.preventDefault(); 
        cargarTabla(0); 
    });

    $resultados.on('click', '.btn-paginacion', function(e) {
        e.preventDefault(); 
        
        if ($(this).hasClass('disabled')) {
            return; 
        }

        let newPage = $(this).data('page'); 
        if (newPage !== undefined) {
            cargarTabla(newPage); 
        }
    });

    $btnLimpiar.on('click', function(e) {
        e.preventDefault(); 
        
        $formFiltros.get(0).reset(); 
        
        $('#usuario').val('').trigger('change'); 
        $('#accion').val('').trigger('change'); 

        document.querySelectorAll(".flatpickr-input").forEach(i => {
            if (i._flatpickr) i._flatpickr.clear();
        });

        cargarTabla(0); 
    });

});