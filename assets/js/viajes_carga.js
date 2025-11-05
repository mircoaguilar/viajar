document.addEventListener('DOMContentLoaded', () => {
  const formViaje = document.getElementById('formViaje');
  const selectRuta = document.getElementById('rela_transporte_rutas');
  const preview = document.getElementById('previewRuta');

  if (selectRuta) {
    selectRuta.addEventListener('change', () => {
      const selected = selectRuta.options[selectRuta.selectedIndex];
      if (!selected.value) {
        preview.style.display = 'none';
        preview.innerHTML = '';
        return;
      }

      const nombre = selected.dataset.nombre;
      const trayecto = selected.dataset.trayecto;
      const duracion = selected.dataset.duracion;
      const precio = selected.dataset.precio;
      const transporte = selected.dataset.transporte;

      preview.innerHTML = `
        <div class="info">
          <p><strong>${nombre}</strong></p>
          <p>Trayecto: ${trayecto}</p>
          <p>Duración: ${duracion}</p>
          <p>Precio: $${precio}</p>
          <p>Transporte: ${transporte}</p>
        </div>
      `;
      preview.style.display = 'block';
    });
  }

  if (formViaje) {
    formViaje.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = new FormData(formViaje);
      formData.append('ajax', '1');
      
      const horaSalida = formViaje.hora_salida.value.trim();
      const horaLlegada = formViaje.hora_llegada.value.trim();
      if (!/^\d{2}:\d{2}$/.test(horaSalida) || !/^\d{2}:\d{2}$/.test(horaLlegada)) {
        Swal.fire('Formato incorrecto', 'Las horas deben tener el formato HH:MM.', 'warning');
        return;
      }

      fetch('controllers/transportes/viajes.controlador.php?action=guardar', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success' || data.status === 'ok') {
          Swal.fire({
            //feedback momentario no es el definitivo
            icon: 'success',
            title: 'Viaje cargado correctamente',
            text: 'Tu viaje fue guardado',
            confirmButtonText: 'Volver a mi perfil',
            confirmButtonColor: '#3085d6'
          }).then(() => {
            window.location.href = 'index.php?page=proveedores_perfil';
          });
        } else {
          Swal.fire('Error', data.message || data.mensaje || 'No se pudo guardar el viaje.', 'error');
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Ocurrió un error al intentar guardar el viaje.', 'error');
      });
    });
  }

});
