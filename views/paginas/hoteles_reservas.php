<?php
if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,3])) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/hotel.php');
require_once('models/reserva.php');

$id_usuario = $_SESSION['id_usuarios'];
$id_hotel = (int)($_GET['id_hotel'] ?? 0);

$hotelModel = new Hotel();
$reservaModel = new Reserva();

$hotelesUsuario = $hotelModel->traer_hoteles($id_usuario);

if (!$id_hotel && !empty($hotelesUsuario)) {
    $id_hotel = $hotelesUsuario[0]['id_hotel'];
}

if ($id_hotel && !$hotelModel->verificar_propietario($id_hotel, $id_usuario)) {
    header('Location: index.php?page=proveedores_perfil&message=Acceso denegado.');
    exit;
}

$reservas = $id_hotel ? $reservaModel->traer_por_hotel($id_hotel) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reservas del Hotel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="assets/css/mis_reservas.css">

</head>
<body>

<main class="reservas-container">

    <h2>Reservas del Hotel</h2>

    <?php if (!empty($hotelesUsuario)): ?>
    <form method="get" action="index.php" class="selector-hotel">
        <input type="hidden" name="page" value="hoteles_reservas">
        <label for="id_hotel"><strong>Hotel:</strong></label>
        <select name="id_hotel" id="id_hotel" onchange="this.form.submit()">
            <?php foreach ($hotelesUsuario as $h): ?>
                <option value="<?= $h['id_hotel'] ?>" <?= ($h['id_hotel'] == $id_hotel ? 'selected' : '') ?>>
                    <?= htmlspecialchars($h['hotel_nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <?php endif; ?>

    <?php if (!empty($reservas)): ?>
    <table class="tabla-reservas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Habitación</th>
                <th>Importe</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($reservas as $r): ?>
            <tr>
                <td><?= $r['id_reservas'] ?></td>
                <td><?= htmlspecialchars($r['cliente'] ?? 'No disponible') ?></td>
                <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
                <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
                <td><?= htmlspecialchars($r['habitacion_nombre']) ?></td>
                <td>$ <?= number_format($r['importe_total'], 2) ?></td>

                <td class="estado <?= strtolower($r['reservas_estado']) ?>">
                    <?= htmlspecialchars($r['reservas_estado']) ?>
                </td>

                <td class="acciones">

                    <button 
                        class="btn-accion btn-ver btn-ver-reserva"
                        data-id="<?= $r['id_reservas'] ?>">
                        Ver
                    </button>

                    <?php if (in_array($r['detalle_hotel_estado'], ['confirmada','pendiente_pago'])): ?>
                        <a href="controllers/reservas/cancelar_detalle_hotel.php?id_detalle=<?= $r['id_detalle_hotel'] ?>"
                        class="btn-accion btn-cancelar"
                        onclick="return confirm('¿Seguro que querés cancelar este servicio de hotel?')">
                            Cancelar
                        </a>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p class="mensaje-vacio">No hay reservas para este hotel.</p>
    <?php endif; ?>

</main>

<div class="modal-container" id="modalVerReserva" style="display: none;">
  <div class="modal-detalle">

    <div class="modal-header-custom">
        <h5 class="modal-title-custom">Detalle de Reserva</h5>
        <button type="button" class="btn-cerrar" id="cerrarModalBtn">&times;</button>
    </div>

    <div class="modal-body-custom">

        <h6>Datos de la reserva</h6>
        <div id="info-reserva"></div>

        <hr>

        <h6>Servicios incluidos</h6>
        <table class="tabla-servicios">
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Cant.</th>
              <th>Precio unit.</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody id="tabla-detalles"></tbody>
        </table>

        <div id="detalles-extra"></div>

    </div>

    <div class="modal-footer-custom">
        <button class="btn-secondary" id="cerrarModalBtnFooter">Cerrar</button>
    </div>

  </div>
</div>
<script src="assets/js/mis_reservas.js"></script>

</body>
</html>