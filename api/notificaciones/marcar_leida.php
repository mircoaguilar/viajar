<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../models/conexion.php';

$userId = $_SESSION['id_usuarios'] ?? 0;
if (!$userId) {
    echo json_encode(['status' => 'error', 'message' => 'No autenticado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? 0;
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
    exit;
}

$conexion = new Conexion();
$mysqli = $conexion->getConexion();

$stmt = $mysqli->prepare("UPDATE notificaciones SET leido = 1 WHERE id_notificacion = ? AND destinario_usuario = ?");
$stmt->bind_param("ii", $id, $userId);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'success', 'message' => 'Notificación marcada como leída']);
