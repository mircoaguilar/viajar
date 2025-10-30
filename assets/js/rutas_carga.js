document.addEventListener('DOMContentLoaded', () => {

  const formRuta = document.getElementById('formRuta');

  if (formRuta) {
    formRuta.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = new FormData(formRuta);
      formData.append('ajax', '1'); 

      fetch('controllers/transportes/rutas.controlador.php?action=guardar', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          alert("Ruta guardada correctamente.");
          window.location.href = 'index.php?page=transportes_mis_transportes';
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert("Ocurrió un error al guardar la ruta");
      });
    });
  }

});
