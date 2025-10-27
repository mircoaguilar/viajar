<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../models/conexion.php'; 

/*  Obtener usuario logueado */
$userId = $_SESSION['id_usuarios'] ?? 0;
if (!$userId) {
    echo json_encode([]);
    exit;
}

/*  Conexión a la base de datos */
$conexion = new Conexion();
$mysqli = $conexion->getConexion();

/*  Consulta de notificaciones del usuario */
$stmt = $mysqli->prepare("
    SELECT id_notificacion, titulo, mensaje, leido, metadata, creado_en 
    FROM notificaciones 
    WHERE destinario_usuario = ? 
    ORDER BY creado_en DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

/*  Construcción del array de resultados */
$notificaciones = [];
while ($row = $result->fetch_assoc()) {
    $notificaciones[] = $row;
}

$stmt->close();

/*  Devolver en formato JSON */
echo json_encode($notificaciones);
exit;
