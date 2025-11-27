<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [2])) {
    header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.admin.php');
require_once(__DIR__ . '/../../models/ganancia.php');

$gananciaModel = new Ganancia();

$ganancias_totales = $gananciaModel->obtenerTodasLasGanancias();

if (count($ganancias_totales) > 0) {
    $ganancia_promedio = array_sum(array_column($ganancias_totales, 'ganancia_neta')) / count($ganancias_totales);
} else {
    $ganancia_promedio = 0;  
}

$ganancias_por_servicio = [
    'hotel' => $gananciaModel->obtenerGananciasPorServicio('hotel'),
    'transporte' => $gananciaModel->obtenerGananciasPorServicio('transporte'),
    'tour' => $gananciaModel->obtenerGananciasPorServicio('tour')
];

?>


<div class="contenido-dashboard">
    <h1>Panel de Ganancias</h1>

    <div class="dashboard-metrics">
        <div class="card">
            <h2>Ganancia Total</h2>
            <p>$<?= number_format(array_sum(array_column($ganancias_totales, 'ganancia_neta')), 2, ',', '.') ?></p>
        </div>
        <div class="card">
            <h2>Ganancia Promedio</h2>
            <p>$<?= number_format($ganancia_promedio, 2, ',', '.') ?></p>
        </div>
    </div>

    <section class="dashboard-section">
        <h2>Ganancias por Servicio</h2>
            <table>
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Ganancia Total</th>
                        <th>Ganancia Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ganancias_por_servicio as $servicio => $ganancias): ?>
                        <tr>
                            <td><?= ucfirst($servicio) ?></td>
                            <td>$<?= number_format(array_sum(array_column($ganancias, 'ganancia_neta')), 2, ',', '.') ?></td>
                            <td>
                                <?php
                                // Verificamos si hay ganancias para este servicio
                                if (count($ganancias) > 0) {
                                    echo "$" . number_format(array_sum(array_column($ganancias, 'ganancia_neta')) / count($ganancias), 2, ',', '.');
                                } else {
                                    echo "$0.00";  // Si no hay ganancias, mostramos 0
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </section>

    <section class="dashboard-section">
        <h2>Ganancias por Mes</h2>
        <div class="chart-container">
            <canvas id="chartGananciasPorMes" width="400" height="300" 
                    data-ganancias='<?php echo json_encode($gananciaModel->obtenerGananciasPorMes()); ?>'></canvas>
        </div>
    </section>

    <section class="dashboard-section">
        <h2>Últimas Ganancias Registradas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Reserva</th>
                    <th>Tipo de Servicio</th>
                    <th>Ganancia Neta</th>
                    <th>Fecha de Cálculo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ganancias_totales as $ganancia): ?>
                    <tr>
                        <td><?= $ganancia['id_reserva'] ?></td>
                        <td><?= htmlspecialchars($ganancia['tipo_servicio']) ?></td>
                        <td>$<?= number_format($ganancia['ganancia_neta'], 2, ',', '.') ?></td>
                        <td><?= $ganancia['fecha_calculo'] ?></td>
                        <td><a href="ganancias.controlador.php?action=verGanancias&id_reserva=<?= $ganancia['id_reserva'] ?>">Ver detalles</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="dashboard-section">
        <a href="ganancias.controlador.php?action=exportarCSV">Exportar Ganancias a CSV</a>
    </section>
</div>

<link rel="stylesheet" href="assets/css/ganancias_dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/viajar/assets/js/ganancias_dashboard.js"></script>


