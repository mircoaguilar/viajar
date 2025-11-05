<?php
require_once(__DIR__ . '/../../models/viaje.php');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'listar':
            listar_viajes();
            break;

        case 'obtener':
            if (isset($_GET['id'])) {
                obtener_viaje($_GET['id']);
            }
            break;

        case 'guardar':
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

        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
            break;
    }
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
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
        return;
    }

    $viaje = new Viaje();
    $viaje->setViaje_fecha($_POST['viaje_fecha']);
    $viaje->setRela_transporte_rutas($_POST['rela_transporte_rutas']);
    $viaje->setHora_salida($_POST['hora_salida']);
    $viaje->setHora_llegada($_POST['hora_llegada']);
    $viaje->setActivo(1);

    $resultado = $viaje->guardar();

    echo json_encode([
        'status' => $resultado ? 'success' : 'error',
        'message' => $resultado ? 'Viaje guardado correctamente.' : 'Error al guardar el viaje.'
    ]);
}

function actualizar_viaje($id) {
    if (!isset($_POST['viaje_fecha'], $_POST['hora_salida'], $_POST['hora_llegada'], $_POST['rela_transporte_rutas'])) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
        return;
    }

    $viaje = new Viaje();
    $viaje->setId_viajes($id);
    $viaje->setViaje_fecha($_POST['viaje_fecha']);
    $viaje->setRela_transporte_rutas($_POST['rela_transporte_rutas']);
    $viaje->setHora_salida($_POST['hora_salida']);
    $viaje->setHora_llegada($_POST['hora_llegada']);
    $viaje->setActivo(1);

    $resultado = $viaje->actualizar();

    echo json_encode([
        'status' => $resultado ? 'success' : 'error',
        'message' => $resultado ? 'Viaje actualizado correctamente.' : 'Error al actualizar el viaje.'
    ]);
}

function eliminar_viaje($id) {
    $viaje = new Viaje($id);
    $resultado = $viaje->eliminar_logico();

    echo json_encode([
        'status' => $resultado ? 'success' : 'error',
        'message' => $resultado ? 'Viaje eliminado correctamente.' : 'Error al eliminar el viaje.'
    ]);
}
