<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

session_start();
require_once(__DIR__ . '/../../models/pago.php');
require_once(__DIR__ . '/../../models/reserva.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\SDK;

// Configurar token de prueba de Mercado Pago
SDK::setAccessToken('APP_USR-4300521315194194-101414-8cb3c58e3049ab571719141b77482b33-2925165946');

if (!isset($_SESSION['id_usuarios']) || !isset($_SESSION['id_reserva'])) {
    die("Error: Debes iniciar sesión y tener una reserva activa.");
}

$id_usuario = $_SESSION['id_usuarios'];
$id_reserva = $_SESSION['id_reserva'];

// Traer la reserva
$reservaModel = new Reserva();
$reserva = $reservaModel->traerPorId($id_reserva);

if (empty($reserva)) {
    die("Error: No se encontró la reserva.");
}

$monto_total = (float)($reserva['total'] ?? 0);

if ($monto_total <= 0) {
    die("Error: El monto total de la reserva es inválido.");
}

// Crear pago pendiente en DB
$pago = new Pago();
$id_pago = $pago->crear_pago($id_reserva, $monto_total, 'pendiente', 8, 3); 

// Crear preferencia de Mercado Pago
$preference = new Preference();

$item = new Item();
$item->title = "Reserva ViajAR #$id_reserva";
$item->quantity = 1;
$item->unit_price = $monto_total;
$item->currency_id = "ARS";

$preference->items = [$item];

// URL pública del servidor (ngrok)
$ngrok_url = "https://rosette-gynomonoecious-aydin.ngrok-free.dev"; 

// Usamos id_pago en las back_urls para identificar el pago
$preference->back_urls = [
    "success" => "$ngrok_url/controllers/pagos/mercado_pago_exito.php?id_pago=$id_pago",
    "failure" => "$ngrok_url/controllers/pagos/mercado_pago_falla.php?id_pago=$id_pago",
    "pending" => "$ngrok_url/controllers/pagos/mercado_pago_pendiente.php?id_pago=$id_pago"
];

// Auto retorno cuando el pago esté aprobado
$preference->auto_return = "approved";

// Guardar preferencia
try {
    $preference->save();
    if (!isset($preference->id)) {
        die("Error: No se pudo crear la preferencia de pago.");
    }
} catch (\Exception $e) {
    die("Error creando preferencia: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Redirigiendo a Mercado Pago...</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <h2>Redirigiendo al pago...</h2>

    <script>
        const mp = new MercadoPago('APP_USR-9e7b095c-3917-47b4-9966-c618dc0d432b', {
            locale: 'es-AR'
        });

        mp.checkout({
            preference: {
                id: '<?php echo htmlspecialchars($preference->id); ?>'
            },
            autoOpen: true
        });
    </script>
</body>
</html>
