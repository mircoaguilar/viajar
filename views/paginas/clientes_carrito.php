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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/carrito.js"></script>
</body>
</html>
