<?php
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuarios'])) {
    echo json_encode([
        'error' => true,
        'message' => 'No autenticado'
    ]);
    exit;
}

require_once __DIR__ . '/../../models/conexion.php';

$conexion = new Conexion();
$mysqli = $conexion->getConexion();

$numero_documento = trim($_POST['numero_documento'] ?? '');

if ($numero_documento === '') {
    echo json_encode(['error' => true, 'message' => 'Documento vacío']);
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];

$sql = "SELECT id_pasajeros, rela_usuario, nombre, apellido, rela_nacionalidad,
               rela_tipo_documento, numero_documento, sexo, fecha_nacimiento
        FROM pasajeros
        WHERE numero_documento = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $numero_documento);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();

    if ((int)$row['rela_usuario'] !== (int)$id_usuario) {
        echo json_encode([
            'exists_other' => true
        ]);
        exit;
    }

    echo json_encode([
        'exists' => true,
        'data' => $row
    ]);
    exit;
}

echo json_encode([
    'exists' => false
]);
exit;
