<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || $_SESSION['id_perfiles'] != 5) {
    header("Location: index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.proveedores.php');
require_once("models/transporte_dashboard.php");

$dash = new TransporteDashboard();
$id_usuario = $_SESSION['id_usuarios'];

$total_transportes = $dash->contar_transportes($id_usuario);
$total_rutas = $dash->contar_rutas($id_usuario);
$viajes_proximos = $dash->contar_viajes_proximos($id_usuario);
$reservas_mes = $dash->contar_reservas_confirmadas_mes($id_usuario);
$ingresos_mes = $dash->ingresos_mes($id_usuario);

$topViajes = $dash->top_viajes_mas_reservados($id_usuario);

$reservasPorMesRaw = $dash->reservas_por_mes($id_usuario);
$reservasPorMes = [];
foreach ($reservasPorMesRaw as $r) {
    $mes = str_pad($r['mes'], 2, '0', STR_PAD_LEFT);
    $reservasPorMes[] = [
        'mes' => date('Y') . '-' . $mes,
        'total' => (int)$r['total']
    ];
}

$ocupacionPorTipoRaw = $dash->ocupacion_por_transporte($id_usuario);
$ocupacionPorTipo = [];
foreach ($ocupacionPorTipoRaw as $t) {
    $ocupacionPorTipo[] = [
        'tipo' => $t['transporte_nombre'],
        'ocupadas' => (int)$t['total_ocupados']
    ];
}
?>

<div class="contenido-dashboard">
    <h1>Mi Panel de Transporte</h1>

    <div class="dashboard-metrics">
        <div class="card">
            <h2>Transportes</h2>
            <p><?= $total_transportes ?></p>
        </div>
        <div class="card">
            <h2>Rutas</h2>
            <p><?= $total_rutas ?></p>
        </div>
        <div class="card">
            <h2>Viajes próximos</h2>
            <p><?= $viajes_proximos ?></p>
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
        <h2>Top viajes más reservados</h2>
        <table>
            <thead>
                <tr>
                    <th>Transporte</th>
                    <th>Ruta</th>
                    <th>Total reservas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($topViajes as $v): ?>
                    <tr>
                        <td><?= $v['transporte_nombre'] ?></td>
                        <td><?= $v['ruta_trayecto'] ?></td>
                        <td><?= $v['total'] ?? 0 ?></td>
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
        <h2>Total de reservas por transporte</h2>
        <canvas id="chartOcupacion" 
                data-tipos='<?= json_encode($ocupacionPorTipo) ?>'></canvas>
    </section>

</div>

<link rel="stylesheet" href="assets/css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/js/dashboard.transportes.js"></script>
