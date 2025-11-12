<?php
if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [2])) {
    header("Location: index.php");
    exit;
}
?>
<div id="menu" class="menu-lateral">
    <ul>
        <li><strong>Menú administrador</strong></li>

        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">
                <i class="fa-solid fa-database"></i> Tablas maestras
                <i class="fa-solid fa-chevron-down" style="margin-left:auto;"></i>
            </a>
            <ul class="dropdown-content">
                <li><a href="index.php?page=usuarios"><i class="fa-solid fa-users"></i> Usuarios</a></li>
                <li><a href="index.php?page=perfiles"><i class="fa-solid fa-user-tag"></i> Perfiles</a></li>
                <li><a href="index.php?page=tipo_pagos"><i class="fa-solid fa-receipt"></i> Tipos de pago</a></li>
                <li><a href="index.php?page=tipo_contactos"><i class="fa-solid fa-address-book"></i> Tipos de contacto</a></li>
                <li><a href="index.php?page=provincias"><i class="fa-solid fa-map"></i> Provincias</a></li>
                <li><a href="index.php?page=estado_reserva"><i class="fa-solid fa-flag"></i> Estados de reserva</a></li>
                <li><a href="index.php?page=temporadas"><i class="fa-solid fa-calendar"></i> Temporadas</a></li>
                <li><a href="index.php?page=monedas"><i class="fa-solid fa-coins"></i> Monedas</a></li>
                <li><a href="index.php?page=tipos_documentos"><i class="fa-solid fa-file-alt"></i> Tipos de documento</a></li>
                <li><a href="index.php?page=tipos_habitaciones"><i class="fa-solid fa-bed"></i> Tipos de habitación</a></li>
                <li><a href="index.php?page=tipos_transportes"><i class="fa-solid fa-bus"></i> Tipos de transporte</a></li>
                <li><a href="index.php?page=tipos_proveedores"><i class="fa-solid fa-truck"></i> Tipos de proveedor</a></li>
                <li><a href="index.php?page=proveedores"><i class="fa-solid fa-truck"></i> Proveedores</a></li>
                <li><a href="index.php?page=ciudades"><i class="fa-solid fa-city"></i> Ciudades</a></li>
                <li><a href="index.php?page=motivos_cancelacion"><i class="fa-solid fa-ban"></i> Motivos de cancelación</a></li>
            </ul>
        </li>

        <li><a href="index.php?page=administrador_perfil"><i class="fa-solid fa-user"></i> Mi perfil</a></li>
        <li><a href="index.php?page=dashboard_admin"><i class="fa fa-chart-line"></i> Dashboard</a></li>
        <li><a href="index.php?page=revision_servicios"><i class="fa-solid fa-check-square"></i> Revisión de servicios</a></li>
        <li><a href="index.php?page=listado_auditorias"><i class="fa-solid fa-clipboard-list"></i> Auditorías</a></li>
        <li><a href="views/paginas/salida.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
    </ul>
</div>


<div id="overlay" class="overlay"></div>

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

<script src="assets/js/menu-dropdown.js"></script>

