<?php
require_once(__DIR__ . '/../../models/ciudad.php');

header('Content-Type: application/json');

if (!isset($_GET['provincia']) || empty($_GET['provincia'])) {
    echo json_encode([]);
    exit;
}

$provinciaId = (int)$_GET['provincia'];

$ciudad = new Ciudad();
$ciudades = $ciudad->traer_por_provincia($provinciaId);

echo json_encode($ciudades);
exit;
