<?php
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 5) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/transporte.php');
require_once('models/reserva.php'); 
require_once('models/viaje.php');
require_once('models/proveedor.php');

$id_usuario = (int)$_SESSION['id_usuarios'];

$proveedorModel = new Proveedor();
$proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);
$id_proveedor = (int)($proveedor['id_proveedores'] ?? 0);

$id_transporte = (int)($_GET['id_transporte'] ?? 0);

$transporteModel = new Transporte();
$reservaModel = new Reserva();
$viajeModel = new Viaje();

$transportesUsuario = $transporteModel->traer_transportes_por_usuario($id_usuario);

if (!$id_transporte && !empty($transportesUsuario)) {
    $id_transporte = (int)$transportesUsuario[0]['id_transporte'];
}

if ($id_transporte && method_exists($transporteModel, 'verificar_propietario')) {
    if (!$transporteModel->verificar_propietario($id_transporte, $id_proveedor)) {
        header('Location: index.php?page=proveedores_perfil&message=Acceso denegado.');
        exit;
    }
}

$reservas = $id_transporte ? $reservaModel->traer_por_transporte($id_transporte) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reservas de Transporte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="assets/css/mis_reservas.css">
</head>
<body>

<main class="reservas-container">
    <h2>Reservas - Mis Transportes</h2>

    <?php if (!empty($transportesUsuario)): ?>
    <form method="get" action="index.php" class="selector-transporte">
        <input type="hidden" name="page" value="transportes_reservas">
        <label for="id_transporte"><strong>Transporte:</strong></label>
        <select name="id_transporte" id="id_transporte" onchange="this.form.submit()">
            <?php foreach ($transportesUsuario as $t): ?>
                <option value="<?= $t['id_transporte'] ?>" <?= ($t['id_transporte'] == $id_transporte ? 'selected' : '') ?>>
                    <?= htmlspecialchars($t['nombre_servicio']) ?> (Mat.: <?= htmlspecialchars($t['transporte_matricula']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <?php else: ?>
        <p>No tenés transportes registrados aún.</p>
    <?php endif; ?>

    <?php if (!empty($reservas)): ?>
    <table class="tabla-reservas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha servicio</th>
                <th>Viaje</th>
                <th>Asiento</th>
                <th>Importe</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($reservas as $r): 
            $cliente = $r['cliente'] ?? ($r['nombre_cliente'] ?? 'No disponible');
            $fecha_servicio = $r['fecha_servicio'] ?? ($r['fecha'] ?? '');
            $viaje_info = $r['viaje_info'] ?? ($r['trayecto'] ?? '');
            $asiento = '';
            if (!empty($r['piso']) || !empty($r['numero_asiento'])) {
                $asiento = 'Piso ' . ($r['piso'] ?? '?') . ' - Asiento ' . ($r['numero_asiento'] ?? '?');
            } elseif (!empty($r['asiento'])) {
                $asiento = $r['asiento'];
            }
            $importe = $r['importe_total'] ?? $r['subtotal'] ?? $r['total'] ?? 0;
        ?>
            <tr>
                <td><?= htmlspecialchars($r['id_reservas'] ?? $r['id']) ?></td>
                <td><?= htmlspecialchars($cliente) ?></td>
                <td><?= htmlspecialchars($fecha_servicio) ?></td>
                <td><?= htmlspecialchars($viaje_info) ?></td>
                <td><?= htmlspecialchars($asiento) ?></td>
                <td>$ <?= number_format((float)$importe, 2, ',', '.') ?></td>
                <td class="estado <?= strtolower($r['reservas_estado'] ?? ($r['estado'] ?? '')) ?>">
                    <?= htmlspecialchars($r['reservas_estado'] ?? ($r['estado'] ?? '')) ?>
                </td>
                <td class="acciones">
                    <button 
                        class="btn-accion btn-ver btn-ver-reserva"
                        data-id="<?= htmlspecialchars($r['id_reservas'] ?? $r['id']) ?>">
                        Ver
                    </button>

                    <?php if (in_array($r['detalle_transporte_estado'], ['confirmada','pendiente_pago'])): ?>
                        <a href="controllers/reservas/cancelar_detalle_transporte.php?id_detalle=<?= $r['id_detalle_transporte'] ?>"
                        class="btn-accion btn-cancelar"
                        onclick="return confirm('¿Seguro que querés cancelar este servicio de transporte?')">
                            Cancelar
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p class="mensaje-vacio">No hay reservas para este transporte seleccionado.</p>
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

<script src="assets/js/mis_reservas_transporte.js"></script>
</body>
</html>
