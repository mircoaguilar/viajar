<?php
session_start();
require_once('../../vendor/autoload.php');
require_once('../../models/pago.php');
require_once('../../models/reserva.php');
require_once('../../models/usuarios.php');
require_once('../../models/Notificacion.php');
require_once('../../models/carrito.php'); 

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

if (!$pagoData) {
    die("Error: No se encontró pago asociado a este ID.");
}

$id_reserva = $pagoData['rela_reservas'] ?? null;
$monto_total = $pagoData['pago_monto'] ?? 0;

if (!$id_reserva) {
    die("Error: El pago no está asociado a ninguna reserva.");
}

$reservaData = $reservaModel->traerPorId($id_reserva);
$id_usuario = $reservaData['rela_usuarios'] ?? null;

if (!$id_usuario) {
    die("Error: No se encontró el usuario asociado a esta reserva.");
}

$pagoModel->setId_pago($id_pago);
$pagoModel->actualizarComprobante($id_pago_mp, 'aprobado');

$reservaModel->setId_reservas($id_reserva);
$reservaModel->setReservas_estado('confirmada');
$reservaModel->setTotal($monto_total);
$reservaModel->actualizar(); 

$carrito_activo = $carritoModel->traer_carrito_activo($id_usuario);
if ($carrito_activo) {
    $carritoModel->setId_carrito($carrito_activo['id_carrito']);
    $carritoModel->limpiar_carrito_usuario($id_usuario); // Limpiar el carrito
}

unset($_SESSION['carrito'], $_SESSION['carrito_total']);

$email_usuario = $usuarioModel->traer_usuarios_por_id($id_usuario);
$email_usuario = $email_usuario[0]['usuarios_email'] ?? null;

if ($id_usuario && $email_usuario) {
    $metadata = ['reserva' => $id_reserva, 'pago' => $id_pago, 'tipo_pago' => 'mercadopago'];
    Notificacion::crear(
        $id_usuario,
        "Pago aprobado #$id_pago",
        "Tu pago por $$monto_total fue aprobado y la reserva #$id_reserva ha sido confirmada.",
        "pago",
        $metadata
    );

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
    } catch (Exception $e) {
    }
} else {
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago exitoso - ViajAR</title>
    <link rel="stylesheet" href="http://localhost/viajar/assets/css/mercado_pago_exito.css">
</head>
<body>
    <div class="mensaje-exito">
        <h1>¡Pago exitoso!</h1>
        <p>Tu pago se procesó correctamente y tu reserva fue confirmada.</p>
        <?php if ($email_usuario): ?>
            <p>Se envió un comprobante a tu correo electrónico: <strong><?php echo htmlspecialchars($email_usuario); ?></strong></p>
        <?php else: ?>
            <p>No se pudo enviar comprobante por correo.</p>
        <?php endif; ?>
        <a href="http://localhost/viajar/index.php" class="btn-volver">Volver al inicio</a>
    </div>
</body>
</html>
