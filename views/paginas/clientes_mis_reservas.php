<?php
require_once __DIR__ . '/../../models/reserva.php';
require_once __DIR__ . '/../../models/hotel.php';
require_once __DIR__ . '/../../models/tour.php';
require_once __DIR__ . '/../../models/pago.php';

$userId = $_SESSION['id_usuarios'] ?? null;
if (!$userId) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$reservaModel = new Reserva();
$hotelModel = new Hotel();
$tourModel = new Tour();
$pagoModel = new Pago();

// Traer reservas del cliente
$reservas = $reservaModel->traerPorUsuario($userId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas | viajAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/viajar/assets/css/mis_reservas.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="mis-reservas-container">
    <h2>Mis Reservas</h2>

    <?php if (!empty($reservas)): ?>
        <table class="tabla-reservas">
            <thead>
                <tr>
                    <th># Reserva</th>
                    <th>Servicios</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva):
                    $detalles = $reservaModel->traerDetallesPorId($reserva['id_reservas']);
                    $servicios = [];
                    $totalPersonas = 0;
                    $ticketEnlace = '/viajar/clientes/ticket.php?id=' . $reserva['id_reservas']; // Enlace único para "Ver Ticket"
                    
                    // Recorrer los detalles para agregar los servicios
                    foreach ($detalles as $det) {
                        if ($det['tipo_servicio'] === 'hotel') {
                            $habitacion = $hotelModel->traer_hoteles_por_usuario($det['rela_servicio']);
                            $hotelNombre = $habitacion['tipo_nombre'] ?? 'Desconocido';
                            $servicios[] = "Hotel: $hotelNombre";
                        } elseif ($det['tipo_servicio'] === 'tour') {
                            $tour = $tourModel->traer_tours_por_usuario($det['rela_servicio']);
                            $tourNombre = $tour['nombre'] ?? 'Desconocido';
                            $servicios[] = "Tour: $tourNombre";
                        }
                    }

                    // Unir todos los servicios
                    $serviciosStr = implode(", ", $servicios);

                    // Estado de la reserva
                    $estadoReserva = ucfirst($reserva['reservas_estado']);
                ?>
                <tr>
                    <td><?= $reserva['id_reservas'] ?></td>
                    <td><?= $serviciosStr ?></td>
                    <td>$<?= number_format($reserva['total'], 0, ',', '.') ?></td>
                    <td><?= $estadoReserva ?></td>
                    <td>
                        <!-- Solo un botón para ver el ticket de la reserva completa -->
                        <a href="<?= $ticketEnlace ?>" class="btn-ver-ticket">Ver Ticket</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tenés reservas aún.</p>
    <?php endif; ?>
</div>
</body>
</html>
