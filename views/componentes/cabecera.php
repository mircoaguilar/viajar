<?php
$page = $_GET['page'] ?? ''; 
if ($page !== 'login' && $page !== 'registro'  && $page !== 'registro_proveedor'): 
?>
<div class="top-bar">
    <div class="left-links">
        <div class="logo">
            <a href="index.php?page=pantalla_hoteles">ViajAR</a>
        </div>

        <nav class="nav-links">
            <a href="index.php?page=pantalla_hoteles">
                <i class="fa-solid fa-hotel"></i> Hoteles
            </a>
            <a href="index.php?page=pantalla_transporte">
                <i class="fa-solid fa-bus"></i> Transporte
            </a>
            <a href="index.php?page=pantalla_guias">
                <i class="fa-solid fa-map"></i> Guías
            </a>
        </nav>
    </div>

    <div class="right-links">

        <a class="btn-proveedor" href="index.php?page=registro_proveedor">
            </i> Registrá tu servicio
        </a>

        <a href="index.php?page=login">Iniciar sesión</a>
        <a href="index.php?page=registro">Crear cuenta</a>
    </div>
</div>
<?php endif; ?>
