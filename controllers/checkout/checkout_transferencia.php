<?php
require __DIR__ . '/../../vendor/autoload.php';

session_start();

require_once __DIR__ . '/../../models/carrito.php';
require_once __DIR__ . '/../../models/carritoitem.php';

if (!isset($_SESSION['id_usuarios'])) {
    die("Debes iniciar sesión para pagar.");
}

MercadoPago\SDK::setAccessToken("APP_USR-4300521315194194-101414-8cb3c58e3049ab571719141b77482b33-2925165946");

$carritoModel = new Carrito();
$carrito = $carritoModel->traer_carrito_activo($_SESSION['id_usuarios']);

if (!$carrito) {
    die("Carrito vacío.");
}

$id_carrito = $carrito['id_carrito'];

$itemModel = new CarritoItem();
$items = $carritoModel->traer_items($id_carrito);

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
    "success" => "http://localhost/viajar/checkout/success.php",
    "failure" => "http://localhost/viajar/checkout/failure.php",
    "pending" => "http://localhost/viajar/checkout/pending.php"
];

$preference->auto_return = "approved";

$preference->save();

header("Location: " . $preference->init_point);
exit;
