document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('form-stock');
  const btnPrevisualizar = document.getElementById('previsualizar');
  const tablaPreview = document.getElementById('tabla-preview');
  const tbody = tablaPreview.querySelector('tbody');

  const fechaInicio = document.getElementById('fecha_inicio');
  const fechaFin = document.getElementById('fecha_fin');
  const selectTour = document.getElementById('rela_tour');
  const inputCantidad = document.getElementById('cantidad');

  const finPicker = flatpickr(fechaFin, {
    dateFormat: "Y-m-d",
    minDate: "today",
    locale: "es"
  });

  flatpickr(fechaInicio, {
    dateFormat: "Y-m-d",
    minDate: "today",
    locale: "es",
    onChange: function(selectedDates, dateStr) {
      finPicker.set('minDate', dateStr);
    }
  });

  // Generar rango de fechas
  function generarRangoFechas(desde, hasta) {
    const fechas = [];
    const inicio = new Date(desde);
    const fin = new Date(hasta);

    if (isNaN(inicio) || isNaN(fin)) return [];

    for (let d = new Date(inicio); d <= fin; d.setDate(d.getDate() + 1)) {
      fechas.push(d.toISOString().split('T')[0]);
    }
    return fechas;
  }

  // PREVISUALIZAR
  btnPrevisualizar.addEventListener('click', () => {
    const idTour = selectTour.value;
    const nombreTour = selectTour.options[selectTour.selectedIndex]?.text || '';
    const desde = fechaInicio.value;
    const hasta = fechaFin.value;
    const cantidad = parseInt(inputCantidad.value);

    if (!idTour || !desde || !hasta || !cantidad || cantidad < 1) {
      alert('Por favor completá todos los campos correctamente.');
      return;
    }

    const fechas = generarRangoFechas(desde, hasta);
    if (fechas.length === 0) {
      alert('Rango de fechas inválido.');
      return;
    }

    // Limpiar tabla y mostrar preview
    tbody.innerHTML = '';
    fechas.forEach(f => {
      const fila = document.createElement('tr');
      fila.innerHTML = `
        <td>${nombreTour}</td>
        <td>${f}</td>
        <td>${cantidad}</td>
      `;
      tbody.appendChild(fila);
    });

    tablaPreview.style.display = 'table';
  });

  // ENVIAR FORMULARIO (AJAX)
  form.addEventListener('submit', e => {
    e.preventDefault();

    const idTour = selectTour.value;
    const desde = fechaInicio.value;
    const hasta = fechaFin.value;
    const cantidad = parseInt(inputCantidad.value);

    if (!idTour || !desde || !hasta || !cantidad || cantidad < 1) {
      alert('Completá todos los campos antes de guardar.');
      return;
    }

    const datos = new FormData(form);

    fetch('controllers/tours/stock_tour.controlador.php?action=guardar_stock', {
      method: 'POST',
      body: datos
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          alert('Stock guardado correctamente.');
          form.reset();
          tbody.innerHTML = '';
          tablaPreview.style.display = 'none';
        } else {
          alert('Error al guardar: ' + data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('Error de conexión con el servidor.');
      });
  });

});
