<?php
require_once(__DIR__ . '/../../models/ciudad.php');

// Indicamos que la respuesta será en formato JSON
header('Content-Type: application/json');

// Si no llega una provincia válida, devolvemos vacío
if (!isset($_GET['provincia']) || empty($_GET['provincia'])) {
    echo json_encode([]);
    exit;
}

// Tomamos el ID de provincia recibido
$provinciaId = (int)$_GET['provincia'];

// Creamos el modelo y buscamos las ciudades de esa provincia
$ciudad = new Ciudad();
$ciudades = $ciudad->traer_por_provincia($provinciaId);

// Devolvemos las ciudades en JSON
echo json_encode($ciudades);
exit;
