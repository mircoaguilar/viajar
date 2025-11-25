<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/hotel_habitaciones.php';
require_once __DIR__ . '/../../models/hotel_habitaciones_stock.php';
require_once __DIR__ . '/../../models/reserva.php';
require_once __DIR__ . '/../../models/pago.php';
require_once __DIR__ . '/../../models/factura.php';
require_once __DIR__ . '/../../models/usuarios.php';
require_once __DIR__ . '/../../models/conexion.php';

$action = $_REQUEST['action'] ?? '';

if (!$action) {
    echo json_encode(['status'=>'error','message'=>'Acción no especificada']);
    exit;
}

$reservaModel = new Reserva();
$usuarioModel = new Usuario();

switch ($action) {

    case 'ver':
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Método no permitido (usar GET)'
            ]);
            exit;
        }

        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status'=>'error','message'=>'ID inválido']);
            exit;
        }

        $reservaModel = new Reserva();
        $usuarioModel = new Usuario();

        $reserva = $reservaModel->ver_reserva_completa($id);

        if (!$reserva) {
            echo json_encode([
                'status'=>'error',
                'message'=>'Reserva no encontrada'
            ]);
            exit;
        }

        $userArray = $usuarioModel->traer_usuarios_por_id($reserva['rela_usuarios']);
        $user = !empty($userArray) ? $userArray[0] : null;

        if ($user && !empty($user['rela_personas'])) {
            $conexion = new Conexion();
            $persona = $conexion->consultar("SELECT personas_nombre, personas_apellido FROM personas WHERE id_personas = ".$user['rela_personas']);
            $reserva['cliente'] = !empty($persona) ? $persona[0]['personas_nombre'].' '.$persona[0]['personas_apellido'] : 'No disponible';
        } else {
            $reserva['cliente'] = 'No disponible';
        }


        echo json_encode([
            'status'=>'success',
            'data'=>$reserva
        ]);
        exit;
    break;

    case 'ver_tour':
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['status'=>'error','message'=>'Método no permitido']);
            exit;
        }

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status'=>'error','message'=>'ID inválido']);
            exit;
        }

        $reserva = $reservaModel->ver_reserva_tour($id);
        if (!$reserva) {
            echo json_encode(['status'=>'error','message'=>'Reserva no encontrada']);
            exit;
        }

        $userArray = $usuarioModel->traer_usuarios_por_id($reserva[0]['rela_usuarios'] ?? 0);
        $user = !empty($userArray) ? $userArray[0] : null;
        if ($user && !empty($user['rela_personas'])) {
            $conexion = new Conexion();
            $persona = $conexion->consultar("SELECT personas_nombre, personas_apellido FROM personas WHERE id_personas = ".$user['rela_personas']);
            $clienteNombre = !empty($persona) ? $persona[0]['personas_nombre'].' '.$persona[0]['personas_apellido'] : 'No disponible';
        } else {
            $clienteNombre = 'No disponible';
        }

        $detalles = [];
        foreach ($reserva as $r) {
            $detalles[] = [
                'id_detalle_tour' => $r['id_detalle_tour'] ?? null,
                'fecha_tour' => $r['fecha_tour'] ?? '',
                'nombre_tour' => $r['nombre_tour'] ?? '',
                'tipo_servicio' => $r['tipo_servicio'] ?? 'tour',
                'cantidad' => $r['cantidad'] ?? 1,
                'precio_unitario' => $r['precio_unitario'] ?? 0,
                'subtotal' => $r['importe_total'] ?? 0,
                'estado' => $r['reservas_estado'] ?? $r['estado'] ?? ''
            ];
        }

        $reservaObj = $reserva[0];
        $reservaObj['cliente'] = $clienteNombre;
        $reservaObj['detalles'] = $detalles;

        echo json_encode([
            'status'=>'success',
            'data'=>$reservaObj
        ]);
        exit;
    break;


    case 'crear_reserva':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status'=>'error','message'=>'Método no permitido']);
            exit;
        }

        $userId = $_SESSION['id_usuarios'] ?? null;
        if (!$userId) {
            echo json_encode(['status'=>'error','message'=>'No autenticado']);
            exit;
        }

        $idhab = (int)($_POST['id_habitacion'] ?? 0);
        $checkin = $_POST['checkin'] ?? '';
        $checkout = $_POST['checkout'] ?? '';
        $personas = (int)($_POST['personas'] ?? 1);

        if (!$idhab || !$checkin || !$checkout) {
            echo json_encode(['status'=>'error','message'=>'Faltan datos']);
            exit;
        }

        $inicio = new DateTime($checkin);
        $fin = new DateTime($checkout);
        $noches = max(1, $inicio->diff($fin)->days);

        $habModel = new Hotel_Habitaciones();
        $stockModel = new Hotel_Habitaciones_Stock();
        $pagoModel = new Pago();
        $facturaModel = new Factura();

        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $mysqli->begin_transaction();

        try {
            $fecha = clone $inicio;
            $fechas = [];
            while ($fecha < $fin) {
                $f = $fecha->format('Y-m-d');
                $fechas[] = $f;
                $stock = $stockModel->get_stock_fecha($idhab, $f);
                if ($stock === null || $stock < 1) throw new Exception("No hay stock en fecha $f");
                $fecha->modify('+1 day');
            }

            $precio_base = $habModel->traer_por_id($idhab)['precio_base_noche'] ?? 0;
            $total = $precio_base * $noches * $personas;

            $estadoReserva = 'pendiente';
            $id_reserva = $reservaModel->crear_reserva($userId, $total, $estadoReserva);
            $id_detalle_reserva = $reservaModel->crear_detalle($id_reserva, 'hotel', $personas, $precio_base, $total);
            $reservaModel->crear_detalle_hotel($id_detalle_reserva, $idhab, $checkin, $checkout, $noches);

            if (!empty($_POST['id_tour']) && !empty($_POST['fecha_tour'])) {
                $id_tour = intval($_POST['id_tour']);
                $fecha_tour = $_POST['fecha_tour'];
                $reservaModel->crear_detalle_tour($id_detalle_reserva, $id_tour, $fecha_tour);
            }

            foreach ($fechas as $f) {
                $stockModel->decrementar_stock($idhab, $f, $mysqli);
            }

            $factura_numero = 'F-' . str_pad($id_reserva, 6, '0', STR_PAD_LEFT);
            $facturaModel->crear_factura($factura_numero, $id_reserva);

            $mysqli->commit();

            echo json_encode([
                'status'=>'success',
                'message'=>'Reserva creada. Redirigiendo a pago...',
                'id_reserva'=>$id_reserva
            ]);

        } catch (Exception $e) {
            $mysqli->rollback();
            echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
        }

    break;

    default:
        echo json_encode(['status'=>'error','message'=>'Acción no válida']);
        exit;
}
