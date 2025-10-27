<?php
if (!isset($_SESSION['id_perfiles']) || $_SESSION['id_perfiles'] != 1) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../../models/carrito.php';
$carritoModel = new Carrito();
$id_usuario = $_SESSION['id_usuarios'] ?? 0;
$total_items = $id_usuario ? $carritoModel->contar_items($id_usuario) : 0;
?>

<!-- Menú lateral -->
<div id="menu" class="menu-lateral">
    <ul>
        <li><strong>Menú</strong></li>
        <li><a href="index.php?page=clientes_perfil"><i class="fa-solid fa-user"></i> Mi perfil</a></li>
        <li><a href="index.php?page=clientes_mis_reservas"><i class="fa-solid fa-bookmark"></i> Mis reservas</a></li>
        <li><a href="index.php?page=soporte"><i class="fa-solid fa-headset"></i> Soporte</a></li>
        <li><a href="views/paginas/salida.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
    </ul>
</div>

<!-- Overlay -->
<div id="overlay" class="overlay"></div>

<!-- Contenido principal -->
<div id="main" class="contenido">
    <div class="top-bar">
        <div class="left-links">
            <div class="logo">
                <a href="index.php?page=pantalla_hoteles">ViajAR</a>
            </div>
            <nav class="nav-links">
                <a href="index.php?page=pantalla_hoteles"><i class="fa-solid fa-hotel"></i> Hoteles</a>
                <a href="index.php?page=pantalla_transporte"><i class="fa-solid fa-bus"></i> Transporte</a>
                <a href="index.php?page=pantalla_guias"><i class="fa-solid fa-map"></i> Guías</a>
            </nav>
        </div>

        <div class="right-links">
            <!-- Notificaciones -->
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


            <!-- Carrito -->
            <div class="carrito-wrapper">
                <a href="index.php?page=clientes_carrito" class="carrito-icon" aria-label="Ver carrito">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="carrito-count"><?= $total_items ?></span>
                </a>
            </div>

            <!-- Usuario -->
            <span class="user-name"><?php echo htmlspecialchars($_SESSION['usuarios_nombre_usuario'] ?? ''); ?></span>

            <!-- Menú toggle -->
            <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</div>
