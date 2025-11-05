document.addEventListener("DOMContentLoaded", function() {
  const navLinks = document.querySelectorAll(".top-bar .nav-links a");

  navLinks.forEach(link => {
    link.addEventListener("click", function() {
      // Quita active de todos
      navLinks.forEach(l => l.classList.remove("active"));
      // Agrega active al link clickeado
      this.classList.add("active");
    });
  });
})