<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once(__DIR__ . '/../../models/admin.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuarios'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$accion = $input['accion'] ?? '';
$tipo = $input['tipo'] ?? '';
$id = (int)($input['id'] ?? 0);
$motivo = trim($input['motivo'] ?? '');
$id_admin = $_SESSION['id_usuarios'];

$admin = new Admin();

if ($accion === 'aprobar') {
    $resultado = $admin->aprobarServicio($tipo, $id, $id_admin);
    echo json_encode($resultado ? 
        ['status' => 'success', 'message' => 'Servicio aprobado correctamente.'] :
        ['status' => 'error', 'message' => 'No se pudo aprobar el servicio.']
    );
} elseif ($accion === 'rechazar') {
    $resultado = $admin->rechazarServicio($tipo, $id, $motivo, $id_admin);
    echo json_encode($resultado ? 
        ['status' => 'success', 'message' => 'Servicio rechazado correctamente.'] :
        ['status' => 'error', 'message' => 'No se pudo rechazar el servicio.']
    );
} else {
    echo json_encode(['status' => 'error', 'message' => 'Acción inválida']);
}
