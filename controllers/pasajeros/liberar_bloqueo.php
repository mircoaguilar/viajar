<?php
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

$id_viaje = intval($_POST['id_viaje'] ?? 0);
$piso = intval($_POST['piso'] ?? 0);
$numero = intval($_POST['numero_asiento'] ?? 0);

if ($id_viaje <= 0 || $piso <= 0 || $numero <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Parámetros inválidos'
    ]);
    exit;
}

$clean = "DELETE FROM transporte_asientos_bloqueados WHERE expires_at < NOW()";
$mysqli->query($clean);


$sql = "DELETE FROM transporte_asientos_bloqueados 
        WHERE id_viaje = ? 
          AND piso = ?
          AND numero_asiento = ?
          AND id_usuario = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error interno en prepare()'
    ]);
    exit;
}

$stmt->bind_param('iiii', $id_viaje, $piso, $numero, $id_usuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {

    echo json_encode([
        'status' => 'success',
        'message' => 'Bloqueo liberado correctamente'
    ]);
    exit;
}

echo json_encode([
    'status' => 'warning',
    'message' => 'No había bloqueo activo para liberar'
]);
exit;
