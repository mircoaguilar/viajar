<?php
require_once('../models/viaje_asiento.php');
require_once('../models/reserva.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['viaje_id'], $data['asientos'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

$viajeId = intval($data['viaje_id']);
$asientos = $data['asientos'];
$idReserva = isset($data['reserva_id']) ? intval($data['reserva_id']) : null;

$viajeAsiento = new ViajeAsiento();
$errores = [];

foreach ($asientos as $a) {
    $fila = $a['fila'];
    $columna = $a['columna'];
    $piso = $a['piso'];
    $todosAsientos = $viajeAsiento->traerAsientosPorViaje($viajeId);
    $asientoEncontrado = null;
    foreach ($todosAsientos as $ta) {
        if ($ta['piso'] == $piso && $ta['fila'] == $fila && $ta['columna'] == $columna) {
            $asientoEncontrado = $ta;
            break;
        }
    }

    if ($asientoEncontrado) {
        $res = $viajeAsiento->ocuparAsiento($asientoEncontrado['id_asiento'], $idReserva ?? 0);
        if (!$res) {
            $errores[] = "No se pudo ocupar asiento Piso $piso - Fila $fila Columna $columna";
        }
    } else {
        $errores[] = "Asiento no encontrado Piso $piso - Fila $fila Columna $columna";
    }
}

if (count($errores) > 0) {
    echo json_encode(['status' => 'error', 'message' => $errores]);
} else {
    echo json_encode(['status' => 'success', 'message' => 'Asientos ocupados correctamente']);
}
