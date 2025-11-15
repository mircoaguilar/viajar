function mostrarMotivo(motivo) {
    document.getElementById('textoMotivo').innerText = motivo;
    document.getElementById('modalMotivo').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalMotivo').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('modalMotivo')) {
        cerrarModal();
    }
}