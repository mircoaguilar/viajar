<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [2])) {
    header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.admin.php');
require_once("models/admin.php");

$adminModel = new Admin();
$anio = isset($_GET['año']) ? $_GET['año'] : date('Y');  

$usuarios_count = $data['usuarios_count'] ?? $adminModel->contarUsuarios();
$reservas_count = $data['reservas_count'] ?? $adminModel->contarReservas();
$ingresos_total = $data['ingresos_total'] ?? $adminModel->obtenerIngresosTotales();

$ultimosUsuarios = $adminModel->traerUltimosUsuarios(5);
$ultimasReservas = $adminModel->traerUltimasReservas(5);
$reservasPorMes = $adminModel->obtenerReservasPorMes($anio);
$serviciosMasReservados = $adminModel->obtenerServiciosMasReservados(5)
?>


<div class="contenido-dashboard">
    <h1>Panel de Administración</h1>

    <div class="dashboard-metrics">
        <div class="card">
            <h2>Usuarios</h2>
            <p><?= $usuarios_count ?></p>
        </div>
        <div class="card">
            <h2>Reservas</h2>
            <p><?= $reservas_count ?></p>
        </div>
        <div class="card">
            <h2>Ingresos</h2>
            <p>$<?= number_format($ingresos_total, 2, ',', '.') ?></p>
        </div>
    </div>

    <section class="dashboard-section">
        <h2>Últimos usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Fecha de creación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ultimosUsuarios as $u): ?>
                    <tr>
                        <td><?= $u['id_usuarios'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['perfil']) ?></td>
                        <td><?= htmlspecialchars($u['usuarios_fecha_alta']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="dashboard-section">
        <h2>Últimas reservas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Servicio</th>
                    <th>Fecha de creación</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ultimasReservas as $r): ?>
                    <tr>
                        <td><?= $r['id_reserva'] ?></td>
                        <td><?= htmlspecialchars($r['usuario']) ?></td>
                        <td><?= htmlspecialchars($r['servicio']) ?></td>
                        <td><?= $r['fecha_reserva'] ?></td>
                        <td>$<?= number_format($r['precio'],2,',','.') ?></td>
                        <td><?= $r['reservas_estado'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="dashboard-section">
        <h2>Servicios más reservados</h2>
        <table>
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Cantidad de reservas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($serviciosMasReservados as $servicio): ?>
                    <tr>
                        <td><?= htmlspecialchars($servicio['servicio']) ?></td>
                        <td><?= $servicio['cantidad'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="dashboard-section">
        <h2>Reservas por mes</h2>
        <label for="filtro-anio">Selecciona el año:</label>
        <select id="filtro-anio" name="filtro-anio">
            <option value="2024">2024</option>
            <option value="2025" selected>2025</option> 
        </select>

        <div class="chart-container">
            <canvas id="chartReservas" width="400" height="300"
                data-reservas='<?= json_encode($reservasPorMes) ?>'></canvas>
        </div>
    </section>



</div>

<link rel="stylesheet" href="assets/css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/viajar/assets/js/dashboard.js"></script>
