<?php
date_default_timezone_set('America/Argentina/Cordoba');

if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuarios'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No autenticado'
    ]);
    exit;
}

require_once __DIR__ . '/../../models/conexion.php';

$conexion = new Conexion();
$mysqli = $conexion->getConexion();

$id_usuario = $_SESSION['id_usuarios'];
$id_viaje   = intval($_POST['id_viaje'] ?? 0);
$piso       = intval($_POST['piso'] ?? 0);
$numero     = intval($_POST['numero_asiento'] ?? 0);

error_log("Parámetros recibidos: id_viaje=$id_viaje, piso=$piso, numero=$numero");

if ($id_viaje <= 0 || $piso <= 0 || $numero <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Parámetros inválidos'
    ]);
    exit;
}

$sqlVerificarOcupado = "
    SELECT 1
    FROM detalle_reserva_transporte
    WHERE id_viaje = ? AND piso = ? AND numero_asiento = ? AND estado IN ('confirmada', 'pendiente')
";
$stmt = $mysqli->prepare($sqlVerificarOcupado);
$stmt->bind_param("iii", $id_viaje, $piso, $numero);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'El asiento ya está reservado o bloqueado por otro usuario.'
    ]);
    exit;
}

$sqlVerificarBloqueo = "
    SELECT 1
    FROM transporte_asientos_bloqueados
    WHERE id_viaje = ? AND piso = ? AND numero_asiento = ? AND expires_at > NOW()
";
$stmt = $mysqli->prepare($sqlVerificarBloqueo);
$stmt->bind_param("iii", $id_viaje, $piso, $numero);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'El asiento ya está bloqueado temporalmente por otro usuario.'
    ]);
    exit;
}

$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

$sql = "
    INSERT INTO transporte_asientos_bloqueados
    (id_viaje, piso, numero_asiento, id_usuario, expires_at)
    VALUES (?, ?, ?, ?, ?)
";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    error_log("Error al preparar la consulta: " . $mysqli->error);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error interno al preparar la consulta.'
    ]);
    exit;
}

$stmt->bind_param('iiiss', $id_viaje, $piso, $numero, $id_usuario, $expires_at);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'id_block' => $mysqli->insert_id,
        'expires_at' => $expires_at
    ]);
    exit;
}

$error_code = $mysqli->errno;
error_log("Error al ejecutar la consulta: " . $mysqli->error); 
echo json_encode([
    'status' => 'error',
    'message' => 'Error inesperado al bloquear asiento. Código: ' . $error_code
]);
exit;
?>
