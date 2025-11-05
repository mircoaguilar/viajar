<?php
require_once('models/conexion.php');
$id_reserva = $_GET['external_reference'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago pendiente</title>
</head>
<body>
    <h1> Pago pendiente</h1>
    <p>Tu pago aún está en proceso. Te notificaremos por correo cuando se confirme.</p>
    <a href="pantalla_principal.php">Volver al inicio</a>
</body>
</html>
