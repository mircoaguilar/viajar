<?php
session_start();
require_once('../../vendor/autoload.php');
require_once('../../models/pago.php');
require_once('../../models/reserva.php');
require_once('../../models/usuarios.php');
require_once('../../models/Notificacion.php');
require_once('../../models/carrito.php');
require_once('../../models/auditoria.php');
require_once('../../models/ganancia.php');

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
$gananciaModel = new Ganancia();

$pagoData = $pagoModel->traerPorId($id_pago);
if (!$pagoData) die("Error: No se encontró pago asociado a este ID.");

// ==========================
// Actualizar pago pendiente
// ==========================
if ($pagoData['pago_estado'] !== 'aprobado') {
    $pagoModel->setId_pago($id_pago);
    $pagoModel->actualizarComprobante($id_pago_mp, 'aprobado');

    $reservaModel->setId_reservas($pagoData['rela_reservas']);
    $reservaModel->setReservas_estado('confirmada');
    $reservaModel->setTotal($pagoData['pago_monto']);
    $reservaModel->actualizar();

    // Recargar pagoData actualizado
    $pagoData = $pagoModel->traerPorId($id_pago);
}

// ==========================
// Procesar reserva aprobada
// ==========================
if ($pagoData['pago_estado'] === 'aprobado') {
    $id_reserva = $pagoData['rela_reservas'];
    $reservaData = $reservaModel->traerPorId($id_reserva);
    $id_usuario = $reservaData['rela_usuarios'];
    $monto_total = $pagoData['pago_monto'];

    // Registrar ganancia
    $ganancia_neta = $gananciaModel->calcularGanancia($monto_total);
    $gananciaModel->registrarGanancia($id_reserva, 'reserva', $ganancia_neta);

    // Procesar detalles de la reserva
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
            }

        } elseif ($detalle['tipo_servicio'] === 'transporte') {
            $id_detalle = $detalle['id_detalle_reserva'];
            $asientos_a_confirmar = $reservaModel->traerDetallesAsientosTransporte($id_detalle);
            if (!empty($asientos_a_confirmar)) {
                $reservaModel->confirmar_detalle_transporte($id_detalle);
                foreach ($asientos_a_confirmar as $asiento) {
                    $id_detalle_transporte = $asiento['id_detalle_transporte'];
                    $reservaModel->confirmar_asiento_transporte($id_detalle_transporte);
                }
            }
        }
    }

    // Limpiar carrito
    $carrito_activo = $carritoModel->traer_carrito_activo($id_usuario);
    if ($carrito_activo) {
        $carritoModel->setId_carrito($carrito_activo['id_carrito']);
        $carritoModel->limpiar_carrito_usuario($id_usuario);
    }
    unset($_SESSION['carrito'], $_SESSION['carrito_total']);

    // Auditoría
    $auditoria = new Auditoria('', $id_usuario, 'Confirmación de pago', "Usuario $id_usuario confirmó pago de reserva #$id_reserva por $$monto_total (MP ID: $id_pago_mp)");
    $auditoria->guardar();

    // Notificación
    $metadata = ['reserva'=>$id_reserva,'pago'=>$id_pago,'tipo_pago'=>'mercadopago'];
    Notificacion::crear($id_usuario,"Pago aprobado #$id_pago","Tu pago por $$monto_total fue aprobado y la reserva #$id_reserva ha sido confirmada.","pago",$metadata);

    // Enviar correo
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
                <h2>¡Gracias por tu compra en ViajAR!</h2>
                <p>Tu pago fue procesado correctamente.</p>
                <hr>
                <p><strong>Reserva:</strong> #$id_reserva</p>
                <p><strong>Fecha:</strong> $fecha</p>
                <p><strong>Monto:</strong> $$monto_total</p>
                <p><strong>Transacción:</strong> $id_pago_mp</p>
            ";
            $mail->AltBody = "Reserva: #$id_reserva, Monto: $$monto_total, Pago ID: $id_pago_mp, Fecha: $fecha";
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: ".$e->getMessage());
        }
    }
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
    <?php if (!empty($email_usuario)): ?>
        <p>Se envió un comprobante a tu correo electrónico: <strong><?= htmlspecialchars($email_usuario) ?></strong></p>
    <?php else: ?>
        <p>No se pudo enviar comprobante por correo.</p>
    <?php endif; ?>
    <a href="http://localhost/viajar/index.php" class="btn-volver">Volver al inicio</a>
</div>
</body>
</html>
