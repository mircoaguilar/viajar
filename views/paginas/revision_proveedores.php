<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || $_SESSION['id_perfiles'] != 2) {
    header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once("models/admin.php");
$adminModel = new Admin();
$proveedoresPendientes = $adminModel->listarProveedoresPendientes();
?>

<div class="contenido-dashboard">
    <h1>Aprobación de proveedores</h1>
    
    <table class="tabla-proveedores">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Fecha registro</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($proveedoresPendientes)): ?>
                <?php foreach($proveedoresPendientes as $prov): ?>
                    <tr data-id="<?= $prov['id_proveedor'] ?>">
                        <td><?= $prov['id_proveedor'] ?></td>
                        <td><?= htmlspecialchars($prov['nombre']) ?></td>
                        <td><?= htmlspecialchars($prov['email']) ?></td>
                        <td><?= htmlspecialchars($prov['fecha_registro']) ?></td>
                        <td><?= htmlspecialchars($prov['estado']) ?></td>
                        <td>
                            <button class="btn-aprobar"><i class="fas fa-check"></i></button>
                            <button class="btn-rechazar"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No hay proveedores pendientes</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<link rel="stylesheet" href="assets/css/revision_proveedores.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/viajar/assets/js/revision_proveedores.js"></script>
