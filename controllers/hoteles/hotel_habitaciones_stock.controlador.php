<?php
session_start();
require_once(__DIR__ . '/../../models/hotel_habitaciones_stock.php');
require_once(__DIR__ . '/../../models/hotel_habitaciones.php');
require_once(__DIR__ . '/../../models/hotel.php');

header('Content-Type: application/json');

/*  Verificación de acceso (solo perfil 3: administrador de hospedaje) */
if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 3) {
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

/*  Validar acción recibida */
if (!isset($_POST['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Acción no especificada']);
    exit;
}

$action = $_POST['action'];

switch ($action) {

    /*  Guardar stock de una habitación */
    case 'guardar_stock':
        $rela_habitacion = $_POST['rela_habitacion'] ?? null;
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_fin = $_POST['fecha_fin'] ?? null;
        $cantidad = $_POST['cantidad'] ?? null;
        $id_usuario_actual = $_SESSION['id_usuarios'];

        // Validación de datos obligatorios
        if (!$rela_habitacion || !$fecha_inicio || !$fecha_fin || !$cantidad) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
            exit;
        }

        // Verificar existencia de la habitación
        $habitacionModel = new Hotel_Habitaciones();
        $habitacion = $habitacionModel->traer_por_id($rela_habitacion);

        if (!$habitacion) {
            echo json_encode(['status' => 'error', 'message' => 'Habitación no encontrada']);
            exit;
        }

        // Validar que el usuario sea propietario del hotel de la habitación
        $rela_hotel = $habitacion['rela_hotel'] ?? 0;
        $hotelModel = new Hotel();
        $hoteles_usuario = $hotelModel->traer_hoteles_por_usuario($id_usuario_actual);
        $es_propietario = false;
        
        foreach ($hoteles_usuario as $hotel) {
            if ($hotel['id_hotel'] == $rela_hotel) {
                $es_propietario = true;
                break;
            }
        }

        if (!$es_propietario) {
            echo json_encode(['status' => 'error', 'message' => 'Acción no autorizada para esta habitación']);
            exit;
        }

        // Guardar stock en el rango de fechas
        try {
            $stockModel = new Hotel_Habitaciones_Stock();

            $insertados = $stockModel->guardar_rango(
                $rela_habitacion,
                $fecha_inicio,
                $fecha_fin,
                $cantidad
            );

            echo json_encode([
                'status' => 'success',
                'message' => "Stock agregado correctamente para $insertados noches"
            ]);

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

        break;

    /*  Acción no reconocida */
    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
