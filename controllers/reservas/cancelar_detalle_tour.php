<?php
session_start();

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,14])) {
    header('Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger');
    exit;
}

require_once('../../models/reserva.php');
require_once('../../models/tour.php');
require_once('../../models/proveedor.php'); 

$id_detalle = (int)($_GET['id_detalle'] ?? 0);
$id_usuario = $_SESSION['id_usuarios'];

$reservaModel = new Reserva();
$tourModel = new Tour();
$proveedorModel = new Proveedor(); 

$detalle = $reservaModel->traer_detalle_tour_por_id($id_detalle);
if (!$detalle) {
    header('Location: ../../index.php?page=tours_reservas&message=Detalle inexistente&status=danger');
    exit;
}

$id_tour = $detalle['rela_tour'];
$tour = $tourModel->traer_tour($id_tour);
if (!$tour) {
    header('Location: ../../index.php?page=tours_reservas&message=Tour inexistente&status=danger');
    exit;
}

$proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);
if (!$proveedor) {
    header('Location: ../../index.php?page=tours_reservas&message=No se encontró proveedor asociado&status=danger');
    exit;
}
$id_proveedor = (int)$proveedor['id_proveedores'];

if (!$tourModel->verificar_propietario($id_tour, $id_proveedor)) {
    header('Location: ../../index.php?page=tours_reservas&message=Acceso denegado&status=danger');
    exit;
}

$reservaModel->cancelar_detalle_tour($id_detalle);

header("Location: ../../index.php?page=tours_reservas&id_tour=$id_tour&status=success&message=" . urlencode("Servicio de tour cancelado correctamente"));
exit;
