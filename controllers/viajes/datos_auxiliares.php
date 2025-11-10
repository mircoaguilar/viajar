<?php
require_once(__DIR__ . '/../../models/conexion.php');

$conexion = new Conexion();

$result = [
  'tipos_documento' => [],
  'nacionalidades' => []
];

try {
    $sql1 = "SELECT id_tipo_documento AS id, nombre FROM tipos_documento WHERE activo = 1 ORDER BY nombre";
    $td = $conexion->consultar($sql1);
    if ($td) {
        $result['tipos_documento'] = $td;
    }

    $sql2 = "SELECT id_nacionalidad AS id, nombre FROM nacionalidad ORDER BY nombre";
    $n = $conexion->consultar($sql2);
    if ($n) {
        $result['nacionalidades'] = $n;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result);
    exit;
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8', true, 500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
