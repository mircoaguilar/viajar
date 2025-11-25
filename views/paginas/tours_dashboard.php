<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || $_SESSION['id_perfiles'] != 14) {
    header("Location: index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.proveedores.php');
require_once("models/tours_dashboard.php");

$dash = new TourDashboard();
$id_usuario = $_SESSION['id_usuarios'];

$totalTours          = $dash->contar_tours($id_usuario);
$toursConReservas    = $dash->tours_con_reservas($id_usuario);
$reservasMes         = $dash->reservas_mes($id_usuario);
$ingresosMes         = $dash->ingresos_mes($id_usuario);

$topTours            = $dash->top_tours_mas_reservados($id_usuario);
$reservasPorMesRaw = $dash->reservas_por_mes($id_usuario, date('Y'));
$reservasPorMes = [];
foreach ($reservasPorMesRaw as $r) {
    $mes = str_pad($r['mes'], 2, '0', STR_PAD_LEFT);
    $reservasPorMes[] = [
        'mes' => date('Y') . '-' . $mes,
        'total' => (int)$r['total']
    ];
}


$reservasPorTourRaw = $dash->reservas_por_tour($id_usuario);
$reservasPorTour = [];
foreach($reservasPorTourRaw as $t){
    $reservasPorTour[] = [
        'tour' => $t['tour'],
        'total' => $t['total']
    ];
}
?>

<div class="contenido-dashboard">
    <h1>Mi Panel de Tours</h1>

    <div class="dashboard-metrics">
        <div class="card">
            <h2>Total de Tours</h2>
            <p><?= $totalTours ?></p>
        </div>
        <div class="card">
            <h2>Tours con reservas</h2>
            <p><?= $toursConReservas ?></p>
        </div>
        <div class="card">
            <h2>Reservas del mes</h2>
            <p><?= $reservasMes ?></p>
        </div>
        <div class="card">
            <h2>Ingresos del mes</h2>
            <p>$<?= number_format($ingresosMes,2,',','.') ?></p>
        </div>
    </div>

    <section class="dashboard-section">
        <h2>Top tours más reservados</h2>
        <table>
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Total reservas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($topTours as $t): ?>
                    <tr>
                        <td><?= $t['nombre_tour'] ?></td>
                        <td><?= $t['total'] ?? 0 ?></td>
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
        <h2>Total de reservas por tour</h2>
        <canvas id="chartOcupacion" 
                data-tipos='<?= json_encode($reservasPorTour) ?>'></canvas>
    </section>
</div>

<link rel="stylesheet" href="assets/css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/viajar/assets/js/dashboard.tours.js"></script> 
