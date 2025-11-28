<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/../../models/proveedor.php');

$input = json_decode(file_get_contents('php://input'), true);

$action = $_POST['action'] ?? $input['action'] ?? null;

$controlador = new ProveedoresControlador();

if ($action) {
    switch ($action) {
        case 'guardar':
            $controlador->guardar();
            break;
        case 'actualizar':
            $controlador->actualizar();
            break;
        case 'eliminar':
            $controlador->eliminar();
            break;
        case 'aprobar':
            $res = $controlador->aprobar($input['id_proveedor'] ?? 0);
            echo json_encode([
                'status' => $res ? 'success' : 'error',
                'message' => $res ? 'Proveedor aprobado correctamente' : 'Error al aprobar proveedor'
            ]);
            exit;
        case 'rechazar':
            $res = $controlador->rechazar($input['id_proveedor'] ?? 0);
            echo json_encode([
                'status' => $res ? 'success' : 'error',
                'message' => $res ? 'Proveedor rechazado correctamente' : 'Error al rechazar proveedor'
            ]);
            exit;
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
        $prov->setProveedor_direccion($_POST['proveedor_direccion'] ?? '');
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
        $prov->setProveedor_direccion($_POST['proveedor_direccion'] ?? '');
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

    public function listarPendientes() {
        $prov = new Proveedor();
        return $prov->listarPendientes(); 
    }

    public function aprobar($id_proveedor) {
        $prov = new Proveedor();
        $prov->setId_proveedores((int)$id_proveedor);
        return $prov->cambiarEstado('aprobado'); 
    }

    public function rechazar($id_proveedor) {
        $prov = new Proveedor();
        $prov->setId_proveedores((int)$id_proveedor);
        return $prov->cambiarEstado('rechazado'); 
    }

    public function mis_hoteles($id_usuario) {
        require_once(__DIR__ . '/../../models/hotel.php');
        $hotelModel = new Hotel();
        return $hotelModel->traer_hoteles_proveedor_completo($id_usuario);
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

    public function verificar_propietario_transporte($id_transporte, $id_usuario) {
        require_once(__DIR__ . '/../../models/transporte.php');
        require_once(__DIR__ . '/../../models/proveedor.php');

        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);

        if (!$proveedor) return false;

        $id_proveedor = (int)$proveedor['id_proveedores'];

        $transporteModel = new Transporte();
        return $transporteModel->verificar_propietario($id_transporte, $id_proveedor);
    }

    public function verificar_propietario_ruta($id_ruta, $id_usuario) {
        $model = new Transporte();
        return $model->es_propietario_de_ruta($id_ruta, $id_usuario);
    }
}
?>
