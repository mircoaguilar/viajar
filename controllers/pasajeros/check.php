<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../../models/conexion.php'); 

$numero_documento = $_POST['numero_documento'] ?? null;
$id_usuario = $_SESSION['id_usuarios'] ?? null; 
$conexion = new Conexion();
$mysqli = $conexion->getConexion(); 

header('Content-Type: application/json');

if (!$id_usuario || !$numero_documento || !$mysqli) {
    echo json_encode(['status' => 'error', 'message' => 'Falta ID de usuario, número de documento, o la conexión DB no está disponible.']);
    exit;
}

$sql = "SELECT id_pasajeros, rela_usuario, nombre, apellido, rela_nacionalidad,
                rela_tipo_documento, numero_documento, sexo, fecha_nacimiento
        FROM pasajeros
        WHERE numero_documento = ?";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta: ' . $mysqli->error]); 
    exit;
}

$stmt->bind_param('s', $numero_documento);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta: ' . $stmt->error]); 
    exit;
}

$res = $stmt->get_result();

if ($res->num_rows > 0) { 
    $row = $res->fetch_assoc();
    $stmt->close(); 
    
    if ((int)$row['rela_usuario'] === (int)$id_usuario) {
          echo json_encode([
            'status' => 'success',
            'found' => true,
            'owner' => true,
            'data' => $row
          ]);
          exit;
        }
} 

$stmt->close();

echo json_encode([
    'status' => 'success',
    'found' => false
]);
exit;