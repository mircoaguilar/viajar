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
    <div class="mensaje-pendiente">
        <link rel="stylesheet" href="../../assets/css/mercado_pago_exito.css">
        <h1>Pago pendiente</h1>
        <p>Tu pago aún está en proceso. Te notificaremos por correo cuando se confirme.</p>
        <?php if ($id_reserva): ?>
            <p><strong>Referencia de reserva:</strong> #<?= htmlspecialchars($id_reserva, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <a href="pantalla_principal.php" class="btn-volver">Volver al inicio</a>
    </div>
</body>
</html>
