<?php
$perfiles_permitidos = [3, 5, 13, 14]; 
if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'], $perfiles_permitidos)) {
    header('Location: index.php?page=login&message=Acceso no autorizado. Inicie sesión como proveedor.&status=danger');
    exit;
}

$nombre_admin = $_SESSION['usuarios_nombre_usuario'] ?? 'Proveedor';
$email_admin = $_SESSION['usuarios_email'] ?? 'N/A';
$perfil_admin = $_SESSION['perfiles_nombre'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
</head>
<body>
    <main class="contenido-principal">
        <div class="container">
            <h1>Mi perfil de proveedor</h1>
            <p>Hola <?= htmlspecialchars($_SESSION['usuarios_nombre_usuario'] ?? '') ?>, Aca podés ver y editar tu información como proveedor.</p>

            <section class="grid-cards">
            <?php
            switch ($_SESSION['id_perfiles']) {
                case 3: 
                    echo '
                    <div class="card">
                        <h3>Mis Hoteles</h3>
                        <p>Gestiona tus hospedajes registrados.</p>
                        <a href="index.php?page=hoteles_mis_hoteles" class="btn">Ver</a>
                    </div>
                    <div class="card">
                        <h3>Reservas</h3>
                        <p>Consulta y gestiona tus reservas recibidas.</p>
                        <a href="index.php?page=hoteles_reservas" class="btn">Ver</a>
                    </div>
                    <div class="card">
                        <h3>Cargar Hotel</h3>
                        <p>Agrega un nuevo hotel.</p>
                        <a href="index.php?page=hoteles_carga" class="btn">Cargar</a>
                    </div>';
                    break;

                case 5: 
                    echo '
                    <div class="card">
                        <h3>Mis Transportes</h3>
                        <p>Gestiona tus vehículos y rutas.</p>
                        <a href="index.php?page=transportes_mis_transportes" class="btn">Ver</a>
                    </div>
                    <div class="card">
                        <h3>Reservas Transporte</h3>
                        <p>Consulta reservas y disponibilidad.</p>
                        <a href="index.php?page=reservas_transporte" class="btn">Ver</a>
                    </div>
                    <div class="card">
                        <h3>Agregar Transporte</h3>
                        <p>Registrá un nuevo vehículo para tus rutas.</p>
                        <a href="index.php?page=transportes_carga" class="btn">Agregar</a>
                    </div>
                    <div class="card">
                        <h3>Crear Nueva Ruta</h3>
                        <p>Configurá una nueva ruta para tus transportes.</p>
                        <a href="index.php?page=transportes_rutas_carga" class="btn">Crear</a>
                    </div>
                    <div class="card">
                        <h3>Agregar Viaje</h3>
                        <p>Publicá un nuevo viaje disponible para los pasajeros.</p>
                        <a href="index.php?page=transportes_viajes_carga" class="btn">Agregar</a>
                    </div>';
                    break;

                case 14:
                    echo '
                    <div class="card">
                        <h3>Mis Tours</h3>
                        <p>Gestiona tus tours creados.</p>
                        <a href="index.php?page=tours_mis_tours" class="btn">Ver</a>
                    </div>
                    <div class="card">
                        <h3>Reservas de Tours</h3>
                        <p>Visualiza las reservas recibidas para cada tour.</p>
                        <a href="index.php?page=reservas_tours" class="btn">Ver</a>
                    </div>
                    <div class="card">
                        <h3>Crear Nuevo Tour</h3>
                        <p>Publica un tour guiado con detalles de lugar, fechas y cupos.</p>
                        <a href="index.php?page=tours_carga" class="btn">Crear</a>
                    </div>
                    <div class="card">
                        <h3>Gestionar Stock</h3>
                        <p>Agregá fechas y cupos disponibles para tus tours.</p>
                        <a href="index.php?page=tours_stock" class="btn">Ir</a>
                    </div>';
                    break;


                case 13: 
                    echo '
                    <div class="card">
                        <h3>Mis Servicios</h3>
                        <p>Gestiona todos los servicios a tu cargo.</p>
                        <a href="index.php?page=mis_servicios" class="btn">Ver</a>
                    </div>';
                    break;
            }
            ?>
            </section>
        </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>
</html>
