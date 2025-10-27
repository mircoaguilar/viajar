document.addEventListener('DOMContentLoaded', () => {

  const formTransporte = document.getElementById('formTransporte');

  // --- Envío por AJAX ---
  if (formTransporte) {
    formTransporte.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(formTransporte);

      fetch('controllers/transportes/transporte.controlador.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          if (confirm("Transporte creado correctamente. ¿Deseas cargar rutas ahora?")) {
            window.location.href = 'index.php?page=rutas_carga&id_transporte=' + data.id_transporte;
          } else {
            window.location.href = 'index.php?page=proveedores_perfil';
          }
        } else {
          alert("Error: " + data.mensaje);
        }
      })
      .catch(err => {
        console.error(err);
        alert("Ocurrió un error al guardar el transporte");
      });
    });
  }

});
