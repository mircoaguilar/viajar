<?php

require_once('../../vendor/autoload.php');
require_once('../../models/usuarios.php'); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Datos esperados
$email_destino = $_POST['email'] ?? null;
$monto = $_POST['monto'] ?? null;
$servicio = $_POST['servicio'] ?? 'Servicio turístico';
$fecha = date('d/m/Y H:i'); // Se generará con la zona horaria correcta
$codigo_pago = $_POST['codigo_pago'] ?? '---';

// Validar datos mínimos
if (!$email_destino || !$monto) {
    echo json_encode(['status' => 'error', 'message' => 'Datos insuficientes para enviar comprobante']);
    exit;
}

// Configuración del correo
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
    $mail->addAddress($email_destino);

    $mail->isHTML(true);
    $mail->Subject = 'Comprobante de pago - ViajAR';

    // Cuerpo del correo
    $mail->Body = "
        <h2 style='color:#2943b9;'>¡Gracias por tu compra en ViajAR!</h2>
        <p>Tu pago fue procesado correctamente.</p>
        <hr>
        <p><strong>Servicio:</strong> $servicio</p>
        <p><strong>Fecha:</strong> $fecha</p>
        <p><strong>Monto:</strong> $$monto</p>
        <p><strong>Número de transacción:</strong> $codigo_pago</p>
        <hr>
        <p>Conservá este correo como comprobante de tu transacción.</p>
        <br>
        <p>Saludos,<br><strong>Equipo ViajAR</strong></p>
    ";

    $mail->AltBody = "Gracias por tu compra en ViajAR\n\n".
                     "Servicio: $servicio\n".
                     "Fecha: $fecha\n".
                     "Monto: $$monto\n".
                     "Número de transacción: $codigo_pago\n\n".
                     "Conservá este correo como comprobante.";

    $mail->send();

    echo json_encode(['status' => 'success', 'message' => 'Comprobante enviado correctamente']);

} catch (Exception $e) {
    error_log("Error al enviar comprobante: " . $mail->ErrorInfo);
    echo json_encode(['status' => 'error', 'message' => 'Error al enviar el comprobante']);
}
