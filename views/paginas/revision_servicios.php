<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [2])) {
    header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.admin.php');
require_once("models/admin.php");

$adminModel = new Admin();

$hotelesPendientes = $adminModel->listarHotelesPendientes('hotel');
$transportesPendientes = $adminModel->listarTransportesPendientes('transporte');
$toursPendientes = $adminModel->listarToursPendientes('tour');
?>

<div class="contenido-dashboard">
    <h1>Revisión de servicios</h1>

    <div class="tabs">
        <button class="tab-btn active" data-tab="hoteles">Hoteles</button>
        <button class="tab-btn" data-tab="transportes">Transportes</button>
        <button class="tab-btn" data-tab="tours">Tours</button>
    </div>

    <div id="tab-hoteles" class="tab-content active">
        <h2>Hoteles pendientes</h2>
        <table class="tabla-servicios" data-tipo="hotel">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Proveedor</th>
                    <th>Fecha de envío</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($hotelesPendientes)): ?>
                    <?php foreach ($hotelesPendientes as $h): ?>
                        <tr data-id="<?= $h['id_hotel'] ?>">
                            <td><?= $h['id_hotel'] ?></td>
                            <td><?= htmlspecialchars($h['nombre']) ?></td>
                            <td><?= htmlspecialchars($h['ubicacion']) ?></td>
                            <td><?= htmlspecialchars($h['proveedor']) ?></td>
                            <td><?= htmlspecialchars($h['fecha']) ?></td>
                            <td>
                                <button class="btn-aprobar"><i class="fas fa-check"></i></button>
                                <button class="btn-rechazar"><i class="fas fa-times"></i> </button> 
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay hoteles pendientes</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="tab-transportes" class="tab-content">
        <h2>Transportes pendientes</h2>
        <table class="tabla-servicios" data-tipo="transporte">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Proveedor</th>
                    <th>Fecha de envío</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transportesPendientes)): ?>
                    <?php foreach ($transportesPendientes as $t): ?>
                        <tr data-id="<?= $t['id_transporte'] ?>">
                            <td><?= $t['id_transporte'] ?></td>
                            <td><?= htmlspecialchars($t['nombre']) ?></td>
                            <td><?= htmlspecialchars($t['tipo']) ?></td>
                            <td><?= htmlspecialchars($t['proveedor']) ?></td>
                            <td><?= htmlspecialchars($t['fecha']) ?></td>
                            <td>
                                <button class="btn-aprobar"><i class="fas fa-check"></i></button>
                                <button class="btn-rechazar"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay transportes pendientes</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="tab-tours" class="tab-content">
        <h2>Tours pendientes</h2>
        <table class="tabla-servicios" data-tipo="tours">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Proveedor</th>
                    <th>Fecha de envío</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($toursPendientes)): ?>
                    <?php foreach ($toursPendientes as $tour): ?>
                        <tr data-id="<?= $tour['id_tour'] ?>">
                            <td><?= $tour['id_tour'] ?></td>
                            <td><?= htmlspecialchars($tour['nombre']) ?></td>
                            <td><?= htmlspecialchars($tour['direccion']) ?></td>
                            <td><?= htmlspecialchars($tour['proveedor']) ?></td>
                            <td><?= htmlspecialchars($tour['fecha']) ?></td>
                            <td>
                                <button class="btn-aprobar"><i class="fas fa-check"></i></button>
                                <button class="btn-rechazar"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay tours pendientes</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<link rel="stylesheet" href="assets/css/revision_servicios.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/viajar/assets/js/revision_servicios.js"></script>

