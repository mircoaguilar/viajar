<?php

session_start();
require_once(__DIR__ . '/../../models/transporte_rutas.php');
require_once(__DIR__ . '/../../models/transporte.php');
require_once(__DIR__ . '/../../models/proveedor.php');

class RutasControlador {

    public function listar() {
        $this->verificarSesionProveedor();

        $id_proveedor = $this->obtenerIdProveedorSesion();

        $rutasModel = new Transporte_Rutas();
        $rutas = $rutasModel->traer_rutas_por_proveedor($id_proveedor);

        require_once(__DIR__ . '/../../views/paginas/transportes_rutas_listar.php');
    }

    public function guardar() {
        $this->verificarSesionProveedor();
        $this->validarMetodoPOST();

        $id_proveedor = $this->obtenerIdProveedorSesion();

        $nombre = trim($_POST['nombre'] ?? '');
        $trayecto = trim($_POST['trayecto'] ?? '');
        $origen = (int)($_POST['rela_ciudad_origen'] ?? 0);
        $destino = (int)($_POST['rela_ciudad_destino'] ?? 0);
        $duracion = trim($_POST['duracion'] ?? '00:00');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = floatval($_POST['precio_por_persona'] ?? 0);
        $rela_transporte = (int)($_POST['rela_transporte'] ?? 0);

        if (!$nombre || !$trayecto || !$origen || !$destino || !$rela_transporte) {
            $this->responder(['status'=>'error', 'message'=>'Faltan datos obligatorios']);
        }
        if ($origen === $destino) {
            $this->responder(['status'=>'error', 'message'=>'La ciudad de origen y destino no pueden ser iguales']);
        }
        if ($precio < 0) {
            $this->responder(['status'=>'error', 'message'=>'El precio debe ser positivo']);
        }

        $transporteModel = new Transporte();
        if (!$transporteModel->verificar_propietario($rela_transporte, $id_proveedor)) {
            $this->responder(['status'=>'error', 'message'=>'El vehículo no pertenece a tu cuenta']);
        }

        $ruta = new Transporte_Rutas();
        $ruta->setNombre($nombre)
             ->setTrayecto($trayecto)
             ->setRela_ciudad_origen($origen)
             ->setRela_ciudad_destino($destino)
             ->setDuracion($duracion)
             ->setDescripcion($descripcion)
             ->setPrecio_por_persona($precio)
             ->setRela_transporte($rela_transporte);
             
        
        $id = $ruta->guardar();
        if ($id) $this->responder(['status'=>'success', 'message'=>'Ruta guardada', 'id'=>$id]);
        else $this->responder(['status'=>'error', 'message'=>'Error al guardar la ruta']);
    }

    public function editar() {
        $this->verificarSesionProveedor();
        $this->validarMetodoPOST();

        $id_ruta = (int)($_POST['id_ruta'] ?? 0);
        if (!$id_ruta) $this->responder(['status'=>'error', 'message'=>'ID de ruta inválido']);

        $id_proveedor = $this->obtenerIdProveedorSesion();

        $rutaModel = new Transporte_Rutas();
        $ruta = $rutaModel->traer_por_id($id_ruta);
        if (!$ruta) $this->responder(['status'=>'error','message'=>'Ruta no encontrada']);

        $transporteModel = new Transporte();
        if (!$transporteModel->verificar_propietario($ruta['rela_transporte'], $id_proveedor)) {
            $this->responder(['status'=>'error','message'=>'No autorizado para editar esta ruta']);
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $trayecto = trim($_POST['trayecto'] ?? '');
        $origen = (int)($_POST['rela_ciudad_origen'] ?? 0);
        $destino = (int)($_POST['rela_ciudad_destino'] ?? 0);
        $precio = floatval($_POST['precio_por_persona'] ?? 0);

        if (!$nombre || !$trayecto || !$origen || !$destino) {
            $this->responder(['status'=>'error', 'message'=>'Faltan datos obligatorios']);
        }
        if ($origen === $destino) {
            $this->responder(['status'=>'error', 'message'=>'La ciudad de origen y destino no pueden ser iguales']);
        }
        if ($precio < 0) {
            $this->responder(['status'=>'error', 'message'=>'El precio debe ser positivo']);
        }

        $rutaModel->setId_ruta($id_ruta)
                  ->setNombre($nombre)
                  ->setTrayecto($trayecto)
                  ->setRela_ciudad_origen($origen)
                  ->setRela_ciudad_destino($destino)
                  ->setDuracion(trim($_POST['duracion'] ?? '00:00'))
                  ->setDescripcion(trim($_POST['descripcion'] ?? ''))
                  ->setPrecio_por_persona($precio)
                  ->setRela_transporte((int)($_POST['rela_transporte'] ?? 0));

        $ok = $rutaModel->actualizar();
        if ($ok) $this->responder(['status'=>'success','message'=>'Ruta actualizada']);
        else $this->responder(['status'=>'error','message'=>'Error al actualizar ruta']);
    }

    public function eliminar() {
        $this->verificarSesionProveedor();

        $id_ruta = (int)($_POST['id_ruta'] ?? $_GET['id_ruta'] ?? 0);
        if (!$id_ruta) $this->responder(['status'=>'error','message'=>'ID inválido']);

        $id_proveedor = $this->obtenerIdProveedorSesion();

        $rutaModel = new Transporte_Rutas();
        $ruta = $rutaModel->traer_por_id($id_ruta);
        if (!$ruta) $this->responder(['status'=>'error','message'=>'Ruta no encontrada']);

        $transporteModel = new Transporte();
        if (!$transporteModel->verificar_propietario($ruta['rela_transporte'], $id_proveedor)) {
            $this->responder(['status'=>'error','message'=>'No autorizado para eliminar esta ruta']);
        }

        $rutaModel->setId_ruta($id_ruta);
        $ok = $rutaModel->eliminar_logico();

        if ($ok) $this->responder(['status'=>'success','message'=>'Ruta eliminada']);
        else $this->responder(['status'=>'error','message'=>'Error al eliminar ruta']);
    }

    private function responder(array $payload) {
        $isAjax = (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) || (isset($_POST['ajax']) && $_POST['ajax']=='1');

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($payload, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $status = ($payload['status'] ?? '') === 'success' ? 'success' : 'danger';
        $msg = rawurlencode($payload['message'] ?? '');
        header("Location: ../../index.php?page=transportes_rutas&message={$msg}&status={$status}");
        exit;
    }

    private function verificarSesionProveedor() {
        if (!isset($_SESSION['id_usuarios']) || !isset($_SESSION['id_perfiles']) || ($_SESSION['id_perfiles'] != 5)) {
            header('Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger');
            exit;
        }
    }

    private function obtenerIdProveedorSesion() {
        $provModel = new Proveedor();
        $proveedor = $provModel->obtenerPorUsuario($_SESSION['id_usuarios']);
        if (!$proveedor) {
            header('Location: ../../index.php?page=proveedores_perfil&message=No se encontró proveedor&status=danger');
            exit;
        }
        return (int)$proveedor['id_proveedores'];
    }

    private function validarMetodoPOST() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responder(['status'=>'error','message'=>'Método inválido']);
        }
    }
}

$action = $_REQUEST['action'] ?? null;
$ctrl = new RutasControlador();

switch ($action) {
    case 'listar':
        $ctrl->listar();
        break;
    case 'guardar':
        $ctrl->guardar();
        break;
    case 'editar':
        $ctrl->editar();
        break;
    case 'eliminar':
        $ctrl->eliminar();
        break;
    default:
        header('HTTP/1.1 400 Bad Request');
        echo "Acción no válida.";
        break;
}
?>
