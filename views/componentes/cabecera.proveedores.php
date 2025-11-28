<?php
if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [3, 5, 13, 14])) {
    header("Location: index.php");
    exit;
}
require_once('models/proveedor.php'); 
$proveedorModel = new Proveedor();
$estadoProveedor = $proveedorModel->obtenerEstadoProveedor($_SESSION['id_usuarios']);
$pendiente = ($estadoProveedor && $estadoProveedor['estado'] !== 'aprobado');
function itemMenu($activo, $link, $icono, $texto) {
    if ($activo) {
        return "<li><a href='$link'><i class='$icono'></i> $texto</a></li>";
    } else {
        return "<li class='item-disabled'><i class='$icono'></i> $texto</li>";
    }
}
?>

<div id="menu" class="menu-lateral">
    <ul>
        <li><strong>Menú proveedor</strong></li>
        <?php
        switch ($_SESSION['id_perfiles']) {
            case 3:
                echo itemMenu(!$pendiente, "index.php?page=hoteles_mis_hoteles", "fa-solid fa-hotel", "Mis hospedajes");
                echo itemMenu(!$pendiente, "index.php?page=hoteles_reservas", "fa-solid fa-calendar-check", "Reservas");
                echo itemMenu(!$pendiente, "index.php?page=hoteles_carga", "fa-solid fa-plus", "Cargar Hotel");
                echo itemMenu(!$pendiente, "index.php?page=hoteles_dashboard", "fa fa-chart-line", "Dashboard");
                break;

            case 5:
                echo itemMenu(!$pendiente, "index.php?page=transportes_mis_transportes", "fa-solid fa-bus", "Mis transportes");
                echo itemMenu(!$pendiente, "index.php?page=reservas_transporte", "fa-solid fa-calendar-check", "Reservas");
                echo itemMenu(!$pendiente, "index.php?page=transportes_carga", "fa-solid fa-plus", "Agregar Transporte");
                echo itemMenu(!$pendiente, "index.php?page=transportes_dashboard", "fa fa-chart-line", "Dashboard");
                break;

            case 14:
                echo itemMenu(!$pendiente, "index.php?page=tours_mis_tours", "fa-solid fa-map-location-dot", "Mis tours");
                echo itemMenu(!$pendiente, "index.php?page=reservas_tours", "fa-solid fa-users", "Reservas de tours");
                echo itemMenu(!$pendiente, "index.php?page=tours_carga", "fa-solid fa-plus", "Crear nuevo tour");
                echo itemMenu(!$pendiente, "index.php?page=tours_dashboard", "fa fa-chart-line", "Dashboard");
                break;

            case 13:
                echo itemMenu(!$pendiente, "index.php?page=mis_servicios", "fa-solid fa-briefcase", "Mis servicios");
                break;
        }
        ?>
        <li><a href="index.php?page=proveedores_perfil"><i class="fa-solid fa-user"></i> Mi perfil</a></li>
        <li><a href="views/paginas/salida.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
    </ul>
</div>

<div id="overlay" class="overlay"></div>

<div id="main" class="contenido">
    <div class="top-bar">
        <div class="left-links">
            <div class="logo">
                <a href="index.php?page=pantalla_hoteles">ViajAR</a>
            </div>
        </div>

        <div class="right-links">
            <div class="notifications-wrapper">
                <button id="notifications" aria-label="Ver notificaciones">
                    <i class="fa-solid fa-bell"></i>
                    <span class="notification-count">0</span>
                </button>
                <div id="notifications-dropdown" class="notifications-dropdown">
                    <ul id="notifications-list">
                        <li class="empty">No hay notificaciones</li>
                    </ul>
                    <button id="mark-all-read" class="btn-mark-all">Marcar todas como leídas</button>
                </div>
            </div>

            <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</div>

<style>
.item-disabled {
    opacity: 0.5;
    cursor: not-allowed;
    padding: 10px;
}
.item-disabled i {
    margin-right: 6px;
}

.bloqueo-pendiente {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    text-align: center;
}
.mensaje-box {
    background-color: #fff3cd;
    padding: 30px;
    border-radius: 10px;
    border: 1px solid #ffeeba;
    color: #856404;
    font-size: 1.2rem;
}
</style>
