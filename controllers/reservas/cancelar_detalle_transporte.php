<?php
session_start();

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,5,13])) {
    header('Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger');
    exit;
}

require_once('../../models/reserva.php');
require_once('../../models/transporte.php');

$id_detalle = (int)($_GET['id_detalle'] ?? 0);
$id_usuario = $_SESSION['id_usuarios'];

$reservaModel = new Reserva();
$transporteModel = new Transporte();

$detalle = $reservaModel->traer_detalle_transporte_por_id($id_detalle);

if (!$detalle) {
    header('Location: ../../index.php?page=transportes_reservas&message=Detalle inexistente&status=danger');
    exit;
}

$id_viaje = $detalle['id_viaje'];

$viaje = $transporteModel->traer_viaje_por_id($id_viaje);

if (!$viaje) {
    header('Location: ../../index.php?page=transportes_reservas&message=Viaje inexistente&status=danger');
    exit;
}

$id_transporte = $viaje['rela_transporte'];

if (!$transporteModel->verificar_propietario($id_transporte, $id_usuario)) {
    header('Location: ../../index.php?page=transportes_reservas&message=Acceso denegado&status=danger');
    exit;
}

$reservaModel->cancelar_detalle_transporte($id_detalle);

header("Location: ../../index.php?page=transportes_reservas&id_transporte=$id_transporte&status=success&message=" . urlencode("Servicio de transporte cancelado correctamente"));
exit;
