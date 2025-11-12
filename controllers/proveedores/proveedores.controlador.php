<?php
session_start(); 
require_once(__DIR__ . '/../../models/proveedor.php');

if (isset($_POST["action"])) {
    $controlador = new ProveedoresControlador();
    switch ($_POST["action"]) {
        case 'guardar':
            $controlador->guardar();
            break;
        case 'actualizar':
            $controlador->actualizar();
            break;
        case 'eliminar':
            $controlador->eliminar();
            break;
        default:
            header("Location: ../../index.php?page=proveedores&message=Acción no válida&status=danger");
            exit;
    }
}

class ProveedoresControlador {
    public function guardar() {
        if (empty($_POST['razon_social']) || empty($_POST['cuit']) || empty($_POST['rela_tipo_proveedor'])) {
            header("Location: ../../index.php?page=proveedores&message=Datos obligatorios incompletos&status=danger");
            exit;
        }

        $prov = new Proveedor();
        $prov->setRazon_social($_POST['razon_social']);
        $prov->setCuit($_POST['cuit']);
        $prov->setProveedor_domicilio($_POST['proveedor_domicilio'] ?? '');
        $prov->setProveedor_email($_POST['proveedor_email'] ?? '');
        $prov->setRela_tipo_proveedor($_POST['rela_tipo_proveedor']);

        $id_nuevo = $prov->guardar();
        if ($id_nuevo) {
            header("Location: ../../index.php?page=proveedores&message=Proveedor guardado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=proveedores&message=Error al guardar&status=danger");
        }
        exit;
    }

    public function actualizar() {
        if (empty($_POST['id_proveedores']) || empty($_POST['razon_social']) || empty($_POST['cuit']) || empty($_POST['rela_tipo_proveedor'])) {
            $id = htmlspecialchars($_POST['id_proveedores'] ?? '');
            header("Location: ../../index.php?page=proveedores&id=$id&message=Datos obligatorios incompletos&status=danger");
            exit;
        }

        $prov = new Proveedor();
        $prov->setId_proveedores($_POST['id_proveedores']);
        $prov->setRazon_social($_POST['razon_social']);
        $prov->setCuit($_POST['cuit']);
        $prov->setProveedor_domicilio($_POST['proveedor_domicilio'] ?? '');
        $prov->setProveedor_email($_POST['proveedor_email'] ?? '');
        $prov->setRela_tipo_proveedor($_POST['rela_tipo_proveedor']);

        if ($prov->actualizar()) {
            header("Location: ../../index.php?page=proveedores&message=Proveedor actualizado&status=success");
        } else {
            header("Location: ../../index.php?page=proveedores&id=".htmlspecialchars($_POST['id_proveedores'])."&message=Error al actualizar&status=danger");
        }
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_proveedor_eliminar'])) {
            header("Location: ../../index.php?page=proveedores&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $prov = new Proveedor();
        $prov->setId_proveedores($_POST['id_proveedor_eliminar']);

        if ($prov->eliminar_logico()) {
            header("Location: ../../index.php?page=proveedores&message=Proveedor eliminado&status=success");
        } else {
            header("Location: ../../index.php?page=proveedores&message=Error al eliminar&status=danger");
        }
        exit;
    }

    public function mis_hoteles($id_usuario) {
        require_once(__DIR__ . '/../../models/hotel.php');
        $hotelModel = new Hotel();

        return $hotelModel->traer_hoteles_por_usuario($id_usuario);
    }

    public function mis_transportes($id_usuario) {
        require_once(__DIR__ . '/../../models/transporte.php');
        $transporteModel = new Transporte();
        return $transporteModel->traer_transportes_por_usuario($id_usuario);
    }

    public function mis_tours($id_usuario) {
        require_once(__DIR__ . '/../../models/Tour.php');
        $tourModel = new Tour();
        return $tourModel->traer_tours_por_usuario($id_usuario);
    }
}
?>
