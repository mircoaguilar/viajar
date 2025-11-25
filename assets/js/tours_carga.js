document.addEventListener('DOMContentLoaded', () => {

  const formTour = document.getElementById('formTour');
  const descripcion = document.getElementById('descripcion');
  const descripcionCount = document.getElementById('descripcion-count');


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
    if (errorEl && errorEl.classList.contains('error-message')) {
      errorEl.remove();
    }
  }

  function validarCampo(input) {
    limpiarError(input);
    const val = input.value.trim();

    switch(input.id) {

      case 'nombre_tour':
        if (!val) mostrarError(input, 'El nombre del tour no puede estar vacío.');
        break;

      case 'precio_por_persona':
        const num = parseFloat(val);
        if (isNaN(num) || num <= 0)
          mostrarError(input, 'El precio por persona debe ser un número positivo.');
        break;

      case 'imagen_principal':
        if (input.files.length > 0) {
          const file = input.files[0];
          const tiposValidos = ["image/jpeg", "image/png", "image/jpg"];
          if (!tiposValidos.includes(file.type)) {
            mostrarError(input, 'La imagen principal debe ser JPG o PNG.');
          }
        }
        break;

      case 'descripcion':
        if (!val) mostrarError(input, 'La descripción no puede estar vacía.');
        else if (val.length > 1000)
          mostrarError(input, 'La descripción no puede superar los 1000 caracteres.');
        break;

      case 'direccion':
        if (!val) mostrarError(input, 'La dirección no puede estar vacía.');
        break;

      case 'lugar_encuentro':
        if (!val) mostrarError(input, 'El lugar de encuentro no puede estar vacío.');
        break;

      case 'duracion_horas':
        if (!val) mostrarError(input, 'La duración no puede estar vacía.');
        else if (!/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test(val))
          mostrarError(input, 'La duración debe tener formato HH:MM (00:00 a 23:59).');
        break;

      case 'hora_encuentro':
        if (!val) mostrarError(input, 'La hora de encuentro no puede estar vacía.');
        else if (!/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test(val))
          mostrarError(input, 'La hora de encuentro debe tener formato HH:MM (00:00 a 23:59).');
        break;
    }
  }


  if (formTour) {

    formTour.querySelectorAll('input, textarea').forEach(input => {
      input.addEventListener('input', () => validarCampo(input));
      input.addEventListener('change', () => validarCampo(input));
    });


    if (descripcion && descripcionCount) {
      descripcion.addEventListener('input', () => {
        let longitud = descripcion.value.length;

        if (longitud > 1000) {
          descripcion.value = descripcion.value.substring(0, 1000);
          longitud = 1000;
        }

        descripcionCount.textContent = `${longitud}/1000`;

        if (longitud >= 1000) {
          descripcionCount.classList.add('exceeded');
          descripcionCount.classList.remove('warning');
        } else if (longitud > 800) {
          descripcionCount.classList.add('warning');
          descripcionCount.classList.remove('exceeded');
        } else {
          descripcionCount.classList.remove('warning', 'exceeded');
        }
      });
    }


    formTour.addEventListener('submit', function(e) {
      e.preventDefault();

      let errores = [];

      formTour.querySelectorAll('input, textarea').forEach(input => {
        limpiarError(input);
        validarCampo(input);
        if (input.classList.contains('input-error')) errores.push(input.id);
      });

      if (errores.length > 0) return;

      const formData = new FormData(formTour);

      const actionOriginal = formTour.querySelector('input[name="action"]').value;
      formData.set('action', actionOriginal);

      let durVal = formData.get('duracion_horas');
      if (!durVal.includes(':')) durVal = '00:' + durVal;
      if (durVal.split(':').length === 2) durVal += ':00';
      formData.set('duracion_horas', durVal);

      let horaVal = formData.get('hora_encuentro');
      if (!horaVal.includes(':')) horaVal = '00:' + horaVal;
      if (horaVal.split(':').length === 2) horaVal += ':00';
      formData.set('hora_encuentro', horaVal);

      fetch('controllers/tours/tours.controlador.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success' || data.status === 'ok') {
          alert('Tu tour fue enviado correctamente y será revisado por el equipo.');
          window.location.href = 'index.php?page=tours_mis_tours';
        } else {
          alert(data.mensaje || data.message || 'No se pudo guardar el tour.');
        }
      })
      .catch(() => {
        alert('Ocurrió un error de conexión al intentar guardar el tour.');
      });

    });

  }

});
