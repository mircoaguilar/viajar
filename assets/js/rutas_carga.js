document.addEventListener('DOMContentLoaded', () => {

  const formRuta = document.getElementById('formRuta');
  const selectTransporte = document.getElementById('rela_transporte');
  const preview = document.getElementById('previewTransporte');
  const origen = document.getElementById('rela_ciudad_origen');
  const destino = document.getElementById('rela_ciudad_destino');

  if (selectTransporte) {
    selectTransporte.addEventListener('change', () => {
      const selected = selectTransporte.options[selectTransporte.selectedIndex];
      if (!selected.value) {
        preview.style.display = 'none';
        preview.innerHTML = '';
        return;
      }

      const nombre = selected.dataset.nombre;
      const tipo = selected.dataset.tipo;
      const matricula = selected.dataset.matricula;
      const capacidad = selected.dataset.capacidad;
      const img = selected.dataset.img;

      preview.innerHTML = `
        ${img ? `<img src="assets/images/${img}" alt="${nombre}">` : ''}
        <div class="info">
          <p><strong>${nombre}</strong></p>
          <p>Tipo: ${tipo}</p>
          <p>Matrícula: ${matricula}</p>
          <p>Capacidad: ${capacidad}</p>
        </div>
      `;
      preview.style.display = 'flex';
    });
  }

  if (origen && destino) {
    destino.addEventListener('change', () => {
      if (origen.value && destino.value && origen.value === destino.value) {
        Swal.fire('Atención', 'La ciudad de origen y destino no pueden ser la misma.', 'warning');
        destino.value = "";
      }
    });
  }

  if (formRuta) {
    formRuta.addEventListener('submit', (e) => {
      e.preventDefault();

      const duracion = formRuta.duracion.value.trim();
      if (!/^\d{1,2}:\d{2}$/.test(duracion)) {
        Swal.fire('Formato incorrecto', 'La duración debe tener el formato HH:MM (ej: 02:30).', 'warning');
        return;
      }

      const formData = new FormData(formRuta);
      formData.append('ajax', '1');

      fetch('controllers/transportes/rutas.controlador.php?action=guardar', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success' || data.status === 'ok') {
          Swal.fire({
            icon: 'success',
            //feedback momentario no es el definitivo
            title: 'Ruta cargada correctamente',
            text: 'Tu ruta fue cargada correctamente.',
            confirmButtonText: 'Volver a mi perfil',
            confirmButtonColor: '#3085d6'
          }).then(() => {
            window.location.href = 'index.php?page=proveedores_perfil';
          });
        } else {
          Swal.fire('Error', data.message || data.mensaje || 'No se pudo guardar la ruta.', 'error');
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Ocurrió un error al intentar guardar la ruta.', 'error');
      });
    });
  }
});
