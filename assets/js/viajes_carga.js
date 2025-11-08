document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formViaje');

  const crearError = (input, mensaje) => {
    eliminarError(input);
    input.classList.add('input-error');
    const error = document.createElement('small');
    error.className = 'error-text';
    error.textContent = mensaje;
    input.insertAdjacentElement('afterend', error);
  };

  const eliminarError = (input) => {
    input.classList.remove('input-error');
    const next = input.nextElementSibling;
    if (next && next.classList.contains('error-text')) next.remove();
  };

  
  form.querySelectorAll('input, select').forEach(el => {
    el.addEventListener('input', () => eliminarError(el));
    el.addEventListener('change', () => eliminarError(el));
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    let valido = true;

    const ruta = form.rela_transporte_rutas;
    const fecha = form.viaje_fecha;
    const salida = form.hora_salida;
    const llegada = form.hora_llegada;

    
    if (ruta.value === '') {
      crearError(ruta, 'Seleccione una ruta válida.');
      valido = false;
    }

    
    const fechaValor = fecha.value.trim();
    const hoy = new Date();
    const fechaIngresada = new Date(fechaValor);

    if (!/^\d{4}-\d{2}-\d{2}$/.test(fechaValor)) {
      crearError(fecha, 'Ingrese una fecha válida (AAAA-MM-DD).');
      valido = false;
    } else if (fechaIngresada < new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate())) {
      crearError(fecha, 'La fecha no puede ser anterior a hoy.');
      valido = false;
    }

    
    const horaSalida = salida.value.trim();
    const horaLlegada = llegada.value.trim();

    if (!/^\d{2}:\d{2}$/.test(horaSalida)) {
      crearError(salida, 'Ingrese una hora de salida válida (HH:MM).');
      valido = false;
    }

    if (!/^\d{2}:\d{2}$/.test(horaLlegada)) {
      crearError(llegada, 'Ingrese una hora de llegada válida (HH:MM).');
      valido = false;
    }

    
    if (/^\d{2}:\d{2}$/.test(horaSalida) && /^\d{2}:\d{2}$/.test(horaLlegada)) {
      const [hS, mS] = horaSalida.split(':').map(Number);
      const [hL, mL] = horaLlegada.split(':').map(Number);
      const minutosSalida = hS * 60 + mS;
      const minutosLlegada = hL * 60 + mL;

      if (minutosLlegada <= minutosSalida) {
        crearError(llegada, 'La hora de llegada debe ser posterior a la hora de salida.');
        valido = false;
      }
    }

    if (!valido) return;
    form.submit();
  });
});
