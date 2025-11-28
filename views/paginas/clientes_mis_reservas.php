<?php
require_once __DIR__ . '/../../models/reserva.php';
require_once __DIR__ . '/../../models/hotel.php';
require_once __DIR__ . '/../../models/tour.php';
require_once __DIR__ . '/../../models/pago.php';

session_start();

$userId = $_SESSION['id_usuarios'] ?? null;
if (!$userId) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$reservaModel = new Reserva();
$hotelModel = new Hotel();
$tourModel = new Tour();
$pagoModel = new Pago();

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
                    <th>Reserva</th>
                    <th>Servicio</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($reservas as $reserva):
                $detalles = $reservaModel->traerDetallesCompletos($reserva['id_reservas']);
                $ticketEnlace = '/viajar/views/paginas/clientes_ticket.php?id=' . $reserva['id_reservas'];
                foreach ($detalles as $det):
                    $servicioNombre = '';
                    switch ($det['tipo_servicio']) {
                        case 'hotel':
                            $servicioNombre = "Hotel: " . ($det['hotel']['hotel_nombre'] ?? 'Desconocido');
                            $estadoDetalle = ucfirst($det['hotel']['estado'] ?? 'Pendiente');
                            break;

                        case 'tour':
                            $servicioNombre = "Tour: " . ($det['tour']['tour_nombre'] ?? 'Desconocido');
                            $estadoDetalle = ucfirst($det['tour']['estado'] ?? 'Pendiente');
                            break;

                        case 'transporte':
                            $asientos = [];
                            if (!empty($det['transporte']) && is_array($det['transporte'])) {
                                foreach ($det['transporte'] as $t) {
                                    $asientos[] = "Piso {$t['piso']} - Asiento {$t['numero_asiento']}";
                                }
                            }
                            $servicioNombre = "Transporte: " . implode(', ', $asientos);
                            $transporte = $det['transporte'][0] ?? null;
                            $estadoDetalle = ucfirst($transporte['estado'] ?? 'Pendiente');
                            break;

                        default:
                            $servicioNombre = "Servicio desconocido";
                            $estadoDetalle = 'Pendiente';
                            break;
                    }
            ?>
                <tr>
                    <td><?= $reserva['id_reservas'] ?></td>
                    <td><?= $servicioNombre ?></td>
                    <td>$<?= number_format($det['subtotal'] ?? $reserva['total'], 0, ',', '.') ?></td>
                    <td><?= $estadoDetalle ?></td>
                    <td class="celda-acciones">
                        <a href="<?= $ticketEnlace ?>" class="btn-ver-ticket">Ver Ticket</a>
                        <?php if ($det['activo'] == '1'): ?>
                            <button class="btn-cancelar" data-id="<?= $det['id_detalle_reserva'] ?>">Cancelar</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tenés reservas aún.</p>
    <?php endif; ?>

    <div id="modal-cancelar">
        <div class="modal-content">
            <button type="button" class="modal-close">&times;</button>
            <h3>Cancelar Servicio</h3>
            <form id="form-cancelar">
                <input type="hidden" name="id_detalle_reserva" id="id_reserva">
                <label for="motivo">Motivo de cancelación:</label>
                <select name="motivo" id="motivo" required>
                    <option value="">Seleccione un motivo</option>
                    <option value="1">Problemas de salud</option>
                    <option value="2">Clima adverso</option>
                    <option value="3">Cambio de fechas</option>
                    <option value="4">Problemas de transporte</option>
                    <option value="5">Cancelación por proveedor</option>
                    <option value="6">Motivos personales</option>
                    <option value="7">Problemas financieros</option>
                    <option value="8">Error en la reserva</option>
                    <option value="9">Otros</option>
                </select>
                <label for="comentario">Comentario (opcional):</label>
                <textarea name="comentario" id="comentario"></textarea>
                <button type="submit">Confirmar Cancelación</button>
            </form>
        </div>
    </div>
</div>
<script src="/viajar/assets/js/mis_reservas_clientes.js"></script>
</body>
</html>
