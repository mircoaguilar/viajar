<?php
session_start();
require_once(__DIR__ . '/../../models/stock_tour.php');
require_once(__DIR__ . '/../../models/Tour.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'], [1, 14])) {
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$stockModel = new Tour_Stock();
$tourModel = new Tour();

try {
    switch ($action) {

        case 'guardar_stock':
            $rela_tour = $_POST['rela_tour'] ?? '';
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $fecha_fin = $_POST['fecha_fin'] ?? '';
            $cantidad = intval($_POST['cantidad'] ?? 0);

            if (!$rela_tour || !$fecha_inicio || !$fecha_fin || $cantidad <= 0) {
                throw new Exception('Datos incompletos o inválidos.');
            }

            $insertados = $stockModel->guardar_rango($rela_tour, $fecha_inicio, $fecha_fin, $cantidad);

            echo json_encode([
                'status' => 'success',
                'message' => "Stock cargado correctamente para {$insertados} fechas."
            ]);
            break;

        case 'traer_stock':
            $rela_tour = $_POST['rela_tour'] ?? '';
            if (!$rela_tour) {
                throw new Exception('ID de tour no especificado.');
            }

            $data = $stockModel->traer_por_tour($rela_tour);

            $stock = [];
            foreach ($data as $row) {
                $stock[] = [
                    'fecha' => $row['fecha'],
                    'cantidad_disponible' => $row['cupos_disponibles']
                ];
            }

            echo json_encode(['status' => 'success', 'stock' => $stock]);
            break;

        case 'actualizar_stock':
            $id_stock = $_POST['id_stock'] ?? '';
            $cantidad = intval($_POST['cantidad'] ?? 0);

            if (!$id_stock || $cantidad < 0) {
                throw new Exception('Datos inválidos para actualizar.');
            }

            $stockModel->setId_stock_tour($id_stock);
            $stockModel->setCupos_disponibles($cantidad);
            $ok = $stockModel->actualizar();

            if ($ok) {
                echo json_encode(['status' => 'success', 'message' => 'Stock actualizado correctamente.']);
            } else {
                throw new Exception('Error al actualizar el stock.');
            }
            break;

        case 'eliminar_stock':
            $id_stock = $_POST['id_stock'] ?? '';
            if (!$id_stock) {
                throw new Exception('ID no especificado.');
            }

            $stockModel->setId_stock_tour($id_stock);
            $ok = $stockModel->eliminar_logico();

            if ($ok) {
                echo json_encode(['status' => 'success', 'message' => 'Stock eliminado correctamente.']);
            } else {
                throw new Exception('Error al eliminar el registro.');
            }
            break;

        default:
            throw new Exception('Acción no válida.');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
