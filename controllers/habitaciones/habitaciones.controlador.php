<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/../../models/hotel_habitaciones.php');

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if (!$action) {
    echo json_encode(['status' => 'error', 'message' => 'Acción no especificada']);
    exit;
}

switch ($action) {

    case 'guardar':

        header('Content-Type: application/json');

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
                            $fotosGuardadas[] = "assets/images/" . $nombreArchivo;
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

            echo json_encode([
                'status' => $id_guardado ? 'success' : 'error',
                'message' => $id_guardado ? 'Habitación guardada correctamente' : 'Error al guardar',
                'id' => $id_guardado,
                'fotos' => $fotosGuardadas
            ]);

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'actualizar':

        $id_hab = $_POST['id_hotel_habitacion'] ?? null;
        $id_hotel = $_POST['rela_hotel'] ?? null;

        if (!$id_hab || !$id_hotel) {
            header("Location: ../../index.php?page=hoteles_mis_hoteles&status=danger&message=Datos incompletos");
            exit;
        }

        $habitacionModel = new Hotel_Habitaciones();
        $habitacionActual = $habitacionModel->traer_por_id($id_hab);

        $fotos_existentes = [];
        if (!empty($habitacionActual["fotos"])) {
            $fotos_existentes = is_array($habitacionActual["fotos"])
                ? $habitacionActual["fotos"]
                : (json_decode($habitacionActual["fotos"], true) ?: []);
        }

        $borrar = $_POST['borrar_fotos'] ?? [];
        if (!empty($borrar)) {
            $fotos_existentes = array_values(array_diff($fotos_existentes, $borrar));
            foreach ($borrar as $foto) {
                $path = __DIR__ . "/../../" . $foto;
                if (file_exists($path)) unlink($path);
            }
        }

        $fotos_nuevas = [];

        if (!empty($_FILES['fotos']['name'][0])) {

            $carpetaDestino = __DIR__ . '/../../assets/images/';
            if (!is_dir($carpetaDestino)) mkdir($carpetaDestino, 0777, true);

            foreach ($_FILES['fotos']['tmp_name'] as $i => $tmpName) {

                if ($_FILES['fotos']['error'][$i] === UPLOAD_ERR_OK) {

                    $nombreArchivo = uniqid('hab_') . '_' . basename($_FILES['fotos']['name'][$i]);
                    $rutaDestino = $carpetaDestino . $nombreArchivo;

                    if (move_uploaded_file($tmpName, $rutaDestino)) {
                        $fotos_nuevas[] = "assets/images/" . $nombreArchivo;
                    }
                }
            }
        }

        $fotos_final = array_values(array_merge($fotos_existentes, $fotos_nuevas));

        try {
            $habitacion = new Hotel_Habitaciones();
            $habitacion->setId_hotel_habitacion($id_hab);
            $habitacion->setRela_hotel($id_hotel);
            $habitacion->setRela_tipo_habitacion($_POST['rela_tipo_habitacion']);
            $habitacion->setCapacidad_maxima($_POST['capacidad_maxima']);
            $habitacion->setPrecio_base_noche($_POST['precio_base_noche']);
            $habitacion->setDescripcion($_POST['descripcion']);
            $habitacion->setActivo($_POST['activo']);
            $habitacion->setFotosArray($fotos_final);

            $habitacion->actualizar();

            header("Location: ../../index.php?page=hoteles_habitaciones&id_hotel=$id_hotel&status=success&message=Habitación actualizada");
            exit;

        } catch (Exception $e) {
            header("Location: ../../index.php?page=hoteles_habitaciones&id_hotel=$id_hotel&status=danger&message=" . urlencode($e->getMessage()));
            exit;
        }

        break;



    case 'traer_por_hotel':

        header('Content-Type: application/json');

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
