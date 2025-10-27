<?php
// Verificamos que el cliente esté logueado y tenga el perfil correspondiente
if (!isset($_SESSION['id_usuarios']) || $_SESSION['perfiles_nombre'] !== 'Cliente') {
    header('Location: index.php?page=login&message=Acceso no autorizado. Inicie sesión como cliente.&status=danger');
    exit;
}

// Obtenemos los datos del cliente desde la sesión o la base de datos
$nombre_cliente = $_SESSION['usuarios_nombre_usuario'] ?? 'Cliente';
$email_cliente = $_SESSION['usuarios_email'] ?? 'N/A';
$telefono_cliente = $_SESSION['usuarios_telefono'] ?? 'N/A';
$direccion_cliente = $_SESSION['usuarios_direccion'] ?? 'N/A';
$fecha_registro_cliente = $_SESSION['usuarios_fecha_registro'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Cliente</title>
    <link rel="stylesheet" href="assets/css/mi_perfil_cliente.css">
</head>
<body>

    <div class="container">
        <?php if (isset($_GET['message'])): ?>
        <div class="alert <?php echo ($_GET['status'] == 'success') ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
        <?php endif; ?>

        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_cliente); ?></h1>
        <p>Este es tu perfil en el sistema ViajAR. Aca podes revisar tus datos personales.</p>

        <h2>Información de tu Perfil</h2>
        <div class="info-box">
            <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($nombre_cliente); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($email_cliente); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono_cliente); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion_cliente); ?></p>
            <p><strong>Fecha de Registro:</strong> <?php echo date('d/m/Y', strtotime($fecha_registro_cliente)); ?></p>
            <a href="index.php?page=editar_perfil_cliente">Editar mis datos</a>
        </div>

        <h2>Accesos Rápidos</h2>
        <div class="dashboard-grid">
            <div class="card">
                <h3><i class="fas fa-bookmark"></i> Mis Reservas</h3>
                <ul>
                    <li><a href="index.php?page=clientes_mis_reservas">Ver mis reservas</a></li>
                </ul>
            </div>

            <div class="card">
                <h3><i class="fas fa-headset"></i> Soporte</h3>
                <ul>
                    <li><a href="index.php?page=soporte">Contacto con soporte</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
