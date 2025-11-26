<?php
session_start();
require_once(__DIR__ . '/../../models/Tour.php');
require_once(__DIR__ . '/../../models/proveedor.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php?page=tours_mis_tours&status=danger&message=Acceso inválido");
    exit;
}

$action = $_POST['action'] ?? null;
$id_tour = $_POST['id_tour'] ?? null;

if (!$action || !$id_tour) {
    header("Location: ../../index.php?page=tours_mis_tours&status=danger&message=Datos incompletos");
    exit;
}

$tourModel = new Tour();
$proveedorModel = new Proveedor();


$id_usuario = $_SESSION['id_usuarios'];
$proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);

if (!$proveedor) {
    header("Location: ../../index.php?page=tours_mis_tours&status=danger&message=No se encontró proveedor.");
    exit;
}

$id_proveedor = $proveedor['id_proveedores'];
$es_dueno = $tourModel->verificar_propietario($id_tour, $id_proveedor);

if (!$es_dueno) {
    header("Location: ../../index.php?page=tours_mis_tours&status=danger&message=No podés modificar este tour.");
    exit;
}



switch ($action) {

    case 'reenviar':
        $resultado = $tourModel->reenviarTour($id_tour);

        if ($resultado) {
            header("Location: ../../index.php?page=tours_mis_tours&status=success&message=Tour enviado nuevamente para revisión.");
        } else {
            header("Location: ../../index.php?page=tours_mis_tours&status=danger&message=No se pudo reenviar el tour.");
        }
        exit;
        break;

    default:
        header("Location: ../../index.php?page=tours_mis_tours&status=danger&message=Acción no válida.");
        exit;
}

?>
