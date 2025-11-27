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

$conexion = new Conexion();
$mysqli = $conexion->getConexion();

$mysqli->query("
    DELETE FROM transporte_asientos_bloqueados
    WHERE expires_at < NOW()
");

$sqlOcupados = "
    SELECT piso, numero_asiento
    FROM detalle_reserva_transporte
    WHERE id_viaje = ?
      AND piso = ?
      AND estado IN ('pendiente','confirmada')
";

$stmt = $mysqli->prepare($sqlOcupados);
$stmt->bind_param("ii", $viajeId, $piso);
$stmt->execute();
$result = $stmt->get_result();

$ocupadosDefinitivos = [];
while ($row = $result->fetch_assoc()) {
    $ocupadosDefinitivos[] = intval($row['numero_asiento']);
}

$sqlBloqueados = "
    SELECT numero_asiento
    FROM transporte_asientos_bloqueados
    WHERE id_viaje = ?
      AND piso = ?
      AND expires_at > NOW()
";

$stmt = $mysqli->prepare($sqlBloqueados);
$stmt->bind_param("ii", $viajeId, $piso);
$stmt->execute();
$res2 = $stmt->get_result();

$bloqueadosTemp = [];
while ($row = $res2->fetch_assoc()) {
    $bloqueadosTemp[] = intval($row['numero_asiento']);
}

$ocupados = array_unique(array_merge($ocupadosDefinitivos, $bloqueadosTemp));

echo json_encode([
    "status" => "success",
    "ocupados" => $ocupados,
    "debug" => [
        "definitivos" => $ocupadosDefinitivos,
        "temporales" => $bloqueadosTemp
    ]
]);
exit;
?>
