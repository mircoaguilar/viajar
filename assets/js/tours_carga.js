document.addEventListener('DOMContentLoaded', () => {

  const formTour = document.getElementById('formTour');

  if (formTour) {
    formTour.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(formTour);
      formData.append('action', 'guardar');

      if (formData.get('duracion_horas')) {
        let duracion = formData.get('duracion_horas');
        if (!duracion.includes(':')) duracion = '00:' + duracion;
        if (duracion.split(':').length === 2) duracion += ':00';
        formData.set('duracion_horas', duracion);
      }

      if (formData.get('hora_encuentro')) {
        let hora = formData.get('hora_encuentro');
        if (!hora.includes(':')) hora = '00:' + hora;
        if (hora.split(':').length === 2) hora += ':00';
        formData.set('hora_encuentro', hora);
      }

      fetch('controllers/tours/tours.controlador.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success' || data.status === 'ok') {
          Swal.fire({
            icon: 'success',
            title: 'Tour enviado para revisión',
            text: 'Tu tour fue enviado correctamente y será revisado por el equipo. Te notificaremos cuando sea aprobado.',
            confirmButtonText: 'Volver al perfil',
            confirmButtonColor: '#3085d6'
          }).then(() => {
            window.location.href = 'index.php?page=proveedores_perfil';
          });
        } else {
          Swal.fire('Error', data.mensaje || data.message || 'No se pudo guardar el tour.', 'error');
        }
      })
      .catch(() => {
        Swal.fire('Error', 'Ocurrió un error de conexión al intentar guardar el tour.', 'error');
      });
    });
  }

});
