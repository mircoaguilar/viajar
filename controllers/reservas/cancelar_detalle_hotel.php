<?php
session_start();

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,3])) {
    header('Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger');
    exit;
}

require_once('../../models/reserva.php');
require_once('../../models/hotel.php');

$id_detalle = (int)($_GET['id_detalle'] ?? 0);
$id_usuario = $_SESSION['id_usuarios'];

$reservaModel = new Reserva();
$hotelModel = new Hotel();

$detalle = $reservaModel->traer_detalle_hotel_por_id($id_detalle);

if (!$detalle) {
    header('Location: ../../index.php?page=hoteles_reservas&message=Detalle inexistente&status=danger');
    exit;
}

$id_hotel = $detalle['rela_hotel'];

if (!$hotelModel->verificar_propietario($id_hotel, $id_usuario)) {
    header('Location: ../../index.php?page=hoteles_reservas&message=Acceso denegado&status=danger');
    exit;
}

$reservaModel->cancelar_detalle_hotel($id_detalle);

header("Location: ../../index.php?page=hoteles_reservas&id_hotel=$id_hotel&status=success&message=" . urlencode("Servicio de hotel cancelado correctamente"));
exit;
