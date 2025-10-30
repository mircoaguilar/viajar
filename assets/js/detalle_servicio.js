document.addEventListener('DOMContentLoaded', function() {
  const carrusel = document.querySelector('.carrusel-fotos');
  if (!carrusel) return;

  let isDown = false;
  let startX;
  let scrollLeft;

  carrusel.addEventListener('mousedown', (e) => {
    isDown = true;
    carrusel.classList.add('active');
    startX = e.pageX - carrusel.offsetLeft;
    scrollLeft = carrusel.scrollLeft;
  });

  carrusel.addEventListener('mouseleave', () => {
    isDown = false;
    carrusel.classList.remove('active');
  });

  carrusel.addEventListener('mouseup', () => {
    isDown = false;
    carrusel.classList.remove('active');
  });

  carrusel.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - carrusel.offsetLeft;
    const walk = (x - startX) * 2; 
    carrusel.scrollLeft = scrollLeft - walk;
  });

  const btnPrev = document.querySelector('.carrusel-prev');
  const btnNext = document.querySelector('.carrusel-next');
  const scrollAmount = 250;

  if (btnPrev) {
    btnPrev.addEventListener('click', () => {
      carrusel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });
  }

  if (btnNext) {
    btnNext.addEventListener('click', () => {
      carrusel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });
  }
});
