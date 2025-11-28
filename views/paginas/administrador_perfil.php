<?php
if (!isset($_SESSION['id_usuarios']) || (isset($_SESSION['perfiles_nombre']) && $_SESSION['perfiles_nombre'] !== 'Administrador')) {
    header('Location: index.php?page=login&message=Acceso no autorizado. Inicie sesión como administrador.&status=danger');
    exit;
}

$nombre_admin = $_SESSION['usuarios_nombre_usuario'] ?? 'Administrador';
$email_admin = $_SESSION['usuarios_email'] ?? 'N/A';
$perfil_admin = $_SESSION['perfiles_nombre'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Administrador</title>
    <link rel="stylesheet" href="assets/css/mi_perfil_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
</head>
<body>

<div class="container">

    <div class="perfil-header">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_admin); ?></h1>
        <p>Este es tu panel de control como Administrador de ViajAR. Desde aca podes gestionar y acceder a todas las funciones del sistema.</p>
        <p>Fecha y hora actual: <?php echo date('d/m/Y H:i'); ?></p>
    </div>

    <div class="tarjetas-principales"> 

        <div class="perfil-card">
            <div class="perfil-header-info">
                <i class="fa-solid fa-user"></i>
                <h2>Información de tu Perfil</h2>
            </div>
            <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($nombre_admin); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($email_admin); ?></p>
            <p><strong>Rol Asignado:</strong> <span class="perfil-rol"><?php echo htmlspecialchars($perfil_admin); ?></span></p>
            <a class="btn-editar" href="index.php?page=usuarios&id=<?php echo $_SESSION['id_usuarios']; ?>">Editar mis datos</a>
        </div>

        <div class="acceso-card">
            <i class="fa fa-chart-line"></i>
            <h3>Dashboard</h3>
            <p>Accede al resumen general de estadísticas del sistema.</p>
            <a href="index.php?page=dashboard_admin" class="btn-acceso">Ver Dashboard</a>
        </div>

        <div class="acceso-card">
            <i class="fa-solid fa-check-square"></i>
            <h3>Revisión de Servicios</h3>
            <p>Gestiona los servicios pendientes de revisión y apruébalos.</p>
            <a href="index.php?page=revision_servicios" class="btn-acceso">Revisar Servicios</a>
        </div>

        <div class="acceso-card">
            <i class="fa-solid fa-clipboard-list"></i>
            <h3>Auditorías</h3>
            <p>Consulta las auditorías realizadas en el sistema.</p>
            <a href="index.php?page=listado_auditorias" class="btn-acceso">Ver Auditorías</a>
        </div>   
        
        <div class="acceso-card">
            <i class="fa-solid fa-wallet"></i>
            <h3>Ganancias</h3>
            <p>Consulta el resumen de las ganancias del sistema y los detalles por servicio.</p>
            <a href="index.php?page=ganancias_dashboard" class="btn-acceso">Ver Ganancias</a>
        </div>

        <div class="acceso-card">
            <i class="fa-solid fa-user-check"></i>
            <h3>Revisión de Proveedores</h3>
            <p>Aprueba o rechaza los proveedores que se han registrado en el sistema.</p>
            <a href="index.php?page=revision_proveedores" class="btn-acceso">Ver Proveedores</a>
        </div>

    </div>
    </div>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>