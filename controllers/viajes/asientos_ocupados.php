<?php
require_once('../../models/viaje_asiento.php');

header('Content-Type: application/json');

$viajeId = isset($_GET['viaje']) ? intval($_GET['viaje']) : 0;
$piso = isset($_GET['piso']) ? intval($_GET['piso']) : 0;

if (!$viajeId || !$piso) {
    echo json_encode([
        "status" => "error",
        "message" => "Parámetros inválidos"
    ]);
    exit;
}

$viajeAsientoModel = new ViajeAsiento();
$ocupadosPorPiso = $viajeAsientoModel->obtener_asientos_ocupados($viajeId);
$ocupados = isset($ocupadosPorPiso['piso'.$piso]) ? $ocupadosPorPiso['piso'.$piso] : [];

echo json_encode([
    "status" => "success",
    "ocupados" => $ocupados
]);
exit;
