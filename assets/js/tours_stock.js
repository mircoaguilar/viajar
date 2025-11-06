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

  function mostrarError(input, mensaje) {
    input.classList.add('input-error');
    let errorSpan = input.nextElementSibling;
    if (!errorSpan || !errorSpan.classList.contains('error-message')) {
      errorSpan = document.createElement('span');
      errorSpan.classList.add('error-message');
      input.insertAdjacentElement('afterend', errorSpan);
    }
    errorSpan.textContent = mensaje;
  }

  function limpiarError(input) {
    input.classList.remove('input-error');
    const errorEl = input.nextElementSibling;
    if (errorEl && errorEl.classList.contains('error-message')) errorEl.remove();
  }

  function validarCampo(input) {
    limpiarError(input);
    const val = input.value.trim();
    switch(input.id) {
      case 'rela_tour':
        if (!val) mostrarError(input, 'Debes seleccionar un tour.');
        break;
      case 'fecha_inicio':
        if (!val) mostrarError(input, 'Debes ingresar la fecha de inicio.');
        break;
      case 'fecha_fin':
        if (!val) mostrarError(input, 'Debes ingresar la fecha de fin.');
        break;
      case 'cantidad':
        const num = parseInt(val);
        if (!val || isNaN(num) || num < 1) mostrarError(input, 'Cupos disponibles debe ser un número positivo.');
        break;
    }
  }


  form.querySelectorAll('input, select').forEach(input => {
    input.addEventListener('input', () => validarCampo(input));
    input.addEventListener('change', () => validarCampo(input));
  });


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

  
  btnPrevisualizar.addEventListener('click', () => {
    ['rela_tour','fecha_inicio','fecha_fin','cantidad'].forEach(id => validarCampo(document.getElementById(id)));

  
    if (form.querySelectorAll('.input-error').length > 0) return;

    const idTour = selectTour.value;
    const nombreTour = selectTour.options[selectTour.selectedIndex]?.text || '';
    const desde = fechaInicio.value;
    const hasta = fechaFin.value;
    const cantidad = parseInt(inputCantidad.value);

    const fechas = generarRangoFechas(desde, hasta);
    if (fechas.length === 0) {
      mostrarError(fechaInicio, 'Rango de fechas inválido.');
      mostrarError(fechaFin, 'Rango de fechas inválido.');
      return;
    }

    tbody.innerHTML = '';
    fechas.forEach(f => {
      const fila = document.createElement('tr');
      fila.innerHTML = `<td>${nombreTour}</td><td>${f}</td><td>${cantidad}</td>`;
      tbody.appendChild(fila);
    });
    tablaPreview.style.display = 'table';
  });

 
  form.addEventListener('submit', e => {
    e.preventDefault();

    ['rela_tour','fecha_inicio','fecha_fin','cantidad'].forEach(id => validarCampo(document.getElementById(id)));

   
    if (form.querySelectorAll('.input-error').length > 0) return;

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
