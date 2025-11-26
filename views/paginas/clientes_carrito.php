<?php
require_once __DIR__ . '/../../models/carrito.php';

if (!isset($_SESSION['id_usuarios'])) {
    header("Location: index.php?page=login&message=Tenés que iniciar sesión.&status=danger");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/carrito.css">
</head>
<body>
<main class="carrito-container">
    <h2>Mi Carrito</h2>
    <div id="carrito-contenido">
    </div>
</main>

<div class="modal-container" id="modalVerCarrito" style="display:none;">
    <div class="modal-detalle">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom">Detalle del ítem</h5>
            <button type="button" class="btn-cerrar" id="cerrarModalCarrito">&times;</button>
        </div>
        <div class="modal-body-custom" id="modal-body-carrito">
        </div>
        <div class="modal-footer-custom">
            <button class="btn-secondary" id="cerrarModalCarritoFooter">Cerrar</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/carrito.js"></script>
</body>
</html>
