<?php
session_start();
require_once('../../vendor/autoload.php');
require_once('../../models/pago.php');
require_once('../../models/reserva.php');
require_once('../../models/usuarios.php');
require_once('../../models/Notificacion.php');
require_once('../../models/carrito.php');
require_once('../../models/auditoria.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('America/Argentina/Cordoba');

$id_pago = $_GET['id_pago'] ?? null;
$id_pago_mp = $_GET['payment_id'] ?? null;

if (!$id_pago || !$id_pago_mp) {
    die("Error: Datos de pago inválidos.");
}

$pagoModel = new Pago();
$reservaModel = new Reserva();
$usuarioModel = new Usuario();
$carritoModel = new Carrito();

$pagoData = $pagoModel->traerPorId($id_pago);
if (!$pagoData) die("Error: No se encontró pago asociado a este ID.");

if ($pagoData['pago_estado'] === 'aprobado') {
    $id_reserva = $pagoData['rela_reservas'];
    $reservaData = $reservaModel->traerPorId($id_reserva);
    $id_usuario = $reservaData['rela_usuarios'];
    $monto_total = $pagoData['pago_monto'];
} else {
    $id_reserva = $pagoData['rela_reservas'] ?? null;
    $monto_total = $pagoData['pago_monto'] ?? 0;
    if (!$id_reserva) die("Error: El pago no está asociado a ninguna reserva.");

    $reservaData = $reservaModel->traerPorId($id_reserva);
    $id_usuario = $reservaData['rela_usuarios'] ?? null;
    if (!$id_usuario) die("Error: No se encontró el usuario asociado a esta reserva.");

    $pagoModel->setId_pago($id_pago);
    $pagoModel->actualizarComprobante($id_pago_mp, 'aprobado');

    $reservaModel->setId_reservas($id_reserva);
    $reservaModel->setReservas_estado('confirmada');
    $reservaModel->setTotal($monto_total);
    $reservaModel->actualizar();
    $detalles = $reservaModel->traerDetallesPorId($id_reserva);

    foreach ($detalles as $detalle) {
        if ($detalle['tipo_servicio'] === 'hotel') {

            $id_detalle = $detalle['id_detalle_reserva'];

            $infoHotel = $reservaModel->traerDetalleHotel($id_detalle);
            $id_habitacion = $infoHotel['rela_habitacion'];
            $check_in      = $infoHotel['check_in'];
            $check_out     = $infoHotel['check_out'];
            $cantidad      = $detalle['cantidad']; 
            $reservaModel->confirmar_detalle_hotel($id_detalle);

            $fecha = new DateTime($check_in);
            $fecha_fin = new DateTime($check_out);

            while ($fecha < $fecha_fin) {
                $dia = $fecha->format("Y-m-d");

                $reservaModel->descontar_stock_hotel($id_habitacion, $dia, $cantidad);

                $fecha->modify("+1 day");
            }

        } elseif ($detalle['tipo_servicio'] === 'tour') {

            $id_detalle = $detalle['id_detalle_reserva'];
            $cantidad = $detalle['cantidad']; 

            $stock = $reservaModel->traerStockTour($id_detalle);
            $id_stock_tour = $stock['id_stock_tour'] ?? null;

            if ($id_stock_tour) {
                $reservaModel->confirmar_detalle_tour($id_detalle); 
                $reservaModel->descontar_stock_tour($id_stock_tour, $cantidad);
            } else {
                error_log("No se encontró stock para el detalle $id_detalle");
            }
        }

    }

    $carrito_activo = $carritoModel->traer_carrito_activo($id_usuario);
    if ($carrito_activo) {
        $carritoModel->setId_carrito($carrito_activo['id_carrito']);
        $carritoModel->limpiar_carrito_usuario($id_usuario);
    }

    unset($_SESSION['carrito'], $_SESSION['carrito_total']);

    $auditoria = new Auditoria(
        '',
        $id_usuario,
        'Confirmación de pago',
        "El usuario ID $id_usuario confirmó el pago de la reserva #$id_reserva por $$monto_total (Mercado Pago ID: $id_pago_mp)"
    );
    $auditoria->guardar();

    $metadata = ['reserva' => $id_reserva, 'pago' => $id_pago, 'tipo_pago' => 'mercadopago'];
    Notificacion::crear(
        $id_usuario,
        "Pago aprobado #$id_pago",
        "Tu pago por $$monto_total fue aprobado y la reserva #$id_reserva ha sido confirmada.",
        "pago",
        $metadata
    );
}

$email_usuario = $usuarioModel->traer_usuarios_por_id($id_usuario)[0]['usuarios_email'] ?? null;
if ($email_usuario) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Port = 587;
        $mail->Username = 'mircoaguilar02@gmail.com';
        $mail->Password = 'ztfd efur zara esyo';
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('mircoaguilar02@gmail.com', 'ViajAR - Comprobante de Pago');
        $mail->addAddress($email_usuario);
        $mail->isHTML(true);
        $mail->Subject = 'Comprobante de pago - ViajAR';

        $fecha = date('d/m/Y H:i');

        $mail->Body = "
            <h2 style='color:#2943b9;'>¡Gracias por tu compra en ViajAR!</h2>
            <p>Tu pago fue procesado correctamente.</p>
            <hr>
            <p><strong>Reserva:</strong> #$id_reserva</p>
            <p><strong>Fecha:</strong> $fecha</p>
            <p><strong>Monto:</strong> $$monto_total</p>
            <p><strong>Número de transacción:</strong> $id_pago_mp</p>
            <hr>
            <p>Conservá este correo como comprobante de tu transacción.</p>
            <p>Saludos,<br><strong>Equipo ViajAR</strong></p>
        ";

        $mail->AltBody = "Gracias por tu compra en ViajAR\nReserva: #$id_reserva\nMonto: $$monto_total\nCódigo de pago: $id_pago_mp\nFecha: $fecha\nConservá este correo como comprobante.";

        $mail->send();
    } catch (Exception $e) {}
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago exitoso - ViajAR</title>
    <link rel="stylesheet" href="../../assets/css/mercado_pago_exito.css">
</head>
<body>
<div class="mensaje-exito">
    <h1>¡Pago exitoso!</h1>
    <p>Tu pago se procesó correctamente y tu reserva fue confirmada.</p>
    <?php if ($email_usuario): ?>
        <p>Se envió un comprobante a tu correo electrónico: <strong><?= htmlspecialchars($email_usuario) ?></strong></p>
    <?php else: ?>
        <p>No se pudo enviar comprobante por correo.</p>
    <?php endif; ?>
    <a href="http://localhost/viajar/index.php" class="btn-volver">Volver al inicio</a>
</div>
</body>
</html>
