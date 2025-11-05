<?php
session_start();
require_once(__DIR__ . '/../../models/hotel_habitaciones.php');

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if (!$action) {
    echo json_encode(['status' => 'error', 'message' => 'Acción no especificada']);
    exit;
}

switch ($action) {

    case 'guardar':
        $id_hotel = $_POST['rela_hotel'] ?? null;
        $tipo = trim($_POST['rela_tipo_habitacion'] ?? '');
        $capacidad = trim($_POST['capacidad_maxima'] ?? '');
        $precio = trim($_POST['precio_base_noche'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (!$id_hotel || !$tipo || !$capacidad) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios.']);
            exit;
        }

        try {
            $fotosGuardadas = [];
            if (!empty($_FILES['fotos']['name'][0])) {
                $carpetaDestino = __DIR__ . '/../../assets/images/';
                if (!is_dir($carpetaDestino)) mkdir($carpetaDestino, 0777, true);

                foreach ($_FILES['fotos']['tmp_name'] as $i => $tmpName) {
                    if ($_FILES['fotos']['error'][$i] === UPLOAD_ERR_OK) {
                        $nombreArchivo = uniqid('hab_') . '_' . basename($_FILES['fotos']['name'][$i]);
                        $rutaDestino = $carpetaDestino . $nombreArchivo;
                        if (move_uploaded_file($tmpName, $rutaDestino)) {
                            $fotosGuardadas[] = 'assets/images/' . $nombreArchivo;
                        }
                    }
                }
            }

            $habitacion = new Hotel_Habitaciones();
            $habitacion->setRela_hotel($id_hotel);
            $habitacion->setRela_tipo_habitacion($tipo);
            $habitacion->setCapacidad_maxima($capacidad);
            $habitacion->setPrecio_base_noche($precio ?: 0);
            $habitacion->setDescripcion($descripcion);
            $habitacion->setActivo(1);
            $habitacion->setFotosArray($fotosGuardadas);

            $id_guardado = $habitacion->guardar();

            if ($id_guardado) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Habitación guardada correctamente',
                    'id' => $id_guardado,
                    'fotos' => $fotosGuardadas
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al guardar la habitación']);
            }

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'traer_por_hotel':
        $id_hotel = $_GET['id_hotel'] ?? null;
        if (!$id_hotel) {
            echo json_encode([]);
            exit;
        }

        $habitacionModel = new Hotel_Habitaciones();
        $habitaciones = $habitacionModel->traer_por_hotel($id_hotel);

        echo json_encode($habitaciones);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
