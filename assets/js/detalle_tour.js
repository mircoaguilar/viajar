document.addEventListener('DOMContentLoaded', function() {
    const fechas = window.fechasDisponibles || [];
    const selectFecha = document.getElementById('fecha_tour');
    const cantidadInput = document.getElementById('cantidad_personas');

    if (!selectFecha) return;

    selectFecha.addEventListener('change', function() {
        const fechaSeleccionada = this.value;
        const fechaData = fechas.find(f => f.fecha === fechaSeleccionada);
        if (fechaData) {
            cantidadInput.max = fechaData.cupos_disponibles;
            if (cantidadInput.value > fechaData.cupos_disponibles) {
                cantidadInput.value = fechaData.cupos_disponibles;
            }
        }
    });
});
