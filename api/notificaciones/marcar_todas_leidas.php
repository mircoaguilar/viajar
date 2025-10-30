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
$ids = $data['ids'] ?? [];

if (!is_array($ids) || count($ids) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'No se proporcionaron IDs']);
    exit;
}

$conexion = new Conexion();
$mysqli = $conexion->getConexion();

$placeholders = implode(',', array_fill(0, count($ids), '?'));

$sql = "UPDATE notificaciones SET leido = 1 
        WHERE id_notificacion IN ($placeholders) AND destinario_usuario = ?";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta']);
    exit;
}

$types = str_repeat('i', count($ids)) . 'i';
$params = array_merge($ids, [$userId]);

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Todas las notificaciones marcadas como leídas']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar notificaciones']);
}

$stmt->close();
$mysqli->close();
