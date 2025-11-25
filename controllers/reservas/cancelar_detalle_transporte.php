<?php
session_start();

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,5,13])) {
    header('Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger');
    exit;
}

require_once('../../models/reserva.php');
require_once('../../models/transporte.php');
require_once('../../models/proveedor.php');

$id_detalle = (int)($_GET['id_detalle'] ?? 0);
$id_usuario = $_SESSION['id_usuarios'];

$reservaModel = new Reserva();
$transporteModel = new Transporte();
$proveedorModel = new Proveedor(); 

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

$proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);
if (!$proveedor) {
    header('Location: ../../index.php?page=transportes_reservas&message=No se encontró proveedor asociado&status=danger');
    exit;
}
$id_proveedor = (int)$proveedor['id_proveedores'];

$id_transporte = $viaje['rela_transporte'];
if (!$transporteModel->verificar_propietario($id_transporte, $id_proveedor)) {
    header('Location: ../../index.php?page=transportes_reservas&message=Acceso denegado&status=danger');
    exit;
}

$reservaModel->cancelar_detalle_transporte($id_detalle);

header("Location: ../../index.php?page=transportes_reservas&id_transporte=$id_transporte&status=success&message=" . urlencode("Servicio de transporte cancelado correctamente"));
exit;
