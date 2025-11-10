<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once(__DIR__ . '/../../models/carrito.php');
require_once(__DIR__ . '/../../models/carritoitem.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuarios'])) {
    echo json_encode(['status' => 'error', 'message' => 'Debes iniciar sesión']);
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];
$action = $_POST['action'] ?? $_GET['action'] ?? null;

if (!$action) {
    echo json_encode(['status' => 'error', 'message' => 'Acción no especificada']);
    exit;
}

$carritoModel = new Carrito();
$itemModel = new CarritoItem();

switch ($action) {

    case 'agregar':
    $tipo_servicio   = $_POST['tipo_servicio'] ?? null; 
    $id_servicio     = $_POST['id_servicio'] ?? null;
    $cantidad        = (int)($_POST['cantidad'] ?? 1);
    $precio_unitario = (float)($_POST['precio_unitario'] ?? 0);

    if (!$tipo_servicio || !$id_servicio || $cantidad <= 0 || $precio_unitario <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
        exit;
    }

    // Obtener o crear carrito activo
    $carrito = $carritoModel->traer_carrito_activo($id_usuario);
    if (!$carrito) {
        $carritoModel->setId_usuario($id_usuario)->setActivo(1);
        $id_carrito = $carritoModel->guardar();
    } else {
        $id_carrito = $carrito['id_carrito'];
    }

    $subtotal = $cantidad * $precio_unitario;
    $fecha_inicio = null;
    $fecha_fin = null;
    $asientos = [];

    // HOTEL
    if ($tipo_servicio === 'hotel' && !empty($_POST['checkin']) && !empty($_POST['checkout'])) {
        $fecha_inicio = $_POST['checkin'];
        $fecha_fin    = $_POST['checkout'];
        $noches = (new DateTime($fecha_fin))->diff(new DateTime($fecha_inicio))->days;
        $subtotal *= max($noches, 1);
    }

    // TOUR
    if ($tipo_servicio === 'tour' && !empty($_POST['fecha_tour'])) {
        $fecha_inicio = $_POST['fecha_tour'];
        $fecha_fin = null;
    }

    // TRANSPORTE
    if ($tipo_servicio === 'transporte') {
        $fecha_inicio = $_POST['fecha_servicio'] ?? null;
        $fecha_fin = null;
        $asientos_json = $_POST['asientos'] ?? '[]';
        $asientos = json_decode($asientos_json, true);

        if (!is_array($asientos)) {
            $asientos = [];
            error_log("Error al decodificar asientos en agregar transporte: " . print_r($asientos_json, true));
        }

        error_log("Agregando transporte al carrito: servicio={$id_servicio}, fecha={$fecha_inicio}, asientos=" . print_r($asientos, true));
    }

    // Crear item del carrito
    $itemModel->setId_carrito($id_carrito)
              ->setTipo_servicio($tipo_servicio)
              ->setId_servicio($id_servicio)
              ->setCantidad($cantidad)
              ->setPrecio_unitario($precio_unitario)
              ->setSubtotal($subtotal)
              ->setFecha_inicio($fecha_inicio)
              ->setFecha_fin($fecha_fin);

    $resultado = $itemModel->guardar();

    if (!$resultado) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al guardar item',
            'sql_error' => $itemModel->getError()
        ]);
        exit;
    }

    // PASAJEROS
    if ($tipo_servicio === 'transporte') {
        require_once(__DIR__ . '/../../models/pasajero.php');

        $pasajeros = $_POST['pasajeros'] ?? [];

        if (!is_array($pasajeros)) {
            $pasajeros = json_decode($pasajeros, true) ?: [];
        }

        if (is_array($pasajeros) && !empty($pasajeros)) {
            $pasajeroModel = new Pasajero();

            foreach ($pasajeros as $p) {
                // ✅ Convertir fecha de nacimiento a formato MySQL (YYYY-MM-DD)
                $fecha_nac = null;
                if (!empty($p['fecha_nacimiento'])) {
                    $fecha_obj = DateTime::createFromFormat('d/m/Y', $p['fecha_nacimiento']);
                    $fecha_nac = $fecha_obj ? $fecha_obj->format('Y-m-d') : null;
                }

                $pasajeroModel->setRela_usuario($id_usuario)
                              ->setNombre($p['nombre'] ?? '')
                              ->setApellido($p['apellido'] ?? '')
                              ->setRela_nacionalidad($p['rela_nacionalidad'] ?? null)
                              ->setRela_tipo_documento($p['rela_tipo_documento'] ?? null)
                              ->setNumero_documento($p['numero_documento'] ?? '')
                              ->setSexo($p['sexo'] ?? '')
                              ->setFecha_nacimiento($fecha_nac)
                              ->guardar();
            }
            error_log("Pasajeros insertados correctamente para usuario $id_usuario");
        } else {
            error_log("No se recibieron pasajeros válidos en la petición");
        }

        // Guardar asientos extra en sesión
        if (!isset($_SESSION['carrito_extra'])) {
            $_SESSION['carrito_extra'] = [];
        }
        $_SESSION['carrito_extra'][$id_servicio] = [
            'asientos' => $asientos,
            'fecha_servicio' => $fecha_inicio
        ];

        error_log("Asientos almacenados en sesión para transporte {$id_servicio}: " . print_r($asientos, true));
    }

    echo json_encode(['status' => 'success', 'message' => 'Item agregado al carrito y pasajeros guardados']);
    break;


    case 'quitar':
        $id_item = (int)($_POST['id_item'] ?? 0);
        if (!$id_item) {
            echo json_encode(['status' => 'error', 'message' => 'ID de item requerido']);
            exit;
        }
        $itemModel->eliminar($id_item);
        echo json_encode(['status' => 'success', 'message' => 'Item eliminado']);
        break;

    case 'actualizar':
        $id_item = (int)($_POST['id_item'] ?? 0);
        $cantidad = (int)($_POST['cantidad'] ?? 0);
        $precio_unitario = (float)($_POST['precio_unitario'] ?? 0);

        if (!$id_item || $cantidad <= 0 || $precio_unitario <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
            exit;
        }

        $item = $itemModel->traer_por_id($id_item);
        if (!$item) {
            echo json_encode(['status' => 'error', 'message' => 'Item no encontrado']);
            exit;
        }

        $subtotal = $cantidad * $precio_unitario;
        if ($item['tipo_servicio'] === 'hotel' && !empty($item['fecha_inicio']) && !empty($item['fecha_fin'])) {
            $fecha_inicio = new DateTime($item['fecha_inicio']);
            $fecha_fin    = new DateTime($item['fecha_fin']);
            $noches = $fecha_fin->diff($fecha_inicio)->days;
            $subtotal *= max($noches, 1);
        }

        $itemModel->setCantidad($cantidad)
                  ->setPrecio_unitario($precio_unitario)
                  ->setSubtotal($subtotal)
                  ->actualizar($id_item);

        echo json_encode(['status' => 'success', 'message' => 'Cantidad actualizada']);
        break;

    case 'listar':
        $carrito = $carritoModel->traer_carrito_activo($id_usuario);
        if (!$carrito) {
            echo json_encode(['status' => 'success', 'items' => []]);
            exit;
        }

        $id_carrito = $carrito['id_carrito'];
        $items = $carritoModel->traer_items($id_carrito);

        if (!empty($_SESSION['carrito_extra'])) {
            foreach ($items as &$it) {
                if ($it['tipo_servicio'] === 'transporte' && isset($_SESSION['carrito_extra'][$it['id_servicio']])) {
                    $it['asientos'] = $_SESSION['carrito_extra'][$it['id_servicio']]['asientos'];
                    $it['fecha_servicio'] = $_SESSION['carrito_extra'][$it['id_servicio']]['fecha_servicio'];
                }
            }
        }

        echo json_encode(['status' => 'success', 'items' => $items]);
        break;


    case 'crear_reserva':
        require_once(__DIR__ . '/../../models/reserva.php');

        $carrito = $carritoModel->traer_carrito_activo($id_usuario);
        if (!$carrito) {
            echo json_encode(['status'=>'error','message'=>'No hay carrito activo']);
            exit;
        }

        $id_carrito = $carrito['id_carrito'];
        $items = $carritoModel->traer_items($id_carrito);
        $extras = $_SESSION['carrito_extra'] ?? [];

        if (empty($items)) {
            echo json_encode(['status'=>'error','message'=>'El carrito está vacío']);
            exit;
        }

        $reservaModel = new Reserva();
        $total = 0;
        foreach ($items as $it) {
            $total += $it['subtotal'];
        }

        $id_reserva = $reservaModel->crear_reserva($id_usuario, $total, 'pendiente');

        error_log("DEBUG crear_reserva: carrito_extra = " . print_r($extras, true));

        foreach ($items as $it) {
            $id_detalle = $reservaModel->crear_detalle(
                $id_reserva,
                $it['tipo_servicio'],
                $it['cantidad'],
                $it['precio_unitario'],
                $it['subtotal']
            );

            if ($it['tipo_servicio'] === 'hotel') {
                $reservaModel->crear_detalle_hotel(
                    $id_detalle,
                    $it['id_servicio'],
                    $it['fecha_inicio'],
                    $it['fecha_fin'],
                    max(1, (new DateTime($it['fecha_fin']))->diff(new DateTime($it['fecha_inicio']))->days)
                );

            } elseif ($it['tipo_servicio'] === 'tour') {
                $reservaModel->crear_detalle_tour(
                    $id_detalle,
                    $it['id_servicio'],
                    $it['fecha_tour'] ?? null
                );

            } elseif ($it['tipo_servicio'] === 'transporte') {
                $extrasTransporte = $extras[$it['id_servicio']] ?? null;

                if (!isset($extrasTransporte['asientos']) || !is_array($extrasTransporte['asientos']) || empty($extrasTransporte['asientos'])) {
                    error_log("crear_reserva: No hay asientos válidos para transporte id_servicio={$it['id_servicio']}");
                    continue;
                }

                $asientos = $extrasTransporte['asientos'];
                $fecha_servicio = $extrasTransporte['fecha_servicio'] ?? null;

                error_log("Creando detalle transporte: servicio={$it['id_servicio']}, fecha={$fecha_servicio}, asientos=" . print_r($asientos, true));

                $reservaModel->crear_detalle_transporte(
                    $id_detalle,
                    $it['id_servicio'],
                    $asientos,
                    $fecha_servicio,
                    $it['precio_unitario']
                );
            }
        }

        $_SESSION['id_reserva'] = $id_reserva;
        echo json_encode(['status'=>'success','id_reserva'=>$id_reserva,'message'=>'Reserva creada']);
        break;


    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
        break;

}
?>
