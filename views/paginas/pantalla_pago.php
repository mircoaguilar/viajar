<?php
session_start();
$logueado = isset($_SESSION['id_usuarios']);

// Obtener parámetros
$idhab = (int)($_GET['idhab'] ?? 0);
$idhotel = (int)($_GET['idhotel'] ?? 0);
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';
$personas = (int)($_GET['personas'] ?? 1);

require_once __DIR__ . '/../../models/hotel.php';
require_once __DIR__ . '/../../models/hotel_habitaciones.php';

// Traer datos del hotel y habitación
$hotelModel = new Hotel();
$habitacionModel = new Hotel_Habitaciones();

$hotel = $hotelModel->traer_hotel($idhotel)[0] ?? null;
$habitaciones = $habitacionModel->traer_por_hotel($idhotel);
$habitacion = null;
foreach ($habitaciones as $hab) {
    if ($hab['id_hotel_habitacion'] == $idhab) {
        $habitacion = $hab;
        break;
    }
}

// Calcular noches y total
$noches = 1;
if (!empty($checkin) && !empty($checkout)) {
    $inicio = new DateTime($checkin);
    $fin = new DateTime($checkout);
    $diff = $inicio->diff($fin);
    $noches = max(1, $diff->days);
}
$total = ($habitacion['precio_base_noche'] ?? 0) * $noches * $personas;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pantalla de Pago | viajAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/viajar/assets/css/pago.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="pago-container">
    <h2>Proceso de Pago</h2>

    <?php if ($hotel && $habitacion): ?>
        <div class="resumen-reserva">
            <h3>Resumen de tu reserva</h3>
            <p><b>Hotel:</b> <?= htmlspecialchars($hotel['hotel_nombre']) ?></p>
            <p><b>Habitación:</b> <?= htmlspecialchars($habitacion['tipo_nombre']) ?></p>
            <p><b>Check-in:</b> <?= htmlspecialchars($checkin) ?></p>
            <p><b>Check-out:</b> <?= htmlspecialchars($checkout) ?></p>
            <p><b>Personas:</b> <?= $personas ?></p>
            <p><b>Noches:</b> <?= $noches ?></p>
            <p><b>Total:</b> $<?= number_format($total,0,',','.') ?></p>
        </div>
    <?php endif; ?>

    <?php if ($logueado): ?>
        <form id="form-pago"
            data-idhab="<?= $idhab ?>"
            data-checkin="<?= htmlspecialchars($checkin) ?>"
            data-checkout="<?= htmlspecialchars($checkout) ?>"
            data-personas="<?= $personas ?>"
            data-total="<?= $total ?>">

            <div class="opciones-pago">
                <label>
                    <input type="radio" name="metodo_pago" value="tarjeta" required>
                    Tarjeta de crédito / débito
                </label>
                <label>
                    <input type="radio" name="metodo_pago" value="transferencia">
                    Transferencia bancaria
                </label>
            </div>

            <!-- Sección tarjeta -->
            <div id="tarjeta-section" class="seccion-oculta">
                <h3>Datos de la tarjeta</h3>
                <input type="text" name="numero_tarjeta" placeholder="Número de tarjeta" required>
                <input type="text" name="nombre_tarjeta" placeholder="Nombre en la tarjeta" required>
                <input type="text" name="expiracion" placeholder="MM/AA" required>
                <input type="text" name="cvv" placeholder="CVV" required>
            </div>

            <!-- Sección transferencia -->
            <div id="transferencia-section" class="seccion-oculta">
                <h3>Datos para transferencia</h3>
                <p><b>Banco:</b> Banco Nación</p>
                <p><b>CBU:</b> 1234567890123456789012</p>
                <p><b>Alias:</b> viajAR.transfer</p>
                <label>Subir comprobante:</label>
                <input type="file" name="comprobante" id="comprobante" accept="image/*,application/pdf">
            </div>

            <button type="submit" class="btn-pagar">Pagar</button>
        </form>
    <?php endif; ?>
</div>

<script>
    const usuarioLogueado = <?= $logueado ? 'true' : 'false' ?>;
</script>
<script src="/viajar/assets/js/pago.js"></script>
</body>
</html>
