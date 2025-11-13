<?php
require __DIR__ . '/../../vendor/autoload.php';
session_start();
require_once __DIR__ . '/../../models/carrito.php';
require_once __DIR__ . '/../../models/carritoitem.php';
require_once __DIR__ . '/../../models/pago.php';
require_once __DIR__ . '/../../models/reserva.php';

if (!isset($_SESSION['id_usuarios'])) die("Debes iniciar sesión para pagar.");

MercadoPago\SDK::setAccessToken("APP_USR-...");

$carritoModel = new Carrito();
$carrito = $carritoModel->traer_carrito_activo($_SESSION['id_usuarios']);
if (!$carrito) die("Carrito vacío.");

$id_carrito = $carrito['id_carrito'];
$itemModel = new CarritoItem();
$items = $carritoModel->traer_items($id_carrito);

$reservaModel = new Reserva();
$total = array_sum(array_map(fn($it) => $it['cantidad'] * $it['precio_unitario'], $items));
$id_reserva = $reservaModel->crear_reserva($_SESSION['id_usuarios'], $total, 'pendiente');

$pagoModel = new Pago();
$id_pago = $pagoModel->crear_pago($id_reserva, $total, 'pendiente', 8, 3); 

$preference = new MercadoPago\Preference();
$preference_items = [];

foreach ($items as $it) {
    $mpItem = new MercadoPago\Item();
    $mpItem->title = ucfirst($it['tipo_servicio']) . " - " . ($it['nombre_servicio'] ?? $it['id_servicio']);
    $mpItem->quantity = $it['cantidad'];
    $mpItem->unit_price = (float)$it['precio_unitario'];
    $preference_items[] = $mpItem;
}

$preference->items = $preference_items;

$preference->back_urls = [
    "success" => "http://localhost/viajar/checkout/success.php?id_pago=$id_pago",
    "failure" => "http://localhost/viajar/checkout/failure.php",
    "pending" => "http://localhost/viajar/checkout/pending.php"
];

$preference->auto_return = "approved";
$preference->save();

header("Location: " . $preference->init_point);
exit;

