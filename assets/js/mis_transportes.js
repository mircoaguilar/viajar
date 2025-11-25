function mostrarMotivo(motivo) {
    document.getElementById("textoMotivo").textContent = motivo;
    document.getElementById("modalMotivo").style.display = "block";
}

function cerrarModal() {
    document.getElementById("modalMotivo").style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById("modalMotivo");
    if (event.target === modal) {
        cerrarModal();
    }
}
