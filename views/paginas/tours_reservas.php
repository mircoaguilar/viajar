<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['id_perfiles']) || $_SESSION['id_perfiles'] != 14) {
    header("Location: index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.proveedores.php');
require_once("models/tour.php");
require_once("models/reserva.php");
require_once("models/proveedor.php"); 

$id_usuario = (int)$_SESSION['id_usuarios'];
$id_tour = (int)($_GET['id_tour'] ?? 0);

$tourModel = new Tour();
$reservaModel = new Reserva();
$proveedorModel = new Proveedor();

$proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);
$id_proveedor = (int)($proveedor['id_proveedores'] ?? 0);

$toursUsuario = $tourModel->traer_tours_aprobados_por_usuario($id_usuario);

if (!$id_tour && !empty($toursUsuario)) {
    $id_tour = $toursUsuario[0]['id_tour'];
}

if ($id_tour && !$tourModel->verificar_propietario($id_tour, $id_proveedor)) {
    header('Location: index.php?page=proveedores_perfil&message=Acceso denegado.');
    exit;
}

$reservas = $id_tour ? $reservaModel->traer_por_tour($id_tour) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reservas de Tours</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="assets/css/mis_reservas.css">

</head>
<body>

<main class="reservas-container">

    <h2>Reservas de Tours</h2>

    <?php if (!empty($toursUsuario)): ?>
    <form method="get" action="index.php" class="selector-tour">
        <input type="hidden" name="page" value="tours_reservas">
        <label for="id_tour"><strong>Tour:</strong></label>
        <select name="id_tour" id="id_tour" onchange="this.form.submit()">
            <?php foreach ($toursUsuario as $t): ?>
                <option value="<?= $t['id_tour'] ?>" <?= ($t['id_tour'] == $id_tour ? 'selected' : '') ?>>
                    <?= htmlspecialchars($t['nombre_tour']) ?>
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
                <th>Fecha del tour</th>
                <th>Cantidad</th>
                <th>Precio unit.</th>
                <th>Subtotal</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($reservas as $r): ?>
            <tr>
                <td><?= $r['id_reservas'] ?></td>
                <td><?= htmlspecialchars($r['cliente'] ?? 'No disponible') ?></td>
                <td><?= htmlspecialchars($r['fecha_tour']) ?></td>
                <td><?= $r['cantidad'] ?></td>
                <td>$ <?= number_format($r['precio_unitario'], 2) ?></td>
                <td>$ <?= number_format($r['subtotal'], 2) ?></td>

                <td class="estado <?= strtolower($r['reservas_estado']) ?>">
                    <?= htmlspecialchars($r['reservas_estado']) ?>
                </td>

                <td class="acciones">

                    <button 
                        class="btn-accion btn-ver btn-ver-reserva"
                        data-id="<?= $r['id_reservas'] ?>">
                        Ver
                    </button>

                    <?php if (in_array($r['detalle_tour_estado'], ['confirmada','pendiente_pago'])): ?>
                        <a href="controllers/reservas/cancelar_detalle_tour.php?id_detalle=<?= $r['id_detalle_tour'] ?>"
                        class="btn-accion btn-cancelar"
                        onclick="return confirm('¿Seguro que querés cancelar este tour?')">
                            Cancelar
                        </a>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p class="mensaje-vacio">No hay reservas para este tour.</p>
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

<script src="assets/js/mis_reservas_tours.js"></script>

</body>
</html>
