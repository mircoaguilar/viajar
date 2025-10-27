<?php
if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [3, 5, 13, 14])) {
    header("Location: index.php");
    exit;
}
?>

<div id="menu" class="menu-lateral">
    <ul>
        <li><strong>Menú proveedor</strong></li>
        <?php
        switch ($_SESSION['id_perfiles']) {
            case 3: // hospedaje
                echo '<li><a href="index.php?page=hoteles_mis_hoteles"><i class="fa-solid fa-hotel"></i> Mis hospedajes</a></li>';
                echo '<li><a href="index.php?page=hoteles_reservas"><i class="fa-solid fa-calendar-check"></i> Reservas</a></li>';
                echo '<li><a href="index.php?page=hoteles_carga"><i class="fa-solid fa-plus"></i> Cargar Hotel / Habitaciones</a></li>';
                break;
            case 5: // transporte
                echo '<li><a href="index.php?page=transportes_mis_transportes"><i class="fa-solid fa-bus"></i> Mis transportes</a></li>';
                echo '<li><a href="index.php?page=reservas_transporte"><i class="fa-solid fa-calendar-check"></i> Reservas</a></li>';
                echo '<li><a href="index.php?page=transportes_carga"><i class="fa-solid fa-plus"></i> Agregar Transporte</a></li>';
                echo '<li><a href="index.php?page=transportes_rutas_carga"><i class="fa-solid fa-route"></i> Crear Nueva Ruta</a></li>';
                break;
           case 14: // Guía
                echo '
                    <li><a href="index.php?page=tours_mis_tours"><i class="fa-solid fa-map-location-dot"></i> Mis tours</a></li>
                    <li><a href="index.php?page=tours_proximos"><i class="fa-solid fa-calendar-days"></i> Próximos tours</a></li>
                    <li><a href="index.php?page=reservas_tours"><i class="fa-solid fa-users"></i> Reservas de tours</a></li>
                    <li><a href="index.php?page=tours_carga"><i class="fa-solid fa-plus"></i> Crear nuevo tour</a></li>
                ';
                break;

            case 13: // encargado general
                echo '<li><a href="index.php?page=mis_servicios"><i class="fa-solid fa-briefcase"></i> Mis servicios</a></li>';
                break;
        }
        ?>
        <li><a href="index.php?page=proveedores_perfil"><i class="fa-solid fa-user"></i> Mi perfil</a></li>
        <li><a href="views/paginas/salida.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
    </ul>
</div>

<div id="overlay" class="overlay"></div>


<!-- Panel principal -->
<div id="main" class="contenido">
    <div class="top-bar">
        <div class="logo">
            <a href="index.php?page=pantalla_hoteles">ViajAR</a>
        </div>
        <div class="right-links">
            <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</div>
