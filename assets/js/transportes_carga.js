document.addEventListener('DOMContentLoaded', () => {
  const formTransporte = document.getElementById('formTransporte');
  const contenedorPisos = document.getElementById('contenedorPisos');
  const btnAgregarPiso = document.getElementById('btnAgregarPiso');
  let contadorPisos = 0;

  if (btnAgregarPiso) {
    btnAgregarPiso.addEventListener('click', () => {
      contadorPisos++;
      const pisoDiv = document.createElement('div');
      pisoDiv.classList.add('piso-card');
      pisoDiv.innerHTML = `
        <h4>Piso ${contadorPisos}</h4>
        <div class="grid">
          <div>
            <label>Filas</label>
            <input type="number" name="pisos[${contadorPisos}][filas]" min="1" required>
          </div>
          <div>
            <label>Asientos por fila</label>
            <input type="number" name="pisos[${contadorPisos}][asientos_por_fila]" min="1" required>
          </div>
        </div>
        <button type="button" class="btn eliminar-piso">Eliminar piso</button>
      `;

      pisoDiv.querySelector('.eliminar-piso').addEventListener('click', () => pisoDiv.remove());
      contenedorPisos.appendChild(pisoDiv);
    });
  }

  if (formTransporte) {
    formTransporte.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(formTransporte);
      formData.append('action', 'guardar');

      fetch('controllers/transportes/transporte.controlador.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success' || data.status === 'ok') {
          Swal.fire({
            icon: 'success',
            title: 'Transporte enviado para revisión',
            text: 'Tu transporte fue cargado correctamente y será revisado por el equipo antes de ser aprobado.',
            confirmButtonText: 'Volver al perfil',
            confirmButtonColor: '#3085d6'
          }).then(() => {
            window.location.href = 'index.php?page=proveedores_perfil';
          });
        } else {
          Swal.fire('Error', data.mensaje || data.message || 'No se pudo guardar el transporte.', 'error');
        }
      })
      .catch(() => {
        Swal.fire('Error', 'Ocurrió un error de conexión al intentar guardar el transporte.', 'error');
      });
    });
  }

});
