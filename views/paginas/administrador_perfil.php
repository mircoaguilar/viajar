<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?page=login");
    exit;
}

$usuario  = $_SESSION['usuario'];
$rol      = $_SESSION['rol'];
$sucursal = $_SESSION['sucursal'] ?? 'Sucursal no asignada';
?>

<div class="container mt-4">

    <!-- HEADER PERFIL -->
    <div class="bg-light p-4 rounded shadow-sm mb-4">
        <h2 class="mb-1">Bienvenido, <?= htmlspecialchars($usuario) ?></h2>
        <p class="text-muted mb-1">
            Cuenta operativa de la sucursal <strong><?= htmlspecialchars($sucursal) ?></strong>
        </p>
        <p class="text-muted">
            Fecha y hora actual: <?= date('d/m/Y H:i') ?>
        </p>
    </div>

    <!-- TARJETAS -->
    <div class="row g-4">

        <!-- INFO PERFIL -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fa-solid fa-user me-2"></i>
                        Información de la cuenta
                    </h5>
                    <hr>
                    <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario) ?></p>
                    <p><strong>Rol:</strong> <?= ucfirst($rol) ?></p>
                    <p><strong>Sucursal:</strong> <?= htmlspecialchars($sucursal) ?></p>

                    <a href="index.php?page=cambiar_password" class="btn btn-outline-secondary btn-sm mt-2">
                        Cambiar contraseña
                    </a>
                </div>
            </div>
        </div>

        <!-- ACCESOS -->
        <div class="col-md-8">
            <div class="row g-4">

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fa-solid fa-truck fa-2x mb-2 text-primary"></i>
                            <h5>Envíos</h5>
                            <p>Gestión y seguimiento de envíos.</p>
                            <a href="index.php?page=envios" class="btn btn-primary btn-sm">
                                Ir a Envíos
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fa-solid fa-route fa-2x mb-2 text-success"></i>
                            <h5>Hojas de Ruta</h5>
                            <p>Planificación y control de recorridos.</p>
                            <a href="index.php?page=hojas_ruta" class="btn btn-success btn-sm">
                                Ver Hojas de Ruta
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fa-solid fa-warehouse fa-2x mb-2 text-warning"></i>
                            <h5>Vehículos</h5>
                            <p>Administración de la flota.</p>
                            <a href="index.php?page=vehiculos" class="btn btn-warning btn-sm">
                                Gestionar Vehículos
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fa-solid fa-cash-register fa-2x mb-2 text-danger"></i>
                            <h5>Caja</h5>
                            <p>Control de movimientos de caja.</p>
                            <a href="index.php?page=caja" class="btn btn-danger btn-sm">
                                Ir a Caja
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
