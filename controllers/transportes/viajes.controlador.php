<?php
require_once(__DIR__ . '/../../models/viaje.php');

$action = $_REQUEST['action'] ?? null;

switch ($action) {
    case 'listar':
        listar_viajes();
        break;

    case 'obtener':
        if (isset($_GET['id'])) {
            obtener_viaje($_GET['id']);
        }
        break;

    case 'guardar_viaje': 
        guardar_viaje();
        break;

    case 'actualizar':
        if (isset($_POST['id_viajes'])) {
            actualizar_viaje($_POST['id_viajes']);
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            eliminar_viaje($_GET['id']);
        }
        break;

    case 'toggle':
        if (isset($_GET['id_viaje'])) {
            toggle_viaje($_GET['id_viaje']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}

function listar_viajes() {
    $viaje = new Viaje();
    $viajes = $viaje->traer_viajes_proximos(20);
    echo json_encode($viajes);
}

function obtener_viaje($id) {
    $viaje = new Viaje();
    $resultado = $viaje->traer_viaje_por_id($id);
    echo json_encode($resultado);
}

function guardar_viaje() {
    if (!isset($_POST['viaje_fecha'], $_POST['rela_transporte_rutas'], $_POST['hora_salida'], $_POST['hora_llegada'])) {
        $id_ruta = $_POST['rela_transporte_rutas'] ?? 0;
        header("Location: ../../index.php?page=transportes_viajes_carga&id_ruta=$id_ruta&status=danger&message=".urlencode('Faltan datos obligatorios'));
        exit;
    }

    $viaje = new Viaje();
    $viaje->setViaje_fecha($_POST['viaje_fecha']);
    $viaje->setRela_transporte_rutas($_POST['rela_transporte_rutas']);
    $viaje->setHora_salida($_POST['hora_salida']);
    $viaje->setHora_llegada($_POST['hora_llegada']);
    $viaje->setActivo(1);

    $resultado = $viaje->guardar();

    $status = $resultado ? 'success' : 'danger';
    $msg    = $resultado ? 'Viaje guardado correctamente.' : 'Error al guardar el viaje.';

    header("Location: ../../index.php?page=transportes_viajes&id_ruta=".$_POST['rela_transporte_rutas']."&status=$status&message=".urlencode($msg));
    exit;
}

function actualizar_viaje($id) {
    if (!isset($_POST['viaje_fecha'], $_POST['hora_salida'], $_POST['hora_llegada'], $_POST['rela_transporte_rutas'])) {
        $id_ruta = $_POST['rela_transporte_rutas'] ?? 0;
        header("Location: ../../index.php?page=transportes_viajes_carga&id_ruta=$id_ruta&status=danger&message=".urlencode('Faltan datos obligatorios'));
        exit;
    }

    $viaje = new Viaje();
    $viaje->setId_viajes($id);
    $viaje->setViaje_fecha($_POST['viaje_fecha']);
    $viaje->setRela_transporte_rutas($_POST['rela_transporte_rutas']);
    $viaje->setHora_salida($_POST['hora_salida']);
    $viaje->setHora_llegada($_POST['hora_llegada']);
    $viaje->setActivo(1);

    $resultado = $viaje->actualizar();

    $status = $resultado ? 'success' : 'danger';
    $msg    = $resultado ? 'Viaje actualizado correctamente.' : 'Error al actualizar el viaje.';

    header("Location: ../../index.php?page=transportes_viajes&id_ruta=".$_POST['rela_transporte_rutas']."&status=$status&message=".urlencode($msg));
    exit;
}

function eliminar_viaje($id) {
    $viaje = new Viaje($id);
    $resultado = $viaje->eliminar_logico();

    $status = $resultado ? 'success' : 'danger';
    $msg    = $resultado ? 'Viaje eliminado correctamente.' : 'Error al eliminar el viaje.';
    $id_ruta = $_GET['id_ruta'] ?? 0;

    header("Location: ../../index.php?page=transportes_viajes&id_ruta=$id_ruta&status=$status&message=".urlencode($msg));
    exit;
}

function toggle_viaje($id) {
    $viaje = new Viaje();
    $actual = $viaje->traer_viaje_por_id($id);

    if (!$actual) {
        echo json_encode(['status' => 'error', 'message' => 'Viaje no encontrado']);
        return;
    }

    $nuevo_estado = ($actual['activo'] == 1) ? 0 : 1;

    $resultado = $viaje->cambiar_estado($id, $nuevo_estado);

    if ($resultado) {
        header("Location: ../../index.php?page=transportes_viajes&id_ruta=".$actual['rela_transporte_rutas']);
        exit;
    } else {
        echo "Error al cambiar estado del viaje.";
    }
}
