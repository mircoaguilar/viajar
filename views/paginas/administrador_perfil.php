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
</head>
<body>

    <div class="container">

        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_admin); ?></h1>
        <p>Este es tu panel de control como Administrador de ViajAR. Aca podes acceder rápidamente a las herramientas de gestión del sistema.</p>
        <p>Fecha y hora actual: <?php echo date('d/m/Y H:i'); ?></p>

        <h2>Información de tu Perfil</h2>
        <div class="info-box">
            <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($nombre_admin); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($email_admin); ?></p>
            <p><strong>Rol Asignado:</strong> <span style="font-weight: bold; color: #007bff;"><?php echo htmlspecialchars($perfil_admin); ?></span></p>
            <a href="index.php?page=usuarios&id=<?php echo $_SESSION['id_usuarios']; ?>">Editar mis datos</a>
        </div>


        <h2>Accesos Rápidos a la Gestión</h2>
        <div class="dashboard-grid">
            <div class="card">
                <h3><i class="fas fa-users"></i> Gestión de Usuarios</h3>
                <ul>
                    <li><a href="index.php?page=usuarios">Ver y Editar Usuarios</a></li>
                    <li><a href="index.php?page=usuarios&action=create">Crear Nuevo Usuario</a></li>
                </ul>
            </div>

            <div class="card">
                <h3><i class="fas fa-user-tag"></i> Gestión de Perfiles</h3>
                <ul>
                    <li><a href="index.php?page=perfiles">Ver y Editar Perfiles</a></li>
                    <li><a href="index.php?page=perfiles&action=create">Crear Nuevo Perfil</a></li>
                </ul>
            </div>
            
            <div class="card">
                <h3><i class="fas fa-receipt"></i> Tipos de Pago</h3>
                <ul>
                    <li><a href="index.php?page=tipo_pagos">Ver y Editar Tipos de Pago</a></li>
                    <li><a href="index.php?page=tipo_pagos&action=create">Crear Nuevo Tipo de Pago</a></li>
                </ul>
            </div>

            <div class="card">
                <h3><i class="fas fa-address-book"></i> Tipos de Contacto</h3>
                <ul>
                    <li><a href="index.php?page=tipo_contactos">Ver y Editar Tipos de Contacto</a></li>
                    <li><a href="index.php?page=tipo_contactos&action=create">Crear Nuevo Tipo de Contacto</a></li>
                </ul>
            </div>

            <div class="card">
                <h3><i class="fas fa-clipboard-list"></i> Historial de Auditorías</h3>
                <ul>
                    <li><a href="index.php?page=listado_auditorias">Ver Auditorías</a></li>
                </ul>
            </div>

            </div>
    </div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>