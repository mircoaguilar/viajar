document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formRuta');
  const maxChars = 500;

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

 
  form.querySelectorAll('input, select, textarea').forEach(el => {
    el.addEventListener('input', () => eliminarError(el));
  });

  
  const descripcion = document.getElementById('descripcion');
  const contador = document.createElement('small');
  contador.id = 'contador';
  contador.style.display = 'block';
  contador.style.marginTop = '4px';
  descripcion.insertAdjacentElement('afterend', contador);

  const actualizarContador = () => {
    const len = descripcion.value.length;
    contador.textContent = `${len}/${maxChars} caracteres`;
    contador.style.color = len > maxChars ? 'red' : '#555';
  };
  descripcion.addEventListener('input', actualizarContador);
  actualizarContador();


  form.addEventListener('submit', (e) => {
    e.preventDefault();
    let valido = true;

    const nombre = form.nombre;
    const trayecto = form.trayecto;
    const transporte = form.rela_transporte;
    const origen = form.rela_ciudad_origen;
    const destino = form.rela_ciudad_destino;
    const duracion = form.duracion;
    const precio = form.precio_por_persona;

    if (!/^[\w\s-]{3,}$/.test(nombre.value.trim())) {
      crearError(nombre, 'Ingrese un nombre válido (mínimo 3 caracteres).');
      valido = false;
    }

    if (!/^[\w\s-]{10,}$/.test(trayecto.value.trim()) || !trayecto.value.includes('-')) {
      crearError(trayecto, 'El trayecto debe tener al menos 10 caracteres y contener guiones (-).');
      valido = false;
    }

    
    if (transporte.value === '') {
      crearError(transporte, 'Seleccione un transporte.');
      valido = false;
    }

    
    if (origen.value === '') {
      crearError(origen, 'Seleccione una ciudad de origen.');
      valido = false;
    }
    if (destino.value === '') {
      crearError(destino, 'Seleccione una ciudad de destino.');
      valido = false;
    }
    if (origen.value && destino.value && origen.value === destino.value) {
      crearError(destino, 'La ciudad de destino no puede ser igual a la de origen.');
      valido = false;
    }

    
    if (!/^(0?[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/.test(duracion.value.trim())) {
      crearError(duracion, 'Ingrese una duración válida (HH:MM).');
      valido = false;
    }

    
    if (!(parseFloat(precio.value) > 0)) {
      crearError(precio, 'Ingrese un precio válido mayor que 0.');
      valido = false;
    }

    const desc = descripcion.value.trim();
    if (desc === '') {
      crearError(descripcion, 'Falta datos en descripción.');
      valido = false;
    } else if (desc.length > maxChars) {
      crearError(descripcion, `La descripción no puede superar los ${maxChars} caracteres.`);
      valido = false;
    }

    if (!valido) return;
    form.submit();
  });
});
