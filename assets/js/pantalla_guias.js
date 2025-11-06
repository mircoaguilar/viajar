document.addEventListener("DOMContentLoaded", function() {
  const navLinks = document.querySelectorAll(".top-bar .nav-links a");

  navLinks.forEach(link => {
    link.addEventListener("click", function() {
      // Quitar active de todos
      navLinks.forEach(l => l.classList.remove("active"));
      // Poner active al clickeado
      this.classList.add("active");
    });
  });
});

