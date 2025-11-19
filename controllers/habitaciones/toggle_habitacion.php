<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,3])) {
    header('Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger');
    exit;
}

require_once(__DIR__ . '/../../models/hotel_habitaciones.php');
require_once(__DIR__ . '/../../models/hotel.php');

$id_hab = (int)($_GET['id_habitacion'] ?? 0);
$id_usuario = $_SESSION['id_usuarios'];

if (!$id_hab) {
    header('Location: ../../index.php?page=proveedores_perfil&message=ID inválido&status=danger');
    exit;
}

$habitacionModel = new Hotel_Habitaciones();
$hotelModel = new Hotel();

$hab = $habitacionModel->traer_por_id($id_hab);
if (!$hab) {
    header('Location: ../../index.php?page=proveedores_perfil&message=Habitación inexistente&status=danger');
    exit;
}

$id_hotel = $hab['rela_hotel'];
if (!$hotelModel->verificar_propietario($id_hotel, $id_usuario)) {
    header('Location: ../../index.php?page=proveedores_perfil&message=Acceso denegado&status=danger');
    exit;
}

$nuevo_estado = ($hab['activo'] == 1) ? 0 : 1;

$habitacionModel->setId_hotel_habitacion($id_hab);
$habitacionModel->setActivo($nuevo_estado);

$ok = $habitacionModel->cambiar_estado($nuevo_estado);

if ($ok) {
    $msg = $nuevo_estado ? 'Habitación activada' : 'Habitación desactivada';
    header("Location: ../../index.php?page=hoteles_habitaciones&id_hotel=$id_hotel&status=success&message=" . urlencode($msg));
} else {
    header("Location: ../../index.php?page=hoteles_habitaciones&id_hotel=$id_hotel&status=danger&message=" . urlencode('No se pudo cambiar el estado'));
}
exit;
