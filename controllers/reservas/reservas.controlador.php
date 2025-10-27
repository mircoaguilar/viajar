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
require_once __DIR__ . '/../../models/Notificacion.php';
require __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Método no permitido']);
    exit;
}

$action = $_POST['action'] ?? '';
if ($action !== 'crear_reserva') {
    echo json_encode(['status'=>'error','message'=>'Acción no válida']);
    exit;
}

// Usuario logueado
$userId = $_SESSION['id_usuarios'] ?? null;
if (!$userId) {
    echo json_encode(['status'=>'error','message'=>'No autenticado']);
    exit;
}

$idhab = (int)($_POST['id_habitacion'] ?? 0);
$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$personas = (int)($_POST['personas'] ?? 1);
$metodo_pago = $_POST['metodo_pago'] ?? '';
$monto_pagado = floatval($_POST['monto'] ?? 0);

if (!$idhab || !$checkin || !$checkout) {
    echo json_encode(['status'=>'error','message'=>'Faltan datos']);
    exit;
}

// Fechas y noches
$inicio = new DateTime($checkin);
$fin = new DateTime($checkout);
$noches = max(1, $inicio->diff($fin)->days);

$habModel = new Hotel_Habitaciones();
$stockModel = new Hotel_Habitaciones_Stock();
$reservaModel = new Reserva();
$pagoModel = new Pago();
$facturaModel = new Factura();

$conexion = new Conexion();
$mysqli = $conexion->getConexion();
$mysqli->begin_transaction();

try {
    // Validar stock
    $fecha = clone $inicio;
    $fechas = [];
    while ($fecha < $fin) {
        $f = $fecha->format('Y-m-d');
        $fechas[] = $f;
        $stock = $stockModel->get_stock_fecha($idhab, $f);
        if ($stock === null || $stock < 1) throw new Exception("No hay stock en fecha $f");
        $fecha->modify('+1 day');
    }

    // Calcular precio total
    $precio_base = $habModel->traer_por_id($idhab)['precio_base_noche'] ?? 0;
    $total = $precio_base * $noches * $personas;
    $estadoReserva = 'pendiente';

    // Crear reserva principal
    $id_reserva = $reservaModel->crear_reserva($userId, $total, $estadoReserva);

    // Crear detalle de reserva (tipo hotel)
    $id_detalle_reserva = $reservaModel->crear_detalle($id_reserva, 'hotel', $personas, $precio_base, $total);

    // Detalle específico de hotel con checkin, checkout y noches
    $reservaModel->crear_detalle_hotel($id_detalle_reserva, $idhab, $checkin, $checkout, $noches);

    // Detalle específico de tour (si aplica)
    if (!empty($_POST['id_tour']) && !empty($_POST['fecha_tour'])) {
        $id_tour = (int)$_POST['id_tour'];
        $fecha_tour = $_POST['fecha_tour'];
        $reservaModel->crear_detalle_tour($id_detalle_reserva, $id_tour, $fecha_tour);
    }

    // Actualizar stock temporalmente
    foreach ($fechas as $f) {
        $stockModel->decrementar_stock($idhab, $f, $mysqli);
    }

    // Guardar id_reserva en sesión
    $_SESSION['id_reserva'] = $id_reserva;

    // Crear factura temporal
    $factura_numero = 'F-' . str_pad($id_reserva, 6, '0', STR_PAD_LEFT);
    $facturaModel->crear_factura($factura_numero, $id_reserva);

    // Crear notificación usando la clase Notificacion
    $metadata = ['reserva' => $id_reserva];
    Notificacion::crear(
        $userId,
        "Reserva creada #$id_reserva",
        "Tu reserva fue creada y está pendiente de pago.",
        "reserva",
        $metadata
    );

    $mysqli->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Reserva creada. Redirigiendo a pago...',
        'id_reserva' => $id_reserva
    ]);

} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
