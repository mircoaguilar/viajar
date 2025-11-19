<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || $_SESSION['id_perfiles'] != 3) {
    header("Location: index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.proveedores.php');
require_once("models/hotel_dashboard.php");

$dash = new HotelDashboard();

$habitaciones_total = $dash->contar_habitaciones();
$ocupacion_actual = $dash->ocupacion_actual();
$reservas_mes       = $dash->contar_reservas_confirmadas(); 
$ingresos_mes       = $dash->ingresosDelMes();

$ultimasReservas = $dash->top_habitaciones_mas_reservadas();

$reservasPorMesRaw = $dash->reservas_por_mes(date('Y'));
$reservasPorMes = [];
foreach ($reservasPorMesRaw as $r) {
    $mes = str_pad($r['mes'], 2, '0', STR_PAD_LEFT);
    $reservasPorMes[] = [
        'mes' => date('Y') . '-' . $mes,
        'total' => (int)$r['total']
    ];
}

$ocupacionPorTipoRaw = $dash->ocupacion_por_hotel();
$ocupacionPorTipo = [];
foreach ($ocupacionPorTipoRaw as $h) {
    $ocupacionPorTipo[] = [
        'tipo' => $h['hotel_nombre'],
        'ocupadas' => (int)$h['total']
    ];
}
?>

<div class="contenido-dashboard">
    <h1>Mi Panel de Hotel</h1>

    <div class="dashboard-metrics">
        <div class="card">
            <h2>Habitaciones</h2>
            <p><?= $habitaciones_total ?></p>
        </div>
        <div class="card">
            <h2>Ocupación actual</h2>
            <p><?= $ocupacion_actual ?> habitaciones ocupadas</p>
        </div>
        <div class="card">
            <h2>Reservas del mes</h2>
            <p><?= $reservas_mes ?></p>
        </div>
        <div class="card">
            <h2>Ingresos del mes</h2>
            <p>$<?= number_format($ingresos_mes,2,',','.') ?></p>
        </div>
    </div>

    <section class="dashboard-section">
        <h2>Top habitaciones más reservadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Hotel</th>
                    <th>Habitación</th>
                    <th>Total reservas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ultimasReservas as $r): ?>
                    <tr>
                        <td><?= $r['hotel_nombre'] ?></td>
                        <td><?= $r['habitacion_nombre'] ?></td>
                        <td><?= $r['total'] ?? 0 ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="dashboard-section">
        <h2>Reservas por mes</h2>
        <canvas id="chartReservas" 
                data-reservas='<?= json_encode($reservasPorMes) ?>'></canvas>
    </section>

    <section class="dashboard-section">
        <h2>Total de reservas por hotel</h2>
        <canvas id="chartOcupacion" 
                data-tipos='<?= json_encode($ocupacionPorTipo) ?>'></canvas>
    </section>
</div>

<link rel="stylesheet" href="assets/css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/viajar/assets/js/dashboard.hoteles.js"></script>
