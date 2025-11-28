<?php
$id_reserva = $_GET['external_reference'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error en el pago</title>
    <link rel="stylesheet" href="../../assets/css/mercado_pago_exito.css">
</head>
<body>
    <div class="mensaje-error">
        <h1>Error en el pago</h1>
        <p>No se pudo procesar tu pago. Podes contactarnos si el problema persiste.</p>
        <a href="pantalla_principal.php" class="btn-volver">Volver al inicio</a>
    </div>
</body>
</html>

