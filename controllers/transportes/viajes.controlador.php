<?php
require_once(__DIR__ . '/../../models/viaje.php');

/*  Enrutador de acciones */
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
            echo json_encode(['error' => 'Acción no válida']);
            break;
    }
}

/*  Listar próximos viajes */
function listar_viajes() {
    $viaje = new Viaje();
    $viajes = $viaje->traer_viajes_proximos(20);
    echo json_encode($viajes);
}

/*  Obtener un viaje por ID */
function obtener_viaje($id) {
    $viaje = new Viaje();
    $resultado = $viaje->traer_viaje_por_id($id);
    echo json_encode($resultado);
}

/*  Guardar un nuevo viaje */
function guardar_viaje() {
    if (!isset($_POST['viaje_fecha'], $_POST['rela_transporte_rutas'], $_POST['hora_salida'], $_POST['hora_llegada'], $_POST['asientos_disponibles'])) {
        echo json_encode(['error' => 'Faltan datos']);
        return;
    }

    $viaje = new Viaje(
        '', // id (autoincrement)
        $_POST['viaje_fecha'],
        1, // activo por defecto
        $_POST['rela_transporte_rutas'],
        $_POST['hora_salida'],
        $_POST['hora_llegada'],
        $_POST['asientos_disponibles']
    );

    $resultado = $viaje->guardar();
    echo json_encode(['success' => $resultado]);
}

/*  Actualizar un viaje existente */
function actualizar_viaje($id) {
    if (!isset($_POST['viaje_fecha'], $_POST['hora_salida'], $_POST['hora_llegada'], $_POST['asientos_disponibles'])) {
        echo json_encode(['error' => 'Faltan datos']);
        return;
    }

    // acá usás $_POST['rela_transporte_rutas'], pero no lo validás arriba.
    $viaje = new Viaje(
        $id,
        $_POST['viaje_fecha'],
        1,
        $_POST['rela_transporte_rutas'],
        $_POST['hora_salida'],
        $_POST['hora_llegada'],
        $_POST['asientos_disponibles']
    );

    $resultado = $viaje->actualizar();
    echo json_encode(['success' => $resultado]);
}

/*  Eliminación lógica de un viaje */
function eliminar_viaje($id) {
    $viaje = new Viaje($id);
    $resultado = $viaje->eliminar_logico();
    echo json_encode(['success' => $resultado]);
}
