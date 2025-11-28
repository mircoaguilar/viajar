<?php
session_start();
require_once __DIR__ . '/../models/reserva.php';
require_once __DIR__ . '/../models/hotel.php';
require_once __DIR__ . '/../models/tour.php';
require_once __DIR__ . '/../models/usuarios.php';
require_once __DIR__ . '/../models/auditoria.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$userId = $_SESSION['id_usuarios'] ?? null;
if (!$userId) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Acceso no autorizado']);
    exit;
}

$reservaModel = new Reserva();
$hotelModel = new Hotel();
$tourModel = new Tour();
$usuarioModel = new Usuario();
$auditoriaModel = new Auditoria();

$id_detalle = $_POST['id_detalle_reserva'] ?? null;
$motivo = $_POST['motivo'] ?? null;
$comentario = $_POST['comentario'] ?? '';

if (!$id_detalle || !$motivo) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Datos incompletos']);
    exit;
}

$detalle = $reservaModel->traerDetallePorId($id_detalle);
if (!$detalle) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Detalle no encontrado']);
    exit;
}

$reservaId = $detalle['rela_reservas'];
$reservaModel->registrarCancelacion($id_detalle, $motivo, $comentario);
$reservaModel->cancelarDetalle($id_detalle);

switch($detalle['tipo_servicio']){
    case 'hotel':
        $hotelModel->liberarHabitacion($detalle['id_detalle_reserva']); 
        break;
   case 'tour':
        if (!empty($id_detalle)) {
            $tourModel->liberarCupo($id_detalle);
        }
        break;
    case 'transporte':
        if(!empty($id_detalle)){
            $reservaModel->liberarAsientoTransporte($id_detalle);
        }
        break;
}

$detallesActivos = $reservaModel->traerDetallesActivos($reservaId);
if (count($detallesActivos) === 0) {
    $reservaModel->actualizarEstado($reservaId, 'cancelada');
} else {
    $reservaModel->actualizarEstado($reservaId, 'confirmada'); 
}

try {
    $phpmailer = new PHPMailer(true);
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = 'mircoaguilar02@gmail.com'; 
    $phpmailer->Password = 'ztfd efur zara esyo';      
    $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $phpmailer->Port = 587;
    $phpmailer->CharSet = 'UTF-8';

    $phpmailer->setFrom('mircoaguilar02@gmail.com', 'Área de Sistemas ViajAR');
    $phpmailer->addAddress($usuarioModel->traerEmailPorId($userId)); 

    $phpmailer->isHTML(true);
    $phpmailer->Subject = 'Cancelación de reserva';
    $phpmailer->Body = "
        Hola,<br><br>
        Se ha cancelado el siguiente servicio de tu reserva:<br>
        <b>ID detalle:</b> $id_detalle<br>
        <b>Motivo:</b> $motivo<br>
        <b>Comentario:</b> $comentario<br><br>
        Si tienes dudas, contacta con nuestro soporte.<br><br>
        Saludos,<br>
        El equipo de ViajAR
    ";
    $phpmailer->AltBody = "Se ha cancelado el detalle $id_detalle. Motivo: $motivo. Comentario: $comentario";

    $phpmailer->send();

    $auditoria = new Auditoria('', $userId, 'Cancelación de reserva', "Detalle $id_detalle cancelado por usuario $userId");
    $auditoria->guardar();

    echo json_encode(['status' => 'ok', 'mensaje' => 'Tu cancelación fue exitosa. Nos contactaremos para la devolución.']);
} catch (Exception $e) {
    error_log("Error al enviar email de cancelación: " . $phpmailer->ErrorInfo);
    echo json_encode(['status' => 'ok', 'mensaje' => 'Cancelación realizada, pero no se pudo enviar el email.']);
}
