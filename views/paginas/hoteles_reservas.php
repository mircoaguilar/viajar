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

// traer hoteles del proveedor
$hotelesUsuario = $hotelModel->traer_hoteles_por_usuario($id_usuario);

if (!$id_hotel && !empty($hotelesUsuario)) {
    $id_hotel = $hotelesUsuario[0]['id_hotel'];
}

if ($id_hotel && !$hotelModel->verificar_propietario($id_hotel, $id_usuario)) {
    header('Location: index.php?page=proveedores_perfil&message=Acceso denegado.');
    exit;
}

// traer reservas
$reservas = $id_hotel ? $reservaModel->traer_por_hotel($id_hotel) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reservas de Hotel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/mis_reservas.css">
</head>
<body>
<main class="reservas-container">
    <h2>Reservas del Hotel</h2>

    <!-- Filtro de hotel -->
    <?php if (!empty($hotelesUsuario)): ?>
    <form method="get" action="index.php" style="margin-bottom:20px; text-align:center;">
        <input type="hidden" name="page" value="hoteles_reservas">
        <label for="id_hotel"><strong>Seleccionar hotel:</strong></label>
        <select name="id_hotel" id="id_hotel" onchange="this.form.submit()">
            <?php foreach ($hotelesUsuario as $h): ?>
                <option value="<?= $h['id_hotel'] ?>" <?= ($h['id_hotel'] == $id_hotel ? 'selected' : '') ?>>
                    <?= htmlspecialchars($h['hotel_nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <?php endif; ?>

    <!-- Tabla de reservas -->
    <?php if (!empty($reservas)): ?>
    <table class="tabla-reservas">
        <thead>
            <tr>
                <th>ID Reserva</th>
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
                <td><?= htmlspecialchars($r['id_reservas']) ?></td>
                <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
                <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
                <td><?= htmlspecialchars($r['habitacion_nombre']) ?></td>
                <td>$ <?= number_format($r['importe_total'], 2) ?></td>
                <td><?= htmlspecialchars($r['reservas_estado']) ?></td>
                <td>
                    <a href="index.php?page=detalle_reserva&id_reserva=<?= $r['id_reservas'] ?>" class="btn-accion">Ver</a>
                    <?php if ($r['reservas_estado'] == 'pendiente'): ?>
                        <a href="index.php?page=confirmar_reserva&id_reserva=<?= $r['id_reservas'] ?>" class="btn-accion">Confirmar</a>
                        <a href="index.php?page=cancelar_reserva&id_reserva=<?= $r['id_reservas'] ?>" class="btn-accion btn-disabled" onclick="return confirm('¿Seguro que querés cancelar esta reserva?')">Cancelar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="mensaje-vacio">No hay reservas cargadas para este hotel.</p>
    <?php endif; ?>
</main>
</body>
</html>
