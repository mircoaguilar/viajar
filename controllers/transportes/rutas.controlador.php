<?php
if (session_status() === PHP_SESSION_NONE) session_start();
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
            header("Location: ../../index.php?page=transportes_rutas&id_transporte={$rela_transporte}&message=Faltan datos obligatorios&status=danger");
            exit;
        }

        if ($origen === $destino) {
            header("Location: ../../index.php?page=transportes_rutas&id_transporte={$rela_transporte}&message=Origen y destino no pueden ser iguales&status=danger");
            exit;
        }

        if ($precio < 0) {
            header("Location: ../../index.php?page=transportes_rutas&id_transporte={$rela_transporte}&message=El precio debe ser positivo&status=danger");
            exit;
        }

        $transporteModel = new Transporte();
        if (!$transporteModel->verificar_propietario($rela_transporte, $id_proveedor)) {
            header("Location: ../../index.php?page=transportes_rutas&id_transporte={$rela_transporte}&message=El vehículo no pertenece a tu cuenta&status=danger");
            exit;
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

        $status = $id ? 'success' : 'danger';
        $mensaje = $id ? 'Ruta guardada correctamente' : 'Error al guardar la ruta';

        header("Location: ../../index.php?page=transportes_rutas&id_transporte={$rela_transporte}&message={$mensaje}&status={$status}");
        exit;
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

    public function obtenerDatosRuta($id_ruta){
        $this->verificarSesionProveedor();

        $id_proveedor = $this->obtenerIdProveedorSesion();

        $rutaModel = new Transporte_Rutas();
        $transporteModel = new Transporte();

        $ruta = $rutaModel->traer_por_id($id_ruta);
        if (!$ruta) {
            header("Location: ../../index.php?page=mis_transportes&message=Ruta no encontrada&status=danger");
            exit;
        }

        if (!$transporteModel->verificar_propietario($ruta['rela_transporte'], $id_proveedor)) {
            header("Location: ../../index.php?page=mis_transportes&message=No autorizado&status=danger");
            exit;
        }

        $transportes = $transporteModel->traer_por_proveedor($id_proveedor);

        require_once(__DIR__ . '/../../models/ciudad.php');
        $ciudadModel = new Ciudad();
        $ciudades = $ciudadModel->traer_ciudades();

        return [
            'ruta'        => $ruta,
            'transportes' => $transportes,
            'ciudades'    => $ciudades
        ];
    }

    public function actualizar(){
        $this->verificarSesionProveedor();
        $this->validarMetodoPOST();

        $id_ruta = (int)($_POST['id_ruta'] ?? 0);
        if (!$id_ruta) {
            header("Location: /viajar/index.php?page=mis_transportes&status=danger&message=ID inválido");
            exit;
        }

        $id_proveedor = $this->obtenerIdProveedorSesion();

        $rutaModel = new Transporte_Rutas();
        $rutaActual = $rutaModel->traer_por_id($id_ruta);

        if (!$rutaActual) {
            header("Location: /viajar/index.php?page=mis_transportes&status=danger&message=Ruta no encontrada");
            exit;
        }

        $transporteModel = new Transporte();
        if (!$transporteModel->verificar_propietario($rutaActual['rela_transporte'], $id_proveedor)) {
            header("Location: /viajar/index.php?page=mis_transportes&message=No autorizado&status=danger");
            exit;
        }

        $nombre   = trim($_POST['nombre'] ?? '');
        $trayecto = trim($_POST['trayecto'] ?? '');
        $origen   = (int)($_POST['rela_ciudad_origen'] ?? 0);
        $destino  = (int)($_POST['rela_ciudad_destino'] ?? 0);
        $duracion = trim($_POST['duracion'] ?? '00:00');
        $desc     = trim($_POST['descripcion'] ?? '');
        $precio   = floatval($_POST['precio_por_persona'] ?? 0);
        $rela_transporte = (int)($_POST['rela_transporte'] ?? 0);

        if (!$nombre || !$trayecto || !$origen || !$destino) {
            header("Location: /viajar/index.php?page=transportes_ruta_editar&id_ruta={$id_ruta}&message=Faltan datos&status=danger");
            exit;
        }

        if ($origen === $destino) {
            header("Location: /viajar/index.php?page=transportes_ruta_editar&id_ruta={$id_ruta}&message=Origen y destino no pueden ser iguales&status=danger");
            exit;
        }

        if ($precio < 0) {
            header("Location: /viajar/index.php?page=transportes_ruta_editar&id_ruta={$id_ruta}&message=Precio inválido&status=danger");
            exit;
        }

        $rutaModel->setId_ruta($id_ruta)
                ->setNombre($nombre)
                ->setTrayecto($trayecto)
                ->setRela_ciudad_origen($origen)
                ->setRela_ciudad_destino($destino)
                ->setDuracion($duracion)
                ->setDescripcion($desc)
                ->setPrecio_por_persona($precio)
                ->setRela_transporte($rela_transporte);

        $ok = $rutaModel->actualizar();

        $status = $ok ? 'success' : 'danger';
        $msg    = $ok ? 'Ruta actualizada' : 'Error al actualizar ruta';

        header("Location: /viajar/index.php?page=transportes_rutas&id_transporte={$rela_transporte}&status={$status}&message={$msg}");
        exit;
    }

    public function toggle() {
        $this->verificarSesionProveedor();

        $id_ruta = (int)($_GET['id_ruta'] ?? 0);
        if (!$id_ruta) {
            header("Location: /viajar/index.php?page=mis_transportes&status=danger&message=ID inválido");
            exit;
        }

        $id_proveedor = $this->obtenerIdProveedorSesion();

        $rutaModel = new Transporte_Rutas();
        $actual = $rutaModel->traer_por_id($id_ruta);

        if (!$actual) {
            header("Location: /viajar/index.php?page=mis_transportes&status=danger&message=Ruta no encontrada");
            exit;
        }

        $transporteModel = new Transporte();
        if (!$transporteModel->verificar_propietario($actual['rela_transporte'], $id_proveedor)) {
            header("Location: /viajar/index.php?page=mis_transportes&status=danger&message=No autorizado");
            exit;
        }

        $nuevo_estado = ($actual['activo'] == 1) ? 0 : 1;

        $ok = $rutaModel->cambiar_estado($id_ruta, $nuevo_estado);

        if ($ok) {
            header("Location: /viajar/index.php?page=transportes_rutas&id_transporte=".$actual['rela_transporte']);
        } else {
            header("Location: /viajar/index.php?page=transportes_rutas&id_transporte=".$actual['rela_transporte']."&status=danger&message=Error al cambiar estado");
        }

        exit;
    }

}

$action = $_REQUEST['action'] ?? null;
$ctrl = new RutasControlador();

if (!isset($_REQUEST['action'])) return;
switch ($action) {
    case 'listar':
        $ctrl->listar();
        break;
    case 'guardar':
        $ctrl->guardar();
        break;
    case 'eliminar':
        $ctrl->eliminar();
        break;
    case 'actualizar':
        $ctrl->actualizar();
        break;
    case 'toggle':
        $ctrl->toggle();
        break;
    default:
        header('HTTP/1.1 400 Bad Request');
        echo "Acción no válida.";
        break;
}
?>
