document.addEventListener('DOMContentLoaded', () => {

  const formTour = document.getElementById('formTour');

  // --- Envío por AJAX ---
  if (formTour) {
    formTour.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(formTour);
      formData.append('action', 'guardar');

      // --- Ajustar formatos para MySQL ---
      if (formData.get('duracion_horas')) {
        let duracion = formData.get('duracion_horas');
        if (!duracion.includes(':')) duracion = '00:' + duracion;
        formData.set('duracion_horas', duracion + ':00');
      }

      if (formData.get('hora_encuentro')) {
        let hora = formData.get('hora_encuentro');
        if (!hora.includes(':')) hora = '00:' + hora;
        formData.set('hora_encuentro', hora + ':00');
      }

      fetch('controllers/tours/tours.controlador.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          if (confirm("Tour creado correctamente. ¿Deseas agregar más información o imágenes?")) {
            window.location.href = 'index.php?page=tours_mis_tours&id_tour=' + data.id_tour;
          } else {
            window.location.href = 'index.php?page=proveedores_perfil';
          }
        } else {
          alert("Error: " + data.mensaje);
        }
      })
      .catch(err => {
        console.error(err);
        alert("Ocurrió un error al guardar el tour");
      });
    });
  }

});
