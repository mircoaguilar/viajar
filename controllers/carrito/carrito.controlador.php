<?php
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
        $tipo_servicio = $_POST['tipo_servicio'] ?? null; 
        $id_servicio   = $_POST['id_servicio'] ?? null;
        $cantidad      = (int)($_POST['cantidad'] ?? 1);
        $precio_unitario = (float)($_POST['precio_unitario'] ?? 0);

        if (!$tipo_servicio || !$id_servicio || $cantidad <= 0 || $precio_unitario <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
            exit;
        }

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

        if ($tipo_servicio === 'hotel' && !empty($_POST['checkin']) && !empty($_POST['checkout'])) {
            $fecha_inicio = $_POST['checkin'];
            $fecha_fin    = $_POST['checkout'];
            $noches = (new DateTime($fecha_fin))->diff(new DateTime($fecha_inicio))->days;
            $subtotal *= max($noches, 1);
        }

        if ($tipo_servicio === 'tour' && !empty($_POST['fecha_tour'])) {
            $fecha_inicio = $_POST['fecha_tour'];
            $fecha_fin = null;
        }

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

        echo json_encode(['status' => 'success', 'message' => 'Item agregado al carrito']);
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
        if (empty($items)) {
            echo json_encode(['status'=>'error','message'=>'El carrito está vacío']);
            exit;
        }

        $reservaModel = new Reserva();
        $total = 0;

        foreach ($items as $it) {
            $total += $it['subtotal'];
        }

        $estadoReserva = 'pendiente';
        $id_reserva = $reservaModel->crear_reserva($id_usuario, $total, $estadoReserva);

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
                $fecha_tour = $it['fecha_tour'] ?? null; 
                $reservaModel->crear_detalle_tour(
                    $id_detalle,
                    $it['id_servicio'],  
                    $fecha_tour  
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
