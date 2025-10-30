document.addEventListener('DOMContentLoaded', () => {

  const formHotel = document.getElementById('formHotel');
  const hotelNombre = document.getElementById('hotel_nombre');
  const provinciaSelect = document.getElementById('rela_provincia');
  const ciudadSelect = document.getElementById('rela_ciudad');
  const direccion = document.getElementById('direccion');
  const imagenPrincipal = document.getElementById('imagen_principal');
  const descripcion = document.getElementById('descripcion');
  const servicios = document.getElementById('servicios');
  const politicas = document.getElementById('politicas_cancelacion');
  const reglas = document.getElementById('reglas');
  const fotos = document.getElementById('fotos');

  const previewPrincipal = document.getElementById('preview-principal');
  const previewFotos = document.getElementById('preview-fotos');

  const descCount = document.getElementById('descripcion-count');
  const servCount = document.getElementById('servicios-count');
  const poliCount = document.getElementById('politicas-count');
  const reglasCount = document.getElementById('reglas-count');

  function mostrarError(input, mensaje) {
    limpiarError(input);
    const error = document.createElement('small');
    error.className = 'error-msg';
    error.textContent = mensaje;
    input.insertAdjacentElement('afterend', error);
    input.classList.add('input-error');
  }

  function limpiarError(input) {
    input.classList.remove('input-error');
    const next = input.nextElementSibling;
    if (next && next.classList.contains('error-msg')) next.remove();
  }

  function isValidImage(file) {
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    return file && validTypes.includes(file.type) && file.size <= 2 * 1024 * 1024;
  }

  function contadorTextarea(textarea, display, min, max) {
    textarea.addEventListener('input', () => {
      const len = textarea.value.length;
      display.textContent = `${len}/${max}`;
      if (len > max) {
        mostrarError(textarea, `Máximo ${max} caracteres alcanzado.`);
        textarea.value = textarea.value.substring(0, max);
      } else if (len < min) {
        mostrarError(textarea, `Mínimo ${min} caracteres.`);
      } else {
        limpiarError(textarea);
      }
    });
  }

  contadorTextarea(descripcion, descCount, 30, 1000);
  contadorTextarea(servicios, servCount, 1, 500);
  contadorTextarea(politicas, poliCount, 20, 500);
  contadorTextarea(reglas, reglasCount, 20, 500);

  imagenPrincipal.addEventListener('change', () => {
    previewPrincipal.innerHTML = '';
    const file = imagenPrincipal.files[0];
    if (!file) return;
    if (!isValidImage(file)) {
      mostrarError(imagenPrincipal, 'Imagen inválida (jpg, jpeg, png, webp, máx. 2MB).');
      imagenPrincipal.value = '';
      return;
    }
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    previewPrincipal.appendChild(img);
    limpiarError(imagenPrincipal);
  });

  fotos.addEventListener('change', () => {
    previewFotos.innerHTML = '';
    const files = Array.from(fotos.files);
    if (files.length > 10) {
      mostrarError(fotos, 'Máximo 10 fotos permitidas.');
      fotos.value = '';
      return;
    }
    files.forEach(file => {
      if (!isValidImage(file)) {
        mostrarError(fotos, 'Todas las fotos deben ser jpg, jpeg, png, webp y < 2MB.');
        fotos.value = '';
        return;
      }
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      previewFotos.appendChild(img);
    });
    limpiarError(fotos);
  });

  provinciaSelect.addEventListener('change', function() {
    const provinciaId = this.value;
    ciudadSelect.innerHTML = '<option value="">Cargando...</option>';
    ciudadSelect.disabled = true;
    if (!provinciaId) {
      ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad...</option>';
      ciudadSelect.disabled = false;
      return;
    }
    fetch(`controllers/ciudades/ciudades_por_provincia.php?provincia=${provinciaId}`)
      .then(res => res.json())
      .then(data => {
        ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad...</option>';
        data.forEach(c => {
          const opt = document.createElement('option');
          opt.value = c.id_ciudad;
          opt.textContent = c.nombre;
          ciudadSelect.appendChild(opt);
        });
        ciudadSelect.disabled = false;
      })
      .catch(() => {
        ciudadSelect.innerHTML = '<option value="">Error al cargar ciudades</option>';
        ciudadSelect.disabled = false;
      });
  });

  [hotelNombre, provinciaSelect, ciudadSelect, direccion, descripcion, servicios, politicas, reglas, imagenPrincipal, fotos].forEach(campo => {
    if (campo.tagName === 'INPUT' || campo.tagName === 'TEXTAREA') {
      campo.addEventListener('input', () => limpiarError(campo));
    }
    if (campo.tagName === 'SELECT') {
      campo.addEventListener('change', () => limpiarError(campo));
    }
  });

  formHotel.addEventListener('submit', function(e) {
    e.preventDefault();
    let valido = true;

    [hotelNombre, provinciaSelect, ciudadSelect, direccion, descripcion, servicios, politicas, reglas, imagenPrincipal].forEach(limpiarError);

    if (!/^[\w\s.,'-]{3,}$/.test(hotelNombre.value.trim())) { mostrarError(hotelNombre, 'Nombre inválido o demasiado corto.'); valido = false; }
    if (!provinciaSelect.value) { mostrarError(provinciaSelect, 'Seleccioná una provincia.'); valido = false; }
    if (!ciudadSelect.value) { mostrarError(ciudadSelect, 'Seleccioná una ciudad.'); valido = false; }
    if (direccion.value.trim().length < 5) { mostrarError(direccion, 'Dirección muy corta.'); valido = false; }
    if (!imagenPrincipal.files[0]) { mostrarError(imagenPrincipal, 'Imagen principal requerida.'); valido = false; }
    else if (!isValidImage(imagenPrincipal.files[0])) { mostrarError(imagenPrincipal, 'Imagen inválida.'); valido = false; }
    if (descripcion.value.trim().length < 30) { mostrarError(descripcion, 'Descripción mínima 30 caracteres.'); valido = false; }
    if (servicios.value.trim().length < 1) { mostrarError(servicios, 'Ingresá al menos un servicio.'); valido = false; }
    if (politicas.value.trim().length < 20) { mostrarError(politicas, 'Políticas mínimo 20 caracteres.'); valido = false; }
    if (reglas.value.trim().length < 20) { mostrarError(reglas, 'Reglas mínimo 20 caracteres.'); valido = false; }

    if (!valido) return;

    const formData = new FormData(formHotel);

    fetch('controllers/hoteles/hoteles.controlador.php', { method: 'POST', body: formData })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Hotel enviado para revisión',
            text: 'Tu hotel fue enviado correctamente y será revisado por el equipo. Te notificaremos cuando sea aprobado.',
            confirmButtonText: 'Volver al perfil',
            confirmButtonColor: '#3085d6'
          }).then(() => {
            window.location.href = 'index.php?page=proveedores_perfil';
          });
        } else {
          Swal.fire('Error', data.message || 'No se pudo guardar el hotel.', 'error');
        }
      })
      .catch(() => Swal.fire('Error', 'Ocurrió un error de conexión al intentar guardar el hotel.', 'error'));
  });


});
