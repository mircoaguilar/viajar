document.addEventListener('DOMContentLoaded', function() {
  const cards = document.querySelectorAll('.habitacion-card');
  cards.forEach(card => {
    const principal = card.querySelector('.foto-principal');
    const miniaturas = card.querySelectorAll('.miniaturas img');
    miniaturas.forEach(mini => {
      mini.addEventListener('click', () => {
        principal.src = mini.src;
      });
    });
  });

  const checkinInput = document.getElementById('checkin');
  const checkoutInput = document.getElementById('checkout');

  const urlParams = new URLSearchParams(window.location.search);
  const checkinVal = urlParams.get('checkin');
  const checkoutVal = urlParams.get('checkout');
  const personasVal = urlParams.get('personas');

  if (checkinVal) checkinInput.value = checkinVal;
  if (checkoutVal) checkoutInput.value = checkoutVal;
  if (personasVal) document.getElementById('personas').value = personasVal;

  const checkoutPicker = flatpickr(checkoutInput, {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    minDate: checkinVal ? new Date(checkinVal) : new Date().fp_incr(1),
    locale: "es"
  });

  flatpickr(checkinInput, {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    minDate: "today",
    locale: "es",
    onChange: function(selectedDates) {
      if (selectedDates.length > 0) {
        const minCheckout = new Date(selectedDates[0].getTime() + 24*60*60*1000);
        checkoutPicker.set('minDate', minCheckout);
        if (checkoutInput.value) {
          const checkoutDate = new Date(checkoutInput.value);
          if (checkoutDate <= selectedDates[0]) {
            checkoutInput.value = '';
          }
        }
      }
    }
  });

  const botonesCarrito = document.querySelectorAll('.btn-agregar-carrito');
  botonesCarrito.forEach(btn => {
    btn.addEventListener('click', function() {
      const data = {
        action: "agregar",
        idhotel: this.dataset.idhotel,
        hotel: this.dataset.hotel,
        idhab: this.dataset.idhab,
        habitacion: this.dataset.hab,
        precio: this.dataset.precio,
        checkin: this.dataset.checkin,
        checkout: this.dataset.checkout,
        personas: this.dataset.personas
      };

      fetch("/viajar/controllers/carrito.controlador.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          const badge = document.getElementById("carrito-count");
          if (badge) badge.textContent = res.cantidad;

          Swal.fire({
            icon: "success",
            title: "Agregado al carrito",
            text: `${data.habitacion} en ${data.hotel} fue agregada correctamente.`,
            showConfirmButton: false,
            timer: 2000
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: res.message || "No se pudo agregar al carrito."
          });
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Ocurrió un problema al procesar la solicitud."
        });
      });
    });
  });

});
