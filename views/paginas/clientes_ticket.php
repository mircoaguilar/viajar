<?php
require_once __DIR__ . '/../../models/reserva.php';
require_once __DIR__ . '/../../models/hotel.php';
require_once __DIR__ . '/../../models/tour.php';
require_once __DIR__ . '/../../models/pago.php';

session_start();
$userId = $_SESSION['id_usuarios'] ?? null;
if (!$userId) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$reservaModel = new Reserva();
$hotelModel = new Hotel();
$tourModel = new Tour();
$pagoModel = new Pago();

$idReserva = (int)($_GET['id'] ?? 0);
$reserva = $reservaModel->traerPorId($idReserva);

if (!$reserva || !isset($reserva['rela_usuarios']) || $reserva['rela_usuarios'] != $userId) {
    echo "Reserva no encontrada o acceso denegado.";
    exit;
}

$pago = $pagoModel->traerPorReserva($idReserva);
$transaccion = $pago['pago_comprobante'] ?? 'N/A';

$detalles = $reservaModel->traerDetallesCompletos($idReserva);

$estado = 'activo';
$mensajeEncabezado = '¡Gracias por tu compra en ViajAR!<br>Tu pago fue procesado correctamente.';
$claseEstado = 'estado-activo';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Reserva #<?= $reserva['id_reservas'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/ticket.css">
</head>
<body>

<div class="ticket-container <?= $claseEstado ?>">
    <h2><?= $mensajeEncabezado ?></h2>

    <div class="detalle general">
        <p class="resaltar">Reserva: #<?= $reserva['id_reservas'] ?></p>
        <p class="resaltar">Fecha: <?= date('d/m/Y H:i', strtotime($reserva['fecha_creacion'])) ?></p>
        <p class="resaltar">Monto: $<?= number_format($reserva['total'], 0, ',', '.') ?></p>
        <p class="resaltar">Transacción: <?= $transaccion ?></p>
    </div>

    <h3>Servicios incluidos:</h3>

    <?php foreach ($detalles as $det): ?>
        <div class="detalle servicio">
            <?php
            switch ($det['tipo_servicio']) {
                case 'hotel':
                    echo "<p>Hotel: " . ($det['hotel']['hotel_nombre'] ?? 'Desconocido') . "</p>";
                    if (!empty($det['hotel']['tipo_habitacion'])) {
                        echo "<p>Habitación: " . $det['hotel']['tipo_habitacion'] . "</p>";
                    }
                    if (!empty($det['hotel']['check_in'])) {
                        echo "<p>Check-in: " . date('d/m/Y', strtotime($det['hotel']['check_in'])) . "</p>";
                    }
                    if (!empty($det['hotel']['check_out'])) {
                        echo "<p>Check-out: " . date('d/m/Y', strtotime($det['hotel']['check_out'])) . "</p>";
                    }
                    break;

                case 'tour':
                    echo "<p>Tour: " . ($det['tour']['tour_nombre'] ?? 'Desconocido') . "</p>";
                    if (!empty($det['tour']['fecha'])) {
                        echo "<p>Fecha del tour: " . date('d/m/Y', strtotime($det['tour']['fecha'])) . "</p>";
                    }
                    break;

                case 'transporte':
                    echo "<p>Transporte: " . ($det['transporte'][0]['nombre_servicio'] ?? 'Desconocido') . "</p>";
                    if (!empty($det['transporte'])) {
                        echo "<p>Fecha del viaje: " . date('d/m/Y', strtotime($det['transporte'][0]['viaje_fecha'])) . "</p>";
                        echo "<p>Asientos:</p><ul>";
                        foreach ($det['transporte'] as $t) {
                            $nombre = $t['pasajero_nombre'] ?? 'No asignado';
                            $apellido = $t['pasajero_apellido'] ?? '';
                            echo "<li>Piso {$t['piso']} - Asiento {$t['numero_asiento']} ({$nombre} {$apellido})</li>";
                        }
                        echo "</ul>";
                    }
                    break;
            }
            ?>
            <p>Subtotal: $<?= number_format($det['subtotal'], 0, ',', '.') ?></p>
        </div>
    <?php endforeach; ?>

    <button class="btn-print" onclick="window.print()">Imprimir / Guardar PDF</button>
</div>

</body>
</html>
